<?php

namespace Modules\SupplyChain\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWrLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_wr_id',
        'scm_service_id',
        'quantity',
        'required_date',
        'description',
        'remarks',
        'closed_at',
        'closed_by',
        'is_closed',        
        'closing_remarks',
        'status',
    ];

    public function scmWr()
    {
        return $this->belongsTo(ScmWr::class, 'scm_wr_id' , 'id');
    }

    public function scmService(): BelongsTo
    {
        return $this->belongsTo(ScmService::class);
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by' , 'id');
    }
}
