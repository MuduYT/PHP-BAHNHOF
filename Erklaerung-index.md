# Erklärung zu `index.php`

Diese Erklärung folgt der aktuellen [index.php](index.php). Die Zeilenangaben beziehen sich auf den Stand beim Erstellen dieses Dokuments. Leere Zeilen dienen nur der Übersicht und werden nicht einzeln erklärt.

## Ablauf auf einen Blick

1. PHP definiert die Klassen `Bahnhof`, `Bahnsteig` und `Zug`.
2. `createBahnhof()` erstellt vier Beispielzüge und verteilt sie auf drei Bahnsteige.
3. Erst wenn das Formular abgeschickt wurde, wird der Bahnhof erstellt.
4. PHP gibt die HTML-Seite aus. Wenn ein Bahnhof existiert, zeigt sie Destinationen und Wagenzahlen an.

## Zeichen und Schreibweisen

- `$` kennzeichnet PHP-Variablen und Objekteigenschaften, zum Beispiel `$bahnhof`.
- `->` greift auf eine Eigenschaft oder Methode eines Objekts zu: `$zug->anzahlWagen()`.
- `{` und `}` begrenzen Klassen, Methoden, Schleifen und Bedingungen.
- `;` beendet eine PHP-Anweisung.
- `//` beginnt einen Kommentar, den PHP nicht ausführt.
- `.` verbindet Zeichenketten. `+=` addiert einen Wert zu einer bestehenden Zahl und speichert die Summe wieder in derselben Variablen.

## Bahnhof-Klasse — Zeilen 1–51

Die Klasse `Bahnhof` speichert mehrere Bahnsteige und berechnet Summen über alle darin enthaltenen Züge.

```php
class Bahnhof
{
    /** @var Bahnsteig[] Alle Bahnsteige dieses Bahnhofs. */
    private array $bahnsteige = [];

    public function addBahnsteig(Bahnsteig $bahnsteig): void
    {
        $this->bahnsteige[] = $bahnsteig;
    }

    public function anzahlZuege(): int
    {
        $gesamtanzahlZuege = 0;
        foreach ($this->bahnsteige as $bahnsteig) {
            $gesamtanzahlZuege += $bahnsteig->anzahlZuege();
        }
        return $gesamtanzahlZuege;
    }
}
```

- **Zeile 1 `<?php`** beginnt den PHP-Code.
- **Zeile 3 `class Bahnhof`** definiert einen Bauplan für Bahnhof-Objekte.
- **Zeile 5** ist ein PHPDoc-Kommentar. `@var Bahnsteig[]` beschreibt, dass das Array Bahnsteig-Objekte enthalten soll. Das ist eine Lesehilfe für Menschen und Editoren.
- **Zeile 6** deklariert `$bahnsteige`. `private` schützt die Eigenschaft vor direktem Zugriff von außerhalb der Klasse. `array` ist der Typ. `[]` erstellt ein leeres Array.
- **Zeilen 9–12 `addBahnsteig()`** fügen einen Bahnsteig hinzu. Der Parametertyp `Bahnsteig` begrenzt den übergebenen Wert auf ein Bahnsteig-Objekt. `public` erlaubt den Aufruf von außerhalb. `: void` bedeutet, dass die Methode keinen Wert zurückgibt.
- **Zeile 11 `$this`** meint das aktuelle Bahnhof-Objekt. `$this->bahnsteige[] = ...` hängt den Bahnsteig an das Array dieses Objekts an.
- **Zeilen 15–18 `getBahnsteige()`** geben das Bahnsteig-Array zurück. `: array` sagt, dass die Methode ein Array liefert. `return` gibt den Wert zurück.
- **Zeilen 21–26 `destinationsListeDrucken()`** durchlaufen die Bahnsteige und delegieren die Zielausgabe an jeden Bahnsteig. `foreach` verarbeitet nacheinander alle Array-Einträge.
- **Zeilen 29–38 `anzahlZuege()`** addieren die Zugzahlen aller Bahnsteige. Die Summe startet bei `0`; `+=` erhöht sie in jeder Schleifenrunde. Am Ende liefert `return` die Gesamtsumme.
- **Zeilen 41–50 `anzahlWagen()`** verwenden dasselbe Muster, um die Wagenzahlen aller Bahnsteige zu addieren.
- **Zeile 51 `}`** schließt die Klasse.

## Bahnsteig-Klasse — Zeilen 53–95

Ein Bahnsteig speichert seine Züge. Außerdem kann er die Destinationen und Summen für genau diese Züge ausgeben beziehungsweise berechnen.

```php
class Bahnsteig
{
    /** @var Zug[] */
    private array $zuege = [];

    public function addZug(Zug $zug): void
    {
        $this->zuege[] = $zug;
    }

    public function anzahlWagen(): int
    {
        $gesamtanzahlWagen = 0;
        foreach ($this->zuege as $zug) {
            $gesamtanzahlWagen += $zug->anzahlWagen();
        }
        return $gesamtanzahlWagen;
    }
}
```

