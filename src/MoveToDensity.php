<?php

namespace App;

use AbstractToNeuron;

class MoveToDensity extends AbstractToNeuron
{
    public function fire()
    {
        $snapshot = Population::snapshot();

        $snapshot->getGrid()->moveTowardsDensity($this->brain->getEntity());
    }
}
