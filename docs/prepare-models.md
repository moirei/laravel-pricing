# Prepare Models

There are several options for working with Eloquent.

1. With the `CastPricing`: cast a model attribute to a single Pricing instance.
1. With the `CastMultiPricing`: cast a model attribute to a `Collection` of Pricing instances. Allows dynamic creation.
1. With the `HasPricing` trait: provides a utility methods for working with named pricing instances.

```php
use Illuminate\Database\Eloquent\Model;
use MOIREI\Pricing\HasPricing;
use MOIREI\Pricing\CastPricing;
use MOIREI\Pricing\CastMultiPricing;

class Product extends Model
{
    use HasPricing; // option 3

    /**
     * The attributes that should be casted to media types.
     *
     * @var array
     */
    protected $casts = [
        'pricing' => CastPricing::class, // option 1
        'international_pricing' => CastMultiPricing::class, // option 2
    ];

    ...
}
```

In your database
```php
...
public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        ...
        $table->json('pricing');
        // $table->json('international_pricing');
        // or
        $table->text('pricing');
    });
    ...
}
```