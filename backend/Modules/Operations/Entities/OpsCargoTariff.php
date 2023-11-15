<?php

namespace Modules\Operations\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsCargoTariff extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'tariff_name',
        'ops_vessel_id',
        'loading_point',
        'unloading_point',
        'ops_cargo_type_id',
        'currency',
        'status',
        'business_unit',
    ];
    
    public function opsCargoType()
    {
        return $this->belongsTo(OpsCargoType::class, 'ops_cargo_type_id' , 'id');
    }
    
    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function opsCargoTariffLines()
    {
        return $this->hasMany(OpsCargoTariffLine::class, 'ops_cargo_tariff_id', 'id');
    }
}
