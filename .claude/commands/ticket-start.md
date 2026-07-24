---
description: Setzt ein Board-Ticket (Projects/2) auf Status "In progress" via gh CLI.
---

# /ticket-start

Setzt den Board-Status eines Tickets auf **In progress**. Argument: Ticket-Nummer.

## Vorgehen
1. Projekt- und Feld-IDs holen (einmal pro Session cachen):
   ```bash
   gh project view 2 --owner Shawn1606 --format json          # projectId
   gh project field-list 2 --owner Shawn1606 --format json    # Status-Feld + Options
   ```
2. Item-ID des Tickets über `gh project item-list 2 --owner Shawn1606 --format json` finden
   (`.items[] | select(.content.number==<N>) | .id`).
3. Status setzen:
   ```bash
   gh project item-edit --id <ITEM_ID> --project-id <PROJECT_ID> \
     --field-id <STATUS_FIELD_ID> --single-select-option-id <IN_PROGRESS_OPTION_ID>
   ```
4. Kurz bestätigen: „Ticket #<N> steht jetzt auf In progress."

## Regeln
- Nur setzen, wenn wirklich mit der Umsetzung begonnen wird.
- Fehler von `gh` (fehlende Rechte/Scope) an den User melden, nicht raten.
