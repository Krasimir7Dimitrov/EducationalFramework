<?php

namespace App\System\DesignPatterns\UnionChainAndMemento;

class Bike
{
    public $tank;

    public function __construct($tank)
    {
        $this->tank = $tank;
    }
}