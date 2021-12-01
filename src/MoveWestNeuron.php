<?php

namespace App;

use AbstractToNeuron;

class MoveWestNeuron extends AbstractToNeuron
{
    public function fire()
    {
        $snapshot = Population::snapshot();

        $snapshot->getGrid()->moveWest($this->brain->getEntity());
    }
}
