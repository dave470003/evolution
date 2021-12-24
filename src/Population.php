<?php

namespace App;

class Population
{
    protected static $instance;
    protected static $snapshot;

    protected $members;
    protected $grid;
    protected $gen;
    protected $turn;
    protected $record;
    protected $survivorFunction;
    protected $populationSize;
    protected $gridSize;

    public function __construct($populationSize, $gridSize, $survivorFunction)
    {
        static::$instance = $this;
        $this->turn = 0;
        $this->gen = 0;
        $this->record = true;
        $this->survivorFunction = $survivorFunction;
        $this->populationSize = $populationSize;
        $this->gridSize = $gridSize;
        $this->initializePopulation();
        $this->initializeGrid($gridSize, $this->members);
    }

    public static function instance()
    {
        return static::instance();
    }

    public static function snapshot()
    {
        if (static::$snapshot === null) {
            static::$snapshot = new Snapshot(static::$instance);
        }
        return static::$snapshot;
    }

    public static function clearSnapshot()
    {
        static::$snapshot = null;
    }

    public function nextGen($record)
    {
        $this->record = $record;

        $this->breedMembers();
        $this->initializeGrid($this->gridSize, $this->members);
        $this->clearSnapshot();
        $this->gen++;
        $this->turn = 0;

        return $this;
    }

    public function initializePopulation()
    {
        $this->members = [];
        for ($i = 0; $i < $this->populationSize; $i++) {
            $genome = '';
            for ($j = 0; $j < 10; $j++) {
                $byteInt = rand(0, 255);
                $byte = str_pad(base_convert($byteInt, 10, 16), 2, "0", STR_PAD_LEFT);
                $genome .= $byte;
            }
            $this->members[] = new Entity($genome, $i);
        }
    }

    public function initializeGrid($gridSize, $members)
    {
        $this->grid = new Grid($members, $gridSize, $gridSize);
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function getGrid()
    {
        return $this->grid;
    }

    public function breedMembers()
    {
        $survivors = $this->grid->getSurvivors($this->survivorFunction);
        $newPop = [];
        $i = 0;
        while (count($newPop) < $this->populationSize) {
            $survivorKeys = array_rand($survivors, 2);
            $baby = $survivors[$survivorKeys[0]]->breed($survivors[$survivorKeys[1]], $i);
            $newPop[] = $baby;
            $i++;
        }

        $this->members = $newPop;
    }

    public function runTurns($num)
    {
        for ($i = 0; $i < $num; $i++) {
            $this->runTurn();
        }
        if ($this->record) {
            $this->makeGif();
        }
    }

    public function runTurn()
    {
        foreach ($this->members as $member) {
            $member->runTurn();
        }
        if ($this->record) {
            $this->generateImage();
        }
        $this->turn++;
    }

    public function generateImage()
    {
        $im = imagecreatetruecolor(605, 605);
        $white = imagecolorallocate($im, 255, 255, 255);
        imagefilledrectangle($im, 4, 4, 600, 600, $white);

        $this->getGrid()->drawEntities($im);

        imagepng($im, './tmp/images/turn' . str_pad($this->turn, 4, "0", STR_PAD_LEFT) . '.png');
    }

    public function makeGif()
    {
        echo 'generating GIF';
        $multiTIFF = new \Imagick();

        $mytifspath = "./tmp/images"; // your image directory

        $files = scandir($mytifspath);

        //print_r($files);

        foreach ($files as $f) {
            if ($f === '.' || $f === '..') {
                continue;
            }
            $auxIMG = new \Imagick();
            $auxIMG->readImage($mytifspath."/".$f);

            $multiTIFF->addImage($auxIMG);
        }

        //file multi.TIF
        $multiTIFF->writeImages('build/images/gen' . $this->gen . '.gif', true); // combine all image into one single image

        $files = glob('./tmp/images/*'); // get all file names
        foreach ($files as $file) { // iterate files
          if (is_file($file)) {
              unlink($file); // delete file
          }
        }
    }
}
