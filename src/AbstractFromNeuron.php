<?php

use App\AbstractNeuron;

abstract class AbstractFromNeuron extends AbstractNeuron
{
    abstract public function trigger();

    public function calculateExcitement()
    {
        $this->excitement = $this->trigger() ? 1 : 0;
    }
}
