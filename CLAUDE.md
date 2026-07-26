# Claude Code – Operative Anweisungen (Gönntertainment)

> Dies ist der **Index**, nicht das ganze Regelwerk. Volltexte siehe verlinkte Docs.

> **Antwort-Stil (hart):** Immer kurz, einfach, alltagssprachlich – keine Fachsprache.
> Status und Ergebnisse **als Tabelle**, nicht als Fließtext. Auch beim Erklären:
> max. 3–5 kurze Sätze oder eine kleine Tabelle. Lieber zu kurz; der User fragt nach.
> **Sprache: Deutsch.**

## 0. RICHTUNGSWECHSEL (gilt vor allem anderen)

- **Einziger Arbeitsort: das Expo-App-Projekt** `C:\Users\shawn\Herd\goenntertainment-app`.
  Am Laravel-Projekt wird **nicht mehr entwickelt**.
- **Backend zieht in die App:** Das gesamte Backend wird Schritt für Schritt von
  PHP/Laravel nach **JS/TS** ins App-Projekt umgeschrieben (Expo-Router API-Routes).
  Ziel: Laravel wird vollständig abgelöst.
- **Laravel = nur Übergang:** dient bis dahin nur noch als Datenquelle/Vorlage.
  Danach hat es keinen Zweck mehr. Kein neues Feature, kein Fix in Laravel.
- **Rückverfolgung ausschließlich im App-Projekt:** Schritte werden in
  `change/ai.md` (für AI) bzw. `change/human.md` (für dich) geführt – nicht mehr
  in Laravel `proc`/`AENDERUNGEN.md`.
- **Checks im App-Kontext:** statt Pint/Pest gelten `expo lint`, TypeScript-Check
  und JS-Tests. (Die alten `/task-check`-PHP-Schritte entfallen hier.)
- Branch-Regeln, Stopp-Pflichten und die 5 kritischen Regeln gelten unverändert –
  nur eben im App-Projekt.

## 1. Was das hier ist

**Gönntertainment** – Social-Plattform für lokale „Activities" (Nutzer erstellen/beitreten
Aktivitäten, Interessen, Business-Accounts). Laravel 13 / PHP 8.4, Blade + Tailwind (Vite),
Pest, Laravel Socialite (Google-Login). Läuft lokal unter **Laravel Herd** (Windows).

| Thema | Doku |
|---|---|
| Coding-Regeln & Verbote | `PROJECT_RULES.md` |
| Wie Tickets geführt werden | `TASK_SYSTEM.md` |
| Änderungs-Log (für künftige AI) | `proc` |
| Review-Log für den User (mit Code) | `AENDERUNGEN.md` – **vor jedem Task lesen**, nach jedem Task ergänzen |
| Aufgaben (Tickets) | GitHub-Board `Projects/2` |

## 2. Branch- & Änderungs-Workflow (hart)

- **Niemals direkt auf `main`.** Pro Ticket ein eigener Branch: `feature/ticket-<N>-<slug>`
  (bzw. `fix/`, `chore/`, `refactor/` je nach Typ).
- **Schnell-Modus:** Ein User-OK am **Ticket-Start**, danach autonom: Branch anlegen →
  umsetzen → testen → committen → Branch pushen → **Pull Request öffnen**.
- **Am Ende:** kurze Ergebnis-Meldung (Dateien + Diff-Stat + was getestet) **+ PR-Link**.
  Der User prüft. **Merge nach `main` erst nach ausdrücklichem User-OK** – ich merge nie
  eigenmächtig.
- **Stopp-Pflicht trotz Schnell-Modus:** `migrate:fresh`/Datenlöschung, Secrets/`.env`,
  externe Dienste live, Scope-Änderungen, Regelwerk-Änderungen → **immer vorher fragen**.
- **Regelwerk-Änderungen** (`CLAUDE.md`, `PROJECT_RULES.md`, `TASK_SYSTEM.md`): Text vorab zeigen + OK.

**Commit-Messages:** Im **Bash-Tool niemals** PowerShell-Here-String `@'...'@` für `git commit`
– das Subject wird zu `@` und der `commit-msg`-Hook lehnt es ab. Stattdessen mehrere
`-m`-Flags oder Heredoc. Bei frischem Clone einmal: `git config core.hooksPath .githooks`.

## 3. Die 5 kritischen Regeln

1. **Ursache verifizieren, bevor du fixt** – belegte Ursache mit `datei.php:zeile`.
2. **Kein Scope-Creep** – „mach gleich noch X" wird zum neuen Board-Ticket.
3. **Einfach und robust statt clever** – kein Overengineering vor dem zweiten Use-Case.
4. **Reversibel** – ein Ticket = ein Commit, per `git revert` rücknehmbar.
5. **Grün heißt nicht fertig** – vor „erledigt" den Weg **einmal echt im Browser** klicken.

Volltext: `PROJECT_RULES.md`.

## 4. Aufgaben-Management (Board-getrieben)

- Tickets leben im **GitHub-Board Projects/2**, gelesen/geschrieben über `gh` CLI.
- Abgearbeitet wird **in Nummern-Reihenfolge** (1 → 18).
- Lebenszyklus, Abnahme, wer darf was: `TASK_SYSTEM.md`.

| Skill | Zweck |
|---|---|
| `/next-ticket` | Nächstes offenes Ticket vorlegen (Modell + Plan → 1× OK → autonom) |
| `/ticket-start` | Board-Status auf „In progress" |
| `/task-check` | Pflicht-Check vor Commit: Pint + Pest + Schichten |
| `/ticket-done` | Board-Status „Done" + Commit + Push + proc-Eintrag |
| `/board-status` | Alle Tickets + Status (read-only) |
| `/ticket-detail` | Ein Ticket im Detail (Beschreibung + Acceptance) |
| `/new-brief` | Detail-Brief für ein Ticket anlegen |
| `/sync-board` | proc-Log ↔ Board-Status abgleichen |

## 5. Vor jeder Änderung / vor „erledigt"

| Wann | Was | Befehl |
|---|---|---|
| Vor Commit | Stil-Check | `php vendor/bin/pint --test` (fix: `php vendor/bin/pint`) |
| Vor Commit | Tests | `php artisan test` |
| Vor „erledigt" | Server-Test | App im Browser aufrufen + Weg durchklicken |
| Bei DB-Änderung | Migration + `down()` | `php artisan migrate` (kein `fresh` ohne OK) |

**PHP-Pfad:** In git-bash ist `php` nicht auf dem PATH – nutze `php.bat` (Herd) oder
PowerShell mit `$env:Path += ";C:\Users\shawn\.config\herd\bin"`.

## 6. Umgebung

- **DB:** lokal unter Herd (siehe `.env`), keine Produktions-DB.
- **Externe Dienste:** Google OAuth (Socialite) – Zugangsdaten in `.env`, Test-Modus.
- **Board-Zugriff:** `gh` CLI, User `Shawn1606` mit `project`-Scope.
