<?php

namespace Modules\Maintenance\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MntSurveyItem extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $fillable = ['item_name'];

    protected $skipForDeletionCheck = [];

    protected $features = [
        'mntSurveys' => 'Surveys',
    ];

    public function mntSurveys() : HasMany {
        return $this->hasMany(MntSurvey::class);
    }
}
