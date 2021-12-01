<?php

namespace App;

class Snapshot
{
    protected $grid;

    public function __construct(Population $population)
    {
        $this->grid = clone $population->getGrid();
    }

    public function getFromPosition($x, $y)
    {
        return $this->grid->getFromPosition($x, $y);
    }

    public function getGrid()
    {
        return $this->grid;
    }
}
