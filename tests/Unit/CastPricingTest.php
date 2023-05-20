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
});

it('should cast to Pricing instance', function () {

    $attributes = [
        'pricing' => Pricing::make()->standard(2)
    ];
    $product = new class($attributes) extends Model
    {
        protected $fillable = ['pricing'];
        protected $casts = [
            'pricing' => CastPricing::class,
        ];
    };

    expect($product->pricing)->toBeInstanceOf(Pricing::class);
});

it('should calculate a valid pricing', function () {
    $attributes = [
        'pricing' => Pricing::make()->standard(2)
    ];
    $product = new class($attributes) extends Model
    {
        protected $fillable = ['pricing'];
        protected $casts = [
            'pricing' => CastPricing::class,
        ];
    };

    $product->pricing->graduated($this->tiers);
    expect($product->pricing->price(1))->toEqual(5.0);
    expect($product->pricing->price(5))->toEqual(25.0);
    expect($product->pricing->price(6))->toEqual(29.0);
});

it('should set pricing using array', function () {
    $product = new class() extends Model
    {
        protected $fillable = ['pricing'];
        protected $casts = [
            'pricing' => CastPricing::class,
        ];
    };

    expect($product->pricing)->toBeNull();

    $product->pricing = [
        'model' => 'graduated',
        'tiers' => $this->tiers
    ];

    expect($product->pricing)->toBeInstanceOf(Pricing::class);
    expect($product->pricing->price(1))->toEqual(5.0);
    expect($product->pricing->price(5))->toEqual(25.0);
    expect($product->pricing->price(6))->toEqual(29.0);
});

fit('should set volume pricing using array', function () {
    $product = new class() extends Model
    {
        protected $fillable = ['pricing'];
        protected $casts = [
            'pricing' => CastPricing::class,
        ];
    };

    expect($product->pricing)->toBeNull();

    $product->pricing = [
        'model' => 'volume',
        'tiers' => [
            [
                'max' => 5,
                'unit_amount' => 3.6,
            ],
            [
                'max' => 10,
                'unit_amount' => 3.3,
            ],
            [
                'max' => 'infinity', // or `-1`
                'unit_amount' => 3.1,
                'flat_amount' => 1.2,
            ],
        ]
    ];

    expect($product->pricing)->toBeInstanceOf(Pricing::class);
    expect($product->pricing->price(1))->toEqual(3.6);
    expect($product->pricing->price(6))->toEqual(19.8);
    expect($product->pricing->price(11))->toEqual(35.3);
});

it('should set pricing using instance', function () {
    $product = new class() extends Model
    {
        protected $fillable = ['pricing'];
        protected $casts = [
            'pricing' => CastPricing::class,
        ];
    };

    expect($product->pricing)->toBeNull();

    $product->pricing = Pricing::make([
        'model' => 'graduated',
        'tiers' => $this->tiers
    ]);

    expect($product->pricing)->toBeInstanceOf(Pricing::class);
    expect($product->pricing->price(1))->toEqual(5.0);
    expect($product->pricing->price(5))->toEqual(25.0);
    expect($product->pricing->price(6))->toEqual(29.0);
});
