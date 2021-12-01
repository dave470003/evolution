<?php

namespace App;

class IsFacingWallNeuron
{
    public function trigger()
    {
        $snapshot = Population::snapshot();

        return $snapshot->getGrid()->getFacing($this->brain->entity) instanceof Entity;
    }
}
