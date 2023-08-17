<script setup>
import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useCustomer from '../../../composables/commercial/useCustomer';
import Title from "../../../services/title";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import {watch, watchEffect, ref} from "vue";
const { customers, getCustomers, customerCodeName, countryName, getCustomerCountryByName, getCustomerByNameOrCode, deleteCustomer, isLoading } = useCustomer();
const { slotPartnerCode, getSlotPartnerByNameOrCode } = useSlotPartner();
import Paginate from '../../../components/utils/paginate.vue';
import useSlotPartner from "../../../composables/dataencoding/useSlotPartner";
import useTableExportExcel from "../../../services/tableExportExcel";

const { tableToExcel } = useTableExportExcel();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();
setTitle('Customers');

// Code for global search starts here
const columns = ["customer_code", "customer_name", "phone"];
const searchKey = useDebouncedRef('', 800);
const table = "customers";

watch(searchKey, newQuery => {
  getCustomers(props.page, columns, searchKey.value, table);
});

function fetchSlotPartner(search, loading) {
  getSlotPartnerByNameOrCode(search);
}

function fetchCustomer(search, loading){
  getCustomerByNameOrCode(search);
}

function fetchCustomerCountry(search, loading){
  getCustomerCountryByName(search);
}

// Code for global search end here

const searchParams = ref({
  similar_codes_name: null,
  similar_codes: '',
  customer_codes_name: null,
  customer_code: '',
  customer_country_name: null,
  country: '',
  customer_company_name: null,
  company_name: '',
  page_view_type: 'Paginate',
});

function clearFormData(){
  searchParams.value = {
    similar_codes_name:  null,
    similar_codes: '',
    customer_codes_name: null,
    customer_code: '',
    customer_country_name: null,
    country: '',
    customer_company_name: null,
    company_name: '',
    page_view_type: 'Paginate',
  };
}

onMounted(() => {
  watchEffect(() => {
    if(searchParams.value.similar_codes_name){
      searchParams.value.similar_codes = searchParams.value.similar_codes_name.code;
    }
    if(searchParams.value.customer_codes_name){
      searchParams.value.customer_code = searchParams.value.customer_codes_name.customer_code;
    }
    if(searchParams.value.customer_country_name){
      searchParams.value.country = searchParams.value.customer_country_name.country;
    }
    if(searchParams.value.customer_company_name){
      searchParams.value.company_name = searchParams.value.customer_company_name.company_name;
    }
    getCustomers(props.page, searchParams.value);
  });
});
</script>

<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Customers</h2>
        <router-link :to="{ name: 'commercial.customers.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
        </router-link>
    </div>

  <div class="w-full flex items-center justify-between mb-2 select-none">
    <fieldset class="w-full grid grid-cols-7 gap-1 px-2 pb-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Search Customer</legend>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Slot Partner</label>
        <v-select placeholder="--Choose an option--" :options="slotPartnerCode" @search="fetchSlotPartner" v-model="searchParams.similar_codes_name" label="code_name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
        <input type="hidden" v-model="searchParams.similar_codes">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Customer</label>
        <v-select placeholder="--Choose an option--" :options="customerCodeName" @search="fetchCustomer" v-model="searchParams.customer_codes_name" label="code_name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
        <input type="hidden" v-model="searchParams.customer_code">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Country</label>
        <v-select placeholder="--Choose an option--" :options="countryName" @search="fetchCustomerCountry" v-model="searchParams.customer_country_name" label="country" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
        <input type="hidden" v-model="searchParams.country">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Legal Name</label>
        <v-select placeholder="--Choose an option--" :options="customerCodeName" @search="fetchCustomer" v-model="searchParams.customer_company_name" label="company_name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
        <input type="hidden" v-model="searchParams.company_name">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Page View</label>
        <select v-model="searchParams.page_view_type" id="" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="Paginate">Paginate</option>
          <option value="All">All</option>
        </select>
      </div>
      <div>
        <label for="">&nbsp;</label>
        <button @click="clearFormData" class="w-full flex items-center justify-center px-2 py-2 mt-1 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Clear</span>
        </button>
      </div>
      <div>
        <label for="">&nbsp;</label>
        <button type="button" @click="tableToExcel('customer-list-table','customer-list')" class="w-full flex items-center justify-center px-2 py-2 mt-1 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-700 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
          <span>Download Excel</span>
        </button>
      </div>
    </fieldset>
  </div>
    <!-- Table -->
    <div class="w-full overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap mb-5" id="customer-list-table">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">SL. </th>
                        <th class="px-4 py-3" data-title="Customer ID">CID</th>
                        <th class="px-4 py-3" data-title="Customer Name">C. Name </th>
                        <th class="px-4 py-3" style="width: 200px">Legal Name </th>
                        <th class="px-4 py-3">Country</th>
                      <th class="px-4 py-3" data-title="Slot Owner Code">S/O Code </th>
                      <th class="px-4 py-3" data-title="Slot partner name">S/P Name </th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(customer,index) in (customers?.data ? customers?.data : customers)" :key="customer.id" v-memo>
                  <td class="px-2 py-3 text-sm" style="font-size: 11px">{{ (customers?.from) ? customers?.from + index : index+1 }}</td>
                  <td class="px-2 py-3 text-sm" style="font-size: 11px">{{ customer?.customer_code }}</td>
                  <td class="px-2 py-3 text-sm" style="font-size: 11px">{{ customer?.customer_name }}</td>
                  <td class="px-2 py-3 text-sm" style="text-align: left;font-size: 11px">{{ customer?.company_name }}</td>
                  <td class="px-2 py-3 text-sm" style="font-size: 11px">{{ customer?.country }}</td>
                  <td class="px-2 py-3 text-sm" style="font-size: 11px">{{ customer?.similar_codes }}</td>
                  <td class="px-2 py-3 text-sm" style="font-size: 11px">{{ customer?.slot_partner?.name }}</td>
                  <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
                    <action-button :action="'edit'" :to="{ name: 'commercial.customers.edit', params: { customerId: customer.id } }"></action-button>
                    <action-button :action="'show'" :to="{ name: 'commercial.customers.show', params: { customerId: customer.id } }"></action-button>
                    <action-button @click="deleteCustomer(customer.id)" :action="'delete'"></action-button>
                    <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: customer.subject_type,subject_id: customer.id } }"></action-button>
                  </td>
                </tr>
                </tbody>
                <tfoot v-if="!(customers?.data ? customers?.data?.length : customers?.length)" class="bg-white dark:bg-gray-800">
                <tr v-if="isLoading">
                  <td colspan="8">Loading...</td>
                </tr>
                <tr v-else-if="!(customers?.data ? customers?.data?.length : customers?.length)">
                  <td colspan="8">No customer found.</td>
                </tr>
                </tfoot>
            </table>
        </div>
      <Paginate :data="customers" to="commercial.customers.index" :page="page"></Paginate>
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
    @apply border border-collapse;
  }
  .paginate-btn {
    @apply px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple;
  }
  .paginate-active {
    @apply text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600;
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

}
</style>