- **Zeile 53 `class Bahnsteig`** beginnt die Bahnsteig-Klasse.
- **Zeilen 55–56** deklarieren das private Array `$zuege`, das anfangs leer ist.
- **Zeilen 59–62 `addZug()`** hängen ein Zug-Objekt an dieses Array an.
- **Zeilen 65–68 `getZuege()`** geben die gespeicherten Züge zurück. Die Methode wird später von der HTML-Ausgabe verwendet.
- **Zeilen 71–76 `destinationsListeDrucken()`** gehen mit `foreach` die Züge durch. `getDestination()` liefert das Ziel des aktuellen Zuges. `echo` schreibt es in die HTML-Antwort; `<br>` sorgt für einen Zeilenumbruch.
- **`htmlspecialchars(..., ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`** schützt die Ausgabe, indem HTML-Sonderzeichen escaped werden. `ENT_QUOTES` berücksichtigt einfache und doppelte Anführungszeichen. `ENT_SUBSTITUTE` ersetzt ungültige Zeichen. Das `|` verbindet die Optionen bitweise.
- **Zeilen 79–82 `anzahlZuege()`** zählen die Züge mit `count($this->zuege)`.
- **Zeilen 85–94 `anzahlWagen()`** summieren die Wagenzahlen der Züge auf diesem Bahnsteig. Die Zahl jedes Zuges kommt aus `$zug->anzahlWagen()`.
- **Zeile 95 `}`** schließt die Klasse.

## Zug-Klasse — Zeilen 97–118

Ein Zug speichert sein Ziel und seine Wagenzahl.

```php
class Zug
{
    private string $destination;
    private int $anzahlWagen;

    public function __construct(string $destination, int $anzahlWagen)
    {
        $this->destination = $destination;
        $this->anzahlWagen = $anzahlWagen;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function anzahlWagen(): int
    {
        return $this->anzahlWagen;
    }
}
```

- **Zeile 97 `class Zug`** definiert den Bauplan für Zug-Objekte.
- **Zeilen 99–100** deklarieren zwei private Eigenschaften: `string` steht für Text und `int` für eine ganze Zahl.
- **Zeilen 103–107 `__construct()`** definieren den Konstruktor. PHP ruft ihn automatisch auf, wenn mit `new Zug(...)` ein Zug erstellt wird. Die übergebenen Werte werden mit `$this` in den Eigenschaften des gerade erstellten Objekts gespeichert.
- **Zeilen 109–112 `getDestination()`** geben das private Ziel zurück. Die Methode bietet kontrollierten Zugriff auf die Eigenschaft.
- **Zeilen 114–117 `anzahlWagen()`** geben die private Wagenzahl zurück. Die Eigenschaft `$anzahlWagen` und die Methode `anzahlWagen()` können denselben Namen haben: Eigenschaften werden mit `$` angesprochen, Methoden mit Klammern.
- **Zeile 118 `}`** schließt die Klasse.

## Beispielbahnhof aufbauen — Zeilen 120–144

```php
function createBahnhof(): Bahnhof
{
    $zugBern = new Zug("Bern", 8);
    $zugParis = new Zug("Paris", 6);
    $zugRom = new Zug("Rom", 10);
    $zugWien = new Zug("Wien", 12);

    $bahnsteigEins = new Bahnsteig();
    $bahnsteigEins->addZug($zugWien);
    // ... weitere Bahnsteige und Züge

    $bahnhof = new Bahnhof();
    $bahnhof->addBahnsteig($bahnsteigEins);
    // ... weitere Bahnsteige hinzufügen

    return $bahnhof;
}
```

- **Zeile 121 `function createBahnhof(): Bahnhof`** definiert eine Funktion. `: Bahnhof` legt fest, dass sie ein Bahnhof-Objekt zurückgibt.
- **Zeilen 123–126** erstellen vier Züge. `new` erzeugt ein neues Objekt und ruft dabei den Konstruktor auf. Die beiden Argumente sind Destination und Wagenzahl.
- **Zeilen 128–136** erstellen drei Bahnsteige und ordnen die Züge zu: Wien kommt auf Bahnsteig 1, Rom auf Bahnsteig 2, Bern und Paris auf Bahnsteig 3.
- **Zeilen 138–141** erstellen den Bahnhof und fügen ihm alle drei Bahnsteige hinzu.
- **Zeile 143 `return $bahnhof`** gibt das fertige Bahnhof-Objekt zurück.
- **Zeile 144 `}`** schließt die Funktion.

## Formular absenden und PHP beenden — Zeilen 146–152

```php
$bahnhof = null;

if (isset($_POST['neueZuege'])) {
    $bahnhof = createBahnhof();
}
?>
```

- `$bahnhof = null` bedeutet, dass zunächst noch kein Bahnhof erstellt wurde.
- `$_POST` ist eine eingebaute PHP-Superglobal-Variable. Sie enthält Daten, die ein Formular mit der Methode POST gesendet hat.
- `isset($_POST['neueZuege'])` prüft, ob das Formularfeld `neueZuege` mitgesendet wurde.
- Wenn die Bedingung wahr ist, wird `createBahnhof()` aufgerufen und das Ergebnis in `$bahnhof` gespeichert.
- `if (...) { ... }` führt den Inhalt der geschweiften Klammern nur aus, wenn die Bedingung erfüllt ist.
- `?>` beendet den PHP-Abschnitt. Danach beginnt das HTML-Dokument.

