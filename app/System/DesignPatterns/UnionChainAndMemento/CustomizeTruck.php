<?php

namespace App\System\DesignPatterns\UnionChainAndMemento;

class CustomizeTruck extends ChainOfVehicles
{
    private $truck;

    public $backup;

    public $previous;


    public function __construct(Truck $truck)
    {
        $this->truck = $truck;
    }

    public function copy(): Memento
    {
        return new Memento(clone $this->truck);
    }

    public function restore(Memento $memento)
    {
        $this->truck = $memento->getBackup();
    }

    public function change($tires, $previous)
    {
        $this->previous = $previous;
        $this->backup = $this->copy();
        $this->truck->tires = $tires;
        if ($tires != 'Debica' && $tires != 'Michelin') {
            $this->restore($this->backup);
            $this->rollback();
            throw new \Exception("Truck error");
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

    public function getTires()
    {
        return $this->truck->tires;
    }
}