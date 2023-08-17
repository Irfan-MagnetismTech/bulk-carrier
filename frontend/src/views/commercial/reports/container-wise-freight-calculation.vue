<script setup>
import {onMounted, watchEffect, watch, ref} from 'vue';
import { useRoute } from 'vue-router';
import moment from 'moment';

import useCustomer from "../../../composables/commercial/useCustomer";
import Title from "../../../services/title";
import useVoyage from "../../../composables/operation/useVoyage";
import useContainerWiseFreightCalculationReport from "../../../composables/commercial/useContainerWiseFreightCalculationReport";
import useTableExportExcel from "../../../services/tableExportExcel";

const route = useRoute();

const { customers, getCustomerWithoutPaginate } = useCustomer();
const { voyageName, getVoyageName, voyageCustomers, getVoyageCustomer } = useVoyage();
const { customerCodeName, getCustomerByNameOrCode } = useCustomer();
const { formParams, freightCalculation, voyages, getFreightCalculation, isLoading } = useContainerWiseFreightCalculationReport();
const { tableToExcel } = useTableExportExcel();

const { setTitle } = Title();

setTitle('Container Wise Freight Calculation');

let totalAmount = ref(0);

function fetchOptions(search) {
  getVoyageName(search);
}


function fetchVoyages(search, loading) {
  getVoyageName(search);
}

function fetchCustomer(search, loading){
  getCustomerByNameOrCode(search);
}

watch(formParams, (value) => {
  getVoyageCustomer(value.voyage_id);
}, {deep: true});

// sum freightCalculation?.containers amount
// watch(() => freightCalculation?.value?.containers, (containers) => {
//   if (containers) {
//     totalAmount = containers.reduce((total, container) => total + container.amount, 0);
//   }
//   //alert(totalAmount);
// });

onMounted(() => {
  watchEffect(() => {

    if(formParams.value.voyage_no){
      formParams.value.voyage_id = formParams.value.voyage_no.id;
    }
    if(formParams.value.customer_codes_name){
      formParams.value.customer_id = formParams.value.customer_codes_name.id;
    }
  });
});


</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Container Wise Freight Calculation</h2>
    </div>
    <!-- Table -->
    <div class="px-4 py-3 mb-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form class="flex items-end justify-between gap-4" @submit.prevent="getFreightCalculation(formParams)">


          <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Voyage No <span class="text-red-500">*</span></span>
              <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchOptions" v-model="formParams.voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required>
                <template #search="{attributes, events}">
                  <input class="vs__search" :required="!formParams.voyage_id" v-bind="attributes" v-on="events"/>
                </template>
              </v-select>
            </label>

            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Customer Code <span class="text-red-500">*</span></span>
                <v-select :options="voyageCustomers" placeholder="--Choose an option--" v-model="formParams.customer_id" :reduce="voyageCustomers => voyageCustomers.customer_id" label="customer_code" required>
                  <input class="vs__search" :required="!formParams.customer_id" v-bind="attributes" v-on="events"/>
                </v-select>
            </label>
            
            <!-- <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Voyage No</span>
                 <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchVoyages" v-model="formParams.voyage_no" label="voyage" required></v-select>
                 <input type="hidden" v-model="formParams.voyage_id">
            </label>

            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Customer Code</span>
              <v-select placeholder="--Choose an option--" :options="customerCodeName" @search="fetchCustomer" v-model="formParams.customer_codes_name" label="code_name"></v-select>
              <input type="hidden" v-model="formParams.customer_id">
            </label> -->

            <!-- Submit button -->
            <button type="submit" :disabled="isLoading"
                class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
        </form>
    </div>
    <div class="w-full mb-2 overflow-hidden border rounded-lg shadow-xs dark:border-0">
        <h1 class="py-1 font-bold text-center text-white bg-green-500">Voyage & Customer Information</h1>
        <div class="w-full px-2 py-2">
            <table class="w-full whitespace-no-wrap">
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr v-if="freightCalculation?.voyage">
                      <td><strong>Voyage:</strong> {{ freightCalculation?.voyage }}</td>
                      <td><strong>Customer Code:</strong> {{ freightCalculation?.customer_code }}</td>
                      <td><strong>Customer Name:</strong> {{ freightCalculation?.customer_name }}</td>
                      <td><strong>Total Container:</strong> {{ freightCalculation?.containers?.length }}</td>
                      <td><strong>Total Amount:</strong> {{ (freightCalculation?.containers.reduce((total, container) => total + container.amount, 0)).toFixed(2) }}</td>
                    </tr>
                    <tr v-else>
                      <td colspan="3">No data found</td>
                    </tr>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </div>
  <button v-if="Object.keys(freightCalculation)?.length" type="button" @click="tableToExcel('container-wise-freight-calculation-table','container-wise-freight-calculation')" class="w-1/6 mb-2 flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
    <span>Download Excel</span>
  </button>
  <div class="w-full mb-8 overflow-hidden border rounded-lg shadow-xs dark:border-0">
    <h1 class="py-1 font-bold text-center text-white bg-green-500">Freight Calculation</h1>
    <div class="w-full">
      <table class="w-full whitespace-no-wrap" id="container-wise-freight-calculation-table">
        <thead>
        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
          <td>SL</td>
          <td>Container No</td>
          <td>Amount</td>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr v-if="freightCalculation?.containers" v-for="(container,index) in freightCalculation?.containers">
            <td>{{ index+1 }}</td>
            <td>{{ container?.container_no }}</td>
            <td>{{ container?.amount.toFixed(2) }}</td>
          </tr>
          <tr v-if="freightCalculation?.containers?.length" class="py-1 font-bold text-right text-white bg-green-500">
            <td colspan="2" style="text-align: right;">Total Amount</td>
            <td> {{ freightCalculation?.total_amount ?? 0.00 }} </td>
          </tr>  
        </tbody>
        <tfoot v-if="!freightCalculation?.containers?.length" class="bg-white dark:bg-gray-800">
                <tr v-if="isLoading">
                  <td colspan="3" style="text-align: center">Loading...</td>
                </tr>
                <tr v-else-if="!freightCalculation?.containers?.length">
                  <td colspan="3" class="text-center">No container found.</td>
                </tr>
        </tfoot>
      </table>
    </div>
  </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
    th {
        @apply p-10 border-r text-center;
    }

  thead td{
    text-transform: capitalize;
  }

    tbody td,
    thead td,
    thead th {
        @apply px-1 py-1 text-xs border-r text-center;
        /*font-size: 10px !important;*/
    }
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