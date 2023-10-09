<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait CreateBusinessUnit
{
    protected static function bootCreateBusinessUnit()
    {
        static::creating(function ($model) {
            $model->business_unit = Auth::user()->business_unit;
        });
    }
}