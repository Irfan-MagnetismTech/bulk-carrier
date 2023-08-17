
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import moment from 'moment';

import useServiceReport from '../../../composables/documentation/useServiceReport';
import useCustomer from "../../../composables/commercial/useCustomer";
import useService from "../../../composables/commercial/useService";
import useVessel from "../../../composables/dataencoding/useVessel";
import useVoyage from "../../../composables/operation/useVoyage";
import Title from "../../../services/title";
import useTableExportExcel from "../../../services/tableExportExcel";

const route = useRoute();

const { customers, getCustomers } = useCustomer();
const { tableToExcel } = useTableExportExcel();

const { services, bounds, getServices, serviceGroupByCode } = useService();
const { vessels, vesselName, getVesselWithoutPaginate } = useVessel();
const { voyages, getVoyageName } = useVoyage();



const { formParams, serviceReportInfo, getServiceReport, isLoading } = useServiceReport();

function getServiceBound(e) {
  serviceGroupByCode(e.target.value);
}

const { setTitle } = Title();

setTitle('Service Report');

onMounted(() => {
  getServices();
  getVesselWithoutPaginate();
});


</script>
<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Service Report</h2>
  </div>
  <!-- Table -->
  <div class="px-4 py-3 mb-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <form class="flex items-end justify-between gap-1" @submit.prevent="getServiceReport(formParams)">
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Service <span class="text-red-500">*</span></span>
        <select v-model="formParams.service" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="">Choose Option</option>
          <option v-for="(service,index) in services" :value="service?.code" :key="index">{{ service?.code }}</option>
        </select>
      </label>

      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
        <select v-model="formParams.vessel_id" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="" disabled selected>--Choose an Option--</option>
          <option v-for="(vessel,index) in vessels" :value="vessel.id" :key="index">{{ vessel.name }}</option>
        </select>
      </label>

      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Voyage</span>
        <input type="text" v-model="formParams.voyage" placeholder="Voyage no" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      </label>

      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Type<span class="text-red-500">*</span></span>
        <select v-model="formParams.type" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="" disabled selected>--Choose an Option--</option>
          <option value="actual_departure_date"> ATA </option>
          <option value="actual_berth_date"> ATB </option>
          <option value="actual_berth_date"> ATD </option>
        </select>
      </label>

      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Duration<span class="text-red-500">*</span></span>
        <select v-model="formParams.duration" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="" disabled>--Choose an Option--</option>
          <option value="7" selected>7 days</option>
          <option value="15">15 days</option>
          <option value="30">30 days</option>
          <option value="custom">Custom</option>
        </select>
      </label>

      <template v-if="formParams.duration === 'custom'">
        <label class="block w-full text-sm">
          <span class="text-gray-700 dark:text-gray-300">From Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="formParams.from_date" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>
        <label class="block w-full text-sm">
          <span class="text-gray-700 dark:text-gray-300">Till Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="formParams.till_date" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>
      </template>

      <!-- Submit button -->
      <button type="submit" :disabled="isLoading"
        class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
    </form>
  </div>
  <button v-if="Object.keys(serviceReportInfo).length" type="button" @click="tableToExcel('service-report-table','service-report')" class="w-1/6 mb-4 flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
    <span>Download Excel</span>
  </button>
  <div class="w-full mb-8 border rounded-lg shadow-xs dark:border-0">
    <div class="w-full">
      <table class="w-full whitespace-no-wrap" id="service-report-table">
        <thead>
          <tr
            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
            <th class="text-center" rowspan="2">SL</th>
            <th class="text-center" rowspan="2">Vessel</th>
            <template v-for="(sector, sectorKey) in serviceReportInfo?.sectors?.south" :key="sectorKey">
              <th class="text-center" rowspan="2">Voyage</th>
              <th class="text-center" rowspan="2">ATB <br> {{sectorKey}}</th>
              <th class="text-center" rowspan="2">ATD <br> {{sectorKey}}</th>
            </template> <!-- south voyage -->

            <template v-for="(sector, sectorKey, index) in serviceReportInfo?.sectors?.north" :key="sectorKey">
              <th class="text-center" rowspan="2" v-if="index == 0" >Voyage </th>
              <template v-if="Object.keys(serviceReportInfo.sectors.north).length-1 == index">
                <th class="text-center" rowspan="2"> ATB <br> {{sectorKey}} </th>
              </template>
              <template v-else>
                <th class="text-center" rowspan="2"> ATD <br> {{sectorKey}}</th>
              </template>
            </template> <!-- north Voyage -->

            <th class="text-center" rowspan="2"> Round <br> Voys Days (ATB) </th>
            <th class="text-center" rowspan="2"> Round <br> Voys Days (ATD) </th>

            <th class="text-center" colspan="3">Export</th>
            <th class="text-center" colspan="3">Import</th>
            <th class="text-center" rowspan="2">TTL <br> LDN Tues</th>
            <th class="text-center" rowspan="2">TTL <br> MTY Tues</th>
            <th class="text-center" rowspan="2">Total <br> Tues</th>
          </tr>

          <tr
            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
            <th class="text-center">LDN</th>
            <th class="text-center">MTY</th>
            <th class="text-center">TTL</th>
            <th class="text-center">LDN</th>
            <th class="text-center">MTY</th>
            <th class="text-center">TTL</th>
          </tr>

        </thead>

        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr v-for="(serviceReportData, index, count) in serviceReportInfo.roundVoyages" :key="index" class="text-gray-700 dark:text-gray-400">
            <td>{{ count + 1 }}</td>
            <td>
              <nobr>{{ serviceReportData?.vesselName }}</nobr>
            </td>
            <template v-for="(sector, sectorKey) in serviceReportInfo.sectors.south" :key="sectorKey">
              <!-- <pre>
                {{serviceReportData}}
              </pre> -->
              <template v-if="serviceReportData.southVoyage">
                <td>{{ serviceReportData?.southVoyage?.voyage }}</td>
                <td>{{ serviceReportData?.southVoyage?.voyage_schedules.find(schedule => schedule.port_code === "BDCGP")?.actual_berth_date ?
                    moment(serviceReportData?.southVoyage?.voyage_schedules.find(schedule => schedule.port_code === "BDCGP")?.actual_berth_date.substring(0, 10)).format('DD-MM-YYYY')
                  : '---' }}</td>
                <td>{{ serviceReportData?.southVoyage?.voyage_schedules.find(schedule => schedule.port_code === "BDCGP")?.actual_departure_date ?
                    moment(serviceReportData?.southVoyage?.voyage_schedules.find(schedule => schedule.port_code === "BDCGP")?.actual_departure_date.substring(0, 10)).format('DD-MM-YYYY')
                  : '---' }}</td>
              </template>
              <template v-else>
                <td></td>
                <td></td>
                <td></td>
              </template>
            </template> <!-- south voyage -->

            <template v-for="(sector, sectorKey, index) in serviceReportInfo.sectors.north" :key="sectorKey">

              <template v-if="serviceReportData.northVoyage">
                <td class="text-center" v-if="index == 0"> {{serviceReportData?.northVoyage?.voyage}} </td>
                <template v-if="Object.keys(serviceReportInfo.sectors.north).length-1 == index">
                  <td class="text-center">
                  {{ serviceReportData?.northVoyage?.voyage_schedules.find(schedule => schedule.port_code === sectorKey)?.actual_berth_date ?
                      moment(serviceReportData?.northVoyage?.voyage_schedules.find(schedule => schedule.port_code === sectorKey)?.actual_berth_date?.substring(0, 10)).format('DD-MM-YYYY')
                    : '---' }}
                  </td>
                </template>
                <template v-else>
                  <td class="text-center">
                    {{ serviceReportData?.northVoyage?.voyage_schedules.find(schedule => schedule.port_code === sectorKey)?.actual_berth_date ?
                      moment(serviceReportData?.northVoyage?.voyage_schedules.find(schedule => schedule.port_code === sectorKey)?.actual_berth_date?.substring(0, 10)).format('DD-MM-YYYY')
                     : '---' }}
                  </td>
                </template>
              </template>

              <template v-else>
                <td v-if="index == 0"> </td>
                <td> </td>
              </template>

            </template> <!-- north Voyage -->

            <td class="text-center">{{ serviceReportData.roundVoyageDays}}</td>
            <td class="text-center">{{ serviceReportData.roundVoyageDaysAtb}}</td>

            <td class="text-right">{{ serviceReportData.exportLadenTues ?? 0 }}</td>
            <td class="text-right">{{ serviceReportData.exportEmptyTues ?? 0 }}</td>
            <td class="text-right">{{ serviceReportData.exportTtlTues ?? 0 }}</td>

            <td class="text-right">{{ serviceReportData.importLadenTues ?? 0 }}</td>
            <td class="text-right">{{ serviceReportData.importEmptyTues ?? 0 }}</td>
            <td class="text-right">{{ serviceReportData.importTtlTues ?? 0 }}</td>

            <td class="text-right">{{ serviceReportData.grandTotalLadenTues ?? 0 }}</td>
            <td class="text-right">{{ serviceReportData.grandTotalEmptyTues ?? 0 }}</td>
            <td class="text-right">{{ serviceReportData.grandTotalTues ?? 0 }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<style lang="postcss" scoped>
tr,
th,
td {
  border: 1px solid gray;
}
.form-input{
  height: 2.4rem !important;
  font-size: 13px !important;
}

thead tr {
  background-color: #656363;
  color: #fff;
}

th {
  @apply p-1 border-r;
}

td {
  @apply px-1 py-1 text-xs;
}

>>> {
  --vs-controls-color: #374151;
  --vs-border-color: #4b5563;

  --vs-dropdown-bg: #282c34;
  --vs-dropdown-color: #eeeeee;
  --vs-dropdown-option-color: #eeeeee;

  --vs-selected-bg: #664cc3;
  --vs-selected-color: #374151;

  --vs-search-input-color: #4b5563;

  --vs-dropdown-option--active-bg: #664cc3;
  --vs-dropdown-option--active-color: #eeeeee;
}
</style>