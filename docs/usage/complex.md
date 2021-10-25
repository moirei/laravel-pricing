# Complex Usage

With the use of the `CastMultiPricing` cast and `HasPricing` trait, you can easily implement multi-currency or locale with price naming.

Here is an example using the `HasPricing` trait.

## Set Pricing

```php
$tiers = [
  [
    'max' => 5,
    'unit_amount' => 3.6,
  ],
  [
    'max' => 10,
    'unit_amount' => 3.3,
  ],
  [
    'max' => 'infinity', // or `inf`, `-1`
    'unit_amount' => 3.1,
    'flat_amount' => 1.2,
  ],
];

$data = [
  'currency' => 'AUD',
  // 'measure' => 'quantity', // additional data for maybe calculating price from quantity, weight, or volume
];

$pricing = [
  'default' => [
    'model' => 'volume',
    'tiers' => $tiers,
    'data' => $data,
  ],
  'US' => [
    'model' => 'volume',
    'tiers' => $tiers,
    'data' => ['currency' => 'USD'],
  ],
];

$product->pricing($pricing);
$product->pricing([
  'model' => 'volume',
  'tiers' => $tiers,
  'data' => $data,
]);

$product->pricing(function($pricing){
  $pricing->model('volume')->tiers($tiers)->data($data);
});

// spacify name
$product->pricing('AU', $pricing);
$product->pricing('AU', [
  'model' => 'volume',
  'tiers' => $tiers,
]);
$product->pricing('AU', function($pricing){
  $pricing->model('volume')->tiers($tiers)->data($data);
});
```

## Get Price
```php

$price = $product->price;
$price = $product->price();

// Get pricing for 2 units
$price = $product->price(2);
```
