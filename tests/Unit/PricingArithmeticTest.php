<?php

namespace MOIREI\Pricing\Tests\Unit;

use MOIREI\Pricing\Pricing;

uses()->group('pricing-arithmetic');

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

it('should add value to STANDARD pricing', function () {
    $pricing = Pricing::make([
        'model' => 'standard',
        'unit_amount' => 25,
    ]);

    expect($pricing->price(4))->toEqual(100.0);

    $pricing->add(5);

    expect($pricing->price(4))->toEqual(120.0);
});

it('should subtract value from STANDARD pricing', function () {
    $pricing = Pricing::make([
        'model' => 'standard',
        'unit_amount' => 25,
    ]);

    expect($pricing->price(4))->toEqual(100.0);

    $pricing->subtract(5);

    expect($pricing->price(4))->toEqual(80.0);
});

it('should add value to PACKAGE pricing', function () {
    $pricing = Pricing::make([
        'model' => 'package',
        'unit_amount' => 25,
        'units' => 5,
    ]);

    expect($pricing->price(4))->toEqual(25.0);
    expect($pricing->price(8))->toEqual(50.0);

    $pricing->add(5);

    expect($pricing->price(4))->toEqual(30.0);
    expect($pricing->price(8))->toEqual(60.0);
});

it('should add value to VOLUME pricing', function () {
    $pricing = Pricing::make([
        'model' => 'volume',
        'tiers' => $this->tiers,
    ]);

    expect($pricing->price(1))->toEqual(5.0);
    expect($pricing->price(5))->toEqual(25.0);
    expect($pricing->price(6))->toEqual(24.0);

    $pricing->add(5);

    expect($pricing->price(1))->toEqual(10.0);
    expect($pricing->price(5))->toEqual(50.0);
    expect($pricing->price(6))->toEqual(54.0);
});

it('should add value to GRADUATED pricing', function () {
    $pricing = Pricing::make([
        'model' => 'graduated',
        'tiers' => $this->tiers,
    ]);

    expect($pricing->price(1))->toEqual(5.0);
    expect($pricing->price(5))->toEqual(25.0);
    expect($pricing->price(6))->toEqual(29.0);

    $pricing->add(5);

    expect($pricing->price(1))->toEqual(10.0);
    expect($pricing->price(5))->toEqual(50.0);
    expect($pricing->price(6))->toEqual(59.0);
});

// ------------------

it('should multiple value on STANDARD pricing', function () {
    $pricing = Pricing::make([
        'model' => 'standard',
        'unit_amount' => 25,
    ]);

    expect($pricing->price(4))->toEqual(100.0);

    $pricing->multiply(2);

    expect($pricing->price(4))->toEqual(200.0);
});

it('should device value on STANDARD pricing', function () {
    $pricing = Pricing::make([
        'model' => 'standard',
        'unit_amount' => 25,
    ]);

    expect($pricing->price(4))->toEqual(100.0);

    $pricing->divide(2);

    expect($pricing->price(4))->toEqual(50.0);
});

it('should device value on PACKAGE pricing', function () {
    $pricing = Pricing::make([
        'model' => 'package',
        'unit_amount' => 25,
        'units' => 5,
    ]);

    expect($pricing->price(4))->toEqual(25.0);
    expect($pricing->price(8))->toEqual(50.0);

    $pricing->divide(5);

    expect($pricing->price(4))->toEqual(5.0);
    expect($pricing->price(8))->toEqual(10.0);
});

it('should device value on VOLUME pricing', function () {
    $pricing = Pricing::make([
        'model' => 'volume',
        'tiers' => $this->tiers,
    ]);

    expect($pricing->price(1))->toEqual(5.0);
    expect($pricing->price(5))->toEqual(25.0);
    expect($pricing->price(6))->toEqual(24.0);

    $pricing->divide(5);

    expect($pricing->price(1))->toEqual(1.0);
    expect($pricing->price(5))->toEqual(5.0);
    expect($pricing->price(6))->toEqual(4.8);
});

it('should device value on GRADUATED pricing', function () {
    $pricing = Pricing::make([
        'model' => 'graduated',
        'tiers' => $this->tiers,
    ]);

    expect($pricing->price(1))->toEqual(5.0);
    expect($pricing->price(5))->toEqual(25.0);
    expect($pricing->price(6))->toEqual(29.0);

    $pricing->divide(5);

    expect($pricing->price(1))->toEqual(1.0);
    expect($pricing->price(5))->toEqual(5.0);
    expect($pricing->price(6))->toEqual(5.8);
});
