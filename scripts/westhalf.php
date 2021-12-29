<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use App\Population;

error_reporting(E_ALL & ~E_NOTICE);

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
    $population->runTurns(200);
    $population = $population->nextGen(in_array($i, [1, 2, 10, 23, 30, 40, 50, 60, 70, 80, 90, 95]));
}
