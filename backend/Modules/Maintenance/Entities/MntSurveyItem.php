<?php

namespace Modules\Maintenance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MntSurveyItem extends Model
{
    use HasFactory;

    protected $fillable = ['item_name'];

    public function mntSurvey() : HasMany {
        return $this->hasMany(MntSurvey::class);
    }
}
