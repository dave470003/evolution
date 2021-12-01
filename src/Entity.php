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
        $splitLength = floor(strlen($this->genome) / 3);
        $redHex = substr($this->genome, 0, $splitLength);
        $greenHex = substr($this->genome, $splitLength, $splitLength);
        $blueHex = substr($this->genome, $splitLength * 2);

        $redDec = base_convert($redHex, 16, 10) % 255;
        $greenDec = base_convert($greenHex, 16, 10) % 255;
        $blueDec = base_convert($blueHex, 16, 10) % 255;

        $redHex = base_convert($redDec, 10, 16);
        $greenHex = base_convert($greenDec, 10, 16);
        $blueHex = base_convert($blueDec, 10, 16);

        return $redHex . $greenHex . $blueHex;
    }

    public function getChromosomes()
    {
        return str_split($this->genome, 4);
    }

    public function breed($partner, $babyId)
    {
        $chromosomes = $this->getChromosomes();
        $partnerChromosomes = $partner->getChromosomes();

        $babyChromosomes = [];
        for ($i = 0; $i < count($chromosomes); $i++) {
            if (rand(0, 1) === 0) {
                $babyChromosomes[] = $chromosomes[$i];
            } else {
                $babyChromosomes[] = $partnerChromosomes[$i];
            }
        }

        var_dump($this->getChromosomes());

        $babyGenome = implode($babyChromosomes);
        $babyGenome = $this->mutateGenome($babyGenome);

        return new Entity($babyGenome, $babyId);
    }

    public static function mutateGenome($genome)
    {
        // var_dump($genome);
        $chromosomes = str_split($genome, 4);
        foreach ($chromosomes as &$chromosome) {
            $binary = base_convert($chromosome, 16, 2);
            $binaryArray = str_split($binary);

            foreach ($binaryArray as &$bin) {
                if (rand(0, 10) === 0) {
                    $bin = ~$bin;
                }
            }

            $binary = implode($binaryArray);
            $chromosome = base_convert($binary, 2, 16);
        }
        return implode('', $chromosomes);
    }
}
