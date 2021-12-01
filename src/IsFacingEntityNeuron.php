<?php

namespace App;

use AbstractFromNeuron;

class IsFacingEntityNeuron extends AbstractFromNeuron
{
    public function trigger()
    {
        $snapshot = Population::snapshot();

        return $snapshot->getGrid()->getFacing($this->brain->getEntity()) instanceof Entity;
    }
}
