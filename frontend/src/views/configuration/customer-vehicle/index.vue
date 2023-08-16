<script setup>
import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useCustomerVehicle from "../../../composables/configuration/useCustomerVehicle";
import Title from "../../../services/title";
import { ref, watch } from "vue";
import { useFuse } from "@vueuse/integrations/useFuse";
import useCustomer from "../../../composables/configuration/useCustomer.js";
import useTableExportExcel from "../../../services/tableExportExcel";


const { customers, searchCustomerByCode, customer } = useCustomer();
const { customerVehicles, getCustomerVehicles, deleteCustomerVehicle, isLoading } = useCustomerVehicle();
const { tableToExcel } = useTableExportExcel();

const { setTitle } = Title();

setTitle('Customer Vehicles');

const formParams = ref({});

watch(() => formParams.value.customer, (value) => {
        formParams.value.customer_id = value?.id;
        formParams.value.customer_name = value?.name;
});

function fetchCustomer(query, loading) {
    searchCustomerByCode(query, loading);
    loading(true)
}

const fetchCustomerVehicles = () => {
  console.log(formParams.value.customer_id)
  getCustomerVehicles(formParams.value.customer_id)
}
</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Customer Vehicles</h2>
    <router-link :to="{ name: 'configuration.customer-vehicle.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
    </router-link>
  </div>
  <!-- Table -->
  <div class="w-full overflow-hidden">
    <div class="w-full overflow-x-auto">
      <div class="input-group mb-5">
                <label class="label-group">
                    <span class="label-item-title">Customer Code <span class="required-style text-red-600">*</span></span>
                    <v-select :options="customers" placeholder="--Choose an option--" @search="fetchCustomer"  v-model="formParams.customer" label="code" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                    <input type="hidden" required v-model="formParams.customer_id" class="label-item-input" name="customer_id" :id="'customer_id'" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Customer Name</span>
                    <input type="text" readonly v-model="formParams.customer_name" class="bg-gray-100 label-item-input" name="customer_id" :id="'customer_id'" />
                </label>
                <label class="label-group flex">
                  <button type="button" @click="fetchCustomerVehicles" :disabled="isLoading" class="px-4 py-2 mt-5 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">Search</button>
                
                  <button v-if="customerVehicles.length > 0" type="button" @click="tableToExcel('all-vehicles', `Customer Vehicles`)" class="ml-2 px-2 py-2 mt-5 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
                    <span>Download Excel</span>
                  </button>
                </label>
        </div>
        <table class="w-full whitespace-no-wrap hidden" id="all-vehicles">
          <thead v-once>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th class="px-4 py-3">SL. </th>
<!--              <th class="px-4 py-3">Vehicle Type</th>-->
              <th class="px-4 py-3">Vehicle Number</th>
              <th class="px-4 py-3">Responsible Person</th>
              <th class="px-4 py-3">Responsible Person Mobile</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
            <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(customerVehicle,index) in customerVehicles" :key="customerVehicle.id">
              <td class="px-4 py-3 text-sm">{{ index + 1 }}</td>
<!--              <td class="px-4 py-3 text-sm">{{ customerVehicle.vehicle_type }}</td>-->
              <td class="px-4 py-3 text-sm">{{ customerVehicle.vehicle_number }}</td>
              <td class="px-4 py-3 text-sm">{{ customerVehicle.assigned_person }}</td>
              <td class="px-4 py-3 text-sm">{{ customerVehicle.assigned_person_contact }}</td>
              
            </tr>
          </tbody>
          <tfoot v-if="!customerVehicles?.length" class="bg-white dark:bg-gray-800">
            <tr v-if="isLoading">
              <td colspan="7">Loading...</td>
            </tr>
            <tr v-else-if="!customerVehicles?.length">
              <td colspan="7">No Data found.</td>
            </tr>
          </tfoot>
        </table>
      <table class="w-full whitespace-no-wrap" >
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">SL. </th>
<!--          <th class="px-4 py-3">Vehicle Type</th>-->
          <th class="px-4 py-3">Vehicle Number</th>
          <th class="px-4 py-3">Responsible Person</th>
          <th class="px-4 py-3">Responsible Person Mobile</th>
          <th class="px-4 py-3 text-center">Actions</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(customerVehicle,index) in customerVehicles" :key="customerVehicle.id">
          <td class="px-4 py-3 text-sm">{{ index + 1 }}</td>
<!--          <td class="px-4 py-3 text-sm">{{ customerVehicle.vehicle_type }}</td>-->
          <td class="px-4 py-3 text-sm">{{ customerVehicle.vehicle_number }}</td>
          <td class="px-4 py-3 text-sm">{{ customerVehicle.assigned_person }}</td>
          <td class="px-4 py-3 text-sm">{{ customerVehicle.assigned_person_contact }}</td>
          <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
            <action-button :action="'edit'" :to="{ name: 'configuration.customer-vehicle.edit', params: { customerVehicleId: customerVehicle.id } }"></action-button>
            <action-button :action="'show'" :to="{ name: 'configuration.customer-vehicle.show', params: { customerVehicleId: customerVehicle.id } }"></action-button>
            <action-button @click="deleteCustomerVehicle(customerVehicle.id)" :action="'delete'"></action-button>
          </td>
        </tr>
        </tbody>
        <tfoot v-if="!customerVehicles?.length" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="7">Loading...</td>
        </tr>
        <tr v-else-if="!customerVehicles?.length">
          <td colspan="7">No Data found.</td>
        </tr>
        </tfoot>
      </table>
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
.search-result {
  @apply px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-300;
}
.search {
  @apply float-right  pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
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