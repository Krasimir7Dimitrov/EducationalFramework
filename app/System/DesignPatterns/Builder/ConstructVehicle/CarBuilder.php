<?php

namespace App\System\DesignPatterns\Builder\ConstructVehicle;

use App\System\DesignPatterns\Builder\Build\Builder;
use App\System\DesignPatterns\Builder\Build\Vehicle;
use App\System\DesignPatterns\Builder\Parts\Door;
use App\System\DesignPatterns\Builder\Parts\Engine;
use App\System\DesignPatterns\Builder\Parts\Wheel;
use App\System\DesignPatterns\Builder\TypeVehicle\Car;

class CarBuilder implements Builder
{
    /**
     * @var Car
     */
    private Car $car;

    public function createVehicle(): void
    {
        $this->car = new Car();
    }

    public function getVehicle(): Vehicle
    {
        return $this->car;
    }

    public function addEngine(): void
    {
        $this->car->setPart('benzineEngine', new Engine());
    }

    public function addWheel(): void
    {
        $this->car->setPart('Front Left Wheel', new Wheel());
        $this->car->setPart('Front Right Wheel', new Wheel());
        $this->car->setPart('Back Left Wheel', new Wheel());
        $this->car->setPart('Back Right Wheel', new Wheel());
    }

    public function addDoors(): void
    {
        $this->car->setPart('Left Door', new Door());
        $this->car->setPart('Right Door', new Door());
        $this->car->setPart('Trunk Door', new Door());
    }
}