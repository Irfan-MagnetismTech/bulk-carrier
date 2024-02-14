<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Modules\SupplyChain\Entities\ScmPo;

class GlobalObserver
{
    /**
     * Handle the Model "deleting" event.
     *
     * @param Model  $model
     * @return bool|array
     */
    public function deleting(Model $model): bool|array
    {
        if (method_exists($model, 'preventDeletionIfRelated')) {
            return $model->preventDeletionIfRelated();
        }

        return true;
    }
}

// change in scm
