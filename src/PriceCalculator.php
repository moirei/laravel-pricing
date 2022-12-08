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
    public static function standard(int|float $quantity, int|float $amount): float
    {
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
    public static function package(int|float $quantity, int|float $amount, int|float $units): float
    {
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
    public static function volume(int|float $quantity, array $tiers): float
    {
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
    public static function graduated(int|float $quantity, array $tiers): float
    {
        $tiers = self::getTiers($quantity, $tiers);
        $price = 0;
        $last = 0;
        $index = 0;
        $max_tier_value = collect($tiers)->map(fn ($tier) => $tier['max'])->max();

        foreach ($tiers as $tier) {
            $max = data_get($tier, 'max');
            if ($is_infinite = self::isInfinite($max)) $max = self::INFINITY;

            if ($index++ == 0) {
                $x = $quantity < $max ? $quantity : $max;
            } elseif ($is_infinite) {
                $x = $quantity - $max_tier_value;
            } else {
                $x = ($quantity < $max ? $quantity : $max) - $last;
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
    public static function getTier(int|float $quantity, Collection|array $tiers): array|null
    {
        $tiers = self::sortTiers($tiers);
        /** @var array|null */
        $tier = null;
        foreach ($tiers->toArray() as $t) {
            if ($quantity <= (self::isInfinite($t['max']) ? self::INFINITY : $t['max'])) {
                $tier = $t;
                break;
            }
        }
        if (!$tier) {
            $tier = $tiers->first(fn ($tier) => self::isInfinite($tier['max']));
        }

        return $tier;
    }

    /**
     * Get the tiers of a given amount
     *
     * @param int|float $quantity
     * @param \Illuminate\Support\Collection|array $tiers
     * @return array
     */
    public static function getTiers(int|float $quantity, Collection|array $tiers): array|null
    {
        $tiers = self::sortTiers($tiers);
        $t = [];
        $max_tier_value = $tiers->map(fn ($tier) => self::isInfinite($tier['max']) ? self::INFINITY : $tier['max'])->max();
        $last = 0;
        foreach ($tiers->toArray() as $tier) {
            $max = self::isInfinite($tier['max']) ? self::INFINITY : $tier['max'];
            if (
                ((!self::isInfinite($max)) && ($quantity >= $max)) ||
                ((!self::isInfinite($max)) && ($quantity <= $max && $quantity >= $last)) ||
                ((self::isInfinite($max)) && ($quantity > $max_tier_value))
            ) {
                array_push($t, $tier);
            }
            $last = $max;
        }

        return $t;
    }

    /**
     * Get tier max
     *
     * @return float
     */
    public static function tierMax(array $tiers)
    {
        return max(array_map(fn ($tier) => static::isInfinite($tier['max']) ? 0 : $tier['max'], $tiers));
    }

    /**
     * Sort the tiers according to their limit
     * Transform numeric values
     *
     * @param \Illuminate\Support\Collection|array $tiers
     * @return \Illuminate\Support\Collection
     */
    protected static function sortTiers(Collection|array $tiers): Collection
    {
        return collect($tiers)->sort(function ($a, $b) {
            if (self::isInfinite($a['max'])) return 1;
            if (self::isInfinite($b['max'])) return -1;
            return (float)$a['max'] - (float)$b['max'];
        })
            ->map(function ($tier) {
                $tier['unit_amount'] = (float)data_get($tier, 'unit_amount', 0);
                $tier['flat_amount'] = (float)data_get($tier, 'flat_amount', 0);
                $tier['max'] = (float)data_get($tier, 'max') ?: self::INFINITY;
                return $tier;
            })
            ->values();
    }

    /**
     * Check if the given value is Infinite type
     *
     * @param {any} value
     * @return bool
     */
    protected static function isInfinite($value): bool
    {
        $inf = [self::INFINITY, "infinity", "inf"];
        return in_array($value, $inf);
    }
}
