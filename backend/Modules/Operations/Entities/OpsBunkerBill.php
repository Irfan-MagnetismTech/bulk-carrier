<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmVendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsBunkerBill extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'date',
        'scm_vendor_id',
        'vendor_bill_no',
        'remarks',
        'attachment',
        'smr_file_path',
        'sub_total_bdt',
        'discount_bdt',
        'grand_total_bdt',
        'business_unit',
    ];

    protected $casts = [
        'sub_total_bdt' => 'float',
        'discount_bdt' => 'float',
        'grand_total_bdt' => 'float',
    ];

    /**
    * @var array
    */
    protected $skipForDeletionCheck = ['relationName'];
    
    /**
    * @var array
    */
    protected $features = [
    // 'relationName'           => 'Menu',
    ];
    

    public function scmVendor()
    {
        return $this->belongsTo(ScmVendor::class, 'scm_vendor_id' , 'id');
    }

    public function opsBunkerBillLines()
    {
        return $this->hasMany(OpsBunkerBillLine::class, 'ops_bunker_bill_id', 'id');
    }
    public function opsBunkerBillLineItems()
    {
        return $this->hasMany(OpsBunkerBillLineItem::class, 'ops_bunker_bill_id', 'id');
    }
}
