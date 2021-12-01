<?php

namespace App;

class MoveEastNeuron
{
    public function fire()
    {
        $snapshot = Population::snapshot();

        $snapshot->getGrid()->moveEast($this->brain->entity);
    }
}
