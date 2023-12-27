<template>
   <div class="flex items-center justify-between w-full my-3" v-once>
      <h2 class="text-2xl font-semibold text-gray-700">Lighter Noon Report Details</h2>
      <default-button :title="'Lighter Noon Report Index'" :to="{ name: 'ops.lighter-noon-reports.index' }" :icon="icons.DataBase"></default-button>
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
                          <td><span :class="lighterNoonReport?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ lighterNoonReport?.business_unit }}</span></td>
                      </tr>
                      <tr>
                          <th class="w-40">Date</th>
                          <td><nobr>{{ lighterNoonReport?.date ? moment(lighterNoonReport?.date).format('DD-MM-YYYY hh:mm A') : null }}</nobr></td>
                      </tr>
                      <tr>
                          <th class="w-40">Vessel</th>
                          <td>{{ lighterNoonReport.opsVessel?.name }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Voyage</th>
                          <td>{{ lighterNoonReport?.opsVoyage?.voyage_sequence }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Ship Master</th>
                          <td>{{ lighterNoonReport?.ship_master }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Chief Engineer</th>
                          <td>{{ lighterNoonReport?.chief_engineer }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Noon Position</th>
                          <td>{{ lighterNoonReport?.noon_position }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Engine Running Hours</th>
                          <td>{{ lighterNoonReport?.engine_running_hours }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Lat/Long</th>
                          <td>{{ lighterNoonReport?.lat_long }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Last Port</th>
                          <td>{{ lighterNoonReport.last_port }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Next Port</th>
                          <td>{{ lighterNoonReport.next_port }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Status</th>
                          <td>{{ lighterNoonReport?.status }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Remarks</th>
                          <td>{{ lighterNoonReport.remarks }}</td>
                      </tr>
                  </tbody>
              </table>
          </div>
      </div>

      <div class="flex md:gap-4 mt-1 md:mt-2" v-if="lighterNoonReport.opsBunkers?.length">
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
                  <th class="w-72">Bunker Name</th>
                  <th>Unit</th>
                  <th>Present Stock</th>
                  <th>FUEL - CON/24H</th>
                  <th>FUEL - CON/Voyage</th>
                  <th class="hidden">FUEL - Stock/L</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="(bunker, index) in lighterNoonReport.opsBunkers">
                  <td>
                    {{ index+1 }}
                  </td>
                  <td>
                    {{ lighterNoonReport.opsBunkers[index].name }}
                  </td>
                  <td>
                    <span v-if="lighterNoonReport.opsBunkers[index]?.unit">{{ lighterNoonReport.opsBunkers[index]?.unit }}</span>
                  </td>
                  <td>
                    {{ lighterNoonReport.opsBunkers[index].opening_balance }}
                  </td>
                  <td>
                    {{ lighterNoonReport.opsBunkers[index].fuel_con_24h }}
                  </td>
                  <td>
                    {{ lighterNoonReport.opsBunkers[index].fuel_con_voyage }}
                  </td>
                  <td class="hidden">
                    {{ lighterNoonReport.opsBunkers[index]?.fuel_stock_l }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
      </div>
    </div>
</template>
<style lang="postcss" scoped>
  th, td, tr {
    @apply text-left border-gray-500
  }
  
  tfoot td{
    @apply text-center
  }  
</style>
<script setup>
import { ref, watchEffect, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useLighterNoonReport from '../../../composables/operations/useLighterNoonReport';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import moment from 'moment';


const icons = useHeroIcon();

const route = useRoute();
const lighterNoonReportId = route.params.lighterNoonReportId;
const { lighterNoonReport, showLighterNoonReport, errors } = useLighterNoonReport();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Lighter Noon Report Details');

onMounted(() => {
    showLighterNoonReport(lighterNoonReportId)
});
</script>