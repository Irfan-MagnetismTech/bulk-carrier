<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmPo;

class GlobalObserver
{
    public function deleting(Model $model)
    {
        if (method_exists($model, 'preventDeletionIfRelated')) {
            return $model->preventDeletionIfRelated();
        }

        return true;
    }
}
