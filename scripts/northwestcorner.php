<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use App\Population;

srand(3);

$population = new Population(
    100,
    30,
    function ($cell) {
        return $cell->getX() < 15
            && $cell->getY() < 15;
    }
);
for ($i = 0; $i < 500; $i++) {
    echo 'gen ' . $i . "\n";
    $population->runTurns(200);
    $j = $i;
    $record = false;
    do {
        $remainder = $j%10;
        $j = floor($j/10);
    } while ($remainder === 0 && $j > 10);
    if ($remainder === 0) {
        $record = true;
    }
    $population = $population->nextGen($record);
}
