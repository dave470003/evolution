<?php

namespace App;

class MoveRandomNeuron
{
    public function fire()
    {
        $snapshot = Population::snapshot();

        $rand = rand(0, 3);

        if ($rand === 0) {
            $snapshot->getGrid()->moveEast($this->brain->entity);
        } elseif ($rand === 1) {
            $snapshot->getGrid()->moveWest($this->brain->entity);
        } elseif ($rand === 2) {
            $snapshot->getGrid()->moveNorth($this->brain->entity);
        } elseif ($rand === 3) {
            $snapshot->getGrid()->moveSouth($this->brain->entity);
        }
    }
}
