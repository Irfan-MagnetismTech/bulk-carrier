<?php

namespace Modules\Operations\Entities;

use App\Traits\DeletableModel;
use App\Traits\GlobalSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsPort extends Model
{
    use HasFactory, GlobalSearchTrait, DeletableModel;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name',
        'business_unit'
    ];
    
    

    /**
     * The accessors to append to the model's array form.
     *
     * @var string[]
     */
    protected $appends = ['code_name'];

    /**
    * @var array
    */
    protected $skipForDeletionCheck = [''];
    
    /**
    * @var array
    */
    protected $features = [
    // 'relationName'           => 'Menu',
    ];
    
    
    /**
     * Concatenate the code and name of the port.
     *
     * @return string
     */
    public function getCodeNameAttribute()
    {
        return $this->code . ' - ' . $this->name;
    }
}
