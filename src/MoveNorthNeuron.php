<?php

namespace App;

class MoveNorthNeuron
{
    public function fire()
    {
        $snapshot = Population::snapshot();

        $snapshot->getGrid()->moveNorth($this->brain->entity);
    }
}
