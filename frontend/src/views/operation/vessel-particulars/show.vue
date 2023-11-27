<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Vessel Particular Details</h2>
    <default-button :title="'Vessel Particular List'" :to="{ name: 'ops.vessel-particulars.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Business Unit </span>
            <span class="show-block">
              {{ vesselParticular?.business_unit }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Vessel </span>
          <span class="show-block">
              {{ vesselParticular?.opsVessel?.name }}
          </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Type </span>
            <span class="show-block">
              {{ vesselParticular?.opsVessel?.vessel_type }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Owner </span>
            <span class="show-block">
              {{ vesselParticular?.owner_name }}
            </span>
        </label>
        
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Previous Name </span>
            <span class="show-block">
              {{ vesselParticular?.previous_name }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Short Code </span>
            <span class="show-block">
              {{ vesselParticular?.short_code }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Call Sign </span>
            <span class="show-block">
              {{ vesselParticular?.call_sign }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Manager/Operator </span>
          <span class="show-block">
              {{ vesselParticular?.manager }}
          </span>
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Classification </span>
            <span class="show-block">
              {{ vesselParticular?.classification }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Previous Flag </span>
            <span class="show-block">
              {{ vesselParticular?.previous_flag }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Flag </span>
            <span class="show-block">
              {{ vesselParticular?.flag }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Delivery Date </span>
          <span class="show-block">
              {{ vesselParticular?.delivery_date ? moment(vesselParticular?.delivery_date).format('DD-MM-YYYY') : null }}
          </span>
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">GRT </span>
            <span class="show-block">
              {{ vesselParticular?.grt }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">NRT </span>
            <span class="show-block">
              {{ vesselParticular?.nrt }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">DWT </span>
            <span class="show-block">
              {{ vesselParticular?.dwt }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">IMO Number </span>
          <span class="show-block">
              {{ vesselParticular?.imo }}
          </span>
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Class No </span>
            <span class="show-block">
              {{ vesselParticular?.class_no }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Official Number </span>
            <span class="show-block">
              {{ vesselParticular?.official_number }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Keel Laying Date </span>
            <span class="show-block">
              {{ vesselParticular?.keel_laying_date ? moment(vesselParticular?.keel_laying_date).format('DD-MM-YYYY') : null }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Launching Date </span>
          <span class="show-block">
              {{ vesselParticular?.launching_date ? moment(vesselParticular?.launching_date).format('DD-MM-YYYY') : null }}
          </span>
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">MMSI </span>
            <span class="show-block">
              {{ vesselParticular?.mmsi }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Length Overall </span>
            <span class="show-block">
              {{ vesselParticular?.overall_length }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Width Overall </span>
            <span class="show-block">
              {{ vesselParticular?.overall_width }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Year Built </span>
          <span class="show-block">
              {{ vesselParticular?.year_built }}
          </span>
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Breadth </span>
            <span class="show-block">
              {{ vesselParticular?.breadth }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Depth (Moulded) </span>
            <span class="show-block">
              {{ vesselParticular?.depth_moulded }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Maker SSAS </span>
            <span class="show-block">
              {{ vesselParticular?.maker_ssas }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Port of Registry </span>
          <span class="show-block">
              {{ vesselParticular?.port_of_registry }}
          </span>
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Engine Type </span>
            <span class="show-block">
              {{ vesselParticular?.engine_type }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">LOA </span>
            <span class="show-block">
              {{ vesselParticular?.loa }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Depth </span>
            <span class="show-block">
              {{ vesselParticular?.depth }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">ECDIS Type </span>
          <span class="show-block">
              {{ vesselParticular?.ecdis_type }}
          </span>
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">BHP </span>
            <span class="show-block">
              {{ vesselParticular?.bhp }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Email </span>
            <span class="show-block">
              {{ vesselParticular?.email }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">LBC </span>
            <span class="show-block">
              {{ vesselParticular?.lbc }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Total Cargo Hold </span>
          <span class="show-block">
              {{ vesselParticular?.total_cargo_hold }}
          </span>
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Capacity </span>
            <span class="show-block">
              {{ vesselParticular?.capacity }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Tues Capacity </span>
            <span class="show-block">
              {{ vesselParticular?.tues_capacity }}
            </span>
        </label>
        <label class="block w-full mt-2 text-sm"></label>
        <label class="block w-full mt-2 text-sm"></label>
    </div>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useVesselParticular from '../../../composables/operations/useVesselParticular';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import moment from 'moment';
const icons = useHeroIcon();

const route = useRoute();
const vesselParticularId = route.params.vesselParticularId;
const { vesselParticular, showVesselParticular, errors } = useVesselParticular();

const { setTitle } = Title();

setTitle('Vessel Particular');

onMounted(() => {
  showVesselParticular(vesselParticularId);
});


</script>