<?php

namespace App;

use AbstractToNeuron;

class Brain
{
    protected $neurons;
    protected $entity;

    public static function fromGenome($genome, $entity)
    {
        $brain = new Brain(4);
        $brain->entity = $entity;

        $chromosomes = str_split($genome, 2);
        foreach ($chromosomes as $chromosome) {
            $hex = unpack('h4hex', $genome);
            $binary = base_convert($hex['hex'], 16, 2);

            $weight = (int) base_convert(substr($binary, 0, 8), 2, 10);
            $fromType = (int) base_convert(substr($binary, 8, 1), 2, 10);
            $fromId = (int) base_convert(substr($binary, 9, 3), 2, 10);
            $toType = (int) base_convert(substr($binary, 12, 1), 2, 10);
            $toId = (int) base_convert(substr($binary, 13, 3), 2, 10);

            if ($fromType === 0) {
                switch ($fromId) {
                    case 0:
                        $fromNeuron = $brain->getByClass(IsFacingEntityNeuron::class);
                        break;

                    case 1:
                        $fromNeuron = $brain->getByClass(IsFacingWallNeuron::class);
                        break;

                    case 2:
                        $fromNeuron = $brain->getByClass(RandomNeuron::class);
                        break;

                    default:
                        $fromNeuron = null;
                        break;
                }
            } else {
                if ($fromId < 4) {
                    $fromNeuron = $brain->getInternalNeuronById($fromId);
                } else {
                    $fromNeuron = null;
                }
            }

            if ($toType === 0) {
                switch ($toId) {
                    case 0:
                        $toNeuron = $brain->getByClass(MoveEastNeuron::class);
                        break;

                    case 1:
                        $toNeuron = $brain->getByClass(MoveWestNeuron::class);
                        break;

                    case 2:
                        $toNeuron = $brain->getByClass(MoveNorthNeuron::class);
                        break;

                    case 3:
                        $toNeuron = $brain->getByClass(MoveSouthNeuron::class);
                        break;

                    case 4:
                        $toNeuron = $brain->getByClass(MoveRandomNeuron::class);
                        break;

                    default:
                        $toNeuron = null;
                        break;
                }
            } else {
                if ($toId < 4) {
                    $toNeuron = $brain->getInternalNeuronById($toId);
                } else {
                    $toNeuron = null;
                }
            }

            if ($fromNeuron !== null && $toNeuron !== null) {
                $brain->addSynapse($fromNeuron, $toNeuron, $weight);
            }
        }

        return $brain;
    }

    public function __construct($internalNeurons)
    {
        for ($i = 0; $i < $internalNeurons; $i++) {
            $this->neurons[] = new InternalNeuron($this, $i);
        }
    }

    public function getByClass($className)
    {
        $neurons = array_filter($this->neurons, function ($neuron) use ($className) {
            return $neuron instanceof $className;
        });
        if (!empty($neurons)) {
            return current($neurons);
        }
        $neuron = new $className($this);
        $this->neurons[] = $neuron;
        return $neuron;

    }

    public function addSynapse($fromNeuron, $toNeuron, $weight)
    {
        $fromNeuron->addSynapseTo($toNeuron, $weight);
        $toNeuron->addSynapseFrom($fromNeuron, $weight);
    }

    public function __clone()
    {
        $this->neurons = array_map(function ($neuron) {
            return clone $neuron;
        }, $this->neurons);
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getInternalNeuronById($id)
    {
        $neurons = array_filter($this->neurons, function ($neuron) use ($id) {
            return $neuron instanceof InternalNeuron
                && $neuron->getId() === $id;
        });

        if (empty($neurons)) {
            return null;
        }
        return current($neurons);
    }

    public function runTurn()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->calculateExcitement();
        }
        $this->triggerNeurons();
    }

    public function calculateExcitement()
    {
        foreach ($this->neurons as $neuron) {
            $neuron->calculateExcitement();
        }
    }

    public function triggerNeurons()
    {
        $toNeurons = array_filter($this->neurons, function($neuron) {
            return $neuron instanceof AbstractToNeuron;
        });
        foreach ($toNeurons as $neuron) {
            if ($neuron->getExcitement() > 0.75) {
                $neuron->fire();
            }
        }
    }
}
