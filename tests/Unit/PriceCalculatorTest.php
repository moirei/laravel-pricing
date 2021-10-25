<?php

namespace MOIREI\Pricing\Tests\Unit;

use MOIREI\Pricing\PriceCalculator;

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

it('calculates valid package pricing', function () {
    expect(PriceCalculator::package(4, 25, 5))->toEqual(25.0);

    // $25 for every 5 units
    // 8 units should be $50
    expect(PriceCalculator::package(8, 25, 5))->toEqual(50.0);
});

it('calculates valid volume pricing', function () {
    expect(PriceCalculator::volume(1, $this->tiers))->toEqual(5.0);
    expect(PriceCalculator::volume(5, $this->tiers))->toEqual(25.0);
    expect(PriceCalculator::volume(6, $this->tiers))->toEqual(24.0);
    expect(PriceCalculator::volume(20, $this->tiers))->toEqual(40.0);
    expect(PriceCalculator::volume(25, $this->tiers))->toEqual(25.0);
});

it('calculates valid graduated pricing', function () {
    expect(PriceCalculator::graduated(1, $this->tiers))->toEqual(5.0);
    expect(PriceCalculator::graduated(5, $this->tiers))->toEqual(25.0);
    expect(PriceCalculator::graduated(6, $this->tiers))->toEqual(29.0);
    expect(PriceCalculator::graduated(20, $this->tiers))->toEqual(70.0);
    expect(PriceCalculator::graduated(25, $this->tiers))->toEqual(75.0);
});
