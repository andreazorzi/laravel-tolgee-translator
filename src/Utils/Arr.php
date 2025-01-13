<?php

namespace LaravelTolgeeTranslator\Utils;

use Illuminate\Support\Str;

class Arr
{
    public static function set_value_by_dot_notation(&$array, $notation, $value) {
        $keys = explode('.', $notation);
        $current = &$array;
        
        foreach ($keys as $key) {
            if (!isset($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }
        
        $current = $value;
    }
}