<?php

namespace MOIREI\Pricing;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Illuminate\Contracts\Support\Arrayable;
use ArrayAccess;
use RuntimeException;

class Pricing implements Arrayable, ArrayAccess
{
    public const MODEL_STANDARD = 'standard';
    public const MODEL_PACKAGE = 'package';
    public const MODEL_VOLUME = 'volume';
    public const MODEL_GRADUATED = 'graduated';

    /**
     * The pricing model
     *
     * @var string
     */
    protected string $model;

    /**
     * The pricing tier factors
     *
     * @var array
     */
    protected array $tiers;

    /**
     * Unit amount for standard and package pricing
     *
     * @var float|int|null
     */
    protected float|int|null $unit_amount = null;

    /**
     * Unit range for package pricing
     *
     * @var float|int|null
     */
    protected float|int|null $units = null;

    /**
     * Miscellaneous data
     *
     * @var array
     */
    protected array|null $data = null;

    /**
     * Construct a new instance
     *
     * @param Pricing|array $attributes
     */
    public function __construct(Pricing | array $attributes = [])
    {
        if ($attributes instanceof Pricing) {
            $attributes = $attributes->toArray();
        }
        $this->model(data_get($attributes, 'model', self::MODEL_STANDARD));
        $this->tiers(data_get($attributes, 'tiers', []));
        $this->data(data_get($attributes, 'data'));
        $this->unitAmount(data_get($attributes, 'unit_amount', 0));
        $this->units(data_get($attributes, 'units', 1));
    }

    /**
     * Make a new instance
     *
     * @param Pricing|array $attributes
     * @return Pricing
     */
    public static function make(Pricing | array $attributes = [])
    {
        return new static($attributes);
    }

    /**
     * Get or set model
     *
     * @param string|null $model
     * @return \MOIREI\Pricing\Pricing|string
     */
    public function model(string $model = null)
    {
        if (is_null($model)) return $this->model;

        $model = Str::lower($model);
        if (!in_array($model, [
            self::MODEL_STANDARD,
            self::MODEL_PACKAGE,
            self::MODEL_VOLUME,
            self::MODEL_GRADUATED,
        ],)) {
            throw new InvalidArgumentException("Invalid pricing model '$model'.");
        }

        $this->model = $model;
        return $this;
    }

    /**
     * Get or set tiers
     *
     * @param array|null $tiers
     * @return \MOIREI\Pricing\Pricing|array
     */
    public function tiers(array $tiers = null)
    {
        if (is_null($tiers)) return $this->tiers;

        $this->tiers = $tiers;
        return $this;
    }

    /**
     * Add a tier
     *
     * @param array $tiers
     * @return \MOIREI\Pricing\Pricing
     */
    public function tier(array $tier)
    {
        array_push($this->tiers, $tier);
        return $this;
    }

    /**
     * Set the units amount value
     *
     * @param float $unit_amount
     * @return \MOIREI\Pricing\Pricing
     */
    public function unitAmount(float $unit_amount = null)
    {
        if (is_null($unit_amount)) return $this->unit_amount;

        $this->unit_amount = $unit_amount;
        return $this;
    }

    /**
     * Set the units value
     *
     * @param float $units
     * @return \MOIREI\Pricing\Pricing
     */
    public function units(float $units = null)
    {
        if (is_null($units)) return $this->units;

        $this->units = $units;
        return $this;
    }

    /**
     * Set standard pricing
     *
     * @param float|int $unit_amount
     * @return \MOIREI\Pricing\Pricing
     */
    public function standard(float|int $unit_amount)
    {
        $this->model(self::MODEL_STANDARD);
        $this->unit_amount = $unit_amount;
        return $this;
    }

    /**
     * Set package pricing
     *
     * @param float|int $unit_amount
     * @param float|int $units
     * @return \MOIREI\Pricing\Pricing
     */
    public function package(float|int $unit_amount, float|int $units)
    {
        $this->model(self::MODEL_PACKAGE);
        $this->unit_amount = $unit_amount;
        $this->units = $units;
        return $this;
    }

    /**
     * Set volume pricing
     *
     * @param array $tiers
     * @return \MOIREI\Pricing\Pricing
     */
    public function volume(array $tiers)
    {
        $this->model(self::MODEL_VOLUME);
        $this->tiers = $tiers;
        return $this;
    }

    /**
     * Set graduated pricing
     *
     * @param array $tiers
     * @return \MOIREI\Pricing\Pricing
     */
    public function graduated(array $tiers)
    {
        $this->model(self::MODEL_GRADUATED);
        $this->tiers = $tiers;
        return $this;
    }

    /**
     * Get the pricing value
     *
     * @param int|float $amount
     * @param int    $precision
     * @return float
     */
    public function price(int|float $amount = 1, int $precision = 4): float
    {
        $model = Str::lower($this->model);

        if ($model === self::MODEL_STANDARD) {
            $value = PriceCalculator::standard($amount, $this->unit_amount);
        } elseif ($model === self::MODEL_PACKAGE) {
            $value = PriceCalculator::package($amount, $this->unit_amount, $this->units);
        } else {
            $value = PriceCalculator::$model($amount, $this->tiers);
        }

        return round($value, $precision);
    }

