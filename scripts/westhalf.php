<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use App\Population;

srand(3);

$population = new Population(
    100,
    30,
    function ($cell) {
        return $cell->getX() < 15;
    }
);
for ($i = 0; $i < 100; $i++) {
    echo 'gen ' . $i . "\n";
    $population->runTurns(300);
    $population = $population->nextGen(in_array($i, [1, 2, 3, 5, 7, 10, 15, 23, 30, 40, 50, 60, 70, 80, 90, 100, 200, 300]));
}
