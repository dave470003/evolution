<?php

namespace App;

class Entity
{
    protected $genome;
    protected $brain;
    protected $facing;
    protected $id;

    public function __construct($genome, $id)
    {
        $this->genome = $genome;
        $this->brain = Brain::fromGenome($genome, $this);
        $this->id = $id;
    }

    public function __clone()
    {
        $this->brain = clone $this->brain;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFacing($facing)
    {
        $this->facing = $facing;
    }

    public function getFacing()
    {
        return $this->facing;
    }

    public function runTurn()
    {
        $this->brain->runTurn();
    }

    public function getColour()
    {
        $hex = unpack('h4hex', $this->genome);
        $splitLength = floor(strlen($hex['hex']) / 3);
        $redHex = substr($hex['hex'], 0, $splitLength);
        $greenHex = substr($hex['hex'], $splitLength, $splitLength * 2);
        $blueHex = substr($hex['hex'], $splitLength * 2);

        $redDec = base_convert($redHex, 16, 10) % 255;
        $greenDec = base_convert($greenHex, 16, 10) % 255;
        $blueDec = base_convert($blueHex, 16, 10) % 255;

        $redHex = base_convert($redDec, 10, 16);
        $greenHex = base_convert($greenDec, 10, 16);
        $blueHex = base_convert($blueDec, 10, 16);

        return $redHex . $greenHex . $blueHex;
    }
}
