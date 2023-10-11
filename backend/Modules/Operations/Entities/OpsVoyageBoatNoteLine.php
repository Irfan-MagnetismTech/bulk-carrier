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
        'date',
        'discharge_date',
        'attachment',
        'business_unit'
    ];

    public function ops_voyage_boat_note()
    {
        return $this->belongsTo(OpsVoyageBoatNote::class, 'ops_voyage_boat_note_id' , 'id');
    }
}
