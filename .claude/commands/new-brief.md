---
description: Legt einen Detail-Brief für ein Board-Ticket an (tasklists/briefs/<NN>-<slug>.md) mit Problem, verifizierter Ursache, Vorgehen, Akzeptanzkriterien, Fallstricken.
---

# /new-brief

Erstellt `tasklists/briefs/<NN>-<slug>.md` für ein Ticket, das mehr Detail braucht als die
Board-Beschreibung. Argument: Ticket-Nummer.

## Vorgehen
1. Ticket-Body vom Board holen (`/ticket-detail`-Query).
2. Ist-Stand im Code prüfen und **verifizierte** Ursache/Ausgangslage mit `datei.php:zeile`
   notieren – keine Vermutung ohne Kennzeichnung.
3. Datei nach `TASK_SYSTEM.md` §5 anlegen:

```markdown
# <NN> – <Titel>

## Problem
<Was der User will, einfach.>

## Ausgangslage (verifiziert)
`app/...php:zeile` – <was da ist / fehlt>.

## Vorgehen
1. <Schritt>

## Akzeptanzkriterien
- [ ] <prüfbar>

## Fallstricke
- <was schiefgeht>
```

Ein Brief ohne Ticket auf dem Board ist unsichtbar – beides muss existieren.
