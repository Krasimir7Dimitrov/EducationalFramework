<?php

namespace App\System\DesignPatterns\UnionChainAndMemento;

class Truck
{
    public $tires;

    public function __construct($tires)
    {
        $this->tires = $tires;
    }
}