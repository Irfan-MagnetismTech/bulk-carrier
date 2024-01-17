<?php

namespace Modules\Crew\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Operations\Entities\OpsVessel;

class CrwRestHourEntry extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = ['ops_vessel_id', 'record_date', 'business_unit'];

	public function crwRestHourEntryLines(){
		return $this->hasMany(CrwRestHourEntryLine::class);
	}
    
	public function opsVessel()
    {
        return $this->belongsTo(OpsVessel::class);
    }    
}
