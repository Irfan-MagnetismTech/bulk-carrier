<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsHandoverTakeover extends Model
{
    use HasFactory;

    protected $fillable = [
        'note_type',
        'effective_date',
        'exchange_rate',
        'currency',
        'ops_vessel_id',
        'ops_charterer_profile_id',
        'remarks',
        'business_unit'
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
