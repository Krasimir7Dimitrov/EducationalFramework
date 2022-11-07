<?php

namespace App\System\DesignPatterns\UnionChainAndMemento;

class CustomizeCar extends ChainOfVehicles
{
    private $car;

    public $backup;

    public $previous;


    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    public function copy(): Memento
    {
        return new Memento(clone $this->car);
    }

    public function restore(Memento $memento)
    {
        $this->car = $memento->getBackup();
    }

    public function change($color, $previous)
    {
        $this->previous = $previous;
        $this->backup = $this->copy();
        $this->car->color = $color;
        if ($color != 'White' && $color != 'Green') {
            $this->restore($this->backup);
            $this->rollback();
            throw new \Exception("Car error");
        }
        $this->next($this);
    }

    public function rollback()
    {
        if (!is_null($this->previous)) {
            $this->previous->restore($this->previous->backup);
            $this->back();
        }
    }

    public function getColor()
    {
        return $this->car->color;
    }
}