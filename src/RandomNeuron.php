<?php

namespace App;

use AbstractFromNeuron;

class RandomNeuron extends AbstractFromNeuron
{
    public function trigger()
    {
        return rand(0, 1) === 0;
    }
}
