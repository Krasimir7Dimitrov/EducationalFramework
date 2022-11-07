<?php

namespace App\System\DesignPatterns\Memento;

use phpDocumentor\Reflection\Types\This;

class Memento
{
    /**
     * @var object
     */
    private object $backup;

    public function __construct(object $backup)
    {
        $this->backup = $backup;
    }

    /**
     * @return object
     */
    public function getBackup(): object
    {
        return $this->backup;
    }
}