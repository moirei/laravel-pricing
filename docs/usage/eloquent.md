# Eloquent

## Standard pricing
```php
$product->pricing(3.6);
// or
$product->pricing(function($pricing){
  $pricing->standard(3.6);
});
```

## Volume & Graduated (tiered) pricing
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
$data = ['currency' => 'AUD'];

$product->pricing([
  'model' => 'volume',
  'tiers' => $tiers,
  'data' => $data,
]);

// or use closure
$product->pricing(function($pricing){
  $pricing->model('volume')->tiers($tiers)->data($data);
});
```

## Get pricing
```php

$price = $product->price;
$price = $product->price();

// Get pricing for 2 units
$price = $product->price(2);
```

