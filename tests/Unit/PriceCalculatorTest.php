<?php

namespace MOIREI\Pricing\Tests\Unit;

use MOIREI\Pricing\Tests\TestCase;
use MOIREI\Pricing\PriceCalculator;

class PriceCalculatorTest extends TestCase
{
    protected $tiers = [
        [
          'max' => 5,
          'unit_amount' => 5,
        ],
        [
          'max' => 10,
          'unit_amount' => 4,
        ],
        [
          'max' => 15,
          'unit_amount' => 3,
        ],
        [
          'max' => 20,
          'unit_amount' => 2,
        ],
        [
          'max' => 'infinity',
          'unit_amount' => 1,
        ],
    ];

    /** @test */
    function package_value_1()
    {
        // $25 for every 5 units
        // 4 units should be $25
        $this->assertEquals(25.0, PriceCalculator::package(4, 25, 5));
    }

    /** @test */
    function package_value_2()
    {
        // $25 for every 5 units
        // 8 units should be $50
        $this->assertEquals(50.0, PriceCalculator::package(8, 25, 5));
    }

    /** @test */
    function volume_value_1()
    {
        $this->assertEquals(5.0, PriceCalculator::volume(1, $this->tiers));
    }

    /** @test */
    function volume_value_5()
    {
        $this->assertEquals(25.0, PriceCalculator::volume(5, $this->tiers));
    }

    /** @test */
    function volume_value_6()
    {
        $this->assertEquals(24.0, PriceCalculator::volume(6, $this->tiers));
    }

    /** @test */
    function volume_value_20()
    {
        $this->assertEquals(40.0, PriceCalculator::volume(20, $this->tiers));
    }

    /** @test */
    function volume_value_25()
    {
        $this->assertEquals(25.0, PriceCalculator::volume(25, $this->tiers));
    }

    /** @test */
    function graduated_value_1()
    {
        $this->assertEquals(5.0, PriceCalculator::graduated(1, $this->tiers));
    }

    /** @test */
    function graduated_value_5()
    {
        $this->assertEquals(25.0, PriceCalculator::graduated(5, $this->tiers));
    }

    /** @test */
    function graduated_value_6()
    {
        $this->assertEquals(29.0, PriceCalculator::graduated(6, $this->tiers));
    }

    /** @test */
    function graduated_value_20()
    {
        $this->assertEquals(70.0, PriceCalculator::graduated(20, $this->tiers));
    }

    /** @test */
    function graduated_value_25()
    {
        $this->assertEquals(75.0, PriceCalculator::graduated(25, $this->tiers));
    }
}