<?php

namespace MOIREI\Pricing\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use MOIREI\Pricing\CastMultiPricing;
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
            'pricing' => CastMultiPricing::class,
        ];
    };
});

it('should cast to collection instance', function () {
    expect($this->product->pricing)->toBeCollection();
    expect($this->product->pricing->isEmpty())->toBeTrue();
});

it('should dynamically create Pricing instance', function () {
    expect($this->product->pricing->aud)->toBeInstanceOf(Pricing::class);
    expect($this->product->pricing->isEmpty())->toBeFalse();

    $aud = $this->product->pricing->aud;
    $aud->graduated($this->tiers);
    expect($aud->price(1))->toEqual(5.0);
    expect($aud->price(5))->toEqual(25.0);
    expect($aud->price(6))->toEqual(29.0);

    $array = $this->product->pricing->toArray();
    expect(Arr::get($array, 'aud.model'))->toEqual(Pricing::MODEL_GRADUATED);
});
