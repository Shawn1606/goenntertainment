---
description: Zeigt ein einzelnes Board-Ticket (Projects/2) im Detail – Beschreibung, Requirements und Acceptance-Criteria. Read-only.
---

# /ticket-detail

Read-only. Argument: Ticket-Nummer.

## Vorgehen
```bash
gh project item-list 2 --owner Shawn1606 --format json --limit 50 \
  --jq '.items[] | select(.content.number==<N>) | {title, status, body: .content.body}'
```

## Ausgabe (Deutsch, kompakt)
```
## Ticket #<N> – <Titel>   (Status: <status>)
**Requirements:** <Liste>
**Acceptance-Criteria:** <Liste als - [ ]>
```
Kein Code anfassen – nur anzeigen.
