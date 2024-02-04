<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsMaritimeCertification extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

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
    
}
