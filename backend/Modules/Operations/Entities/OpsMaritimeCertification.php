<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsMaritimeCertification extends Model
{
    use HasFactory;
    use App\Traits\CreateBusinessUnit;

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
