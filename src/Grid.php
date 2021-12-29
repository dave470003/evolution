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
        $this->seedFood(100);
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

    public function seedFood($quantity)
    {
        for ($i = 0; $i < $quantity; $i++) {
            $x = rand(0, $this->xSize - 1);
            $y = rand(0, $this->ySize - 1);
            if ($this->cells[$x][$y]->hasFood() === false) {
                $this->cells[$x][$y]->addFood();
            }
        }
        foreach ($this->cells as $x => $column) {
            foreach ($column as  $y => $cell) {
                if ($cell->hasFood()) {
                    for ($i = $x - 3; $i < $x + 4; $i++) {
                        for ($j = $x - 3; $j < $x + 4; $j++) {
                            $foodAroma = max(0, (3 - sqrt((pow($x - $i, 2)) + (pow($y - $j, 2)))));
                            // var_dump($foodAroma);
                            if (isset($this->cells[$i]) && isset($this->cells[$i][$j])) {
                                $this->cells[$i][$j]->addAroma($foodAroma);
                            }
                        }
                    }
                }
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
                    && $cell->getEntity()->getId() === $entity->getId()) {
                    if ($x < $this->xSize - 1) {
                        $eastEntity = $this->cells[$x+1][$y]->getEntity();
                        if ($eastEntity === null) {
                            $this->cells[$x][$y]->setEntity(null);
                            $this->cells[$x+1][$y]->setEntity($entity);
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
                if ($cell->getEntity() !== null
                    && $cell->getEntity()->getId() === $entity->getId()) {
                    if ($x > 0) {
                        $eastEntity = $this->cells[$x-1][$y]->getEntity();
                        if ($eastEntity === null) {
                            $this->cells[$x][$y]->setEntity(null);
                            $this->cells[$x-1][$y]->setEntity($entity);
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
                if ($cell->getEntity() !== null
                    && $cell->getEntity()->getId() === $entity->getId()) {
                    if ($y < $this->ySize - 1) {
                        $eastEntity = $this->cells[$x][$y+1]->getEntity();
                        if ($eastEntity === null) {
                            $this->cells[$x][$y]->setEntity(null);
                            $this->cells[$x][$y+1]->setEntity($entity);
                            $entity->setFacing('N');
                            return;
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
                    && $cell->getEntity()->getId() === $entity->getId()) {
                    if ($y > 0) {
                        $eastEntity = $this->cells[$x][$y-1]->getEntity();
                        if ($eastEntity === null) {
                            $this->cells[$x][$y]->setEntity(null);
                            $this->cells[$x][$y-1]->setEntity($entity);
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
                if ($cell->getEntity() !== null
                    && $cell->getEntity()->getId() === $entity->getId()
                ) {
                    switch ($entity->getFacing()) {
                        case 'N':
                            if ($y === $this->ySize - 1) {
                                return 'wall';
                            }
                            return $this->cells[$x][$y+1];
                        case 'S':
                            if ($y === 0) {
                                return 'wall';
                            }
                            return $this->cells[$x][$y-1];
                        case 'E':
                            if ($x === $this->xSize - 1) {
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

    public function drawEntities($image)
    {
        foreach ($this->cells as $x => $column) {
            foreach ($column as $y => $cell) {
                $redAmount = (int) floor(125 * ($cell->getFoodAroma() / 15));
                // var_dump($cell->getFoodAroma());
                // var_dump($redAmount);
                $colour = imagecolorallocate($image, 255, 255 - $redAmount, 255 - $redAmount);
                imagelayereffect($image, IMG_EFFECT_REPLACE);
                imagefilledrectangle($image, $x*(600 / $this->xSize), $y*(600 / $this->ySize), 600 / $this->ySize, 600 / $this->ySize, $colour);
                if ($cell->getEntity() instanceof Entity) {
                    $hexValue = $cell->getEntity()->getColour();
                    $redValue = base_convert(substr($hexValue, 0, 2), 16, 10);
                    $greenValue = base_convert(substr($hexValue, 2, 2), 16, 10);
                    $blueValue = base_convert(substr($hexValue, 4, 2), 16, 10);
                    $colour = imagecolorallocate($image, $redValue, $greenValue, $blueValue);
                    imagelayereffect($image, IMG_EFFECT_REPLACE);
                    imagefilledellipse($image, (300 / $this->ySize) + $x*(600 / $this->xSize), (300 / $this->ySize) + $y*(600 / $this->ySize), 600 / $this->ySize, 600 / $this->ySize, $colour);

                    // $black = imagecolorallocate($image, 0, 0, 0);
                    // // The text to draw
                    // $text = $cell->getEntity()->getId();
                    // // Replace path by your own font path
                    // $font = 'arial.ttf';
                    // // Add the text

                    // imagestring($image, 5, $x*6, 10 + $y*6, $text, $black);
                }
                if ($cell->hasFood()) {
                    $colour = imagecolorallocate($image, 0, 0, 0);
                    imagelayereffect($image, IMG_EFFECT_REPLACE);
                    imagefilledellipse($image, (300 / $this->ySize) + $x*(600 / $this->xSize), (300 / $this->ySize) + $y*(600 / $this->ySize), 3, 3, $colour);
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
                if ($cell->getEntity() instanceof Entity
                    && $survivorFunction($cell)
                ) {
                    $survivors[] = $cell->getEntity();
                }
            }
        }
        return $survivors;
    }
}
