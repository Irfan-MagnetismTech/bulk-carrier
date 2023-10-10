<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\CreateBusinessUnit;

class OpsMaritimeCertification extends Model
{
    use HasFactory, CreateBusinessUnit;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $fillable = [
        'name',
        'type',
        'validity',
        'authority',
        'business_unit'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Operations\Database\factories\OpsMaritimeCertificationFactory::new();
    }

    
}
