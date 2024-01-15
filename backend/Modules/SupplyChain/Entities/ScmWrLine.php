<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
