<?php

namespace App;

use App\AbstractNeuron;

class InternalNeuron extends AbstractNeuron
{
    protected $id;

    public function __construct($brain, $id)
    {
        $this->brain = $brain;
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}
