<template>
   <div class="flex items-center justify-between w-full my-3" v-once>
      <h2 class="text-2xl font-semibold text-gray-700">Bulk Noon Report Details</h2>
      <default-button :title="'Bulk Noon Report Index'" :to="{ name: 'ops.bulk-noon-reports.index' }" :icon="icons.DataBase"></default-button>
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
                          <td><span :class="bulkNoonReport?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ bulkNoonReport?.business_unit }}</span></td>
                      </tr>
                      <tr>
                        <th class="w-40">Date</th>
                        <td><nobr>{{ formatDateTime(bulkNoonReport?.date_time) }}</nobr></td>
                      </tr>
                      <tr>
                          <th class="w-40">Vessel</th>
                          <td>{{ bulkNoonReport.opsVessel?.name }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Voyage</th>
                          <td>{{ bulkNoonReport.opsVoyage?.voyage_no }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Ship Master</th>
                          <td>{{ bulkNoonReport?.ship_master }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Chief Engineer</th>
                          <td>{{bulkNoonReport?.chief_engineer }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Noon Position</th>
                          <td>{{bulkNoonReport?.noon_position }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Status</th>
                          <td>{{bulkNoonReport?.status }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Engine Running Hours</th>
                          <td>{{bulkNoonReport?.engine_running_hours }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Lat/Long</th>
                          <td>{{bulkNoonReport?.lat_long }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Wind Condition</th>
                          <td>{{bulkNoonReport?.wind_condition }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Remarks</th>
                          <td>{{bulkNoonReport?.remarks }}</td>
                      </tr>
                  </tbody>
              </table>
          </div>
      </div>
      <div class="flex md:gap-4" v-if="bulkNoonReport.opsBulkNoonReportPorts?.length">
        <div class="w-full">
          <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
            <legend class="px-2 rounded !text-center font-bold bg-green-600 uppercase text-white">Upcoming Port</legend>
            <table class="w-full mt-1 md:mt-2" v-for="(port, index) in bulkNoonReport.opsBulkNoonReportPorts">
              <tr>
                <th class="w-40">Last Port:</th>
                <td colspan="2">{{ bulkNoonReport.opsBulkNoonReportPorts[index].last_port ? bulkNoonReport.opsBulkNoonReportPorts[index].last_port :'' }}</td>
                <th class="w-40">Next Port:</th>
                <td colspan="2">{{ bulkNoonReport.opsBulkNoonReportPorts[index].next_port ? bulkNoonReport.opsBulkNoonReportPorts[index].next_port :'' }}</td>
              </tr>
              <tr>
                <th class="w-40">ETA:</th>
                <td class="w-40">{{ bulkNoonReport.opsBulkNoonReportPorts[index].eta }}</td>
                <th class="w-40">Distance Run:</th>
                <td class="w-40">{{ bulkNoonReport.opsBulkNoonReportPorts[index].distance_run }}</td>
                <th class="w-40">DTG:</th>
                <td class="w-40">{{ bulkNoonReport.opsBulkNoonReportPorts[index].dtg }}</td>
              </tr>
              <tr>
                <th class="w-40">Remarks:</th>
                <td colspan="5">{{ bulkNoonReport.opsBulkNoonReportPorts[index].remarks }}</td>
              </tr>
            </table>
          </fieldset>
        </div>
      </div>
      <div class="flex md:gap-4">
        <div class="w-full">
          <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
            <legend class="px-2 rounded !text-center font-bold bg-green-600 uppercase text-white">Distance and Vessel</legend>
            <table class="w-full mt-1 md:mt-2" v-if="bulkNoonReport?.opsBulkNoonReportDistance">
              <tr>
                <th class="w-36">CP/Ordered Speed:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.cp_ordered_speed }}</td>
                <th class="w-36">Average RPM:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.average_rpm }}</td>
                <th class="w-36">Reported Speed:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.reported_speed }}</td>
                <th class="w-36">Fwd Draft:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.fwd_draft }}</td>
              </tr>
              <tr>
                <th class="w-36">Observed Distance:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.observed_distance }}</td>
                <th class="w-36">Mild Draft:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.mild_draft }}</td>
                <th class="w-36">Engine Distance:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.engine_distance }}</td>
                <th class="w-36">Aft Draft:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.aft_draft }}</td>
              </tr>
              <tr>
              </tr>
              <tr>
                <th class="w-36">Main Engine Revs:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.main_engine_revs }}</td>
                <th class="w-36">Heading:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.heading }}</td>
                <th class="w-36">Slip %:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.slip_percent }}</td>
                <th class="w-36">Steaming Hours:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.steaming_hours }}</td>
              </tr>
              <tr>
                <th class="w-36">Salinity:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.salinity}}</td>
                <th class="w-36">S. DWT:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.s_dwt }}</td>
                <th class="w-36">Ballast:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.ballast }}</td>
                <th class="w-36">S. Displacement:</th>
                <td>{{ bulkNoonReport?.opsBulkNoonReportDistance?.s_displacement }}</td>
              </tr> 
              <tr>
                <th class="w-36">FW Last Day Noon ROB:</th>
                <td>{{ bulkNoonReport?.fw_last_day_noon_rob }}</td>
                <th class="w-36">FW Production:</th>
                <td>{{ bulkNoonReport?.fw_production }}</td>
                <th class="w-36">FW Consumption:</th>
                <td>{{ bulkNoonReport?.fw_consumption }}</td>
                <th class="w-36">FW Today Noon ROB:</th>
                <td>{{ bulkNoonReport?.fw_today_noon_rob }}</td>
              </tr>
            </table>

            <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded" v-if="bulkNoonReport.opsBulkNoonReportCargoTanks?.length">
              <legend class="px-2 rounded !text-center text-sm font-bold bg-green-600 uppercase text-white">Cargo Tank Info</legend>
              <table class="w-full mt-1 md:mt-2" v-if="bulkNoonReport.opsBulkNoonReportCargoTanks">
                <tr>
                  <th>Cargo Tanks</th>
                  <th>Liq Level</th>
                  <th>Pressure</th>
                  <th>Vapor Temp</th>
                  <th>Liq Temp</th>
                  <th>Quantity (MT)</th>
                </tr>
                <tbody>
                  <tr v-for="(tank, index) in bulkNoonReport.opsBulkNoonReportCargoTanks">
                    <td>{{ 'CT' + (index+1) }}</td>
                    <td>{{ bulkNoonReport.opsBulkNoonReportCargoTanks[index].liq_level }}</td>
                    <td>{{ bulkNoonReport.opsBulkNoonReportCargoTanks[index].pressure }}</td>
                    <td>{{ bulkNoonReport.opsBulkNoonReportCargoTanks[index].vapor_temp }}</td>
                    <td>{{ bulkNoonReport.opsBulkNoonReportCargoTanks[index].liq_temp }}</td>
                    <td>{{ bulkNoonReport.opsBulkNoonReportCargoTanks[index].quantity_mt }}</td>
                  </tr>
                </tbody>
              </table>
            </fieldset>
          </fieldset>
        </div>
      </div>

      <div class="flex md:gap-4" v-if="bulkNoonReport.opsBulkNoonReportConsumptions?.length">
        <div class="w-full">
          <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
            <legend class="px-2 rounded !text-center font-bold bg-green-600 uppercase text-white">Bunker Consumptions</legend>
            <table class="w-full mt-1 md:mt-2">
              <tr>
                <th class="w-40">Type</th>
                <th class="w-40">Previous ROB</th>
                <th class="w-40">Received</th>
                <th class="w-40">Consumption</th>
                <th class="w-40">ROB</th>
              </tr>
              <tbody>
                <tr v-for="(consumption, index) in bulkNoonReport.opsBulkNoonReportConsumptions">
                  <td>{{ bulkNoonReport.opsBulkNoonReportConsumptions[index]?.scmMaterial?.name }}</td>
                  <td>{{ bulkNoonReport.opsBulkNoonReportConsumptions[index]?.previous_rob }}</td>
                  <td>{{ bulkNoonReport.opsBulkNoonReportConsumptions[index]?.received }}</td>
                  <td>{{ bulkNoonReport.opsBulkNoonReportConsumptions[index]?.consumption }}</td>
                  <td>{{ bulkNoonReport.opsBulkNoonReportConsumptions[index]?.rob }}</td>
                </tr>
              </tbody>
            </table>
          </fieldset>
        </div>
      </div>

      <div class="flex md:gap-4" v-if="bulkNoonReport.opsBulkNoonReportEngineInputs?.length">
        <div class="w-full">
          <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
            <legend class="px-2 rounded !text-center font-bold bg-green-600 uppercase text-white">Engine Info</legend>
            <table class="w-full mt-1 md:mt-2">
              <tr>
                <th class="w-40">Unit </th>
                <th class="w-40">PCO</th>
                <th class="w-40">Rack</th>
                <th class="w-40">Exh. Temp.</th>
              </tr>
              <tbody>
                <tr v-for="(consumption, index) in bulkNoonReport.opsBulkNoonReportEngineInputs">
                  <td>{{ (bulkNoonReport.opsBulkNoonReportEngineInputs[index]?.type == 'engine_unit')? 'Engine Unit': bulkNoonReport.opsBulkNoonReportEngineInputs[index]?.type }}<span class="ml-2"></span>{{ (bulkNoonReport.opsBulkNoonReportEngineInputs[index]?.engine_unit)?bulkNoonReport.opsBulkNoonReportEngineInputs[index]?.engine_unit : null }}</td>
                  <td>{{ bulkNoonReport.opsBulkNoonReportEngineInputs[index]?.pco }}</td>
                  <td>{{ bulkNoonReport.opsBulkNoonReportEngineInputs[index]?.rack }}</td>
                  <td>{{ bulkNoonReport.opsBulkNoonReportEngineInputs[index]?.exh_temp }}</td>
                </tr>
              </tbody>
            </table>
          </fieldset>
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
import useBulkNoonReport from '../../../composables/operations/useBulkNoonReport';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import moment from 'moment';
import { formatDateTime } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const bulkNoonReportId = route.params.bulkNoonReportId;
const { bulkNoonReport, showBulkNoonReport, errors } = useBulkNoonReport();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Bulk Noon Report Details');

onMounted(() => {
    showBulkNoonReport(bulkNoonReportId)
});
</script>