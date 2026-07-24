# TASK_SYSTEM – Wie Tickets in diesem Projekt geführt werden

> Meta-Doku. Die konkreten Aufgaben stehen im **GitHub-Projects-Board #2**, nicht im Repo.

## §1 Wo leben Tickets

- **Quelle der Wahrheit:** das GitHub-Board `https://github.com/users/Shawn1606/projects/2`
  (privat, 18 Tickets, nummeriert 1–18). Der User hat es explizit als Tasklist bestimmt –
  das schlägt die „keine externen Tools"-Regel aus der Ursprungs-Vorlage.
- **Zugriff:** `gh` CLI (installiert, User mit `project`-Scope authentifiziert).
  ```bash
  gh project item-list 2 --owner Shawn1606 --format json --limit 50
  ```
- **Briefs (optional):** Braucht ein Ticket mehr als ein paar Zeilen Detail (verifizierte
  Ursache, Vorgehen, Fallstricke), lege ich `tasklists/briefs/<NN>-<slug>.md` an. Das Board
  ist die **Landkarte**, der Brief die **Detailkarte**.
- **`proc`** (Repo-Root): laufendes Änderungs-Log für künftige AI-Sessions – jede Änderung dort.
- **Nie** im session-lokalen Task-Tool des Agenten (nach Chat-Ende weg).

## §2 Reihenfolge & Lebenszyklus

- Tickets werden **in Nummern-Reihenfolge** abgearbeitet (1, 2, 3 …).
- Board-Status-Feld (Single-Select): `Backlog → In progress → Done`.
- **Ticket-Start:** Status auf `In progress` (Skill `/ticket-start`).
- **Ticket-Ende:** Status auf `Done`, Commit + Push, `proc`-Eintrag (Skill `/ticket-done`).

```
Backlog  ──/ticket-start──▶  In progress  ──/ticket-done──▶  Done
```

## §3 Abnahme-Kriterien (wann ist ein Ticket „grün")

Ein Ticket ist **erst erledigt**, wenn:
1. Alle Acceptance-Criteria aus der Ticket-Beschreibung erfüllt sind.
2. `php vendor/bin/pint --test` nicht schlechter als Baseline (`.githooks/pint-baseline.txt`).
3. `php artisan test` grün.
4. Der Weg wurde **einmal echt im Browser durchgeklickt** (Server-Test, CLAUDE.md §5).
5. `proc` aktualisiert, Board-Status auf `Done`.

## §4 Wer darf was (nach Modell)

| Wer | Darf | Darf nicht |
|---|---|---|
| **User** | Alles | – |
| **Opus (groß)** | Tickets umsetzen, Briefs schreiben, Architektur | Feature-Wünsche eigenmächtig zu Tickets machen |
| **Sonnet (mittel)** | Eng gefasste Tickets umsetzen, abhaken | Board-Reihenfolge ändern, neue Tickets erfinden |
| **Haiku (klein)** | Kleinstfixes, Status abhaken | Tickets umschreiben, Migrationen |

## §5 Format eines Briefs (`tasklists/briefs/<NN>-<slug>.md`)

1. **Problem** – was der User will, in einfachen Worten.
2. **Ursache / Ausgangslage (verifiziert)** – mit `datei.php:zeile`.
3. **Vorgehen** – konkrete Schritte.
4. **Akzeptanzkriterien** – als `- [ ]`, prüfbar.
5. **Fallstricke** – was typischerweise schiefgeht.

## §6 Anti-Pattern

- Tickets im Chat abhandeln, Board-Status nicht nachziehen → nächster Chat weiß nichts.
- Mehrere Tickets in einem Commit → `git revert`-Granularität kaputt.
- „Müsste jetzt gehen" als erledigt behandeln → nicht geklickt = nicht fertig.
- Änderung nicht in `proc` → künftige AI tappt im Dunkeln.
