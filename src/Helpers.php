<?php

namespace MOIREI\Pricing;

use Illuminate\Support\Str;


class Helpers
{
    public static function appendUnit(float $a, $b, $flip = false, $plurable = false, bool $spacer = false)
    {
        if ($plurable && $a > 1) {
            $b = Str::plural($b);
        }
        $separator = $spacer ? ' ' : '';
        return trim($flip ? "$b$separator$a" : "$a$separator$b");
    }

    public static function join()
    {
        $args = array_filter(func_get_args(), function ($v) {
            return !is_null($v);
        });
        return implode(' ', $args);
    }
}
