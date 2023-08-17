<script setup>
import { onMounted } from '@vue/runtime-core';
import useVoyageJobStatusReport from '../../composables/commercial/useVoyageJobStatusReport';
import useVoyage from "../../composables/operation/useVoyage";
import Title from "../../services/title";
import {watch} from "vue";
import useCustomer from "../../composables/commercial/useCustomer";
import useTableExportExcel from "../../services/tableExportExcel";

const { voyages, voyageName, getVoyageName } = useVoyage();
const { customers, getCustomerWithoutPaginate } = useCustomer();
const { formParams, voyageJobStatus, getVoyageJobStatus, isLoading } = useVoyageJobStatusReport();

const { tableToExcel } = useTableExportExcel();

const groupOptions = ['group-1', 'group-2', 'group-3', 'common'];

function fetchOptions(search, loading) {
  getVoyageName(search);
}

const { setTitle } = Title();

setTitle('Customer Assign');


</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Voyage Job Status </h2>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <form class="flex items-end justify-between gap-4" @submit.prevent="getVoyageJobStatus(formParams)">
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Voyage <span class="text-red-500">*</span></span>
        <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchOptions" v-model="formParams.voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required></v-select>
      </label>

      <!-- Submit button -->
      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
    </form>
  </div>
  <div v-if="Object.keys(voyageJobStatus).length">
    <div class="w-full overflow-hidden">
        <div class="w-full overflow-x-auto">
          <table class="w-full mb-2 whitespace-no-wrap">
            <thead v-once>
                <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                  <th class="p-1"> Voyage </th>
                  <th class="p-1"> Service </th>
                  <th class="p-1"> ETD </th>
                  <th class="p-1"> ETA </th>
                  <th class="p-1"> Final TDR </th>
                  <th class="p-1" colspan="3"> Special Container </th>
                  <th class="p-1"> Exchange Rate </th>
                  <th class="p-1"> As On </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <tr class="text-center text-gray-700 dark:text-gray-400">
                  <td>{{ voyageJobStatus.voyage }}</td>
                  <td>{{ voyageJobStatus.service }}</td>
                  <td> {{ voyageJobStatus.etd_date }} </td>
                  <td> {{ voyageJobStatus.eta_date }} </td>
                  <td>{{ voyageJobStatus.tdr_uploaded }}</td>
                  <td> DG : {{ voyageJobStatus.dg_assigned }}</td>
                  <td> RF : {{ voyageJobStatus.rf_assign }}</td>
                  <td> OG : {{ voyageJobStatus.fr_assign }}</td>
                  <td>{{ voyageJobStatus.exchange_rate ?? " --- " }}</td>
                  <td>{{ voyageJobStatus.reporting_date ?? " --- " }}</td>
                </tr>
            </tbody>
          </table>
        </div>
      </div>
    <button v-if="Object.keys(voyageJobStatus).length" type="button" @click="tableToExcel('voyage-job-status-table','voyage-job-status')" class="w-1/6 mb-2 flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
      <span>Download Excel</span>
    </button>
    <div class="w-full overflow-hidden">
        <div class="w-full overflow-x-auto">
            <div class="px-1 py-1 bg-green-500 rounded-sm text-center">
              <h5 class="text-white"> <strong> Customer Details </strong> </h5>
            </div>
          <table class="w-full mb-8 whitespace-no-wrap" id="voyage-job-status-table">
            <thead v-once>
                <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                  <th class="p-1"> S/O </th>
                  <th class="p-1"> Sector </th>
                  <th class="p-1"> TTL CNT </th>
                  <th class="p-1"> TTL TEU </th>
                  <th class="p-1"> Status </th>
                  <th class="p-1"> Rate Type </th>
                  <th class="p-1"> Customer </th>
                  <th class="p-1"> Contract </th>
                  <th class="p-1"> Invoice </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800" v-if="voyageJobStatus?.voyage_customers?.length">
                <tr class="text-center text-gray-700 dark:text-gray-400" v-for="(customer, index) in voyageJobStatus.voyage_customers" :key="index">
                  <td>{{ customer.slot_partner }}</td>
                  <td>{{ customer.sector }}</td>
                  <td>{{ customer.ttl_containers }}</td>
                  <td>{{ customer.ttl_tues }}</td>
                  <td>{{ customer.load_type }}</td>
                  <td>{{ customer.rate_type }}</td>
                  <td>{{ customer.customer_code }}</td>
                  <td>{{ customer.contract_no }}</td>
                  <td>{{ customer.invoice_no }}</td>
                </tr>
            </tbody>

            <tfoot v-if="!voyageJobStatus?.voyage_customers?.length" class="bg-white dark:bg-gray-800">
              <tr v-if="isLoading">
                <td colspan="9">Loading...</td>
              </tr>
              <tr v-else-if="!voyageJobStatus?.voyage_customers?.length">
                <td colspan="9">Customer Not Assigned.</td>
              </tr>
            </tfoot>

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