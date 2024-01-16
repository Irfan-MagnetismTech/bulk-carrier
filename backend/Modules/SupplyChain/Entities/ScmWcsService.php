<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWcsService extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_wr_id',
        'scm_wcs_id',
        'scm_service_id',
        'quantity',
    ];

    public function scmWr()
    {
        return $this->belongsTo(ScmWr::class, 'scm_wr_id', 'id');
    }

    public function scmWcs()
    {
        return $this->belongsTo(ScmWcs::class, 'scm_wcs_id', 'id');
    }

    public function scmService()
    {
        return $this->belongsTo(ScmService::class, 'scm_service_id', 'id');
    }
}
