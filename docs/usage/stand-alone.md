# Stand-alone

The `MOIREI\Pricing\Pricing` class may be used stand-alone without an Eloquent Model. However custom naming is only available with the `MOIREI\Pricing\HasPricing` trait.

```php
use MOIREI\Pricing\Pricing;

...

// (Optional) accepts a Pricing instances or array with defaults
$attributes = [];
$pricing1 = new Pricing($attributes);
$pricing1->package(25, 5); // force the instance to be a package type with `unit_amount` and `units` values 25 and 5

// can use the `make` method
$pricing2 = Pricing::make($pricing1);
$pricing2->units(10); // update `units`

dump( $pricing2->toArray() );
// or
dump( (array)$pricing2 );
```