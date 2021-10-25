<?php

namespace MOIREI\Pricing\Tests\Unit;

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

it('should be a valid standard pricing', function () {
    $pricing = Pricing::make([
        'model' => 'standard',
        'unit_amount' => 25,
    ]);
    expect($pricing->price(4))->toEqual(100.0);
});

it('should be a valid package pricing', function () {
    $pricing = Pricing::make([
        'model' => 'package',
        'unit_amount' => 25,
        'units' => 5,
    ]);
    expect($pricing->price(4))->toEqual(25.0);
    expect($pricing->price(8))->toEqual(50.0);
});

it('should be a valid volume pricing', function () {
    $pricing = Pricing::make([
        'model' => 'volume',
        'tiers' => $this->tiers,
    ]);
    expect($pricing->price())->toEqual(5.0);
    expect($pricing->price(1))->toEqual(5.0);
    expect($pricing->price(5))->toEqual(25.0);
    expect($pricing->price(6))->toEqual(24.0);
});

it('should be a valid graduated pricing', function () {
    $pricing = Pricing::make([
        'model' => 'graduated',
        'tiers' => $this->tiers,
    ]);
    expect($pricing->price())->toEqual(5.0);
    expect($pricing->price(1))->toEqual(5.0);
    expect($pricing->price(5))->toEqual(25.0);
    expect($pricing->price(6))->toEqual(29.0);
});

it('should be have valid miscellaneous data', function () {
    $pricing = Pricing::make()->data(['currency' => 'AUD']);
    $tiers_count = $pricing->data('meta.tiers_count', 4);
    expect($pricing->data()->get('currency'))->toEqual('AUD');
    expect($pricing->data('meta.tiers_count'))->toEqual(4);
    expect($tiers_count)->toEqual(4);
});
