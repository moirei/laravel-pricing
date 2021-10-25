# Eloquent: CastPricing Cast

Cast a model attribute to a single Pricing instance.

```php
use Illuminate\Database\Eloquent\Model;
use MOIREI\Pricing\CastPricing;

class Product extends Model
{
    ...

    /**
     * The attributes that should be casted to media types.
     *
     * @var array
     */
    protected $casts = [
        'pricing' => CastPricing::class,
    ];

    ...
}
```

```php
$product = new Product();

expect($product->pricing)->toBeInstanceOf(Pricing::class);
```