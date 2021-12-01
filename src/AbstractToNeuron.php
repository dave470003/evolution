<?php

use App\AbstractNeuron;

abstract class AbstractToNeuron extends AbstractNeuron
{
    abstract public function fire();
}
