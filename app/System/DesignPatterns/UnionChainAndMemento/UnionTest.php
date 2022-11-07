<?php

namespace App\System\DesignPatterns\UnionChainAndMemento;

use PHPUnit\Framework\TestCase;

class UnionTest extends TestCase
{
    public function testSequence()
    {
        $customCar = new CustomizeCar(new Car('Green'));
        $customTruck = new CustomizeTruck(new Truck('Michelin'));
        $customBike = new CustomizeBike(new Bike(20));

        $customTruck->then($customCar, "White");
        $customCar->then($customBike, 40);


        $message = '';
        try {
            $customTruck->change('Debica', null);
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        $color = $customCar->getColor();
        $tires = $customTruck->getTires();
        $tankVol = $customBike->getTankVol();

        //var_dump($tankVol, $tires, $color);


        self::assertEquals("Green", $color);
        self::assertEquals("Michelin", $tires);
        self::assertEquals(20, $tankVol);
        self::assertEquals('Bike error', $message);
    }
}

