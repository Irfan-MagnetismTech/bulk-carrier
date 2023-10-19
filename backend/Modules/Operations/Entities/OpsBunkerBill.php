<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBunkerBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'scm_vendor_id',
        'vendor_bill_no',
        'remarks',
        'attachment',
        'smr_file_path',
        'sub_total',
        'discount',
        'grand_total',
        'business_unit',
    ];

    // public function scmVendor()
    // {
    //     return $this->belongsTo(ScmVendor::class, 'scm_vendor_id' , 'id');
    // }

    public function opsBunkerBillLines()
    {
        return $this->hasMany(OpsBunkerBillLine::class, 'ops_bunker_bill_id', 'id');
    }
}
