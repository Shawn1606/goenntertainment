# Änderungen zum Gegenlesen (Human Review)

Diese Datei ist **für dich (Shawn)** zum groben Gegenlesen. Zu jedem Task fasse ich hier
meine letzten Schritte zusammen – **mit Code-Auszügen** und einer Erklärung in einfachen
Worten, was der Code tut und warum.

**So läuft es:**
- **Vor** jedem Task lese ich diese Datei (letzter Eintrag = wo wir stehen).
- **Nach** jedem Task hänge ich unten einen neuen Eintrag an (neueste zuletzt).
- Im Code selbst stehen zusätzlich deutsche Kommentare für den „menschlichen Kontext".

> Unterschied zu `proc`: `proc` ist mein knappes technisches Log. Diese Datei hier ist
> ausführlicher und mit Code, damit **du** ohne Vorwissen mitlesen kannst.

---

## 2026-07-24 · Setup: Arbeits-Workflow eingerichtet

**Worum ging's:** Bevor wir die Board-Tickets abarbeiten, habe ich einen festen Arbeits-
Ablauf eingerichtet (Regeln, Sicherheits-Hooks, Skills). Das sorgt dafür, dass jede
Änderung nachvollziehbar und rückgängig-machbar ist.

**Neue Dateien:**

| Datei | Wofür |
|---|---|
| `CLAUDE.md` | Kurz-Überblick, den jede neue AI-Session zuerst liest |
| `PROJECT_RULES.md` | Coding-Regeln (z. B. „ein Ticket = ein Commit") |
| `TASK_SYSTEM.md` | Wie die Tickets vom GitHub-Board abgearbeitet werden |
| `.githooks/` | 2 Sicherheits-Hooks (siehe Code unten) |
| `.claude/commands/` | 8 Kurzbefehle (`/next-ticket`, `/task-check`, …) |
| `proc` | Mein technisches Log |
| `AENDERUNGEN.md` | Diese Datei |

**Wichtigster Code – der `pre-push`-Hook** (verhindert, dass schlechter formatierter Code
ins Repo gepusht wird). In einfachen Worten: vor jedem `git push` läuft der Code-Formatierer
„Pint" im Prüf-Modus; wenn mehr Dateien unsauber sind als vorher (Baseline = 4), wird der
Push gestoppt.

```sh
# .githooks/pre-push (gekürzt)
# Anzahl Dateien mit Style-Themen zaehlen:
dirty=$("$PHP" vendor/bin/pint --test 2>/dev/null | grep -o '"path":' | wc -l | tr -d ' ')
baseline=$(tr -dc '0-9' < "$baseline_file")   # steht in .githooks/pint-baseline.txt (=4)

if [ "$dirty" -gt "$baseline" ]; then
  echo "pre-push BLOCKIERT: $dirty Dateien unsauber, Baseline ist $baseline." >&2
  exit 1   # Push wird abgebrochen
fi
```

**Der `commit-msg`-Hook** erzwingt saubere Commit-Nachrichten (z. B. `feat(auth): ...`)
und fängt einen typischen Copy-Paste-Fehler ab (Nachricht beginnt mit `@`).

**Getestet:** Hooks greifen (falsche Nachricht wird abgelehnt), `php artisan test` = 5 grün,
Formatierer-Baseline = 4 Dateien.

**Nächster Schritt:** Ticket #1 (Registration / Log-In) – fehlende Views bauen.

---

## 2026-07-24 · API für die Handy-App (Login/Registrieren)

**Worum ging's:** Die Web-Seiten (Blade) kann die Expo-Handy-App nicht nutzen – die
braucht eine **API**, die reines JSON zurückgibt und mit einem **Token** statt Cookie
arbeitet. Genau das habe ich gebaut. Die Web-Seiten bleiben unverändert; die API kommt
**daneben** dazu. Ab jetzt gilt: **App-Code lebt im eigenen Repo `goenntertainment-app`**,
dieses Repo hier ist nur noch das Backend.

**Neue Endpunkte:**

| Methode | Pfad | Zweck |
|---|---|---|
| POST | `/api/register` | Registrieren, gibt Token |
| POST | `/api/login` | Login, gibt Token |
| POST | `/api/logout` | Token löschen |
| GET | `/api/user` | Eigener User (nur mit Token) |
| POST | `/api/auth/google` | App schickt Google-Token → bekommt App-Token |

**Wichtigster Code – Login gibt einen Token zurück** (aus `Api/AuthController.php`).
In einfachen Worten: E-Mail + Passwort prüfen; stimmt es, bekommt die App einen langen
Schlüssel (`token`), den sie danach bei jeder Anfrage mitschickt.

```php
public function login(LoginRequest $request): JsonResponse
{
    $user = User::query()->where('email', $request->string('email')->toString())->first();

    if ($user === null || ! Hash::check($request->string('password')->toString(), (string) $user->password)) {
        throw ValidationException::withMessages(['email' => __('auth.failed')]);
    }

    return $this->tokenResponse($user, $request->string('device_name', 'mobile')->toString());
}
```

**Google-Login für die App** (aus `Api/GoogleAuthController.php`): die App loggt sich bei
Google ein, holt dort einen `access_token` und schickt ihn hierher. Wir fragen damit bei
Google die Nutzerdaten ab, legen den User bei Bedarf an und geben einen App-Token zurück.

```php
$googleUser = Socialite::driver('google')->stateless()
    ->userFromToken($request->string('access_token')->toString());
// ... User finden/anlegen ...
$token = $user->createToken($deviceName)->plainTextToken;
```

**Getestet:** Pint (Formatierer) grün, Baseline von 4 auf 3 gesenkt. 14/14 Tests grün
(9 neue für die API). Zusätzlich echt im Browser/HTTP geprüft: ohne Token → 401,
falsches Passwort → 422, jeweils sauberes JSON.

**Noch von dir nötig:** In der Google Cloud Console einen eigenen OAuth-Client für die
Expo-App anlegen (sonst funktioniert `/api/auth/google` nicht).

**Nächster Schritt:** In der App (`goenntertainment-app`) den Login-Screen an diese API
anschließen.

---

## 2026-07-24 · Ticket #3 (Schritt 1/2): Activity-Backend

**Worum ging's:** Grundlage für „Event/Activity erstellen". Bis jetzt gab es Activities nur
als Beispiel-Daten in der App. Jetzt können Activities echt in der Datenbank angelegt und
gelesen werden. Der Plus-Button in der App (Schritt 2) baut später darauf auf.

**Neue Tabellen:** `activities` (Titel, Beschreibung, Ort, Start-Zeit, optionales Banner-Bild,
Host = User) und `activity_interest` (verknüpft eine Activity mit bis zu 5 Interessen).

**Neue API-Endpunkte:**

| Methode | Pfad | Wofür |
|---|---|---|
| GET | `/api/interests` | Liste der Interessen (öffentlich, für die Auswahl) |
| GET | `/api/activities` | Alle Activities (nur mit Token) |
| POST | `/api/activities` | Neue Activity anlegen (nur mit Token) |

**Wichtigster Code – Activity anlegen** (aus `Api/ActivityController.php`). In einfachen
Worten: optionales Banner-Bild speichern, Activity mit dem eingeloggten User als Host
anlegen, danach die gewählten Interessen verknüpfen.

```php
$bannerPath = $request->hasFile('banner')
    ? $request->file('banner')->store('banners', 'public')
    : null;

$activity = Activity::query()->create([
    'user_id' => $request->user()->id,   // Host = eingeloggter User
    'title' => $request->string('title')->toString(),
    // ... description, location, starts_at ...
    'banner_path' => $bannerPath,
]);

if ($request->filled('interests')) {
    $activity->interests()->sync($request->input('interests'));  // max 5 (Validierung)
}
```

**Regeln (Validierung, `StoreActivityRequest`):** Titel/Beschreibung/Ort/Start-Zeit sind
Pflicht; Banner optional (Bild, max 5 MB); höchstens 5 Interessen.

**Getestet:** Pint sauber (keine neuen Baseline-Fehler), Pest 23/23 grün (10 neue Tests für
Activities + Interessen, inkl. Banner-Upload und „max 5 Interessen"). Zusätzlich echt per
HTTP geprüft: `/interests` → 200, Activity anlegen → 201, Liste → 200.

**Noch offen (Schritt 2):** In der App das Plus-Button-Formular bauen (Foto aus Galerie/
Kamera, Interessen, Beschreibung, Ort, Datum/Zeit) und an diese API anschließen; Home-Screen
auf echte Activities umstellen.
