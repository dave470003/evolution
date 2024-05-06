<?php

namespace App;

class Grid
{
    protected $cells;
    protected $xSize;
    protected $ySize;

    public function __construct($members, $x = 30, $y = 30)
    {
        $this->xSize = $x;
        $this->ySize = $y;

        for ($i = 0; $i < $x; $i++) {
            for ($j = 0; $j < $y; $j++) {
                $cell = new Cell($i, $j);
                $this->cells[$i][$j] = $cell;
            }
        }

        $this->seed($members);
    }

    public function seed($members)
    {
        $membersToPopulate = $members;
        while (count($membersToPopulate) > 0) {
            $x = rand(0, $this->xSize - 1);
            $y = rand(0, $this->ySize - 1);
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

    public function populateDensity()
    {
        foreach ($this->cells as $x => $column) {
            foreach ($column as  $y => $cell) {
                $cell->setDensity($this->calculateDensity($cell));
            }
        }
    }

    public function calculateDensity($cell)
    {
        $cellsEmpty = 0;
        $cellsFull = 0;
        for ($i = $cell->getX() - 5; $i <= $cell->getX() + 5; $i++) {
            for ($j = $cell->getY() - 5; $j <= $cell->getY() + 5; $j++) {
                if ($i === $cell->getX() && $j === $cell->getY()) {
                    continue;
                }
                if (isset($this->cells[$i]) && isset($this->cells[$j])) {
                    if ($this->cells[$i][$j]->getEntity() instanceof Entity) {
                        $cellsFull += 1;
                    } else {
                        $cellsEmpty += 1;
                    }
                }
            }
        }
        return $cellsFull / ($cellsEmpty + $cellsFull);
    }

    public function moveEast($entity)
    {
        foreach ($this->cells as $x => $column) {
            foreach ($column as  $y => $cell) {
                if (
                    $cell->getEntity() !== null
                    && $cell->getEntity()->getId() === $entity->getId()
                ) {
                    if ($x < $this->xSize - 1) {
                        $eastEntity = $this->cells[$x + 1][$y]->getEntity();
                        if ($eastEntity === null) {
                            $this->cells[$x][$y]->setEntity(null);
                            $this->cells[$x + 1][$y]->setEntity($entity);
                            $entity->setFacing('E');
                            return;
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
                if (
                    $cell->getEntity() !== null
                    && $cell->getEntity()->getId() === $entity->getId()
                ) {
                    if ($x > 0) {
                        $eastEntity = $this->cells[$x - 1][$y]->getEntity();
                        if ($eastEntity === null) {
                            $this->cells[$x][$y]->setEntity(null);
                            $this->cells[$x - 1][$y]->setEntity($entity);
                            $entity->setFacing('W');
                            return;
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
                if (
                    $cell->getEntity() !== null
                    && $cell->getEntity()->getId() === $entity->getId()
                ) {
                    if ($y < $this->ySize - 1) {
                        $eastEntity = $this->cells[$x][$y + 1]->getEntity();
                        if ($eastEntity === null) {
                            $this->cells[$x][$y]->setEntity(null);
                            $this->cells[$x][$y + 1]->setEntity($entity);
                            $entity->setFacing('N');
                            return;
                        }
                    }
                }
            }
        }
    }

    public function moveForward($entity)
    {
        switch ($entity->getFacing()) {
            case 'N':
                $this->moveNorth($entity);
                break;
            case 'S':
                $this->moveSouth($entity);
                break;
            case 'W':
                $this->moveWest($entity);
                break;
            case 'E':
                $this->moveEast($entity);
                break;
        }
    }

    public function moveSouth($entity)
    {
        foreach ($this->cells as $x => $column) {
            foreach ($column as  $y => $cell) {
                if (
                    $cell->getEntity() !== null
                    && $cell->getEntity()->getId() === $entity->getId()
                ) {
                    if ($y > 0) {
                        $eastEntity = $this->cells[$x][$y - 1]->getEntity();
                        if ($eastEntity === null) {
                            $this->cells[$x][$y]->setEntity(null);
                            $this->cells[$x][$y - 1]->setEntity($entity);
                            $entity->setFacing('S');
                            return;
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
                if (
                    $cell->getEntity() !== null
                    && $cell->getEntity()->getId() === $entity->getId()
                ) {
                    switch ($entity->getFacing()) {
                        case 'N':
                            if ($y === $this->ySize - 1) {
                                return 'wall';
                            }
                            return $this->cells[$x][$y + 1];
                        case 'S':
                            if ($y === 0) {
                                return 'wall';
                            }
                            return $this->cells[$x][$y - 1];
                        case 'E':
                            if ($x === $this->xSize - 1) {
                                return 'wall';
                            }
                            return $this->cells[$x + 1][$y];
                        case 'W':
                            if ($x === 0) {
                                return 'wall';
                            }
                            return $this->cells[$x - 1][$y];
                    }
                }
            }
        }
    }

    public function isWallNearby($entity)
    {
        foreach ($this->cells as $x => $column) {
            foreach ($column as  $y => $cell) {
                if (
                    $cell->getEntity() !== null
                    && $cell->getEntity()->getId() === $entity->getId()
                ) {
                    return $cell->getX() === 0
                        || $cell->getY() === 0
                        || $cell->getX() === $this->xSize - 1
                        || $cell->getY() === $this->ySize - 1;
                }
            }
        }

        return false;
    }

    public function getDensity($entity)
    {
        foreach ($this->cells as $x => $column) {
            foreach ($column as  $y => $cell) {
                if (
                    $cell->getEntity() !== null
                    && $cell->getEntity()->getId() === $entity->getId()
                ) {
                    return $cell->getDensity();
                }
            }
        }

        return 0;
    }

    public function moveTowardsDensity($entity)
    {
        foreach ($this->cells as $x => $column) {
            foreach ($column as  $y => $cell) {
                if (
                    $cell->getEntity() !== null
                    && $cell->getEntity()->getId() === $entity->getId()
                ) {
                    $densities = [];
                    if (isset($this->cells[$x + 1]) && isset($this->cells[$x + 1][$y])) {
                        $densities['E'] = $this->cells[$x + 1][$y]->getDensity();
                    }
                    if (isset($this->cells[$x]) && isset($this->cells[$x][$y + 1])) {
                        $densities['S'] = $this->cells[$x][$y + 1]->getDensity();
                    }
                    if (isset($this->cells[$x - 1]) && isset($this->cells[$x - 1][$y])) {
                        $densities['W'] = $this->cells[$x - 1][$y]->getDensity();
                    }
                    if (isset($this->cells[$x]) && isset($this->cells[$x][$y - 1])) {
                        $densities['N'] = $this->cells[$x][$y - 1]->getDensity();
                    }
                    arsort($densities);
                    $targetDir = array_key_first($densities);

                    switch ($targetDir) {
                        case 'N':
                            $this->moveNorth($entity);
                            break;
                        case 'S':
                            $this->moveSouth($entity);
                            break;
                        case 'W':
                            $this->moveWest($entity);
                            break;
                        case 'E':
                            $this->moveEast($entity);
                            break;
                    }
                }
            }
        }
    }

    public function moveFromDensity($entity)
    {
        foreach ($this->cells as $x => $column) {
            foreach ($column as  $y => $cell) {
                if (
                    $cell->getEntity() !== null
                    && $cell->getEntity()->getId() === $entity->getId()
                ) {
                    $densities = [];
                    if (isset($this->cells[$x + 1]) && isset($this->cells[$x + 1][$y])) {
                        $densities['E'] = $this->cells[$x + 1][$y]->getDensity();
                    }
                    if (isset($this->cells[$x]) && isset($this->cells[$x][$y + 1])) {
                        $densities['S'] = $this->cells[$x][$y + 1]->getDensity();
                    }
                    if (isset($this->cells[$x - 1]) && isset($this->cells[$x - 1][$y])) {
                        $densities['W'] = $this->cells[$x - 1][$y]->getDensity();
                    }
                    if (isset($this->cells[$x]) && isset($this->cells[$x][$y - 1])) {
                        $densities['N'] = $this->cells[$x][$y - 1]->getDensity();
                    }
                    asort($densities);
                    $targetDir = array_key_first($densities);

                    switch ($targetDir) {
                        case 'N':
                            $this->moveNorth($entity);
                            break;
                        case 'S':
                            $this->moveSouth($entity);
                            break;
                        case 'W':
                            $this->moveWest($entity);
                            break;
                        case 'E':
                            $this->moveEast($entity);
                            break;
                    }
                }
            }
        }
    }

    public function drawEntities($image)
    {
        foreach ($this->cells as $x => $column) {
            foreach ($column as $y => $cell) {
                if ($cell->getEntity() instanceof Entity) {
                    $hexValue = $cell->getEntity()->getColour();
                    $redValue = base_convert(substr($hexValue, 0, 2), 16, 10);
                    $greenValue = base_convert(substr($hexValue, 2, 2), 16, 10);
                    $blueValue = base_convert(substr($hexValue, 4, 2), 16, 10);
                    $colour = imagecolorallocate($image, $redValue, $greenValue, $blueValue);
                    imagefilledellipse($image, 10 + $x * (600 / $this->xSize), 10 + $y * (600 / $this->ySize), 20, 20, $colour);

                    // $black = imagecolorallocate($image, 0, 0, 0);
                    // // The text to draw
                    // $text = $cell->getEntity()->getId();
                    // // Replace path by your own font path
                    // $font = 'arial.ttf';
                    // // Add the text

                    // imagestring($image, 5, $x*6, 10 + $y*6, $text, $black);
                }
            }
        }
        return $image;
    }

    public function getSurvivors($survivorFunction)
    {
        $survivors = [];
        foreach ($this->cells as $column) {
            foreach ($column as $cell) {
                if (
                    $cell->getEntity() instanceof Entity
                    && $survivorFunction($cell)
                ) {
                    $survivors[] = $cell->getEntity();
                }
            }
        }
        return $survivors;
    }
}
