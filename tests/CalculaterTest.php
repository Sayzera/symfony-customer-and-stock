<?php

namespace App\Tests;

use App\Services\Calculator;
use PHPUnit\Framework\TestCase;

class CalculaterTest extends TestCase
{
    public function testSomething(): void
    {
        $calculater = new Calculator();

        $result = $calculater->add(1, 9);

        $this->assertEquals(10, $result);
    }
}
