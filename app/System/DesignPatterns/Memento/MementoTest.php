<?php

namespace App\System\DesignPatterns\Memento;

use PHPUnit\Framework\TestCase;

class MementoTest extends TestCase
{
    public function testSetColor()
    {
        $custom = new Customizer(new Car('Green'));
        self::assertEquals($custom->getColor(), 'Green');
    }

    public function testBackup()
    {
        $custom = new Customizer(new Car('Green'));
        $backup = $custom->copy();

        $custom->changeColor("White");
        self::assertEquals("White", $custom->getColor());

        $custom->restore($backup);
        $previousValue = $custom->getColor();

        self::assertEquals("Green", $previousValue, );
    }
}