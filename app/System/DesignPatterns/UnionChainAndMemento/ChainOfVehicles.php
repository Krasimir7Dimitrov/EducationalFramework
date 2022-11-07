<?php

namespace App\System\DesignPatterns\UnionChainAndMemento;

abstract class ChainOfVehicles
{
    private $value;

    /**
     * @var ChainOfVehicles
     */
    protected ChainOfVehicles $nextVehicle;


    public function then(ChainOfVehicles $vehicle, $value)
    {
        $this->nextVehicle = $vehicle;
        $this->value = $value;
    }

    public abstract function change($value, $previous);

    public abstract function rollback();

    public function next($previous)
    {
        if (!isset($this->nextVehicle)) return;
        $this->nextVehicle->change($this->value, $previous);
    }

    public function back()
    {
        $this->previous->rollback();
    }
}