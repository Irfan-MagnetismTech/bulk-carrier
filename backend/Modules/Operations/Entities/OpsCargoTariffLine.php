<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsCargoTariffLine extends Model
{
    use HasFactory, DeletableModel;

    protected $fillable = [
        'ops_cargo_tariff_id',
        'particular',
        'unit',
        'jan',
        'feb',
        'mar',
        'apr',
        'may',
        'jun',
        'jul',
        'aug',
        'sep',
        'oct',
        'nov',
        'dec',
    ];

    public function opsCargoTariff()
    {
        return $this->belongsTo(OpsCargoTariff::class, 'ops_cargo_tariff_id' , 'id');
    }

    /**
    * @var array
    */
    protected $skipForDeletionCheck = ['relationName'];
    
    /**
    * @var array
    */
    protected $features = [
    // 'relationName'           => 'Menu',
    ];
    
}
