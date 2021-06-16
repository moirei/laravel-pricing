# Complex Usage

You can easily implement multi-currency or locale with price naming.

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
    'data' => $data,
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

Get pricing
```php

$price = $product->price;
$price = $product->price();

// Get pricing for a measure of 2
$price = $product->price(2);
$price = $product->price(2, 'AU'); // specify name
```
