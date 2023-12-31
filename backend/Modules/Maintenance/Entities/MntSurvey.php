<?php

namespace Modules\Maintenance\Entities;

use App\Traits\GlobalSearchTrait;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Operations\Entities\OpsVessel;

class MntSurvey extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = [
        'mnt_survey_item_id',
        'mnt_survey_type_id',
        'short_code',
        'survey_name',
    ];

    protected $appends = ["status"];

    public function mntSurveyItem() : BelongsTo {
        return $this->belongsTo(MntSurveyItem::class);
    }

    public function mntSurveyType() : BelongsTo {
        return $this->belongsTo(MntSurveyType::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($survey) {
            $survey->survey_name = $survey->short_code . '-' . $survey->survey_name;
        });
    }

    public function getSurveyNameAttribute($value)
    {
        if($this->applySurveyNameModification && strpos($value, $this->short_code."-") !== false){
            return substr_replace($value, '', 0, strlen($this->short_code)+1);
        }
        return $value;
    }
}
