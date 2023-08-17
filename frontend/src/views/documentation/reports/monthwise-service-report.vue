
<script setup>
import {onMounted, ref} from 'vue';
import { useRoute } from 'vue-router';
import moment from 'moment';

import useMonthWiseServiceReport from '../../../composables/documentation/useMonthWiseServiceReport';
import useService from "../../../composables/commercial/useService";
import Title from "../../../services/title";
import useTableExportExcel from "../../../services/tableExportExcel";

const route = useRoute();
const months = ref([]);
const { services, bounds, getServices, serviceGroupByCode } = useService();

const { tableToExcel } = useTableExportExcel();

const { formParams, serviceLoadInfo, monthWiseServiceReport, isLoading } = useMonthWiseServiceReport();

function getServiceBound(e){
  serviceGroupByCode(e.target.value);
}

const { setTitle } = Title();

setTitle('Month Wise Service Report');

onMounted(() => {
  months.value = moment.monthsShort();
  getServices();
});


</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200"> Month Wise Service Report </h2>
    </div>
    <!-- Table -->
    <div class="px-4 py-3 mb-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form class="flex items-end justify-between gap-4" @submit.prevent="monthWiseServiceReport(formParams)">
          <label class="block w-full text-sm">
            <span class="text-gray-700 dark:text-gray-300">Service <span class="custom_required_field">*</span></span>
            <select v-model="formParams.service" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <option value="">--Choose an Option--</option>
              <option v-for="(service,index) in services" :value="service?.code" :key="index">{{ service?.code }}</option>
            </select>
          </label>
          <label class="block w-full text-sm">
            <span class="text-gray-700 dark:text-gray-300">Year</span>
            <select v-model="formParams.year" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <option value="">--Choose an Option--</option>
              <option value="2021">2021</option>
              <option value="2022" selected>2022</option>
              <option value="2023">2023</option>
            </select>
          </label>
            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Month</span>
              <select v-model="formParams.month" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="">--Choose an Option--</option>
                <option v-for="(month,index) in months" :key="index">{{ month }}</option>
              </select>
            </label>

            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
        </form>
    </div>
  <button v-if="Object.keys(serviceLoadInfo).length" type="button" @click="tableToExcel('monthwise-service-report-table','monthwise-service-report')" class="w-1/6 mb-4 flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
    <span>Download Excel</span>
  </button>
    <div class="w-full mb-8 border rounded-lg shadow-xs dark:border-0">
        <div class="w-full mb-2" v-if="Object.keys(serviceLoadInfo)?.length">
            <table class="w-full whitespace-no-wrap">
            <thead>
            <tr style="background-color: #376473" class="font-semibold tracking-wide text-left text-gray-500 uppercase border-b text-xm dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
              <th colspan="11" class="text-center">{{ serviceLoadInfo?.headers?.service_code }}<br>
                Route: {{ serviceLoadInfo?.headers?.service_route }}<br>
              </th>
            </tr>
            </thead>
            </table>
        </div>
           <!--- Table For Export -->
      <div class="w-full mb-2" v-for="(serviceInfo,index) in serviceLoadInfo?.bounds" :key="index">
            <table class="w-full whitespace-no-wrap" id="monthwise-service-report-table">
                <thead>
                    <tr style="background-color: #2ba92b" class="font-semibold tracking-wide text-center text-gray-500 uppercase border-b text-xm dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                      <th colspan="11" class="text-center" v-if="index==='south'">Export</th>
                      <th colspan="11" class="text-center" v-else>Import</th>
                    </tr>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <th class="text-center" rowspan="2">SL</th>
                        <th class="text-center" rowspan="2">Vessel</th>
                        <th class="text-center" rowspan="2">Voyage</th>
                        <th class="text-center" rowspan="2">ETB</th>
                        <th class="text-center" rowspan="2">ETD</th>
                        <th class="text-center" rowspan="2">Laden Boxes</th>
                        <th class="text-center" rowspan="2">Empty Boxes</th>
                        <th class="text-center" rowspan="2">Total Boxes</th>
                        <th class="text-center" rowspan="2">Laden Tues</th>
                        <th class="text-center" rowspan="2">Empty Tues</th>
                        <th class="text-center" colspan="2">Total Tues</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                  <tr v-for="(singleServiceReport,reportIndex) in serviceInfo" class="text-gray-700 dark:text-gray-400">
                    <td>{{ reportIndex+1 }}</td>
                    <td>{{ singleServiceReport?.vessel?.name }}</td>
                    <td>{{ singleServiceReport?.voyage }}</td>
                    <td v-if="index==='south'">{{ singleServiceReport?.voyage_schedule_first_port?.etb_date ? moment(singleServiceReport?.voyage_schedule_first_port?.etb_date).format('DD-MM-YYYY') : null}}</td>
                    <td v-else>{{ singleServiceReport?.voyage_schedule_last_port?.etb_date ? moment(singleServiceReport?.voyage_schedule_last_port?.etb_date).format('DD-MM-YYYY') : null}}</td>

                    <td v-if="index==='south'">{{ singleServiceReport?.voyage_schedule_first_port?.etd_date ? moment(singleServiceReport?.voyage_schedule_first_port?.etd_date).format('DD-MM-YYYY') : null}}</td>
                    <td v-else>{{ singleServiceReport?.voyage_schedule_last_port?.etd_date ? moment(singleServiceReport?.voyage_schedule_last_port?.etd_date).format('DD-MM-YYYY') : null}}</td>
                    <td>{{ singleServiceReport?.totalLadenUnit }}</td>
                    <td>{{ singleServiceReport?.totalEmptyUnit }}</td>
                    <td>{{ singleServiceReport?.totalEmptyUnit }}</td>
                    <td>{{ singleServiceReport?.totalLadenTues }}</td>
                    <td>{{ singleServiceReport?.totalLadenTues }}</td>
                    <td>{{ singleServiceReport?.totalEmptyTues }}</td>
                  </tr>
                </tbody>
            </table>
      </div>
    </div>
</template>
<style lang="postcss" scoped>
tr,th,td{
    border: 1px solid gray;
}

thead tr{
  background-color: #656363;
  color: #fff;
}

th {
    @apply p-1 border-r;
}

td {
    @apply px-3 py-1 text-xs;
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