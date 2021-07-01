<?php

namespace MOIREI\Pricing;

use Closure;
use Illuminate\Support\Arr;

trait HasPricing
{
    /**
     * Get price Attribute
     *
     * @var float
     */
    public function getPriceAttribute()
    {
        return $this->price();
    }

    /**
     * Get pricing
     *
     * @param int|float $measure
     * @param string $name
     * @return float
     */
    public function price(int|float $measure = 1, string $name = 'default'): float
    {
        $pricings = $this->pricing ?? [];
        if (is_string($pricings)) $pricings = json_decode($pricings);
        return Pricing::make(Arr::get($pricings, $name, []))->price($measure);
    }

    /**
     * Set pricing
     *
     * @param Closure|array|float|int|string name, price-value, pricing data, or name-pricing (key-value) array
     * @param Closure|array pricing data, or name-pricing (key-value) array
     * @return mixed
     */
    public function pricing(Closure|array|float|int|string $arg1, Closure|array|null $arg2 = null)
    {
        if (is_null($arg2)) {
            $data = $arg1;
        } else {
            $data = $arg2;
        }

        $pricings = $this->pricing ?? [];
        if (is_string($pricings)) $pricings = json_decode($pricings);

        if (is_numeric($data) || Arr::has($data, 'model') || Arr::has($data, 'tiers') || Arr::has($data, 'unit_amount') || Arr::has($data, 'units')) {
            $name = is_string($arg1) ? $arg1 : 'default';
            $pricings[$name] = static::resolvePricing($data);
        } else {
            foreach ($data as $name => $data) {
                $pricings[$name] = static::resolvePricing($data);
            }
        }

        return $this->pricing = $pricings;
    }

    /**
     * Resolve a pricing from data input
     *
     * @param Closure|array|float|int|string name, price-value, pricing data, or name-pricing (key-value) array
     * @return Pricing
     */
    public static function resolvePricing(Closure|array|float|int|string $data): Pricing
    {
        if ($data instanceof Closure) {
            $pricing = Pricing::make();
            $data($pricing);
        } elseif (is_numeric($data)) {
            $pricing = Pricing::make()->standard($data);
        } else {
            $pricing = Pricing::make($data);
        }
        return $pricing;
    }
}
