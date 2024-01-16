<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWcsVendorService extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_vendor_id',
        'scm_wcs_id',
        'scm_wr_id',
        'scm_wcs_vendor_id',
        'scm_wcs_service_id',
        'rate',
        'quantity',
        'quotation_ref_no',
        'quotation_date',  
    ];


    public function scmWr()
    {
        return $this->belongsTo(ScmWr::class, 'scm_wr_id', 'id');
    }

    public function scmVendor()
    {
        return $this->belongsTo(ScmVendor::class, 'scm_vendor_id', 'id');
    }

    public function scmWcs()
    {
        return $this->belongsTo(ScmWcs::class, 'scm_wcs_id', 'id');
    }

    public function scmWcsVendor()
    {
        return $this->belongsTo(ScmWcsVendor::class, 'scm_wcs_vendor_id', 'id');
    }

    public function scmWcsService()
    {
        return $this->belongsTo(ScmWcsService::class, 'scm_wcs_service_id', 'id');
    }
}
