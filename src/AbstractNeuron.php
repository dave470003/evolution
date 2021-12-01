<?php

namespace App;

abstract class AbstractNeuron
{
    protected $excitement = 0;
    protected $fromSynapses = [];
    protected $toSynapses = [];
    protected $brain;

    public function __construct($brain)
    {
        $this->brain = $brain;
    }

    public function addSynapseFrom($neuron, $weight)
    {
        $this->fromSynapses[] = [
            'neuron' => $neuron,
            'weight' => $weight
        ];
    }

    public function addSynapseTo($neuron, $weight)
    {
        $this->toSynapses[] = [
            'neuron' => $neuron,
            'weight' => $weight
        ];
    }

    public function getExcitement()
    {
        return $this->excitement;
    }

    public function calculateExcitement()
    {
        $this->excitement = tanh(array_reduce($this->fromSynapses, function ($acc, $synapse) {
            return $acc + ($synapse['weight'] * $synapse['neuron']->getExcitement());
        }));
    }
}
