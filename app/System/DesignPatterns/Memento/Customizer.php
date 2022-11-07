<?php

namespace App\System\DesignPatterns\Memento;

class Customizer
{
    private $car;

    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    public function copy()
    {
        return new Memento(clone $this->car);
    }

    public function restore(Memento $memento)
    {
        $this->car = $memento->getBackup();
    }

    public function changeColor($color)
    {
        $this->car->color = $color;
    }

    public function getColor()
    {
        return $this->car->color;
    }
}