<?php

namespace App\System\DesignPatterns\Builder\Director;

use App\System\DesignPatterns\Builder\Build\Builder;
use App\System\DesignPatterns\Builder\Build\Vehicle;

class Director
{
    function build(Builder $builder): Vehicle
    {
        $builder->createVehicle();
        $builder->addEngine();
        $builder->addWheel();
        $builder->addDoors();

        return $builder->getVehicle();
    }
}