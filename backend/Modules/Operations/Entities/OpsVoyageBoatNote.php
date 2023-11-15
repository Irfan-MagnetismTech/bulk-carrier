<?php

namespace Modules\Operations\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsVoyageBoatNote extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'ops_voyage_id',
        'ops_vessel_id',
        'type',
        'vessel_draft',
        'water_density',
        'business_unit'
    ];

    public function opsVoyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }

    public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function opsVoyageBoatNoteLines()
    {
        return $this->hasMany(OpsVoyageBoatNoteLine::class, 'ops_voyage_boat_note_id', 'id');
    }
}
