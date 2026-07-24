---
description: Zeigt alle Tickets des GitHub-Boards Projects/2 mit Nummer, Titel und Status als Tabelle. Read-only.
---

# /board-status

Read-only. **Keine** Änderungen.

## Vorgehen
```bash
gh project item-list 2 --owner Shawn1606 --format json --limit 50 \
  --jq '.items[] | "\(.content.number)\t[\(.status // "no-status")]\t\(.title)"'
```
Nach Nummer sortieren.

## Ausgabe
```
## Board Projects/2 – Übersicht
**Erledigt:** <X> / 18

| # | Ticket | Status |
|---|--------|--------|
| 1 | ...    | ...    |

**Nächstes offenes:** #<N> – <Titel>
```
