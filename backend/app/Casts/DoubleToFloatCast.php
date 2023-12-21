<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class DoubleToFloatCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return floatval($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}

?>