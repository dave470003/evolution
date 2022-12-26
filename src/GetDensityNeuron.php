<?php

namespace App;

use AbstractFromNeuron;

class GetDensityNeuron extends AbstractFromNeuron
{
    public function trigger()
    {
        $snapshot = Population::snapshot();

        return $snapshot->getGrid()->getDensity($this->brain->getEntity());
    }

    public function calculateExcitement()
    {
        return $this->trigger();
    }
}
