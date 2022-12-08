# Utility Methods

## Basic arithmetic

With the `add`, `subtract`, `multiply` and `divide` methods, you can simply modify a pricing object based on a given value.

```php
$pricing = Pricing::make([
  'model' => 'standard',
  'unit_amount' => 25, // $25
]);

$pricing->price(4); // 100.0

$pricing->add(5); // add $5

$pricing->price(4); // 120.0
```

Modifying a pricing in this way can be done regardless of the pricing model.

> Note that these methods don't modify flat fees on tiered pricings.

## Pricing Summaries

Sometimes it's helpful to present to application users the details of a pricing configuration. Especially useful for helping app users understand tiered pricing.

```php
$pricing = new Pricing(...);

$summary = $pricing->summary();
```

### Arguments

| Name                | Description                                                                  | Type     | Default  |
| ------------------- | ---------------------------------------------------------------------------- | -------- | -------- |
| `$quantity`         | Give a pricing quantity to summarise with                                    | `float`  | `null`   |
| `$qualifier`        | A qualifier to use against unit amounts. Typical a currency symol, e.g. `$`. | `string` | `""`     |
| `$unit`             | A unit name for pricing quantity. E.g. `kg`, `g`.                            | `string` | `"unit"` |
| `$prependQualifier` | Indicate whether to prepend the amount qualifier.                            | `bool`   | `true`   |
| `$prependUnit`      | Indicate whether to prepend the quantity unit.                               | `bool`   | `false`  |
| `$plurableUnit`     | Indicate if the unit can be pluralised in the case of more than 1 quantity.  | `bool`   | `true`   |

### Examples

```php
dump(
  $pricing->summary(qualifier: '$')
);
```

An example with a `standard` pricing:

```shell
array:1 [
  0 => "$25 per unit"
]
```

An example with a `package` pricing:

```shell
array:1 [
  0 => "$25 for every 5 units"
]
```

An example with a `volume` pricing:

```shell
array:5 [
  0 => "The first 5 units @ $5 per unit"
  1 => "From 5 to 10 units @ $4 per unit"
  2 => "From 10 to 15 units @ $3 per unit"
  3 => "From 15 to 20 units @ $2 per unit"
  4 => "From 20 units and above @ $1 per unit"
]
```

An example with a `volume` pricing:

```shell
array:4 [
  0 => "First 5 units x $5 = $25"
  1 => "Next 5 units x $4 = $20"
  2 => "Next 5 units x $3 = $15"
  3 => "Next 1 x $2 = $2"
]
```

## The calculator

The `PriceCalculator` class contains all the underlying methods used for all calculations.
