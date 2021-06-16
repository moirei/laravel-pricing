<?php

namespace MOIREI\Pricing\Tests\Unit;

use MOIREI\Pricing\Tests\TestCase;
use MOIREI\Pricing\Pricing;

class PricingTest extends TestCase
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
    function make_standard_pricing()
    {
        $pricing = Pricing::make([
            'model' => 'standard',
            'unit_amount' => 25,
        ]);
        $this->assertEquals(100.0, $pricing->price(4));
    }

    /** @test */
    function make_package_pricing()
    {
        $pricing = Pricing::make([
            'model' => 'package',
            'unit_amount' => 25,
            'units' => 5,
        ]);
        $this->assertEquals(25.0, $pricing->price(4));
        $this->assertEquals(50.0, $pricing->price(8));
    }

    /** @test */
    function make_volume_pricing()
    {
        $pricing = Pricing::make([
            'model' => 'volume',
            'tiers' => $this->tiers,
        ]);
        $this->assertEquals(5.0, $pricing->price());
        $this->assertEquals(25.0, $pricing->price(5));
        $this->assertEquals(24.0, $pricing->price(6));
    }

    /** @test */
    function make_graduated_pricing()
    {
        $pricing = Pricing::make([
            'model' => 'graduated',
            'tiers' => $this->tiers,
        ]);
        $this->assertEquals(5.0, $pricing->price());
        $this->assertEquals(25.0, $pricing->price(5));
        $this->assertEquals(29.0, $pricing->price(6));
    }

    /** @test */
    function confirm_pricing_data()
    {
        $pricing = Pricing::make()->data(['currency' => 'AUD']);
        $this->assertEquals('AUD', $pricing->data()->get('currency'));
    }
}