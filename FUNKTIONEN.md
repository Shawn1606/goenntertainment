# Funktions-Übersicht (Gönntertainment)

> Stand: 2026-07-25. Was schon da ist, was fehlt, was noch kommen soll.
> Betrifft **App** (`goenntertainment-app`) und **Backend** (`goenntertainment`, Laravel).

## 1. Geht schon (im Code vorhanden)

| Funktion | Wo | Anmerkung |
|---|---|---|
| Registrieren (Name, Username, E-Mail, Passwort, Konto-Typ) | App + Backend | Läuft |
| Login + Google-Login | App + Backend | Läuft |
| Event erstellen: Titel, Beschreibung, Ort, Datum, Uhrzeit | App + Backend | Alle Felder da |
| Event-Foto aus **Galerie** oder **Kamera** | App | Code ist da (`create-activity.tsx`) |
| Interessen bei **Event-Erstellung** wählen | App + Backend | Code ist da (Chips) |
| Events auflisten (Start/Explore) | App + Backend | Läuft |

## 2. Fehlt / muss geprüft werden

| # | Funktion | Wo | Warum wichtig |
|---|---|---|---|
| A | Interessen bei der **Account-Erstellung** wählen | App + Backend | Fehlt komplett im Register-Screen |
| B | Foto-/Interessen-Auswahl in der **Web-Vorschau** | App | Verdacht: `Alert`-Auswahl-Dialog geht im Browser nicht → wirkt „kaputt" |
| C | Datum/Uhrzeit per **Picker** statt Texteingabe | App | Tippen von „TT.MM.JJJJ" ist fehleranfällig |
| D | Event **beitreten** (Join) | App + Backend | Kernidee der Plattform; Backend hat noch keinen Join-Endpoint |

## 3. Soll in Zukunft rein (Backlog)

| Funktion | Kurz |
|---|---|
| Event **bearbeiten / löschen** | Ersteller kann sein Event ändern |
| **Profil** ansehen / bearbeiten, Avatar-Foto | Eigenes Profil pflegen |
| **Business-Account**-Funktionen | Was kann Business, was Personal? |
| **Suche / Filter** nach Interessen & Ort | Passende Events finden |
| **Karte** mit Event-Standorten | Ort visuell |
| **Benachrichtigungen** (Push) | Erinnerung an Events |
| Teilnehmer-Liste je Event | Wer ist dabei? |

---

*Diese Liste ist eine Übersicht. Umgesetzt wird pro Punkt ein eigenes Board-Ticket
(Projects/2). Reihenfolge und Umfang stimmt der User ab.*
