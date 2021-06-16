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
        $pricing = json_decode($value);
        return collect($pricing)->map(fn($p) => Pricing::make($p));
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
        return json_encode($value);
    }
}
