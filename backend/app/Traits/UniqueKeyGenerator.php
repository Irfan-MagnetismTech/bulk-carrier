<?php

namespace App\Traits;

use Services\UniqueId;

trait UniqueKeyGenerator
{
    /**
     * On creating a model, put a unique key in ref_no column.
     * 
     * @return void
     */
    protected static function bootUniqueKeyGenerator(): void
    {
        static::creating(function ($model) {
            $model->ref_no = UniqueId::generate($model, $model->refKeyPrefix);
        });
    }
}
