---
description: Gleicht das lokale proc-Log und den Code-Stand mit den Board-Status (Projects/2) ab und meldet Abweichungen. Read-only, ändert nichts ohne Rückfrage.
---

# /sync-board

Prüft, ob Board-Status, `proc`-Log und Code-Realität zusammenpassen. **Meldet** Abweichungen,
korrigiert nichts stillschweigend.

## Vorgehen
1. Board lesen (`gh project item-list 2 ...`).
2. `proc` lesen – welche Tickets sind dort als erledigt dokumentiert?
3. Für „Done"-Tickets stichprobenartig prüfen, ob der Code das hergibt.
4. Abweichungen als Tabelle melden:

```
| # | Board | proc | Code | Hinweis |
|---|-------|------|------|---------|
```

## Regeln
- Nur bei ausdrücklichem User-OK einen Board-Status ändern.
- Differenz ist die Information – nicht wegbügeln.
