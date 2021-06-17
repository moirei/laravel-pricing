# Eloquent

## Set Pricing
### Standard Pricing
Set a standard pricing of $5 per unit
```php
$product->pricing([
  'model' => 'standard',
  'unit_amount' => 5,
]);
// or
$product->pricing(5);
// or
$product->pricing(function($pricing){
  $pricing->standard(5);
});
```
### Package Pricing
Set a package pricing of $25 per 5 units
```php
$product->pricing([
  'model' => 'package',
  'unit_amount' => 25,
  'units' => 5,
]);
// or
$product->pricing(function($pricing){
  $pricing->package(25, 5);
});
```

### Volume & Graduated Pricing
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

## Get Price
```php

$price = $product->price;
$price = $product->price();

// Get pricing for 2 units
$price = $product->price(2);
```

