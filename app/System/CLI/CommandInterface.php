<?php

namespace App\System\CLI;

interface CommandInterface
{
    public function execute();

    public function getOpt();
}