## HTML-Grundgerüst und Formular — Zeilen 153–168

```html
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bahnhof</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="card">
        <h1>Bahnhof</h1>
        <form method="post">
            <button type="submit" name="neueZuege">Züge erstellen</button>
        </form>
    </main>
</body>
</html>
```

- `<!DOCTYPE html>` teilt dem Browser mit, dass die Seite HTML5 verwendet.
- `<html lang="de">` legt Deutsch als Dokumentsprache fest.
- `<head>` enthält Seiteneinstellungen und Metadaten, die nicht als normaler Seiteninhalt erscheinen.
- `charset="utf-8"` ermöglicht die korrekte Darstellung von Umlauten.
- `viewport` sorgt für eine passende Darstellung auf schmalen Bildschirmen.
- `<title>` setzt den Titel im Browser-Tab.
- `<link rel="stylesheet" href="style.css">` bindet die CSS-Datei ein.
- `<body>` enthält den sichtbaren Inhalt. `<main>` markiert den Hauptinhalt; `card` ist ein CSS-Klassenname.
- `<form method="post">` sendet die Formulardaten per POST an dieselbe Seite.
- Der Button ist vom Typ `submit`. Beim Klicken sendet der Browser sein `name`-Attribut `neueZuege`, das PHP oben prüft.
- Der HTML-Kommentar in Zeile 165 ist eine Notiz im Dokument und wird nicht als sichtbarer Text angezeigt.

## Ergebnisse ausgeben — Zeilen 170–205

```php
<?php if ($bahnhof !== null): ?>
    <h2>Züge am Bahnhof: <?= $bahnhof->anzahlZuege() ?></h2>

    <h2>Destinationen</h2>
    <p><?php $bahnhof->destinationsListeDrucken(); ?></p>

    <?php foreach ($bahnhof->getBahnsteige() as $bahnsteigNummer => $bahnsteig): ?>
        <h2>Bahnsteig <?= $bahnsteigNummer + 1 ?></h2>

        <?php foreach ($bahnsteig->getZuege() as $zugNummer => $zug): ?>
            <p>Zug <?= $zugNummer + 1 ?> – Anzahl Wagen: <?= $zug->anzahlWagen() ?></p>
        <?php endforeach; ?>

        <p>Wagen am Bahnsteig: <?= $bahnsteig->anzahlWagen() ?></p>
    <?php endforeach; ?>

    <p>Wagen am Bahnhof insgesamt: <?= $bahnhof->anzahlWagen() ?></p>
<?php endif; ?>
```

- `if ($bahnhof !== null)` prüft, ob ein Bahnhof-Objekt vorhanden ist. `!==` bedeutet „nicht identisch mit“.
- Die Schreibweise `if: ... endif;` ist eine alternative PHP-Syntax. Sie ist praktisch, wenn PHP und HTML ineinander verschachtelt sind.
- `<?= ... ?>` ist eine Kurzform für `<?php echo ...; ?>`: Der berechnete Wert wird in die HTML-Seite geschrieben.
- Die erste `foreach`-Schleife geht alle Bahnsteige durch. `=>` ordnet den Array-Schlüssel `$bahnsteigNummer` dem jeweiligen Objekt `$bahnsteig` zu.
- Array-Schlüssel beginnen hier bei `0`. Deshalb wird für die sichtbare Nummer `+ 1` gerechnet.
- Die innere `foreach`-Schleife durchläuft die Züge des jeweiligen Bahnsteigs. `$zugNummer` beginnt auf jedem Bahnsteig erneut bei `0`.
- `endforeach;` beendet die jeweilige `foreach`-Schleife; `endif;` beendet die Bedingung.
- Die Wagenzahl pro Zug kommt aus `Zug::anzahlWagen()`. Die Summe für einen Bahnsteig kommt aus `Bahnsteig::anzahlWagen()`. Die Bahnhofssumme kommt aus `Bahnhof::anzahlWagen()`.
- HTML-Tags wie `<h2>`, `<p>`, `<span>` und `<strong>` strukturieren und betonen den Inhalt. CSS-Klassen wie `details`, `label` und `value` können in `style.css` gestaltet werden.

## Ergebnis beim Klick

Nach dem Absenden des Formulars werden die Beispielobjekte neu erstellt. Die Ausgabe enthält vier Züge und diese Summen:

| Bereich | Züge | Wagen |
|---|---:|---:|
| Bahnsteig 1 — Wien | 1 | 12 |
| Bahnsteig 2 — Rom | 1 | 10 |
| Bahnsteig 3 — Bern und Paris | 2 | 14 |
| Bahnhof insgesamt | 4 | 36 |

Die Destinationen erscheinen in der Reihenfolge, in der die Bahnsteige und Züge durchlaufen werden: Wien, Rom, Bern, Paris. Die Werte werden nicht dauerhaft gespeichert; beim Absenden des Formulars baut PHP sie erneut auf.
