<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsVoyageBoatNoteLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_voyage_boat_note_id',
        'voyage_note_type',
        'loading_point',
        'unloading_point',
        'date',
        'discharge_date',
        'quantity',
        'attachment',
        'business_unit'
    ];

    public function opsVoyageBoatNote()
    {
        return $this->belongsTo(OpsVoyageBoatNote::class, 'ops_voyage_boat_note_id' , 'id');
    }
    public function loadingPoint() {
        return $this->belongsTo(OpsPort::class, 'loading_point', 'code');
    }

    public function unloadingPoint() {
        return $this->belongsTo(OpsPort::class, 'unloading_point', 'code');
    }
}
