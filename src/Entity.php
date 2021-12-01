<?php

namespace App;

class Entity
{
    protected $genome;
    protected $brain;
    protected $facing;

    public function __construct($genome)
    {
        $this->genome = $genome;
        $this->brain = Brain::fromGenome($genome, $this);
    }

    public function __clone()
    {
        $this->brain = clone $this->brain;
    }

    public function setFacing($facing)
    {
        $this->facing = $facing;
    }

    public function getFacing()
    {
        return $this->facing;
    }
}