    /**
     * Add value to the pricing.
     * Does not apply to flat_amount.
     *
     * @param float $value
     * @return self
     */
    public function add(float $value)
    {
        if (is_numeric($this->unit_amount)) {
            $this->unit_amount += $value;
        }
        $this->tiers = array_map(function ($tier) use ($value) {
            if (is_numeric($tier['unit_amount'])) {
                $tier['unit_amount'] += $value;
            }
            return $tier;
        }, $this->tiers);

        return $this;
    }

    /**
     * Subtract value from the pricing. Calls add method.
     *
     * @param float $value
     * @return self
     */
    public function subtract(float $value)
    {
        return $this->add($value * -1);
    }

    /**
     * Multiply value on the pricing.
     * Does not apply to flat_amount.
     *
     * @param float $value
     * @return self
     */
    public function multiply(float $value)
    {
        if (is_numeric($this->unit_amount)) {
            $this->unit_amount *= $value;
        }
        $this->tiers = array_map(function ($tier) use ($value) {
            if (is_numeric($tier['unit_amount'])) {
                $tier['unit_amount'] *= $value;
            }
            return $tier;
        }, $this->tiers);

        return $this;
    }

    /**
     * Divide pricing by value. Calls add multiply.
     *
     * @param float $value
     * @return self
     * @throws RuntimeException
     */
    public function divide(float $value)
    {
        if ($value == 0) {
            throw new RuntimeException("Cannot devide by 0");
        }
        return $this->multiply(1 / $value);
    }

    /**
     * Get or set miscellaneous data
     *
     * @param Illuminate\Support\Collection|array|null $key
     * @param mix $value
     * @return \MOIREI\Pricing\Pricing|Collection
     */
    public function data(Collection|array|string $key = null, $value = null)
    {
        if (!is_null($value)) {
            if (empty($this->data)) {
                $this->data = [];
            }
            Arr::set($this->data, $key, ($value instanceof Collection) ? $value->toArray() : $value);
            return $value;
        }

        if (is_null($key)) return collect($this->data);
        if (is_string($key)) return Arr::get($this->data, $key);

        $this->data = collect($key)->toArray();

        return $this;
    }

    /**
     * Check is pricing is standard type
     *
     * @return bool
     */
    public function isStandard(): bool
    {
        return $this->model === self::MODEL_STANDARD;
    }

    /**
     * Check is pricing is package type
     *
     * @return bool
     */
    public function isPackage(): bool
    {
        return $this->model === self::MODEL_PACKAGE;
    }

    /**
     * Check is pricing is volume type
     *
     * @return bool
     */
    public function isVolume(): bool
    {
        return $this->model === self::MODEL_VOLUME;
    }

    /**
     * Check is pricing is graduated type
     *
     * @return bool
     */
    public function isGraduated(): bool
    {
        return $this->model === self::MODEL_GRADUATED;
    }

    /**
     * Check is pricing is tiered
     *
     * @return bool
     */
    public function isTiered(): bool
    {
        return $this->isVolume() || $this->isGraduated();
    }

