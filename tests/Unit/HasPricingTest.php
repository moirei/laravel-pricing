<?php

namespace MOIREI\Pricing\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use MOIREI\Pricing\Pricing;
use MOIREI\Pricing\HasPricing;
use MOIREI\Pricing\Tests\TestCase;

class Product extends Model
{
    use HasPricing;

    protected $fillable = [
        'pricing'
    ];
    protected $attributes = [
        'pricing' => null
    ];
}

class HasPricingTest extends TestCase
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
    function set_pricing_from_fillables()
    {
        $product = (new Product)->fill([
            'pricing' => [
                'default' => [
                    'model' => Pricing::MODEL_VOLUME,
                    'tiers' => $this->tiers,
                ]
            ]
        ]);
        $this->assertEquals(5.0, $product->price);
    }

    /** @test */
    function set_volume_pricing_from_method()
    {
        $product = new Product;
        $product->pricing([
            'model' => Pricing::MODEL_VOLUME,
            'tiers' => $this->tiers,
        ]);
        $this->assertEquals(5.0, $product->price);
    }

    /** @test */
    function set_standard_pricing_from_method()
    {
        $product = new Product;
        $product->pricing(5);
        $this->assertEquals(5.0, $product->price);
        $this->assertEquals(10.0, $product->price(2));
    }

    /** @test */
    function set_multiple_pricing_from_method()
    {
        $product = new Product;
        $product->pricing([
            'default' => [
                'model' => Pricing::MODEL_STANDARD,
                'unit_amount' => 25,
            ],
            'other' => [
                'model' => Pricing::MODEL_PACKAGE,
                'unit_amount' => 25,
                'units' => 5,
            ],
        ]);
        $this->assertEquals(100.0, $product->price(4));
        $this->assertEquals(25.0, $product->price(4, 'other'));
        $this->assertEquals(50.0, $product->price(8, 'other'));
    }
}
