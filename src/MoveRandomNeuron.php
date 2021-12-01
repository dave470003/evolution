<?php

namespace App;

use AbstractToNeuron;

class MoveRandomNeuron extends AbstractToNeuron
{
    public function fire()
    {
        $snapshot = Population::snapshot();

        $rand = rand(0, 3);

        if ($rand === 0) {
            $snapshot->getGrid()->moveEast($this->brain->getEntity());
        } elseif ($rand === 1) {
            $snapshot->getGrid()->moveWest($this->brain->getEntity());
        } elseif ($rand === 2) {
            $snapshot->getGrid()->moveNorth($this->brain->getEntity());
        } elseif ($rand === 3) {
            $snapshot->getGrid()->moveSouth($this->brain->getEntity());
        }
    }
}
