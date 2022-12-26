<?php

namespace App;

use AbstractFromNeuron;

class IsNearWallNeuron extends AbstractFromNeuron
{
    public function trigger()
    {
        $snapshot = Population::snapshot();

        return $snapshot->getGrid()->isWallNearby($this->brain->getEntity());
    }

}
