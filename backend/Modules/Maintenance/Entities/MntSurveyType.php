<?php

namespace Modules\Maintenance\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MntSurveyType extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = ['survey_type_name','due_period','window_period'];

    public function mntSurveys() : HasMany {
        return $this->hasMany(MntSurvey::class);
    }
}
