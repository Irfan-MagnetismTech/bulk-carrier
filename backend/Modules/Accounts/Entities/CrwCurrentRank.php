<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CrwCurrentRank extends Model
{
    use HasFactory;

    protected $fillable = ['crw_crew_id', 'crw_rank_id', 'rank_name', 'effective_date'];
}
