<?php

namespace MOIREI\Pricing;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;

class CastMultiPricing implements CastsAttributes
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
        $pricing = json_decode($value, true) ?? [];

        return (new class($pricing) extends Collection
        {
            /**
             * Dynamically access collection proxies.
             *
             * @param  string  $key
             * @return mixed
             *
             * @throws \Exception
             */
            public function __get($key)
            {
                if (!$this->has($key)) {
                    $this->put($key, new Pricing);
                }

                return $this->get($key);
            }
        })
            ->map(fn ($p) => Pricing::make($p));
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  \Illuminate\Support\Collection  $value
     * @param  array  $attributes
     * @return string|null
     */
    public function set($model, $key, $value, $attributes)
    {
        return [$key => json_encode($value->toArray())];
    }
}
