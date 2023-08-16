<script setup>
import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useBilling from "../../../composables/revenue/useBilling";
import Title from "../../../services/title";
import { ref, watch } from "vue";
import { useFuse } from "@vueuse/integrations/useFuse";
import moment from 'moment';
import useHelper from '../../../composables/useHelper';
import useCustomer from "../../../composables/configuration/useCustomer.js";

const { bills, getBills, deleteBill, isLoading, downloadBill } = useBilling();
const { numberFormat } = useHelper();
const { setTitle } = Title();
const { customers, searchCustomerByCode, customer } = useCustomer();

setTitle('All Bills');

onMounted(() => {
  getBills();
});

const formParams = ref({});

function fetchCustomer(query, loading) {
  searchCustomerByCode(query, loading);
  loading(true)
}

watch(() => formParams.value.customer, (value) => {
  formParams.value.customer_name = value?.name;
  formParams.value.customer_id = value?.id;
}, { deep: true })
</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Customer Bills</h2>
    <router-link :to="{ name: 'revenue.billing.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
    </router-link>
  </div>
  
<!--  <div class="flex gap-4 mb-8">-->
<!--      <label class="label-group">-->
<!--        <span class="label-item-title">Customer Code <span class="required-style">*</span></span>-->
<!--        <v-select :options="customers" placeholder="&#45;&#45;Choose an option&#45;&#45;" @search="fetchCustomer" v-model="formParams.customer" label="code" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>-->
<!--      </label>-->
<!--      <label class="label-group">-->
<!--        <span class="text-gray-700 dark:text-gray-300">Customer Name<span class="text-red-500">*</span></span>-->
<!--        <input type="text" readonly v-model="formParams.customer_name" required class="bg-gray-100 block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
<!--      </label>-->
<!--      <label class="label-group">-->
<!--        <span class="text-gray-700 dark:text-gray-300">From Date<span class="text-red-500">*</span></span>-->
<!--        <input type="date" v-model="formParams.from_date" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
<!--      </label>-->
<!--      <label class="label-group">-->
<!--        <span class="text-gray-700 dark:text-gray-300">Till Date<span class="text-red-500">*</span></span>-->
<!--        <input type="date" v-model="formParams.till_date" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
<!--      </label>-->
<!--      <button type="button" :disabled="isLoading"-->
<!--          class="flex items-center justify-center px-4 mt-6 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">Search</button>-->
<!--    </div>-->

  <div class="w-full overflow-hidden" >
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">SL. </th>
          <th class="px-4 py-3">Bill No. </th>
          <th class="px-4 py-3">Date</th>
          <th class="px-4 py-3">Customer Name</th>
          <th class="px-4 py-3">Amount</th>
          <th class="px-4 py-3">Due Date</th>
          <th class="px-4 py-3 text-center">Actions</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(bill,index) in bills" :key="bill.id">
          <td class="px-4 py-3 text-sm">{{ index + 1 }}</td>
          <td class="px-4 py-3 text-sm">{{ bill?.bill_no}}</td>
          <td class="px-4 py-3 text-sm">{{ (bill?.bill_date) ? moment(bill?.bill_date).format('DD/MM/YYYY') : '' }}</td>
          <td class="px-4 py-3 text-sm">{{ bill?.customer?.name }}</td>
          <td class="px-4 py-3 text-sm">{{ numberFormat(bill?.amount) }}</td>
          <td class="px-4 py-3 text-sm">{{ (bill?.due_date) ? moment(bill?.due_date).format('DD/MM/YYYY') : '' }}</td>

          <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
            <action-button :action="'edit'" :to="{ name: 'revenue.billing.edit', params: { billId: bill.id } }"></action-button>
            <action-button @click="downloadBill(bill.id)" :action="'print'"></action-button>
            <action-button @click="deleteBill(bill.id)" :action="'delete'"></action-button>

          </td>
        </tr>
        </tbody>
        <tfoot v-if="!bills?.length" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="6">Loading...</td>
        </tr>
        <tr v-else-if="!bills?.length">
          <td colspan="6">No bill found.</td>
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
  @apply block w-full text-sm;
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