<?php

namespace Modules\Maintenance\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Operations\Entities\OpsVessel;

class MntSurveyEntry extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'ops_vessel_id',
        'mnt_survey_id',
        'range_date_from',
        'range_date_to',
        'assigned_date',
        'due_date',
        'business_unit',
    ];

    protected $appends = ["status"];

    public function opsVessel() : BelongsTo {
        return $this->belongsTo(OpsVessel::class);
    }

    public function mntSurvey() : BelongsTo {
        return $this->belongsTo(MntSurvey::class);
    }

    public function getStatusAttribute() {
        $status = "Not Due";
        $today = new DateTime(date('Y-m-d'));
        $dueDate = new DateTime($this->due_date);

        $interval = $dueDate->diff($today);
        $daysPassed =(int) $interval->format('%R%a');

        $status = ($daysPassed > 0) ? "Due" : (($daysPassed >= -30) ? "Due Soon" : $status);

        return $status;
    }
}
