<?php

namespace App;

class MoveSouthNeuron
{
    public function fire()
    {
        $snapshot = Population::snapshot();

        $snapshot->getGrid()->moveSouth($this->brain->entity);
    }
}
