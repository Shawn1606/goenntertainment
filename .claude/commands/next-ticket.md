---
description: Liest das GitHub-Board Projects/2, schlägt das nächste offene Ticket (kleinste Nummer, nicht Done) samt Plan und Modell-Empfehlung vor. Nach 1× User-OK wird es autonom umgesetzt, getestet, committet und der Board-Status gepflegt.
---

# /next-ticket

Du arbeitest das **GitHub-Board Projects/2** in Nummern-Reihenfolge ab. Aufgabe: das
**nächste offene Ticket** finden, verstehen und dem User mit Plan vorlegen. **Erst nach OK**
Code anfassen (Schnell-Modus, `PROJECT_RULES.md` §8).

## Vorgehen

0. **`AENDERUNGEN.md` lesen** – den letzten Eintrag, um zu wissen, wo wir stehen und was
   zuletzt geändert wurde (menschenlesbares Review-Log).
1. **Board lesen:**
   ```bash
   gh project item-list 2 --owner Shawn1606 --format json --limit 50
   ```
   PATH ggf. um `/c/Program Files/GitHub CLI` ergänzen.
2. **Nächstes Ticket wählen:** kleinste `content.number`, deren `status` nicht `Done` ist.
   Ein Ticket mit `In progress` hat Vorrang vor `Backlog`.
3. **Beschreibung + Acceptance lesen** (`content.body`). Das ist die Wahrheit.
4. **Brief prüfen:** existiert `tasklists/briefs/<NN>-*.md`, ganz lesen.
5. **Ist-Stand im Code prüfen:** was ist schon da? (Controller/Model/Views/Migrationen).
   Bei Ticket-Teilen, die schon existieren, das im Plan erwähnen.
6. **Scope/Modell schätzen:** wie viele Dateien, grob LOC → Empfehlung klein/mittel/groß.
7. **Ausgabe an User** (kurz, Deutsch, Tabelle):
   ```
   ## Nächstes Ticket: #<N> – <Titel>   (Board-Status: <status>)
   **Modell:** <klein|mittel|groß>  •  **Scope:** ~<N> Dateien
   **Was:** <1–2 Sätze>
   **Schon da:** <was existiert / "nichts">
   **Fehlt:** <konkrete Punkte>
   **Braucht Migration:** <ja/nein>  •  **Stopp-Pflicht:** <ja: was / nein>
   **Soll ich starten? (ja / nein / anderes Ticket)**
   ```
8. **Auf OK warten** – einziges Gate. Nach OK:
   ```bash
   git switch main && git switch -c feature/ticket-<N>-<slug>   # nie direkt auf main
   ```
   dann `/ticket-start` → umsetzen → `/task-check` → Server-Test → `/ticket-done`.

## Regeln
- **Niemals direkt auf `main`** – immer erst den Ticket-Branch anlegen (`PROJECT_RULES.md` §4a).
- Reihenfolge nur mit User-OK überspringen.
- Stopp-Pflicht trotz Schnell-Modus: siehe `PROJECT_RULES.md` §8.
