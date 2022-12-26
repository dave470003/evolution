<?php

namespace App;

use AbstractToNeuron;

class MoveForwardNeuron extends AbstractToNeuron
{
    public function fire()
    {
        $snapshot = Population::snapshot();

        $snapshot->getGrid()->moveForward($this->brain->getEntity());
    }
}
