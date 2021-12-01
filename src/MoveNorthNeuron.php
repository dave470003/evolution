<?php

namespace App;

use AbstractToNeuron;

class MoveNorthNeuron extends AbstractToNeuron
{
    public function fire()
    {
        $snapshot = Population::snapshot();

        $snapshot->getGrid()->moveNorth($this->brain->getEntity());
    }
}
