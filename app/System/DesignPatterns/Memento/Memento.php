<?php

namespace App\System\DesignPatterns\Memento;

class Memento
{
    private $car;

    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    /**
     * @return Car
     */
    public function getCar(): Car
    {
        return $this->car;
    }
}