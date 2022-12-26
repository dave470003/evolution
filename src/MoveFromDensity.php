<?php

namespace App;

use AbstractToNeuron;

class MoveFromDensity extends AbstractToNeuron
{
    public function fire()
    {
        $snapshot = Population::snapshot();

        $snapshot->getGrid()->moveFromDensity($this->brain->getEntity());
    }
}
