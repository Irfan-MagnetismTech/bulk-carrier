<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsVoyageSector extends Model
{
    use HasFactory;

    protected $fillable = [
        'ops_voyage_id',
        'ops_cargo_tariff_id',
        'loading_point',
        'unloading_point',
        'rate',
        'initial_survey_qty',
        'approx_amount',
        'discount',
        'discount_amount',
        'approx_amount_after_disscount',
        'final_survey_qty',
        'final_received_qty',
        'boat_note_qty',
        'business_unit'
    ];
}
