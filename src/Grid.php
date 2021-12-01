<?php

namespace App;

class Grid
{
    protected $cells;

    public function __construct($members, $x = 100, $y = 100)
    {
        for ($i = 0; $i < $x; $i++) {
            for ($j = 0; $j < $y; $j++) {
                $cell = new Cell();
                $this->cells[$i][$j] = $cell;
            }
        }

        $this->seed($members);
    }

    public function seed($members)
    {
        $membersToPopulate = $members;
        while (count($membersToPopulate) > 0) {
            $x = rand(0, 99);
            $y = rand(0, 99);
            if ($this->cells[$x][$y]->getEntity() === null) {
                $entity = array_pop($membersToPopulate);
                $this->cells[$x][$y]->setEntity($entity);
            }
        }
    }

    public function clone()
    {
        $this->cells = array_map(function ($cellColumn) {
            return array_map(function ($cell) {
                return clone $cell;
            }, $cellColumn);
        }, $this->neurons);
    }

    public function moveEast($entity)
    {
        foreach ($this->cells as $x => $column) {
            foreach ($column as  $y => $cell) {
                if ($cell->getEntity() !== null
                    && $cell->getEntity()->id = $entity->id) {
                    if ($x < 99) {
                        $eastEntity = $this->cells[$x+1][$y]->getEntity();
                        if ($eastEntity === null) {
                            $this->cells[$x][$y]->setEntity(null);
                            $this->cells[$x+1][$y]->setEntity($entity);
                            $entity->setFacing('E');
                        }
                    }
                }
            }
        }
    }

    public function moveWest($entity)
    {
        foreach ($this->cells as $x => $column) {
            foreach ($column as  $y => $cell) {
                if ($cell->getEntity() !== null
                    && $cell->getEntity()->id = $entity->id) {
                    if ($x > 0) {
                        $eastEntity = $this->cells[$x-1][$y]->getEntity();
                        if ($eastEntity === null) {
                            $this->cells[$x][$y]->setEntity(null);
                            $this->cells[$x-1][$y]->setEntity($entity);
                            $entity->setFacing('W');
                        }
                    }
                }
            }
        }
    }

    public function moveNorth($entity)
    {
        foreach ($this->cells as $x => $column) {
            foreach ($column as  $y => $cell) {
                if ($cell->getEntity() !== null
                    && $cell->getEntity()->id = $entity->id) {
                    if ($y < 99) {
                        $eastEntity = $this->cells[$x][$y+1]->getEntity();
                        if ($eastEntity === null) {
                            $this->cells[$x][$y]->setEntity(null);
                            $this->cells[$x][$y+1]->setEntity($entity);
                            $entity->setFacing('N');
                        }
                    }
                }
            }
        }
    }

    public function moveSouth($entity)
    {
        foreach ($this->cells as $x => $column) {
            foreach ($column as  $y => $cell) {
                if ($cell->getEntity() !== null
                    && $cell->getEntity()->id = $entity->id) {
                    if ($y > 0) {
                        $eastEntity = $this->cells[$x][$y-1]->getEntity();
                        if ($eastEntity === null) {
                            $this->cells[$x][$y]->setEntity(null);
                            $this->cells[$x][$y-1]->setEntity($entity);
                            $entity->setFacing('S');
                        }
                    }
                }
            }
        }
    }

    public function getFacing($entity)
    {
        foreach ($this->cells as $x => $column) {
            foreach ($column as  $y => $cell) {
                if ($cell->getEntity() !== null
                    && $cell->getEntity()->id = $entity->id
                ) {
                    switch ($entity->getFacing()) {
                        case 'N':
                            if ($y === 99) {
                                return 'wall';
                            }
                            return $this->cells[$x][$y+1];
                        case 'S':
                            if ($y === 0) {
                                return 'wall';
                            }
                            return $this->cells[$x][$y-1];
                        case 'E':
                            if ($x === 99) {
                                return 'wall';
                            }
                            return $this->cells[$x+1][$y];
                        case 'W':
                            if ($x === 0) {
                                return 'wall';
                            }
                            return $this->cells[$x-1][$y];
                    }
                }
            }
        }
    }
}
