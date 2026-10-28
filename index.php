<?php
// Die Klassen bilden die Beziehungen zwischen Bahnhof, Bahnsteigen und Zügen ab.
class Bahnhof
{
    /** @var Bahnsteig[] Alle Bahnsteige dieses Bahnhofs. */
    private array $bahnsteige = [];

    // Fügt einen Bahnsteig zum Bahnhof hinzu.
    public function addBahnsteig(Bahnsteig $bahnsteig): void
    {
        $this->bahnsteige[] = $bahnsteig;
    }

    /** @return Bahnsteig[] */
    public function getBahnsteige(): array
    {
        return $this->bahnsteige;
    }

    // Gibt die Ziele aller Züge auf den Bahnsteigen des Bahnhofs aus.
    public function destinationsListeDrucken(): void
    {
        foreach ($this->bahnsteige as $bahnsteig) {
            $bahnsteig->destinationsListeDrucken();
        }
    }

    // Addiert die Zugzahlen aller Bahnsteige.
    public function anzahlZuege(): int
    {
        $gesamtanzahlZuege = 0;

        foreach ($this->bahnsteige as $bahnsteig) {
            $gesamtanzahlZuege += $bahnsteig->anzahlZuege();
        }

        return $gesamtanzahlZuege;
    }

    // Addiert die Wagenzahlen aller Bahnsteige.
    public function anzahlWagen(): int
    {
        $gesamtanzahlWagen = 0;

        foreach ($this->bahnsteige as $bahnsteig) {
            $gesamtanzahlWagen += $bahnsteig->anzahlWagen();
        }

        return $gesamtanzahlWagen;
    }
}

class Bahnsteig
{
    /** @var Zug[] Alle Züge, die diesem Bahnsteig zugeordnet sind. */
    private array $zuege = [];

    // Stellt einen Zug auf diesen Bahnsteig.
    public function addZug(Zug $zug): void
    {
        $this->zuege[] = $zug;
    }

    /** @return Zug[] */
    public function getZuege(): array
    {
        return $this->zuege;
    }

    // Gibt die Ziele der Züge auf diesem Bahnsteig aus.
    public function destinationsListeDrucken(): void
    {
        foreach ($this->zuege as $zug) {
            echo htmlspecialchars($zug->getDestination(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "<br>";
        }
    }

    // Die Zugzahl entspricht der Anzahl der gespeicherten Züge.
    public function anzahlZuege(): int
    {
        return count($this->zuege);
    }

    // Addiert die Wagen aller Züge auf diesem Bahnsteig.
    public function anzahlWagen(): int
    {
        $gesamtanzahlWagen = 0;

        foreach ($this->zuege as $zug) {
            $gesamtanzahlWagen += $zug->anzahlWagen();
        }

        return $gesamtanzahlWagen;
    }
}

class Zug
{
    private string $destination;
    private int $anzahlWagen;

    // Ziel und Wagenzahl werden beim Erstellen des Zuges festgelegt.
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

// Erstellt die Beispielzüge und verteilt sie wie in der Referenz auf drei Bahnsteige.
function createBahnhof(): Bahnhof
{
    $zugBern = new Zug("Bern", 8);
    $zugParis = new Zug("Paris", 6);
    $zugRom = new Zug("Rom", 10);
    $zugWien = new Zug("Wien", 12);

    $bahnsteigEins = new Bahnsteig();
    $bahnsteigEins->addZug($zugWien);

    $bahnsteigZwei = new Bahnsteig();
    $bahnsteigZwei->addZug($zugRom);

    $bahnsteigDrei = new Bahnsteig();
    $bahnsteigDrei->addZug($zugBern);
    $bahnsteigDrei->addZug($zugParis);

    $bahnhof = new Bahnhof();
    $bahnhof->addBahnsteig($bahnsteigEins);
    $bahnhof->addBahnsteig($bahnsteigZwei);
    $bahnhof->addBahnsteig($bahnsteigDrei);

    return $bahnhof;
}

// Der Bahnhof wird erst nach dem Absenden des Formulars aufgebaut.
$bahnhof = null;

if (isset($_POST['neueZuege'])) {
    $bahnhof = createBahnhof();
}
?>
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

            <!-- Das Formular lädt die Seite mit den neu erstellten Beispielzügen neu. -->
            <form method="post">
                <button type="submit" name="neueZuege">Züge erstellen</button>
            </form>

            <?php if ($bahnhof !== null): ?>
                <div class="side-by-side">
                    <div class="column">
                        <section class="details">
                            <h2>Züge am Bahnhof: <?= $bahnhof->anzahlZuege() ?></h2>

                            <h2>Destinationen</h2>
                            <p><?php $bahnhof->destinationsListeDrucken(); ?></p>

                            <!-- Pro Bahnsteig werden die Wagenzahlen seiner Züge aufsummiert. -->
                            <?php foreach ($bahnhof->getBahnsteige() as $bahnsteigNummer => $bahnsteig): ?>
                                <h2>Bahnsteig <?= $bahnsteigNummer + 1 ?></h2>

                                <?php foreach ($bahnsteig->getZuege() as $zugNummer => $zug): ?>
                                    <p>
                                        <span class="label"><strong>Zug <?= $zugNummer + 1 ?> – Anzahl Wagen:</strong></span>
                                        <span class="value"><?= $zug->anzahlWagen() ?></span>
                                    </p>
                                <?php endforeach; ?>

                                <!-- Diese Summe umfasst nur die Züge des aktuellen Bahnsteigs. -->
                                <p>
                                    <span class="label"><strong>Wagen am Bahnsteig:</strong></span>
                                    <span class="value"><?= $bahnsteig->anzahlWagen() ?></span>
                                </p>
                            <?php endforeach; ?>

                            <!-- Die Bahnhofsklasse addiert die Wagenzahlen aller Bahnsteige. -->
                            <p>
                                <span class="label"><strong>Wagen am Bahnhof insgesamt:</strong></span>
                                <span class="value"><?= $bahnhof->anzahlWagen() ?></span>
                            </p>
                        </section>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </body>
</html>
