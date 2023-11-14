<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MntSurveyType extends Model
{
    use HasFactory;

    protected $fillable = ['suvey_type_name','due_period','window_period'];

    public function mntSurvey() : HasMany {
        return $this->hasMany(MntSurvey::class);
    }
}
