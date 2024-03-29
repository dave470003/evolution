<?php

namespace App;

class Cell
{
    protected $entity;
    protected $x;
    protected $y;
    protected $density;

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

    public function setDensity($density)
    {
        $this->density = $density;
    }

    public function getDensity()
    {
        return $this->density;
    }
}
