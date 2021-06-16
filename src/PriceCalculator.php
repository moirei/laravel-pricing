<?php

namespace MOIREI\Pricing;

use Illuminate\Support\Collection;

class PriceCalculator
{
    public const INFINITY = -1;

    /**
     * Get standard pricing
     *
     * @param int|float $quantity
     * @param int|float $amount
     * @return float
     */
    public static function standard(int|float $quantity, int|float $amount): float{
        return $quantity * $amount;
    }

    /**
     * Get package pricing
     *
     * @param int|float $quantity
     * @param int|float $amount
     * @param int|float $units
     * @return float
     */
    public static function package(int|float $quantity, int|float $amount, int|float $units): float{
        $count = ceil($quantity / $units);
        return $amount * $count;
    }

    /**
     * Get volume pricing
     *
     * @param int|float $quantity
     * @param array $tiers
     * @return float
     */
    public static function volume(int|float $quantity, array $tiers): float{
        $tier = self::getTier($quantity, $tiers);
        return ($quantity * data_get($tier, 'unit_amount', 0)) + data_get($tier, 'flat_amount', 0);
    }

    /**
     * Get graduated pricing
     *
     * @param int|float $quantity
     * @param array $tiers
     * @return float
     */
    public static function graduated(int|float $quantity, array $tiers): float{
        $tiers = self::getTiers($quantity, $tiers);
        $price = 0;
        $last = 0;
        $index = 0;
        $max_tier_value = collect($tiers)->map(fn($tier) => $tier['max'])->max();

        foreach($tiers as $tier){
            $max = data_get($tier, 'max');
            if($index++ == 0){
                $x = $quantity < $max? $quantity : $max;
            }elseif($max == self::INFINITY){
                $x = $quantity - $max_tier_value;
            }else{
                $x = ($quantity < $max? $quantity : $max) - $last;
            }

            $last = $max;
            $price += ($x * data_get($tier, 'unit_amount', 0)) + data_get($tier, 'flat_amount', 0);
        }

        return $price;
    }

    /**
     * Get the tier of a given amount
     *
     * @param int|float $quantity
     * @param \Illuminate\Support\Collection|array $tiers
     * @return array|null
     */
    protected static function getTier(int|float $quantity, Collection|array $tiers): array|null{
        $tiers = self::sortTiers($tiers);
        $tier = null;
        foreach($tiers->toArray() as $t){
            if($quantity <= $t['max']){
                $tier = $t;
                break;
            }
        }
        if(!$tier){
            $tier = $tiers->firstWhere('max', self::INFINITY);
        }

        return $tier;
    }

    /**
     * Get the tiers of a given amount
     *
     * @param int|float $value
     * @param \Illuminate\Support\Collection|array $tiers
     * @return array
     */
    protected static function getTiers(int|float $quantity, Collection|array $tiers): array|null{
        $tiers = self::sortTiers($tiers);
        $t = [];
        $max_tier_value = $tiers->map(fn($tier) => $tier['max'])->max();
        $last = 0;
        foreach($tiers->toArray() as $tier){
            $max = $tier['max'];
            if(
                (($max != self::INFINITY) && ($quantity >= $max)) ||
                (($max != self::INFINITY) && ($quantity <= $max && $quantity >= $last)) ||
                (($max == self::INFINITY) && ($quantity > $max_tier_value))
            ){
                array_push($t, $tier);
            }
            $last = $max;
        }

        return $t;
    }

    /**
     * Sort the tiers according to their limit
     * Transform numeric values
     *
     * @param \Illuminate\Support\Collection|array $tiers
     * @return \Illuminate\Support\Collection
     */
    protected static function sortTiers(Collection|array $tiers): Collection{
        $inf = [self::INFINITY, 'infinity', 'inf'];
        return collect($tiers)->sort(function($a, $b) use($inf){
            if(in_array($a['max'], $inf)) return 1;
            if(in_array($b['max'], $inf)) return -1;
            return (float)$a['max'] - (float)$b['max'];
        })
        ->map(function($tier){
            $tier['unit_amount'] = (float)data_get($tier, 'unit_amount', 0);
            $tier['flat_amount'] = (float)data_get($tier, 'flat_amount', 0);
            $tier['max'] = (float)data_get($tier, 'max')?: self::INFINITY;
            return $tier;
        })
        ->values();
    }
}

