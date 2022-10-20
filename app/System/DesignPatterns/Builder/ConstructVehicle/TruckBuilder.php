<?php

namespace App\System\DesignPatterns\Builder\ConstructVehicle;

use App\System\DesignPatterns\Builder\Build\Builder;
use App\System\DesignPatterns\Builder\Build\Vehicle;
use App\System\DesignPatterns\Builder\Parts\Door;
use App\System\DesignPatterns\Builder\Parts\Engine;
use App\System\DesignPatterns\Builder\Parts\Wheel;
use App\System\DesignPatterns\Builder\TypeVehicle\Truck;

class TruckBuilder implements Builder
{
    /**
     * @var Truck
     */
    private Truck $truck;

    public function createVehicle(): void
    {
        $this->truck = new Truck();
    }

    public function getVehicle(): Vehicle
    {
        return $this->truck;
    }

    public function addEngine(): void
    {
        $this->truck->setPart('dieselEngine', new Engine());
    }

    public function addWheel(): void
    {
        $this->truck->setPart('Front Left Wheel', new Wheel());
        $this->truck->setPart('Front Right Wheel', new Wheel());
        $this->truck->setPart('First Back Left Wheel', new Wheel());
        $this->truck->setPart('Second Back Left Wheel', new Wheel());
        $this->truck->setPart('First Back Right Wheel', new Wheel());
        $this->truck->setPart('Second Back Right Wheel', new Wheel());
    }

    public function addDoors(): void
    {
        $this->truck->setPart('Left Door', new Door());
        $this->truck->setPart('Right Door', new Door());
    }
}