---
description: Pflicht-Check vor jedem Code-Commit – Laravel Pint gegen Baseline, Pest-Tests, Schichten-Hygiene.
---

# /task-check

Pflicht vor **jedem Commit, der Code ändert**. Bei ❌ wird **nicht** committet – erst den
User informieren. PHP in git-bash über `php.bat` (Herd), sonst PowerShell mit Herd-PATH.

## 1. Pint (Stil, gegen Baseline)
```bash
php.bat vendor/bin/pint --test
```
Anzahl „dirty" Dateien vs. `.githooks/pint-baseline.txt` (aktuell 4).

| Ergebnis | Bedeutung |
|---|---|
| ≤ Baseline | ✅ ok |
| > Baseline | ❌ blockiert – `php vendor/bin/pint` ausführen und erneut prüfen |
| < Baseline | ✅ super – Baseline mit-senken und im Commit erwähnen |

## 2. Tests
```bash
php.bat artisan test
```
Muss **grün** sein (0 failed).

## 3. Schichten-Hygiene (`PROJECT_RULES.md` §2)
Beide müssen **leer** sein:
```bash
grep -rn "DB::\|->join(\|->where(" resources/views/
grep -rn "\$request->validate(" app/Http/Controllers/
```

## Ausgabe
```
## Task-Check: Ticket #<N>
✅/❌ Pint:      <D dirty (Baseline B)>
✅/❌ Tests:     <P passed / F failed>
✅/❌ Schichten: <clean | Verstöße>
→ <Commit darf raus | Commit blockiert: behebe die ❌ zuerst>
```

## Was dieser Check NICHT tut
- Kein Browser-Klick-Test – der ist manuell (Server-Test, Abnahme `TASK_SYSTEM.md` §3).
- Keine Logik-Review.
