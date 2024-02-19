<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsVoyageBoatNoteLine extends Model
{
    use HasFactory, DeletableModel;

    protected $fillable = [
        'ops_voyage_boat_note_id',
        'voyage_sector_id',
        'voyage_note_type',
        'loading_point',
        'unloading_point',
        'date',
        'discharge_date',
        'quantity',
        'attachment',
        'business_unit'
    ];

    /**
    * @var array
    */
    protected $skipForDeletionCheck = [''];
    
    /**
    * @var array
    */
    protected $features = [
    // 'relationName'           => 'Menu',
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
