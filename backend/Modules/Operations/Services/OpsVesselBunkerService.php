<?php

namespace Modules\Operations\Services;

use Modules\Operations\Entities\OpsVessel;
use Modules\SupplyChain\Entities\ScmStockLedger;

class OpsVesselBunkerService
{
   public static function getBunkers($vessel_ids, $business_unit) {
        
        $bunkers = OpsVessel::query();

        if(is_array($vessel_ids)) {
            $bunkers->whereIn('id', $vessel_ids);
        } else if(!empty($vessel_ids)) {
            $bunkers->where('id', $vessel_ids);
        }

        if(!empty($business_unit)) {
            $bunkers->where('business_unit', $business_unit);
        }

        $bunkers = $bunkers->with('opsBunkers')->get();

        $result = $bunkers->map(function($item) {
            return $item->opsBunkers->map(function($bunker) {
                return [
                    'scm_material_id' => $bunker->scm_material_id,
                    'name' => $bunker->scmMaterial->name
                ];
            });
        })->flatten(1)->unique();

        return $result;
   }
}
