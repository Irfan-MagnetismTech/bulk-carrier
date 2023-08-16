<script setup>
import { ref, watch } from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../../services/title";
import useMaterial from "../../../../composables/scm/useMaterial";
import useTableExportExcel from "../../../../services/tableExportExcel";
import useRevenueReport from "../../../../composables/revenue/useRevenueReport";
import moment from 'moment';
import useHelper from "../../../../composables/useHelper";
import useCustomer from "../../../../composables/configuration/useCustomer.js";
const { customers, searchCustomerByCode, customer } = useCustomer();

const { setTitle } = Title();
const { materials, searchMaterial } = useMaterial();
const { tableToExcel } = useTableExportExcel();
const { salesData, getCustomerReport } = useRevenueReport();
const { numberFormat } = useHelper();

const shifts = ["A", "B"];
setTitle('Customer Reports');

const formParams = ref({
    shift: "",
    duration: "",
    from_date: "",
    till_date: "",
    customer_id: ""
});


const fetchSaleReport = (form) => {
    if(form.customer) {
      delete form.customer
    }
    getCustomerReport(form)
}

function fetchCustomer(query, loading) {
    searchCustomerByCode(query, loading);
    loading(true)
}

watch(() => formParams.value.customer, (value) => {
    formParams.value.customer_id = value?.id;
    formParams.value.customer_name = value?.name;
});
</script>


<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Customer Report</h2>
    </div>
    <div class="px-4 py-3 mb-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
      <form class="flex items-end justify-between gap-1" @submit.prevent="fetchSaleReport(formParams)">

        <label class="block w-full text-sm">
            <span class="label-item-title">Customer Code </span>
            <v-select :options="customers" placeholder="--Choose an option--" @search="fetchCustomer"  v-model="formParams.customer" label="code" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
            <input type="hidden" v-model="formParams.customer_id" class="label-item-input" name="customer_id" :id="'customer_id'" />
        </label>
        <label class="block w-full text-sm">
            <span class="label-item-title">Customer Name </span>
            <input type="text" readonly v-model="formParams.customer_name" class="bg-gray-100 label-item-input" name="customer_id" :id="'customer_id'" />

        </label>

        <label class="block w-full text-sm">
          <span class="text-gray-700 dark:text-gray-300">Shift</span>
          <select v-model="formParams.shift" class="block w-full mt-1 label-item-input text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled selected>--Choose an Option--</option>
            <option v-for="(shift, index) in shifts" :key="index" :value="shift">{{ shift }}</option>
          </select>
        </label>

        <label class="block w-full text-sm">
          <span class="text-gray-700 dark:text-gray-300">Duration<span class="text-red-500">*</span></span>
          <select v-model="formParams.duration" required class="block w-full mt-1 label-item-input text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled>--Choose an Option--</option>
            <option value="today" selected>Today</option>
            <option value="7">7 days</option>
            <option value="15">15 days</option>
            <option value="30">30 days</option>
            <option value="custom">Custom</option>
          </select>
        </label>

        <template v-if="formParams.duration === 'custom'">
          <label class="block w-full text-sm">
            <span class="text-gray-700 dark:text-gray-300">Start Date<span class="text-red-500">*</span></span>
            <input type="date" v-model="formParams.from_date" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
          <label class="block w-full text-sm">
            <span class="text-gray-700 dark:text-gray-300">End Date<span class="text-red-500">*</span></span>
            <input type="date" v-model="formParams.till_date" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
        </template>

        <!-- Submit button -->
        <button type="submit" :disabled="isLoading"
          class="flex items-center justify-center px-4 py-2 mt-2 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">Search</button>

        <button v-if="salesData?.totalSales" type="button" @click="tableToExcel('sale-report-table','Customer Report')" class="w-full flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
          <span>Download Excel</span>
        </button>
      </form>

      <table v-if="salesData?.totalSales != ''" class="my-5 w-full" id="sale-report-table">
        <thead>
          <tr>
            <td class="!border-0 !text-center py-3 font-semibold text-lg" colspan="2">
              Customer Credit Report: 
              <span v-if="formParams.duration == 'custom' && formParams.from_date != '' && formParams.till_date != ''">

                {{ moment(formParams.from_date).format('DD/MM/YYYY') }} - {{ moment(formParams.till_date).format('DD/MM/YYYY') }}
              </span>
              <span v-if="formParams.duration != 'custom'">For {{ formParams.duration }} day/s</span>
            </td>
          </tr>
          <tr>
            <td class="!text-center font-semibold">Customer</td>
            <td class="!text-center font-semibold">Amount</td>
          </tr>
        </thead>
        <tbody>
          <template v-for="(sale, key, index) in salesData?.totalSales" :key="index" >
            <tr class="hover:bg-gray-200">
              <td class="!text-left">{{ sale?.customer_name }}</td>
              <td> {{ numberFormat(sale?.total_amount) }} </td>
            </tr>
          </template>
        </tbody>
        <tr v-if="salesData?.grandTotal">
            <td class="!text-right pr-5 font-semibold">Grand Total</td>
            
            <td>
              <strong>{{ numberFormat(salesData?.grandTotal) }}</strong>
            </td>
        </tr>
      </table>


    </div>
    
</template>
<style lang="postcss" scoped>
table, tr, th, td {
  @apply border border-collapse
}
td {
  @apply p-1
}
th {
  @apply py-2
}
td {
  @apply text-right
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
</style>