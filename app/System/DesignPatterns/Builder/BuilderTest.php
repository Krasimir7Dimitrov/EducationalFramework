<?php

namespace App\System\DesignPatterns\Builder;

use App\System\DesignPatterns\Builder\ConstructVehicle\CarBuilder;
use App\System\DesignPatterns\Builder\ConstructVehicle\TruckBuilder;
use App\System\DesignPatterns\Builder\Director\Director;
use App\System\DesignPatterns\Builder\TypeVehicle\Car;
use App\System\DesignPatterns\Builder\TypeVehicle\Truck;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    /**
     * @return void
     */
    public function testCanBuildTruck()
    {
        $truckBuilder = new TruckBuilder();
        $newVehicle = (new Director())->build($truckBuilder);

        $this->assertInstanceOf(Truck::class, $newVehicle);
    }

    /**
     * @return void
     */
    public function testCanBuildCar()
    {
        $carBuilder = new CarBuilder();
        $newVehicle = (new Director())->build($carBuilder);

        $this->assertInstanceOf(Car::class, $newVehicle);
    }

}