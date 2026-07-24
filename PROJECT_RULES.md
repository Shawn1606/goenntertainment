# PROJECT_RULES – Coding Standards & harte Verbote (Gönntertainment)

Volltext zu den Regeln, auf die `CLAUDE.md` verweist. Bei Konflikt zwischen dieser Datei
und einer mündlichen User-Anweisung gewinnt der User **nur**, wenn er explizit sagt
„überschreib die Regel". Sonst gilt diese Datei.

**Erzwingungs-Klassen:** 🔒 = hart (Git-Hook blockt) · 📝 = Konvention (Review).

## §1 Kritische Regeln

### 1. Ursache verifizieren, bevor du fixt 📝
Jede Aufgabe nennt eine **belegte** Ursache mit `datei.php:zeile`. Eine Vermutung wird als
Vermutung gekennzeichnet und **vor** dem Fix verifiziert.

### 2. Kein Scope-Creep 📝
Wünsche außerhalb des aktiven Tickets („mach gleich noch X schöner") werden **abgelehnt**
oder als neues Ticket auf dem Board angelegt. Nicht ins laufende Ticket ziehen.

### 3. Einfach und robust statt clever 📝
Keine Service-/Repository-Pattern, keine Events/Jobs, keine abstrakten Basisklassen vor dem
zweiten Use-Case. Drei Zeilen Duplikat sind ok. Ab dem vierten gleichen Block: Helper.

### 4. Reversibilität 🔒
Ein Ticket = ein Commit, per `git revert` rücknehmbar. Keine Commits über Ticket-Grenzen.
Keine Migration ohne `down()`-Methode.

### 5. Grün heißt nicht fertig 🔒
`php artisan test` grün sagt nichts über den echten Klick-Weg. Vor „erledigt": den Weg
**einmal echt im Browser durchklicken** (Server-Test, siehe CLAUDE.md §5).

### 6. Datenbank ist lokal, aber vorsichtig 🔒
DB läuft lokal unter Herd (SQLite/MySQL). Vor `migrate:fresh`/`db:wipe` **immer fragen** –
das löscht alle lokalen Testdaten. Neue Migrationen sind ok, brauchen aber `down()`.

## §2 Schichten-Import-Regeln (Laravel)

| Schicht | Pfad | Darf nutzen | Darf NICHT |
|---|---|---|---|
| Route | `routes/*.php` | Controller, Middleware | Model-Queries inline (dünn halten) |
| Controller | `app/Http/Controllers/**` | FormRequest, Model, View, Enum | Validierung inline (→ FormRequest) |
| FormRequest | `app/Http/Requests/**` | Rules, Enum | Views, Business-Logik |
| Model | `app/Models/**` | andere Models, Casts, Relations | Controller, View, Request |
| Blade View | `resources/views/**` | nur übergebene Daten | DB-Queries / `DB::` im Template |
| Migration/Seeder | `database/**` | Schema, Model | HTTP-Layer |

**Faustregel:** Abhängigkeiten gehen **runter** (Route → Controller → Model), nie hoch.

### Prüfbar per grep (das macht `/task-check`)
```bash
grep -rn "DB::\|->join(\|->where(" resources/views/    # sollte leer sein (keine Queries im Blade)
grep -rn "\$request->validate(" app/Http/Controllers/   # leer: Validierung gehört in FormRequests
```
Stand 2026-07-24: beide sauber.

## §3 Datei-Größen-Budgets (Soft, Warnung)

| Bereich | Limit |
|---|---|
| Controller | ≤ 200 LOC |
| Model | ≤ 250 LOC |
| Blade-View | ≤ 250 LOC |
| FormRequest | ≤ 120 LOC |
| Migration | ≤ 120 LOC |

Budget gerissen → Refactor-Ticket aufs Board, nicht ignorieren.

## §4 Naming-Konvention (Laravel-Standard)

| Element | Muster | Beispiel |
|---|---|---|
| Controller | PascalCase + `Controller` | `ActivityController` |
| Model | PascalCase Singular | `Activity` |
| Tabelle | snake_case Plural | `activities` |
| Migration | `verb_object_table` | `create_activities_table` |
| Route-Name | dot.case | `activities.show` |
| Blade-View | kebab/dot | `activities.show` |
| FormRequest | `VerbObjectRequest` | `StoreActivityRequest` |
| Enum | PascalCase | `AccountType` |

### §4a Branch-Namen 🔒 (Konvention)
| Typ | Muster | Beispiel |
|---|---|---|
| Board-Feature | `feature/ticket-<N>-<slug>` | `feature/ticket-1-registration-login` |
| Bugfix | `fix/<slug>` | `fix/login-redirect` |
| Infrastruktur/Doku | `chore/<slug>` | `chore/workflow-setup` |

Ein Branch pro Ticket. Nach dem Merge wird der Branch gelöscht.

## §5 Domänen-Invarianten 🔒

- **Interessen:** Ein Nutzer muss **mindestens 3** Interessen haben, sonst gilt das Profil als
  unvollständig (`User::hasCompletedProfile()`). Registrierung/Google-Vervollständigung müssen
  das erzwingen.
- **Kontotyp:** immer aus dem Enum `App\AccountType` (`personal`/`business`), nie hartcodierte
  Strings.
- **Passwort:** min. 8 Zeichen, Buchstaben **und** Zahlen (`Password::min(8)->letters()->numbers()`).
- **Eindeutigkeit:** E-Mail und Username sind unique – Validierung im FormRequest, nicht nur DB.

## §6 Fehler-Konvention

- Validierung immer im **FormRequest**, nicht im Controller.
- Deutsche Fehlermeldungen für den Nutzer (siehe `messages()` in den Requests).
- Redirects nach Aktionen mit `->with('status', ...)` für Feedback.

## §6a Kommentare für menschlichen Kontext 📝

Der User (Nicht-Techniker) liest den Code grob mit. Deshalb in **neuem/geändertem** Code:
- Kurze **deutsche** Kommentare, die das **Warum** erklären (nicht das offensichtliche Was).
- Über nicht-trivialen Blöcken (Validierungslogik, Flows, Bedingungen) 1 Satz Kontext.
- Nicht zukleistern – ein Kommentar pro sinnvoller Einheit reicht.
- Zusätzlich pro Ticket ein Eintrag in `AENDERUNGEN.md` (mit Code-Auszügen) für das Gegenlesen.

## §7 Commit-Konvention 🔒

- Format: `<typ>(<bereich>): <Kurzbeschreibung>`
- Typen: `feat`, `fix`, `refactor`, `docs`, `chore`, `style`, `security`, `perf`, `test`.
- **Ein Commit pro Ticket.**
- Kein `@` als erstes Zeichen im Subject – der `commit-msg`-Hook lehnt das ab.
- Referenz aufs Board-Ticket im Body: `Ticket #<N> (Board Projects/2).`

## §8 Änderungs-Workflow (Schnell-Modus, Branch + PR)

**Ticket-Vorschlag → 1× User-OK → Branch anlegen → Umsetzen → `/task-check` → Server-Test →
Commit → Branch pushen → PR öffnen → Meldung + PR-Link → User prüft → Merge nur mit OK.**

- **Niemals direkt auf `main`.** Pro Ticket ein Branch `feature/ticket-<N>-<slug>` (§4a).
- **Ein OK am Ticket-Start** genügt; danach inkl. Test, Commit, Push und PR-Öffnen keine
  weitere Rückfrage – aber **kein Merge** ohne ausdrücklichen User-OK.
- **Stopp-Pflicht** trotz Schnell-Modus: `migrate:fresh`/Datenlöschung, Secrets/`.env`,
  externe Dienste live schalten, Scope-Änderungen, Regelwerk-Änderungen, **Merge nach main**.
- Jede Änderung wird in `proc` (technisch) und `AENDERUNGEN.md` (für den User) protokolliert.

**Erzwingbarkeit (ehrlich):** Hart sind nur die Git-Hooks (commit-msg, pre-push/Pint).
Alles andere ist Konvention; das Sicherheitsnetz ist Regel #4 (`git revert`).
