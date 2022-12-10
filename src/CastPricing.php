<?php

namespace MOIREI\Pricing;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class CastPricing implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array|null
     */
    public function get($model, $key, $value, $attributes)
    {
        $pricing = json_decode($value, true);
        if (!$pricing) {
            return null;
        }
        return Pricing::make($pricing);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  \MOIREI\Pricing\Pricing  $value
     * @param  array  $attributes
     * @return string|null
     */
    public function set($model, $key, $value, $attributes)
    {
        if (is_numeric($value)) {
            $value = Pricing::make()->standard(floatval($value));
        }
        return [$key => json_encode($value instanceof Pricing ? $value->toArray() : $value)];
    }
}
