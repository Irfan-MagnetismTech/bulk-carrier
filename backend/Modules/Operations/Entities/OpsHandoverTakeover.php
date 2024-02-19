<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsHandoverTakeover extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'note_type',
        'effective_date',
        'exchange_rate_bdt',
        'exchange_rate_usd',
        'total_amount_bdt',
        'total_amount_usd',
        'amount',
        'currency',
        'ops_vessel_id',
        'ops_charterer_profile_id',
        'remarks',
        'business_unit'
    ];

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
    

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function opsChartererProfile()
    {
        return $this->belongsTo(OpsChartererProfile::class, 'ops_charterer_profile_id' , 'id');
    }
    
    public function opsBunkers()
    {
        return $this->morphMany(OpsBunker::class, 'bunkerable');
    }
}
