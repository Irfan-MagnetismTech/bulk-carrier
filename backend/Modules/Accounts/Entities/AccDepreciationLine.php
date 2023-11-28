<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccDepreciationLine extends Model
{
    use HasFactory;

    protected $fillable = ['acc_depreciation_line_id', 'acc_fixed_asset_id', 'amount', 'depreciation_rate'];

    public function accFixedAsset()
    {
        return $this->belongsTo(AccFixedAsset::class, 'acc_fixed_asset_id', 'id');
    }      
}
