<?php

namespace App\System\DesignPatterns\UnionChainAndMemento;

class Car
{
    public $color;

    public function __construct($color)
    {
        $this->color = $color;
    }
}