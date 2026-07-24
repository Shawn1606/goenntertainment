---
description: Ticket abschließen – Acceptance geprüft, /task-check grün, Server-Test erfolgt, Board-Status auf Done, proc-Eintrag, Commit + Push.
---

# /ticket-done

Schließt ein Ticket ab. Argument: Ticket-Nummer (oder das gerade bearbeitete).

## 1. Abnahme prüfen (`TASK_SYSTEM.md` §3)
- [ ] Alle Acceptance-Criteria der Ticket-Beschreibung erfüllt
- [ ] `/task-check` grün (Pint ≤ Baseline, Tests grün, Schichten clean)
- [ ] Weg **einmal echt im Browser** durchgeklickt (Server-Test)

Ist etwas nur „müsste gehen" → **nicht abschließen**, melden.

## 2. Logs schreiben
- **`proc`** (technisch, knapp): Datum, Ticket-Nr + Titel, geänderte Dateien, was getan,
  was getestet. Format wie die bestehenden Einträge.
- **`AENDERUNGEN.md`** (für den User, ausführlich): neuen Eintrag unten anhängen mit
  Datum + Ticket, „Worum ging's" in einfachen Worten, Tabelle der geänderten Dateien und
  **1–3 wichtigen Code-Auszügen** mit Erklärung, was der Code tut und warum. So kann der
  User den Code grob gegenlesen.

## 3. Board-Status
Solange der PR offen ist: Ticket bleibt **In progress**. Erst **nach dem Merge nach `main`**
(mit User-OK) auf `Done` setzen (wie `/ticket-start`, aber `Done`-Option-ID).

## 4. Commit, Branch-Push & Pull Request (ein Commit pro Ticket)
**Niemals nach `main` pushen.** Auf dem Ticket-Branch committen, den Branch pushen, PR öffnen:
```bash
git commit -m "feat(<bereich>): <Kurzbeschreibung>" \
           -m "Ticket #<N> (Board Projects/2) erledigt. <geaenderte Bereiche>."
git push -u origin feature/ticket-<N>-<slug>
gh pr create --base main --title "Ticket #<N>: <Titel>" \
  --body "Ticket #<N> (Board Projects/2). Getestet: <Pint/Tests/Browser>. Siehe AENDERUNGEN.md."
```
**Nie** PowerShell-Here-Strings (`@'...'@`) im Bash-Tool.
Pint unter Baseline gefallen → `.githooks/pint-baseline.txt` im selben Commit mit-senken.

## 5. Merge – NICHT eigenmächtig
Merge nach `main` **nur nach ausdrücklichem User-OK**. Bis dahin dem User den PR-Link geben
und um Prüfung bitten.

## Ausgabe
```
✅ Ticket umgesetzt: #<N> – <Titel>  (wartet auf deine Prüfung)
- Dateien:   <Liste + Diff-Stat>
- Getestet:  <Pint / Tests / Browser-Weg>
- Board:     Done
- Branch:    feature/ticket-<N>-<slug>  •  PR: <URL>
👉 Bitte prüfen. Merge mache ich erst nach deinem OK.
```
