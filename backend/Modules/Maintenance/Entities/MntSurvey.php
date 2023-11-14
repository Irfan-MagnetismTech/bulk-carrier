<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Operations\Entities\OpsVessel;

class MntSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'mnt_survey_item_id',
        'mnt_survey_type_id',
        'ops_vessel_id',
        'short_code',
        'survey_name',
        'range_date_from',
        'range_date_to',
        'assigned_date',
        'due_date',
        'business_unit',
    ];

    public function opsVessel() : BelongsTo {
        return $this->belongsTo(OpsVessel::class);
    }

    public function mntSurveyItem() : BelongsTo {
        return $this->belongsTo(MntSurveyItem::class);
    }

    public function mntSurveyType() : BelongsTo {
        return $this->belongsTo(MntSurveyType::class);
    }
}
