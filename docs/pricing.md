# Pricing Models

## Standard

This is the classic pricing model where your price result is a linear multiple of the unit price.

```php
use MOIREI\Pricing\Pricing;

...
$pricing = Pricing::make([
  'model' => 'standard',
  'unit_amount' => 25,
]);

$pricing->price(4); // returns 100.0
```

## Package

`Package` pricing computes the total result but unit groups. For example, a unit amount of $25.0 for every 5 units. Results are rounded up such that 8 units returns $50.0

```php
use MOIREI\Pricing\Pricing;

...
$pricing = Pricing::make([
  'model' => 'package',
  'unit_amount' => 25,
  'units' => 5,
]);

$pricing->price(4); // returns 25.0
$pricing->price(8); // returns 50.0
```

## Volume
Use `volume` pricing to apply charges based on tier of the `quanity`.
For example, with the tiers below, charges on 1-5 units falls in the first tier, 6-10 within the second and within the third for 11 and above.

```php
$pricing = Pricing::make([
  'model' => 'volume',
  'tiers' => [
    [
      'max' => 5,
      'unit_amount' => 3,
    ],
    [
      'max' => 10,
      'unit_amount' => 2,
    ],
    [
      'max' => 'infinity',
      'unit_amount' => 1,
      'flat_amount' => 0.3,
    ],
  ],
]);

$pricing->price(4); // returns 4 x 3 = 12.0
$pricing->price(8); // returns 8 x 2 = 16.0
$pricing->price(12); // returns (12 x 1) + 0.3 = 12.3
```

## Graduated
Use `graduated` pricing to progressively calculate a charge based on all applicable tiers. For example, with the tiers below, a unit of 6 falls between tiers 0-1, 12 falls between 0-2, and so on.

```php
$pricing = Pricing::make([
  'model' => 'volume',
  'tiers' => [
    [
      'max' => 5,
      'unit_amount' => 4,
    ],
    [
      'max' => 10,
      'unit_amount' => 3,
      'flat_amount' => 0.1,
    ],
    [
      'max' => 15,
      'unit_amount' => 2,
      'flat_amount' => 0.2,
    ],
    [
      'max' => 'infinity',
      'unit_amount' => 1,
      'flat_amount' => 0.3,
    ],
  ],
]);

$pricing->price(4);  // returns 4 x 4 = 16.0
$pricing->price(8);  // returns (5 x 4) + (3 x 3 + 0.1) = 29.1
$pricing->price(12); // returns (5 x 4) + (5 x 3 + 0.1) + (2 x 2 + 0.2) = 39.3
```

## Flat Fees
For `volume` and `graduated` pricing, using `flat_amount` for the provided tiers to include a flat fee for every charge.
