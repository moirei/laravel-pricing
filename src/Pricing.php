<?php

namespace MOIREI\Pricing;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Illuminate\Contracts\Support\Arrayable;

class Pricing implements Arrayable
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
     * @var float|int
     */
    protected float|int $unit_amount;

    /**
     * Unit range for package pricing
     *
     * @var float|int
     */
    protected float|int $units;

    /**
     * Miscellaneous data
     *
     * @var array
     */
    protected array $data;

    /**
     * Construct a new instance
     *
     * @param Pricing|array $attributes
     */
    public function __construct(Pricing | array $attributes = [])
    {
        if($attributes instanceof Pricing){
            $attributes = $attributes->toArray();
        }
        $this->model(data_get($attributes, 'model', self::MODEL_STANDARD));
        $this->tiers(data_get($attributes, 'tiers', []));
        $this->data(data_get($attributes, 'data', []));
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
    public function model(string $model = null){
        if(is_null($model)) return $this->model;

        $model = Str::lower($model);
        if(!in_array($model, [
            self::MODEL_STANDARD,
            self::MODEL_PACKAGE,
            self::MODEL_VOLUME,
            self::MODEL_GRADUATED,
        ],)){
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
    public function tiers(array $tiers = null){
        if(is_null($tiers)) return $this->tiers;

        $this->tiers = $tiers;
        return $this;
    }

    /**
     * Add a tier
     *
     * @param array $tiers
     * @return \MOIREI\Pricing\Pricing
     */
    public function tier(array $tier){
        array_push($this->tiers, $tier);
        return $this;
    }

    /**
     * Set the units amount value
     *
     * @param float $unit_amount
     * @return \MOIREI\Pricing\Pricing
     */
    public function unitAmount(float $unit_amount){
        if(is_null($unit_amount)) return $this->unit_amount;

        $this->unit_amount = $unit_amount;
        return $this;
    }

    /**
     * Set the units value
     *
     * @param float $units
     * @return \MOIREI\Pricing\Pricing
     */
    public function units(float $units){
        if(is_null($units)) return $this->units;

        $this->units = $units;
        return $this;
    }

    /**
     * Set standard pricing
     *
     * @param float|int $unit_amount
     * @return \MOIREI\Pricing\Pricing
     */
    public function standard(float|int $unit_amount){
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
    public function package(float|int $unit_amount, float|int $units){
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
    public function volume(array $tiers){
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
    public function graduated(array $tiers){
        $this->model(self::MODEL_GRADUATED);
        $this->tiers = $tiers;
        return $this;
    }

    /**
     * Get the pricing value
     *
     * @param int|float $amount
     * @param string $channel
     * @return float
     */
    public function price(int|float $amount = 1): float{
        $model = Str::lower($this->model);

        if($model === self::MODEL_STANDARD){
            return PriceCalculator::standard($amount, $this->unit_amount);
        }elseif($model === self::MODEL_PACKAGE){
            return PriceCalculator::package($amount, $this->unit_amount, $this->units);
        }

        return PriceCalculator::$model($amount, $this->tiers);
    }

    /**
     * Get or set tiers
     *
     * @param Illuminate\Support\Collection|array|null $data
     * @return \MOIREI\Pricing\Pricing|Collection
     */
    public function data(Collection|array $data = null){
        if(is_null($data)) return collect($this->data);

        $this->data = collect($data)->toArray();
        return $this;
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
     * Get the result array data.
     *
     * @return array
     */
    public function toArray(): array{
        return [
            'model' => $this->model,
            'tiers' => $this->tiers,
            'unit_amount' => $this->unit_amount,
            'units' => $this->units,
            'data' => $this->data,
        ];
    }
}
