<?php

namespace MOIREI\Pricing\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use MOIREI\Pricing\HasPricing;
use MOIREI\Pricing\Tests\TestCase;

class Product extends Model{
    use HasPricing;

    protected $fillable = [
        'pricing'
    ];
}

class HasPricingTest extends TestCase{
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
    function get_price()
    {
        $product = (new Product)->fill([
            'pricing' => [
                'default' => [
                  'model' => 'volume',
                  'tiers' => $this->tiers,
                ]
            ]
        ]);
        $this->assertEquals(5.0, $product->price);
    }
}