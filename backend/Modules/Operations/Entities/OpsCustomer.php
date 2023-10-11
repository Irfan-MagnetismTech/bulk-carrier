<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpsCustomer extends Model
{
    use HasFactory;

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
}
