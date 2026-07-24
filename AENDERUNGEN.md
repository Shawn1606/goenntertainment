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
