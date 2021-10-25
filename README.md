# Laravel Pricing

Manage complex pricing for your eloquent models.

To use in frontend or Node.js, checkout [moirei/complex-pricing](https://github.com/moirei/complex-pricing).



## Documentation

All documentation is available at [the documentation site](https://moirei.github.io/laravel-pricing).


## Example

```php
...
$product = Product::find(1);

$product->pricing([
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
  ],
]);

$price = $product->price; // price for 1 item

$price = $product->price(4); // price = 4 x 3.6 = 14.4
$price = $product->price(7); // price = 7 x 3.3 = 23.1
$price = $product->price(15); // price = (15 x 3.1) + 1.2 = 47.7
```

## Installation

```bash
composer require moirei/laravel-pricing
```



## Concept

In large applications, pricing for provided goods or service are often not straight forward. For instance, you might want to charge $10 on an item for every 5 units purchased in AU, while at the same time, for your customers in US, regressively charge $50, $40, $30 for every quantity ranged between 0-30, 31-40, 50-infinity respectively.

This package has the concept of `standard`, `package`, `volume`, and `graduated` pricing intended to cover most (if not all) complex pricing scenarios. It also allows naming for multi-currency and multi-region use cases.


## Changelog

Please see [CHANGELOG](./CHANGELOG.md).



## Credits

- [Augustus Okoye](https://github.com/augustusnaz)

## Tests

```php
composer run test
```


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.