<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\SupplyChain\Entities\ScmSirLine;
use Modules\SupplyChain\Entities\ScmSi;
use Modules\SupplyChain\Entities\ScmWarehouse;

class ScmSir extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_no', 'scm_si_id', 'scm_warehouse_id', 'acc_cost_center_id', 'department_id', 'date', 'business_unit', 'created_by',
    ];

    public function scmSirLines(): HasMany
    {
        return $this->hasMany(ScmSirLine::class);
    }

    public function scmWarehouse(): BelongsTo
    {
        return $this->belongsTo(ScmWarehouse::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function scmSi(): BelongsTo
    {
        return $this->belongsTo(ScmSi::class);
    }
}
