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

        // var_dump($genome);

        $chromosomes = str_split($genome, 4);
        foreach ($chromosomes as $chromosome) {
            // var_dump($chromosome);
            $binary = base_convert($chromosome, 16, 2);

            // 1111111100100001
            // 1111111100100001
            // 1111111100010010
            // 1111111100010010

            // var_dump($binary);
            $weight = (int) base_convert(substr($binary, 0, 8), 2, 10);
            $fromType = (int) base_convert(substr($binary, 8, 1), 2, 10);
            $fromId = (int) base_convert(substr($binary, 9, 3), 2, 10);
            $toType = (int) base_convert(substr($binary, 12, 1), 2, 10);
            $toId = (int) base_convert(substr($binary, 13, 3), 2, 10);

            if ($fromType === 0) {
                switch ($fromId % 5) {
                    case 0:
                        $fromNeuron = $brain->getByClass(IsFacingEntityNeuron::class);
                        break;

                    case 1:
                        $fromNeuron = $brain->getByClass(IsFacingWallNeuron::class);
                        break;

                    case 2:
                        $fromNeuron = $brain->getByClass(RandomNeuron::class);
                        break;

                    case 3:
                        $fromNeuron = $brain->getByClass(IsNearWallNeuron::class);
                        break;

                    case 4:
                        $fromNeuron = $brain->getByClass(GetDensityNeuron::class);
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
                switch ($toId % 8) {
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

                    case 5:
                        $toNeuron = $brain->getByClass(MoveForwardNeuron::class);
                        break;

                    case 6:
                        $toNeuron = $brain->getByClass(MoveToDensity::class);
                        break;

                    case 7:
                        $toNeuron = $brain->getByClass(MoveFromDensity::class);
                        break;

                    default:
                        $toNeuron = null;
                        break;
                }
            } else {
                $toNeuron = $brain->getInternalNeuronById($toId % 4);
            }

            if ($fromNeuron !== null && $toNeuron !== null) {
                $brain->addSynapse($fromNeuron, $toNeuron, $weight);
            }
        }
        // exit;

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
        $toNeurons = array_filter($this->neurons, function ($neuron) {
            return $neuron instanceof AbstractToNeuron;
        });
        if (count($toNeurons) === 0) {
            return;
        }

        // var_dump($toNeurons);exit;
        $toNeurons = array_filter($toNeurons, function ($neuron) {
            return $neuron->getExcitement() > 0.5;
        });

        if (count($toNeurons) === 0) {
            return;
        }


        reset($toNeurons);

        current($toNeurons)->fire();
    }
}
