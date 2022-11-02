<?php

namespace App\System\DesignPatterns\Memento;

class Car
{
    public $color;

    public function __construct($color)
    {
        $this->color = $color;
    }
}