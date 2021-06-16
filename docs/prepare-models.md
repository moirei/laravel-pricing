# Prepare Models

Use the pricing traint and cast;

**NOTE:** Casting is optional

```php
use Illuminate\Database\Eloquent\Model;
use MOIREI\Pricing\HasPricing;
use MOIREI\Pricing\CastPricing;

class Product extends Model
{
    use HasPricing;

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

In your database
```php
...
public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        ...
        $table->json('pricing');
        // or
        $table->text('pricing');
    });
    ...
}
```