<?php

namespace App;

class Cell
{
    protected $entity;

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
}
