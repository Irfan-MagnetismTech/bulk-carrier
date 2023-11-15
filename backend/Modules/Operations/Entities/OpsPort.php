<?php

namespace Modules\Operations\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\GlobalSearchTrait;

class OpsPort extends Model
{
    use HasFactory, GlobalSearchTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name'
    ];
    
    

    /**
     * The accessors to append to the model's array form.
     *
     * @var string[]
     */
    protected $appends = ['code_name'];

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
