<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsCustomer extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;

    protected $fillable = [
        'code',
        'legal_name',
        'name',
        'postal_address',
        'city',
        'post_code',
        'country',
        'tax_id',
        'business_license_no',
        'bin_gst_sst_type',
        'bin_gst_sst_no',
        'phone',
        'company_reg_no',
        'email_general',
        'email_agreement',
        'email_invoice',
        'business_unit'
    ];

    
    /**
     * The accessors to append to the model's array form.
     *
     * @var string[]
     */
    protected $appends = ['name_code'];

    /**
     * Concatenate the code and name of the port.
     *
     * @return string
     */
    public function getNameCodeAttribute()
    {
        return  $this->name. ' - '.$this->code;
    }

    public function opsVoyages()
    {
        return $this->hasMany(OpsVoyage::class, 'ops_customer_id', 'id');
    }
}
