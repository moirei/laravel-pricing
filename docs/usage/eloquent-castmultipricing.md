# Eloquent: CastMultiPricing Cast

Cast a model attribute to a multiple Pricing instances.

```php
use Illuminate\Database\Eloquent\Model;
use MOIREI\Pricing\CastMultiPricing;

class Product extends Model
{
    ...

    /**
     * The attributes that should be casted to media types.
     *
     * @var array
     */
    protected $casts = [
        'pricing' => CastMultiPricing::class,
    ];

    ...
}
```

The pricing attribute should cast to a collection of Pricing instances.
```php
$product = new Product();

expect($product->pricing)->toBeCollection();
expect($product->pricing->isEmpty())->toBeTrue();
```

Accessing a field name should return or create a new instance
```php
expect($product->pricing->aud)->toBeInstanceOf(Pricing::class);
expect($product->pricing->isEmpty())->toBeFalse();

$aud = $product->pricing->aud;
$tiers = [...];
$aud->graduated($tiers);
expect($aud->price(1))->toBeTruthy();
```
