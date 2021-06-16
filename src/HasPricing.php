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
    public function price(int|float $measure = 1, string $name = 'default'): float{
        $pricing = $this->pricing?? [];
        if(is_string($pricing)) $pricing = json_decode($pricing);
        return Pricing::make(Arr::get($pricing, $name, []))->price($measure);
    }

    /**
     * Set pricing
     *
     * @param Closure|array|float|int|string name, price-value, pricing data, or name-pricing (key-value) array
     * @param Closure|array pricing data, or name-pricing (key-value) array
     * @return mixed
     */
    public function pricing(Closure|array|float|int|string $arg1, Closure|array|null $arg2 = null){
        if(is_string($arg1)){
            $data = is_null($arg2)? [] :  $arg2;
        }else{
            $data = $arg1;
        }

        $pricings = $this->pricing?? [];
        if(is_string($pricings)) $pricings = json_decode($pricings);

        function resolve($data): Pricing{
            if($data instanceof Closure){
                $pricing = Pricing::make();
                $data($pricing);
            }elseif(is_numeric($data)){
                $pricing = Pricing::make()->standard($data);
            }else{
                $pricing = Pricing::make($data);
            }
            return $pricing;
        }

        if(Arr::has($data, 'model') || Arr::has($data, 'tiers') || Arr::has($data, 'unit_amount') || Arr::has($data, 'units')){
            $name = is_string($arg1)? $arg1 : 'default';
            $pricings[$name] = resolve($data);
        }else{
            foreach($data as $name => $data){
                $pricings[$name] = resolve($data);
            }
        }

        return $this->pricing = $pricings;
    }
}