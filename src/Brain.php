<?php

namespace App;

class Brain
{
    public static function fromGenome()
    {
    }

    public function __clone()
    {
        $this->neurons = array_map(function ($neuron) {
            return clone $neuron;
        }, $this->neurons);
    }
}
