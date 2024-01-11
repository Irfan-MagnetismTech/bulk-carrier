<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Voyage Details</h2>
    <default-button :title="'Voyage List'" :to="{ name: 'ops.voyages.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <table class="w-full">
            <thead>
              <tr>
                  <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Basic Info</td>
              </tr>
            </thead>
            <tbody>
              <tr>
                  <th class="w-40">Business Unit</th>
                  <td><span :class="voyage?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ voyage?.business_unit }}</span></td>
              </tr>
              <tr>
                <th class="w-32">Voyage No.</th>
                <td>{{ voyage?.voyage_no }}</td>
              </tr>
              <tr>
                <th class="w-32">Mother Vessel Name </th>
                <td>{{ voyage?.mother_vessel }}</td>
              </tr>
              <tr>
                <th class="w-32">Vessel</th>
                <td>{{ voyage?.opsVessel?.name }}</td>
              </tr>
              <tr>
                <th class="w-32">Customer Name</th>
                <td>{{ voyage?.opsCustomer?.name }}</td>
              </tr>
              <tr>
                <th class="w-32">Cargo Type</th>
                <td>{{ voyage?.opsCargoType?.cargo_type }}</td>
              </tr>
              <tr>
                <th class="w-32">Voyage Sequence</th>
                <td>{{ voyage?.voyage_sequence }}</td>
              </tr>
              <tr>
                <th class="w-32">Route</th>
                <td>{{ voyage?.route }}</td>
              </tr>
              <tr>
                <th class="w-32">Load Port Distance (NM)</th>
                <td>{{ voyage?.load_port_distance }}</td>
              </tr>
              <tr>
                <th class="w-32">Sail Date</th>
                <td>{{ voyage?.sail_date?formatDateTime(voyage?.sail_date) : null }}</td>
              </tr>
              <tr>
                <th class="w-32">Transit Date</th>
                <td>{{ voyage?.transit_date?formatDateTime(voyage?.transit_date) : null }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="flex md:gap-4 mt-1 md:mt-2" v-if="voyage.opsVoyageSectors?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Sectors</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th>SL</th>
                <th>Loading Point</th>
                <th>Unloading Point</th>
                <th>Tonnage (Initial Survey) </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(sector, index) in voyage.opsVoyageSectors">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  <span v-if="sector.loadingPoint?.name">{{ sector.loadingPoint?.name }}</span>
                </td>
                <td>
                  <span v-if="sector.unloadingPoint?.name">{{ sector.unloadingPoint?.name }}</span>
                </td>
                <td>
                  <span>
                      {{ numberFormat(sector?.initial_survey_qty) }}
                    </span>
                </td>              
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="flex md:gap-4 mt-1 md:mt-2" v-if="voyage.opsVoyageSectors?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Bunker Information</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th>SL</th>
                <th>Bunker Name</th>
                <th>Unit</th>
                <th>Presenet Stock</th>
                <th>Stock In</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(bunker, index) in voyage.opsBunkers">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  <span v-if="bunker.scmMaterial?.name">{{ bunker.scmMaterial?.name }}</span>
                </td>
                <td>
                  <span >{{ bunker.unit }}</span>
                </td>
                <td>
                  <span >{{ numberFormat(bunker.opening_balance) }}</span>
                </td>
                <td>
                  <span>
                    {{ numberFormat(bunker?.fuel_stock_l) }}
                  </span>
                </td>              
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="flex md:gap-4 mt-1 md:mt-2" v-if="voyage.opsVoyagePortSchedules?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Voyage Port Schedule</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th>SL</th>
                <th>Port</th>
                <th>Operation Type</th>
                <th>ATA</th>
                <th>Load Commence</th>
                <th>Unload Commence</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(schedule, index) in voyage.opsVoyagePortSchedules">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  <span v-if="schedule.portCode?.code_name">{{ schedule.portCode?.code_name }}</span>
                </td>
                <td>
                  <span >{{ schedule.operation_type }}</span>
                </td>
                <td>
                  <span >{{ schedule?.ata ?formatDateTime(schedule?.ata) : null }}</span>
                </td>             
                <td>
                  <span >{{ schedule?.load_commence ? formatDateTime(schedule?.load_commence)  : null }}</span>
                </td>             
                <td>
                  <span >{{ schedule?.unload_commence ? formatDateTime(schedule?.unload_commence)  : null }}</span>
                </td>             
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  </div>

</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useVoyage from '../../../composables/operations/useVoyage';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import moment from 'moment';
import { formatDateTime } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const voyageId = route.params.voyageId;
const { voyage, showVoyage, errors } = useVoyage();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Voyage Details');

onMounted(() => {
  showVoyage(voyageId);
});
</script>
<style lang="postcss" scoped>
  th, td {
    @apply text-left
  }
</style>