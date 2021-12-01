<?php

namespace App;

class MoveWestNeuron
{
    public function fire()
    {
        $snapshot = Population::snapshot();

        $snapshot->getGrid()->moveWest($this->brain->entity);
    }
}