    /**
     * Summarise pricing
     *
     * @param float $quantity
     * @param string $qualifier
     * @param string $unit
     * @param bool $prependQualifier
     * @param bool $prependUnit
     * @param bool $plurableUnit
     * @return array
     */
    public function summary(
        float $quantity = null,
        string $qualifier = '',
        string $unit = 'unit',
        bool $prependQualifier  = true,
        bool $prependUnit = false,
        bool $plurableUnit = true,
    ): array {
        $unit_amount = $this->unit_amount;
        $summaries = [];

        if ($this->isStandard()) {
            $summaries[] = Helpers::join(
                Helpers::appendUnit($unit_amount, $qualifier, $prependQualifier),
                'per',
                $unit,
            );
        } elseif ($this->isPackage()) {
            if ($this->units > 1) $unit = Str::plural($unit);
            $summaries[] = Helpers::join(
                Helpers::appendUnit($unit_amount, $qualifier, $prependQualifier),
                'for every',
                Helpers::appendUnit($this->units, $unit, $prependUnit, $plurableUnit, true),
            );
        } elseif ($this->isVolume()) {
            if ($quantity) {
                $tier = PriceCalculator::getTier($quantity, $this->tiers);
                $unit_amount = floatval($tier['unit_amount']);
                $flat_amount = empty($tier['flat_amount']) ? '' : $tier['flat_amount'];
                $total = ($unit_amount * $quantity) + ($flat_amount ? $flat_amount : 0);
                $summaries[] = Helpers::join(
                    Helpers::appendUnit($quantity, $unit, $prependUnit, $plurableUnit, true),
                    'x',
                    Helpers::appendUnit($unit_amount, $qualifier, $prependQualifier),
                    $flat_amount ? '+' : null,
                    $flat_amount ? Helpers::appendUnit($flat_amount, $qualifier, $prependQualifier) : null,
                    '=',
                    Helpers::appendUnit($total, $qualifier, $prependQualifier),
                );
            } else {
                $length = count($this->tiers);
                foreach ($this->tiers as $i => $tier) {
                    $max = floatval($tier['max']);
                    $unit_amount = floatval($tier['unit_amount']);
                    $flat_amount = empty($tier['flat_amount']) ? '' : $tier['flat_amount'];
                    if ($i == 0) {
                        $total = ($unit_amount * $max) + ($flat_amount ? $flat_amount : 0);
                        $summaries[] = Helpers::join(
                            'The first',
                            Helpers::appendUnit($max, $unit, $prependUnit, $plurableUnit, true),
                            '@',
                            Helpers::appendUnit($unit_amount, $qualifier, $prependQualifier),
                            'per',
                            $unit,
                            $flat_amount ? '+' : null,
                            $flat_amount ? Helpers::appendUnit($flat_amount, $qualifier, $prependQualifier) : null,
                        );
                    } else if ($i == $length - 1) { // is last
                        $total = ($unit_amount * $max) + ($flat_amount ? $flat_amount : 0);
                        $summaries[] = Helpers::join(
                            'From',
                            Helpers::appendUnit($this->tiers[$i - 1]['max'], $unit, $prependUnit, $plurableUnit, true),
                            'and above',
                            '@',
                            Helpers::appendUnit($unit_amount, $qualifier, $prependQualifier),
                            'per',
                            $unit,
                            $flat_amount ? '+' : null,
                            $flat_amount ? Helpers::appendUnit($flat_amount, $qualifier, $prependQualifier) : null,
                        );
                    } else {
                        $total = ($unit_amount * $max) + ($flat_amount ? $flat_amount : 0);
                        $summaries[] = Helpers::join(
                            'From',
                            floatval($this->tiers[$i - 1]['max']),
                            'to',
                            Helpers::appendUnit($max, $unit, $prependUnit, $plurableUnit, true),
                            '@',
                            Helpers::appendUnit($unit_amount, $qualifier, $prependQualifier),
                            'per',
                            $unit,
                            $flat_amount ? '+' : null,
                            $flat_amount ? Helpers::appendUnit($flat_amount, $qualifier, $prependQualifier) : null,
                        );
                    }
                }
            }
        } elseif ($this->isGraduated()) {
            if (!$quantity) {
                $quantity = PriceCalculator::tierMax($this->tiers);
            }
            $tiers = PriceCalculator::getTiers($quantity, $this->tiers);
            $length = count($tiers);

            foreach ($tiers as $i => $tier) {
                $max = $tier['max'];
                $unit_amount = $tier['unit_amount'];
                $flat_amount = empty($tier['flat_amount']) ? '' : $tier['flat_amount'];

                if ($i == 0) {
                    $total = ($unit_amount * $max) + ($flat_amount ? $flat_amount : 0);
                    $total = number_format($total, 2);

                    $summaries[] = Helpers::join(
                        'First',
                        Helpers::appendUnit($max, $unit, $prependUnit, $plurableUnit, true),
                        'x',
                        Helpers::appendUnit($unit_amount, $qualifier, $prependQualifier),
                        $flat_amount ? '+' : null,
                        $flat_amount ? Helpers::appendUnit($flat_amount, $qualifier, $prependQualifier) : null,
                        '=',
                        Helpers::appendUnit($total, $qualifier, $prependQualifier),
                    );
                } elseif ($i == $length - 1) {
                    $total = $unit_amount + ($flat_amount ? $flat_amount : 0);
                    $total = number_format($total, 2);
                    $summaries[] = Helpers::join(
                        'Next 1 x',
                        Helpers::appendUnit($unit_amount, $qualifier, $prependQualifier, true),
                        $flat_amount ? '+' : null,
                        $flat_amount ? Helpers::appendUnit($flat_amount, $qualifier, $prependQualifier) : null,
                        '=',
                        Helpers::appendUnit($total, $qualifier, $prependQualifier),
                    );
                } else {
                    $quantity = $max - floatval($tiers[$i - 1]['max']);
                    $total = ($unit_amount * $quantity) + ($flat_amount ? $flat_amount : 0);
                    $total = number_format($total, 2);
                    $summaries[] = Helpers::join(
                        'Next',
                        Helpers::appendUnit($quantity, $unit, $prependUnit, $plurableUnit, true),
                        'x',
                        Helpers::appendUnit($unit_amount, $qualifier, $prependQualifier),
                        $flat_amount ? '+' : null,
                        $flat_amount ? Helpers::appendUnit($flat_amount, $qualifier, $prependQualifier) : null,
                        '=',
                        Helpers::appendUnit($total, $qualifier, $prependQualifier),
                    );
                }
            }
        }

        return $summaries;
    }

    /**
     * Access any existing data
     *
     * @return object
     */
    public function __get($key)
    {
        return Arr::get($this->data, $key);
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return in_array($offset, [
            'model', 'tiers',
            'unit_amount', 'units',
            'data',
        ]);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        //
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        //
    }

    /**
     * Get the result array data.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'model' => $this->model,
            'tiers' => $this->tiers,
            'unit_amount' => $this->unit_amount,
            'units' => $this->units,
            'data' => $this->data,
        ];
    }
}
