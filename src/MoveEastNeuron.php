<?php

namespace App;

use AbstractToNeuron;

class MoveEastNeuron extends AbstractToNeuron
{
    public function fire()
    {
        $snapshot = Population::snapshot();

        $snapshot->getGrid()->moveEast($this->brain->getEntity());
    }
}
