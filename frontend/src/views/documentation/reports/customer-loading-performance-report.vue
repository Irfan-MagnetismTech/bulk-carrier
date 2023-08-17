
<script setup>
import {onMounted, watch} from 'vue';
import { useRoute } from 'vue-router';
import moment from 'moment';

import useParticularCustomerReport from '../../../composables/documentation/useParticularCustomerReport';
import useCustomer from "../../../composables/commercial/useCustomer";
import useService from "../../../composables/commercial/useService";
import useVessel from "../../../composables/dataencoding/useVessel";
import useVoyage from "../../../composables/operation/useVoyage";
import useCustomerLoadingPerformanceReport from "../../../composables/documentation/useCustomerLoadingPerformanceReport";
import Title from "../../../services/title";
import useTableExportExcel from "../../../services/tableExportExcel";

const route = useRoute();

const { tableToExcel } = useTableExportExcel();
const { customers, getCustomerWithoutPaginate } = useCustomer();
const { services, bounds, getServices, serviceGroupByCode } = useService();
const { vessels, vesselName, getVesselName } = useVessel();
const { voyages, voyageName, getVoyageName } = useVoyage();



const { formParams, performanceReportInfo, getCustomerLoadingPerformanceReport, isLoading } = useCustomerLoadingPerformanceReport();

function getServiceBound(e){
  serviceGroupByCode(e.target.value);
}

const { setTitle } = Title();

setTitle('Customer Loading Performance');

onMounted(() => {
  getCustomerWithoutPaginate();
  getServices();
  getVesselName();
  getVoyageName();
  //particularCustomerReport();
});


</script>
<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200"> Booking vs Loading Performance Report </h2>
  </div>
  <!-- Table -->
  <div class="px-4 py-3 mb-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <form class="flex items-end justify-between gap-4" @submit.prevent="getCustomerLoadingPerformanceReport(formParams)">
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Customer Code <span class="custom_required_field">*</span></span>
        <v-select :options="customers" placeholder="--Choose an option--" v-model="formParams.customer_id" :reduce="customer => customer.id" label="customer_code" required></v-select>
      </label>

      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Service <span class="custom_required_field">*</span></span>
        <select v-model="formParams.service_code" @change="getServiceBound($event)" style="min-height: 38px" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="">--Choose an Option--</option>
          <option v-for="(service,index) in services" :value="service.code" :key="index">{{ service.code }}</option>
        </select>
      </label>

      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Bound <span class="custom_required_field">*</span></span>
        <select v-model="formParams.bound" style="min-height: 38px" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="">--Choose an Option--</option>
          <option value="All"> All </option>
          <option v-for="(bound,index) in bounds" :value="bound" :key="index">{{ bound.toUpperCase() }}</option>
        </select>
      </label>

      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Vessel Name</span>
        <v-select :options="vesselName" placeholder="--Choose an option--" v-model="formParams.vessel_name" label="name" required></v-select>
      </label>

      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Voyage</span>
        <v-select :options="voyageName" placeholder="--Choose an option--" v-model="formParams.voyage" :reduce="voyageName => voyageName.voyage" label="voyage" required></v-select>
      </label>

      <!-- Submit button -->
      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
    </form>
  </div>
  <button v-if="Object.keys(performanceReportInfo).length != 0" type="button" @click="tableToExcel('customer-loading-performance-table','customer-loading-performance')" class="w-1/6 mb-4 flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
    <span>Download Excel</span>
  </button>
  <div v-if="Object.keys(performanceReportInfo).length != 0" class="w-full border rounded-lg shadow-xs dark:border-0">
    <div class="w-full">
      <table class="w-full whitespace-no-wrap" id="customer-loading-performance-table">
        <thead>
        <tr>
          <th style="background-color: #eeba96" colspan="18">
            {{performanceReportInfo.customerCode}} - PERFORMANCE IN {{performanceReportInfo.serviceCode}} SERVICE
            ( {{performanceReportInfo.bound == 'south' ? 'Ex' : 'Im'}} - {{performanceReportInfo.boundRoute}} )
            </th>
        </tr>
        <tr style="background-color: #c1d9f6" class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
          <th class="text-center" rowspan="2"> Vessel </th>
          <th class="text-center" rowspan="2"> Voyage </th>
          <th class="text-center" rowspan="2">ETB <br> {{performanceReportInfo?.polCodes?.[0]}} </th>
          <th class="text-center" rowspan="2">ETD <br> {{performanceReportInfo?.polCodes?.[0]}} </th>
          <th class="text-center" rowspan="2">Week No. <br> (Based on ETD {{performanceReportInfo?.polCodes?.[0]}})</th>
          <th class="text-center" rowspan="2">Allocation/<br> Flow Share</th>
          <th class="text-center" rowspan="2">Initial Booking</th>
          <th class="text-center" rowspan="2">Final Booking</th>
          <th class="text-center" rowspan="2">Space Released</th>
          <th class="text-center" colspan="3">Total Laden</th>
          <th class="text-center" colspan="3">Total Empty</th>
          <th class="text-center" colspan="3">Actual Loading</th>
        </tr>
        <tr style="background-color: #c1d9f6" class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
          <th class="text-center">Boxes</th>
          <th class="text-center">Tues</th>
          <th class="text-center">Tons</th>
          <th class="text-center">Boxes</th>
          <th class="text-center">Tues</th>
          <th class="text-center">Tons</th>
          <th class="text-center">Boxes</th>
          <th class="text-center">Tues</th>
          <th class="text-center">Weight</th>
        </tr>
        </thead>

        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400" v-for="(voyage, index, voyageKey) in performanceReportInfo.voyages" :key="voyageKey">
          <td class="text-left">
            <nobr> {{ voyage.vessel.name }} </nobr>
          </td>
          <td>
            <nobr>{{voyage.voyage}}</nobr>
          </td>
          <td> <nobr>{{ moment(voyage.polEtbDate).format('DD-MM-YYYY') }}</nobr> </td>
          <td> <nobr>{{ moment(voyage.polEtdDate).format('DD-MM-YYYY') }}</nobr> </td>
          <td>{{voyage.weekNo}}</td>
          <td>??</td>
          <td>??</td>
          <td>??</td>
          <td>??</td>
          <td>{{voyage.ladenBox}}</td>
          <td>{{voyage.ladenTues}}</td>
          <td>{{voyage.ladenWeight}}</td>
          <td>{{voyage.emptyBox}}</td>
          <td>{{voyage.emptyTues}}</td>
          <td>{{voyage.emptyWeight}}</td>
          <td>{{voyage.totalBox}}</td>
          <td>{{voyage.totalTues}}</td>
          <td>{{voyage.totalWtMt}}</td>
        </tr>
        </tbody>
<!--        <tfoot v-if="!performanceReportInfo?.voyages?.length" class="bg-white dark:bg-gray-800">-->
<!--        <tr v-if="isLoading">-->
<!--          <td colspan="17">Loading...</td>-->
<!--        </tr>-->
<!--        <tr v-else-if="!services?.length">-->
<!--          <td colspan="17">No data found.</td>-->
<!--        </tr>-->
<!--        </tfoot>-->
      </table>
    </div>
  </div>
</template>
<style lang="postcss" scoped>
th {
  @apply p-1 border-r;
}

td {
  @apply px-3 py-1 text-xs border-r;
}
table th,tr,td{
  border: 1px solid darkgray;
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