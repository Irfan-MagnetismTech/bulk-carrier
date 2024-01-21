<?php

namespace Modules\SupplyChain\Entities;

use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmService extends Model
{
    use HasFactory, GlobalSearchTrait;

    protected $appends = ['service_name_and_code'];

    protected $fillable = [
        'name', 'short_code', 'description'
    ];

    public function getServiceNameAndCodeAttribute(): string
    {
        return $this->name . ' - ' . $this->short_code;
    }
}
