<script setup>
import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../components/buttons/ActionButton.vue';
import {ref,watch} from "vue";
import useCustomer from "../../composables/commercial/useCustomer";
import useMoneyReceipt from "../../composables/moneyreceipt/useMoneyReceipt";
import Title from "../../services/title";

const { formParams, receipts, getReceipts, deleteMoneyReceipt, downloadMoneyReceipt, isLoading } = useMoneyReceipt();
const { customers, getCustomerWithoutPaginate } = useCustomer();

const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  openTab.value = tabNumber;
}

function downloadReceipt(receiptId){
  downloadMoneyReceipt(receiptId);
}

const { setTitle } = Title();

setTitle('Money Receipts');

onMounted(() => {
  getCustomerWithoutPaginate();
  getReceipts();
});
</script>
<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Money Receipt List</h2>
    <router-link :to="{ name: 'money.receipt.create' }" title="Create Invoice" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
    </router-link>
  </div>
<!--  <div class="px-2 py-2 mb-5 bg-white rounded-lg shadow-md dark:bg-gray-800">-->
<!--    <form @submit.prevent="getReceipts(formParams)" class="flex items-end justify-between gap-4">-->
<!--      &lt;!&ndash; Booking Form &ndash;&gt;-->
<!--      <label class="block w-full mt-3 text-sm">-->
<!--        <span class="text-gray-700 dark:text-gray-300">Money Receipt No</span>-->
<!--        <v-select :options="customers" placeholder="&#45;&#45;Choose an option&#45;&#45;" v-model="formParams.customer_id" :reduce="customer => customer.id" label="customer_code" required></v-select>-->
<!--      </label>-->
<!--      &lt;!&ndash; Submit button &ndash;&gt;-->
<!--      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>-->
<!--    </form>-->
<!--  </div>-->
  <div class="">
    <div class="w-full overflow-hidden">
      <div class="w-full">
        <table class="w-full mb-10 whitespace-no-wrap">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">Sl</th>
            <th class="px-4 py-3">Received Date</th>
            <th class="px-4 py-3">Customer</th>
            <th class="px-4 py-3">Invoice No</th>
            <th class="px-4 py-3">Collected Amount</th>
            <th class="px-4 py-3">Action</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr v-for="(moneyReceipt, index) in receipts" :key="moneyReceipt.id" class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm">{{ index + 1 }}</td>
            <td class="px-4 py-3 text-sm">{{ moneyReceipt?.issue_date }}</td>
            <td class="px-4 py-3 text-sm">{{ moneyReceipt?.customer?.customer_name }}-{{ moneyReceipt?.customer?.customer_code }}</td>

            <td class="px-4 py-3 text-sm">
              {{ moneyReceipt?.money_receipt_invoices.map(place => place.invoice?.invoice_number).join(', ') }}
            </td>

            <td class="px-4 py-3 text-sm">{{ moneyReceipt?.collected_amount ?? '---' }}</td>

            <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
              <nobr>
                <action-button :action="'download receipt'" @click="downloadReceipt(moneyReceipt?.id)"></action-button>
                <action-button :action="'edit'" :to="{ name: 'money.receipt.edit', params: { receiptId: moneyReceipt.id } }"></action-button>
                <action-button :action="'show'" :to="{ name: 'money.receipt.show', params: { receiptId: moneyReceipt.id } }"></action-button>
                <action-button @click="deleteMoneyReceipt(moneyReceipt.id)" :action="'delete'"></action-button>
                <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: moneyReceipt.subject_type,subject_id: moneyReceipt.id } }"></action-button>
              </nobr>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!receipts?.length" class="bg-white dark:bg-gray-800">
          <tr v-if="isLoading">
            <td colspan="6">Loading...</td>
          </tr>
          <tr v-else-if="!receipts?.length">
            <td colspan="6">No money receipt found.</td>
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
    @apply border border-collapse;
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