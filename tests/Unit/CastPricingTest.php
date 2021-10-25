<?php

namespace MOIREI\Pricing\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use MOIREI\Pricing\CastPricing;
use MOIREI\Pricing\Pricing;

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
        protected $fillable = ['pricing'];
        protected $attributes = [
            'pricing' => null
        ];
        protected $casts = [
            'pricing' => CastPricing::class,
        ];
    };
});

it('should cast to Pricing instance', function () {
    expect($this->product->pricing)->toBeInstanceOf(Pricing::class);
});

it('should calculate a valid pricing', function () {
    $this->product->pricing->graduated($this->tiers);
    expect($this->product->pricing->price(1))->toEqual(5.0);
    expect($this->product->pricing->price(5))->toEqual(25.0);
    expect($this->product->pricing->price(6))->toEqual(29.0);
});
