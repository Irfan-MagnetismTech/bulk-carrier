<?php

namespace Modules\Operations\Services;

use Modules\Operations\Entities\OpsVessel;
use Modules\SupplyChain\Entities\ScmStockLedger;

class OpsVesselBunkerService
{
   public static function getBunkers($vessel_ids) {
        
        $bunkers = OpsVessel::query();

        if(is_array($vessel_ids)) {
            $bunkers->whereIn('id', $vessel_ids);
        } else {
            $bunkers->where('id', $vessel_ids);
        }

        $result = $bunkers->with('opsBunkers')->get()->map(function($item) {
            return $item->opsBunkers->map(function($bunker) {
                return [
                    'scm_material_id' => $bunker->scm_material_id,
                    'name' => $bunker->scmMaterial->name
                ];
            });
        });

        return $result;
   }
}
