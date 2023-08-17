<script setup>
import { onMounted } from '@vue/runtime-core';
import useCustomerAssign from "../../../composables/commercial/useCustomerAssign";
import useVoyage from "../../../composables/operation/useVoyage";
import Title from "../../../services/title";
import {watch,ref} from "vue";
import useCustomer from "../../../composables/commercial/useCustomer";
import useCustomerAllocation from "../../../composables/commercial/useCustomerAllocation";

const { voyages, voyageName, getVoyageName } = useVoyage();
const { customers, getCustomerWithoutPaginate } = useCustomer();
const { voyage, storeCustomerAllocation, voyageCustomers, formParams, slotPartnerListParams, isLoading, getVoyageCustomer, assignSlotPartner } = useCustomerAllocation();

const groupOptions = ['group-1', 'group-2', 'group-3', 'common'];

function fetchOptions(search, loading) {
  getVoyageName(search);
}

function assignDgContainer(dgContainerParams) {

  let data = {
    dg_containers: dgContainerParams,
    voyage_id: formParams.value.voyage_id,
  };
  assignDgContainerGroup(data);
}

const { setTitle } = Title();

setTitle('Customer Allocation - Booking Vs Allocation');

const form = ref([]);
// const form = ref( {
//   customer_id: '',
//   slot_partner_id: '',
//   voyage_id: '',
//   voy_cus_id: '',
//   initial_booking: '',
//   actual_booking: '',
// });

watch(voyage,function (voyageData) {

    voyage?.value?.map(function (voyageData,voyageIndex){
    voyageData?.customers.map(function (voyageCustomer,voyageCustomerIndex){
      if(voyage?.value[voyageIndex].customer_id == voyageCustomer?.id){
        //voyage?.value[voyageIndex].company_name = voyageCustomer?.company_name;
        voyage.value[voyageIndex].company_name = voyageCustomer?.company_name;
      }
    });
  });
}, {deep: true});

onMounted(() => {
  getCustomerWithoutPaginate();
});
</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Booking Vs Allocation </h2>
  </div>
  <div class="px-4 py-3 mb-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <form class="flex items-end justify-between gap-4" @submit.prevent="getVoyageCustomer(formParams)">
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Voyage <span class="text-red-500">*</span></span>
        <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchOptions" v-model="formParams.voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required></v-select>
      </label>

      <!-- Submit button -->
      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
    </form>
  </div>
  <div>
    <form class="flex items-end justify-between gap-4" @submit.prevent="storeCustomerAllocation(voyageCustomers)">
      <div class="w-full overflow-hidden">
        <div class="w-full overflow-x-auto">
          <table class="w-full mb-4 whitespace-no-wrap">
            <thead v-once>
            <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th class="p-1"> CID</th>
              <th class="p-1"> ALLOCATION</th>
              <th class="p-1"> ACTUAL</th>
            </tr>
            </thead>
            <tbody v-if="voyageCustomers?.length" class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
            <tr v-for="(voyageCustomer,index) in voyageCustomers" :key="index" class="text-center text-gray-700 dark:text-gray-400">
              <td>{{ voyageCustomer?.customer_code }}</td>
              <td>
                <input type="number" required v-model="voyageCustomers[index].initial_booking" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" placeholder="Allocation" />
              </td>
              <td>
                <input type="number" required v-model="voyageCustomers[index].actual_booking" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" placeholder="Actual" />
              </td>
            </tr>
            </tbody>
            <tfoot>
            <tr v-if="!voyageCustomers?.length" class="text-center text-gray-700 dark:text-gray-400">
              <td colspan="3">No data found</td>
            </tr>
            </tfoot>
          </table>
        </div>
        <button v-if="voyageCustomers?.length" type="submit" :disabled="isLoading" class="w-full px-4 py-2 mb-5 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
      </div>
    </form>
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