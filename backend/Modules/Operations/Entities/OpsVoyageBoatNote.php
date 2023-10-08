<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsVoyageBoatNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_voyage_id',
        'ops_vessel_id',
        'type',
        'vessel_draft',
        'water_density'
    ];

    public function ops_voyage()
    {
        return $this->belongsTo(OpsVoyage::class, 'ops_voyage_id' , 'id');
    }

    public function ops_vessel()
    {
        return $this->belongsTo(OpsVessel::class, 'ops_vessel_id' , 'id');
    }

    public function ops_voyage_boat_note_lines()
    {
        return $this->hasMany(OpsVoyageBoatNoteLine::class, 'ops_voyage_boat_note_id', 'id');
    }
}
