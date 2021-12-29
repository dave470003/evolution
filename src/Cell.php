<?php

namespace App;

class Cell
{
    protected $entity;
    protected $x;
    protected $y;
    protected $food;
    protected $foodAroma = 0;

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function hasFood()
    {
        return $this->food === true;
    }

    public function addFood()
    {
        $this->food = true;
    }

    public function addAroma($aroma)
    {
        $this->foodAroma += $aroma;
    }

    public function getFoodAroma()
    {
        return $this->foodAroma;
    }

    public function clone()
    {
        $this->entity = clone $this->entity;
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }
}
