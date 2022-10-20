<?php

namespace App\System\DesignPatterns\Builder\Build;

interface Builder
{
    /**
     * @return void
     */
    public function createVehicle(): void;

    /**
     * @return Vehicle
     */
    public function getVehicle(): Vehicle;

    /**
     * @return void
     */
    public function addEngine(): void;

    /**
     * @return void
     */
    public function addWheel(): void;

    /**
     * @return void
     */
    public function addDoors(): void;
}