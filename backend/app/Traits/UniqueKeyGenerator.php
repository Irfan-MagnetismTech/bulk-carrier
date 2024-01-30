<?php

namespace App\Traits;

use Services\UniqueId;

trait UniqueKeyGenerator
{
    protected static function bootUniqueKeyGenerator()
    {
        static::creating(function ($model) {
            $model->ref_no = UniqueId::generate($model, $model->refKeyPrefix);
        });
    }
}
