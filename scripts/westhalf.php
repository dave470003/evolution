<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use App\Population;

$population = new Population();
for ($i = 0; $i < 1; $i++) {
    $population->runTurns(200);
    $population = $population->nextGen();
}
