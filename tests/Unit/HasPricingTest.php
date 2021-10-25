<?php

namespace MOIREI\Pricing\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use MOIREI\Pricing\Pricing;
use MOIREI\Pricing\HasPricing;
use MOIREI\Pricing\Tests\TestCase;

beforeEach(function () {
    $this->tiers = [
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

    $this->product = new class extends Model
    {
        use HasPricing;
    
        protected $fillable = [
            'pricing'
        ];
        protected $attributes = [
            'pricing' => null
        ];
    };
});

it('should set pricing from array', function () {
    $this->product->fill([
        'pricing' => [
            'default' => [
                'model' => Pricing::MODEL_VOLUME,
                'tiers' => $this->tiers,
            ]
        ]
    ]);

    expect($this->product->price)->toEqual(5.0);
});

it('should set volume pricing from method', function () {
    $this->product->pricing([
        'model' => Pricing::MODEL_VOLUME,
        'tiers' => $this->tiers,
    ]);

    expect($this->product->price)->toEqual(5.0);
});

it('should set standard pricing from method', function () {
    $this->product->pricing(5);

    expect($this->product->price)->toEqual(5.0);
    expect($this->product->price(2))->toEqual(10.0);
});

it('should set multiple pricing from method', function () {
    $this->product->pricing([
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

    expect($this->product->price(4))->toEqual(100.0);
    expect($this->product->price(4, 'other'))->toEqual(25.0);
    expect($this->product->price(8, 'other'))->toEqual(50.0);
});
