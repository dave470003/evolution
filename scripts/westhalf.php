<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use App\Population;

srand(1);

$population = new Population(function ($cell) {
    return $cell->getX() < 50;
});
for ($i = 0; $i < 1000; $i++) {
    echo 'gen ' . $i . "\n";
    $population->runTurns(5);
    $population = $population->nextGen(in_array($i, [1, 2, 10, 100, 1000]));
}
