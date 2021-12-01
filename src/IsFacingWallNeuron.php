<?php

namespace App;

use AbstractFromNeuron;

class IsFacingWallNeuron extends AbstractFromNeuron
{
    public function trigger()
    {
        $snapshot = Population::snapshot();

        return $snapshot->getGrid()->getFacing($this->brain->getEntity()) === 'wall';
    }
}
