<script setup>

import { onMounted, watch } from 'vue';
import Error from "../Error.vue";
import {useRoute} from "vue-router";
import usePort from "../../composables/usePort";
import useExpenditureReport from "../../composables/finance/useExpenditureReport";
import useService from '../../composables/commercial/useService';
import useVessel from '../../composables/dataencoding/useVessel';
import useVoyagePairing from "../../composables/finance/useVoyagePairing";


const { report, formParams, getProfitabilityReport, getRevenuevsExpenseReport, downloadProfitabilityReport, expenseHeads, expenseEntries } = useExpenditureReport();
const route = useRoute();
const pairId = route.params.pairId;
const { ports, portName, getPortsByNameOrCode } = usePort();
const { services, bounds, getServices, serviceGroupById, serviceGroupByCode } = useService();
const { pairList, getPairedVoyageName } = useVoyagePairing();
const { vessels, getVessels } = useVessel();

function fetchOptions(search, loading) {
  getPortsByNameOrCode(search, formParams.value.service_id);
}

function fetchVoyagePairs(search, loading) {
  getPairedVoyageName(search, formParams.value.voyagePair);
}

function getReport(formData)
{
  console.log("formdata", formData)
  // if(!formData.date_from || !formData.date_to)
  // {
  //   alert('You must fill date range!')
  // } else if(formData.service_id || formData.port_name) {
  //   getProfitabilityReport(formData);
  // } else {
  //   alert('You must choose a service or port!')
  // }
  getProfitabilityReport(formData);

}

function downloadReport(formData) {
  downloadProfitabilityReport(formData);
}

onMounted(() => {
  getServices();
  getVessels();

});
</script>
<template>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <!-- <label class="block w-full mt-3 text-sm md:w-3/6">
      <span class="text-gray-700 dark:text-gray-300">Port </span>
      <v-select v-model="formParams.port_name" :id="'port_name' + index" @search="fetchOptions" value="id" :options="portName" :reduce="portName => portName.code" label="code_name" placeholder="Enter Port Code or Name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
    </label> -->
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Service </span>
      <select v-model="formParams.service_id" class="label-item-input">
                <option value>-- Select Service --</option>
                <option v-for="service in services" :value="service.id" :key="service.id">{{ service.code }}</option>
              </select>
    </label>
    <!-- <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Vessel </span>
      <select v-model="formParams.vessel_id" class="label-item-input">
        <option value>-- Select Vessel --</option>
        <option v-for="vessel in vessels.data" :value="vessel.id" :key="vessel.id">{{ vessel.name }}</option>
      </select>
    </label> -->
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Start Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="formParams.date_from" required placeholder="Ex: BES" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">End Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="formParams.date_to" required placeholder="Ex: BES" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class=" w-full mt-3 text-sm md:w-1/6 block">
      <button type="button" class="btn-submit-default md:mt-6" @click="getReport(formParams)">Process</button>
    </label>
    <label class=" w-full mt-3 text-sm md:w-1/6 block">
      <button type="button" class="btn-submit-default md:mt-6" @click="downloadReport(formParams)">Download</button>
    </label>
  </div>

  <div class="overflow-x-auto my-3 py-2 scroll-smooth scroll-show">
    <div v-html="report"></div>
  </div>
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}

.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}

.scroll-show::-webkit-scrollbar {
    width: 1.5rem !important;
    height: 2em !important;
    color: red !important;
    background: red !important;
}

table th{
  text-transform: capitalize;
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