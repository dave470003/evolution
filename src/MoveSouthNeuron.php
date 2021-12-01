<?php

namespace App;

use AbstractToNeuron;

class MoveSouthNeuron extends AbstractToNeuron
{
    public function fire()
    {
        $snapshot = Population::snapshot();

        $snapshot->getGrid()->moveSouth($this->brain->getEntity());
    }
}
