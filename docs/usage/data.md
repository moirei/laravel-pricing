# Miscellaneous Data
To store miscellaneous data, e.g. currency, exchange-rate, use the `data` method on a pricing instance.

## Set/update data

```php
$pricing = Pricing::make([
  ...
  'data' => [
    'currency' => 'AUD',
  ],
]);
$pricing->data('meta.tiers_count', 4);

// or
$pricing->data([
  'currency' => 'AUD',
  'meta' => [
    'tiers_count' => 4,
  ]
]);
```

## Get data
```php
$data = $pricing->data(); // returns all data as collection

$currency = $data->get('currency');
$tiers_count = $pricing->data('meta.tiers_count', 4);
```