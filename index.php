<?php
class Bahnhof{

public int $anzahlZuege;
public int $anzahlWagen;


public function destinationsListeDrucken(){
    
}

}



class Zug{
    public String $destination;
    public int $anzahlWagen;

    public function destinationsListeDrucken(){
        echo "Destination: " . $this->destination . "<br>";
        echo "Anzahl Wagen: " . $this->anzahlWagen . "<br>";
    }
}


function createZuege() {
    // Hier werden neue Züge erstellt
    $zug1 = new Zug();
    $zug1->destination = "Bern";
    $zug1->anzahlWagen = 8;

    $zug2 = new Zug();
    $zug2->destination = "Paris";
    $zug2->anzahlWagen = 6;

    $zug3 = new Zug();
    $zug3->destination = "Rom";
    $zug3->anzahlWagen = 10;

    $zug4 = new Zug();
    $zug4->destination = "Wien";
    $zug4->anzahlWagen = 12;

    return [$zug1, $zug2, $zug3, $zug4];
}






if(isset($_POST['neueZuege'])) {
    // Hier werden neue Züge erstellt
    $zuege = createZuege();

}





?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Klassen</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <main class="card">
            <h1>Bahnhof</h1>

            <div class="side-by-side">
                <div class="column">
                    <form method="post">
                        <button type="submit" name="neueZuege">Züge erstellen</button>
                    </form>

                    <?php if(isset($zuege)): ?>
                        <?php foreach($zuege as $index => $zug): ?>
                            <section class="details">
                                <h2>Zug<?= $index + 1 ?>-Details:</h2>
                                <p>

                                    <span class="label"><strong>Destination:</strong></span>
                                    <span class="value"><?= htmlspecialchars($zug->destination) ?></span>

                                </p>

                                <p>

                                    <span class="label"><strong>Anzahl Wagen:</strong></span>
                                    <span class="value"><?= $zug->anzahlWagen ?></span>

                                </p>
                            </section>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

               
            </div>
        </main>
    </body>
</html>
