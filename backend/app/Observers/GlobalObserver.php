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
     * @return bool|array|null
     */
    public function deleting(Model $model): bool|array|null
    {
        if (method_exists($model, 'preventDeletionIfRelated')) {
            $response = $model->preventDeletionIfRelated();

            if ($response === null) {
                return true;
            }
        }
    }
}
