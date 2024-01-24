<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Traits\StockLedger;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVesselBunker extends Model
{
    use HasFactory, GlobalSearchTrait, StockLedger, DeletableModel;

    protected $fillable = [
        'ops_vessel_id',
        'ops_voyage_id',
        'currency',
        'exchange_rate_bdt',
        'exchange_rate_usd',
        'total_amount_bdt',
        'total_amount_usd',
        'type',
        'usage_type',
        'date',
        'from_date',
        'till_date',
        'business_unit'
    ];

    public function opsBunkers()
    {
        return $this->morphMany(OpsBunker::class, 'bunkerable');
    }

    // public function opsBunkers()
    // {
    //     return $this->morphMany(OpsBunker::class, 'bunkerable');
    // }

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class);
    }

    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class);
    }
}
