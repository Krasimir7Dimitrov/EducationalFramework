<?php

namespace App\System\DesignPatterns\UnionChainAndMemento;

class CustomizeBike extends ChainOfVehicles
{
    private $bike;

    public $backup;

    public $previous;


    public function __construct(Bike $bike)
    {
        $this->bike = $bike;
    }

    public function copy(): Memento
    {
        return new Memento(clone $this->bike);
    }

    public function restore(Memento $memento)
    {
        $this->bike = $memento->getBackup();
    }

    public function change($volume, $previous)
    {
        $this->previous = $previous;
        $this->backup = $this->copy();
        $this->bike->tank = $volume;
        if ($volume != 20 && $volume != 30) {
            $this->restore($this->backup);
            $this->rollback();
            throw new \Exception("Bike error");
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

    public function getTankVol()
    {
        return $this->bike->tank;
    }
}