<template>
   <div class="flex items-center justify-between w-full my-3" v-once>
      <h2 class="text-2xl font-semibold text-gray-700">Lighter Noon Report Details</h2>
      <default-button :title="'Lighter Noon Report Index'" :to="{ name: 'ops.lighter-noon-reports.index' }" :icon="icons.DataBase"></default-button>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="show-block">
          {{ lighterNoonReport.business_unit }}
        </span>
      </label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel </span>
              <span class="show-block">{{ lighterNoonReport.opsVessel?.name }}</span>
      </label>
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Voyage </span>
              <span class="show-block">{{ lighterNoonReport.opsVoyage?.voyage_sequence }}</span>

      </label>
      
      
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Ship Master</span>
              <span class="show-block">{{ lighterNoonReport.ship_master }}</span>
              
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Chief Engineer</span>
            <span class="show-block">{{ lighterNoonReport.chief_engineer }}</span>
      </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Noon Position</span>
              <span class="show-block">{{ lighterNoonReport.noon_position }}</span>
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Status</span>
              <span class="show-block">{{ lighterNoonReport.status }}</span>
        </label>
        
        
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Engine Running Hours</span>
              <span class="show-block">{{ lighterNoonReport.engine_running_hours }}</span>
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Lat/Long</span>
              <span class="show-block">{{ lighterNoonReport.lat_long }}</span>
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Date</span>
              <span class="show-block">
                <nobr>{{ lighterNoonReport?.date ? moment(lighterNoonReport?.date).format('DD-MM-YYYY hh:mm A') : null }}</nobr>
              </span>
        </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">

        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Last Port <span class="text-red-500">*</span></span>
          <span class="show-block">{{ lighterNoonReport.last_port }}</span>

        </label>

        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Next Port <span class="text-red-500">*</span></span>
          <span class="show-block">{{ lighterNoonReport.next_port }}</span>

        </label>


    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Remarks</span>
              <span class="show-block">{{ lighterNoonReport.remarks }}</span>
        </label>
    </div>

    <div id="bunkers" class="my-5" v-if="lighterNoonReport.opsBunkers?.length > 0">
      <h4 class="text-md font-semibold my-3">Bunker Info</h4>

      <table class="w-full whitespace-no-wrap" >
          <thead v-once>
            <tr class="w-full">
              <th>SL</th>
              <th class="w-72">Bunker Name</th>
              <th>Unit</th>
              <th><nobr> Present Stock </nobr></th>
              <th><nobr> FUEL - CON/24H </nobr></th>
              <th><nobr> FUEL - CON/Voyage </nobr></th>
              <th class="hidden"><nobr> FUEL - Stock/L </nobr></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(certificate, index) in lighterNoonReport.opsBunkers">
              <td>
                {{ index+1 }}
              </td>
              <td>
                
                <span class="show-block !bg-gray-100">{{ lighterNoonReport.opsBunkers[index].name }}</span>
              </td>
              <td>
                <span class="show-block !justify-center !bg-gray-100" v-if="lighterNoonReport.opsBunkers[index]?.unit">{{ lighterNoonReport.opsBunkers[index]?.unit }}</span>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <span class="show-block !block !bg-gray-100 !text-right">{{ lighterNoonReport.opsBunkers[index].opening_balance }}</span>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <span class="show-block !block !bg-gray-100 !text-right">{{ lighterNoonReport.opsBunkers[index].fuel_con_24h }}</span>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <span class="show-block !block !bg-gray-100 !text-right">{{ lighterNoonReport.opsBunkers[index].fuel_con_voyage }}</span>
                </label>
              </td>
              <td class="hidden">
                <label class="block w-full mt-2 text-sm">
                  <span class="show-block !block !bg-gray-100 !text-right">{{ lighterNoonReport.opsBunkers[index]?.fuel_stock_l }}</span>
                </label>
              </td>
            </tr>
          </tbody>
        </table>
    </div>
</template>
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