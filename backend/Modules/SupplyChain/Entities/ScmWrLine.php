<?php

namespace Modules\SupplyChain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScmWrLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scm_wr_id',
        'scm_material_id',
        'quantity',
        'required_date',
        'description',
        'remarks'
    ];

    public function scmWr()
    {
        return $this->belongsTo(ScmWr::class, 'scm_wr_id' , 'id');
    }
}
