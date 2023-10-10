<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\CreateBusinessUnit;

class OpsPort extends Model
{
    use HasFactory,CreateBusinessUnit;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['code', 'name','business_unit'];
    
    protected static function newFactory()
    {
        return \Modules\Operations\Database\factories\OpsPortFactory::new();
    }
}
