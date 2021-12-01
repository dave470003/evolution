<?php

namespace App;

class Population
{
    protected static $instance;
    protected static $snapshot;

    protected $members;
    protected $grid;
    protected $gen;

    public function __construct()
    {
        static::$instance = $this;
        $this->turn = 0;
        $this->gen = 0;
        $this->initializePopulation();
        $this->initializeGrid($this->members);
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

    public function nextGen()
    {
        $this->breedMembers();
        $this->initializeGrid($this->members);
        $this->gen++;
    }

    public function initializePopulation()
    {
        // generate random;
        $this->members = [];
    }

    public function initializeGrid($members)
    {
        $this->grid = new Grid($members);
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
        // breed survivors
        $this->members = [];
    }
}
