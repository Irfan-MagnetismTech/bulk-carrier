<script setup>
import { onMounted } from '@vue/runtime-core';
import useCustomerLedgerReport from '../../composables/commercial/useCustomerLedgerReport';

import useVoyage from "../../composables/operation/useVoyage";
import Title from "../../services/title";
import {watch} from "vue";
import useCustomer from "../../composables/commercial/useCustomer";
import useTableExportExcel from "../../services/tableExportExcel";

const { voyages, voyageName, getVoyageName } = useVoyage();
const { customers, getCustomerWithoutPaginate, customerCodeName, getCustomerByNameOrCode } = useCustomer();
const { tableToExcel } = useTableExportExcel();

const { formParams, customerLedgers, getCustomerLedger, isLoading } = useCustomerLedgerReport();

function fetchOptions(search, loading) {
  getVoyageName(search);
}

function fetchCustomer(search, loading){
  getCustomerByNameOrCode(search);
}

const { setTitle } = Title();

setTitle('Customer Ledger');


</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Customer Ledger </h2>
  </div>
  <div class="px-4 py-3 mb-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <form class="flex items-end justify-between gap-4" @submit.prevent="getCustomerLedger(formParams)">
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Customer <span class="custom_required_field">*</span></span>
        <v-select placeholder="--Choose an option--" :options="customerCodeName" @search="fetchCustomer" v-model="formParams.customer_id" :reduce="customerCodeName => customerCodeName.id" label="code_name"></v-select>
        <input type="hidden" v-model="formParams.customer_id" required>
      </label>
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">From Date</span>
        <input type="date" v-model="formParams.from_date" class="block w-full px-4 py-2 mt-1 text-sm bg-white border border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray">
      </label>
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Till Date</span>
        <input type="date" v-model="formParams.till_date" class="block w-full px-4 py-2 mt-1 text-sm bg-white border border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700 focus:border-blue-500 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray">
      </label>
      <!-- Submit button -->
      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
    </form>
  </div>
  <button v-if="Object.keys(customerLedgers).length" type="button" @click="tableToExcel('customer-ledger-table','customer-ledger')" class="w-1/6 mb-2 flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
    <span>Download Excel</span>
  </button>
  <div v-if="Object.keys(customerLedgers).length">
    <div class="w-full overflow-hidden">
        <div class="w-full overflow-x-auto">
            <div class="px-1 py-1 bg-green-500 rounded-sm text-center">
              <h5 class="text-white"> <strong> Customer Ledger </strong> </h5>
            </div>
          <table class="w-full mb-8 whitespace-no-wrap" id="customer-ledger-table">
            <thead v-once>
                <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                  <th class="p-1"> Date </th>
                  <th class="p-1"> Particulars </th>
                  <th class="p-1"> Dr </th>
                  <th class="p-1"> Cr </th>
                  <th class="p-1"> Balance </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <tr>
                  <td></td>
                  <td> <strong> Opening Balance </strong> </td>
                  <td>{{ customerLedgers.opening_dr }}</td>
                  <td>{{ customerLedgers.opening_cr }}</td>
                  <td></td>
                </tr>
                <tr class="text-center text-gray-700 dark:text-gray-400" v-for="(ledger, index) in customerLedgers.ledgers" :key="index">
                  <td>{{ ledger.date }}</td>
                  <td>{{ ledger.ledgerParticular }}</td>
                  <td>{{ ledger.dr }}</td>
                  <td>{{ ledger.cr }}</td>
                  <td>{{ ledger.balance }}</td>
                </tr>
                <tr>
                  <td></td>
                  <td> <strong> Closing Balance </strong> </td>
                  <td>{{ customerLedgers.closing_dr }}</td>
                  <td>{{ customerLedgers.closing_cr }}</td>
                  <td></td>
                </tr>
            </tbody>
          </table>
        </div>
      </div>
  </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
  .tab {
    @apply p-2.5 text-xs;
  }
  thead tr {
    @apply font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  th {
    @apply tab text-center;
  }
  tbody {
    @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
  }
  tbody tr {
    @apply text-gray-700 dark:text-gray-400;
  }
  tbody tr td {
    @apply tab text-center;
  }
  tfoot td {
    @apply tab text-center;
  }

  table, th,td{
    @apply border-gray-400;
  }
}

.input-group {
  @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
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
table, th,td{
  @apply border border-collapse;
}

.slot-partner-table-border-none{
  border-bottom: 1px solid white;
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