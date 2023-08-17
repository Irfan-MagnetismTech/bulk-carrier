<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
        <span>Money Receipt Details:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ contractId }}</span>
    </div>
    <!-- Table -->
    <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap table">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Field Name</th>
                        <th class="px-4 py-3">Data</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="text-sm">Received Date</td>
                        <td class="text-sm">{{ receipt?.issue_date }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Customer Code</td>
                      <td class="text-sm">{{ receipt?.customer?.customer_code }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Customer Name</td>
                      <td class="text-sm">{{ receipt?.customer?.company_name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
  <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
    <span>Payment Methods:</span>
  </div>
  <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap text-center table">
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">Method</th>
          <th class="px-4 py-3">Amount</th>
          <th class="px-4 py-3">Bank Name</th>
          <th class="px-4 py-3">TRX Ref</th>
          <th class="px-4 py-3">Doc Date</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
           <tr v-for="(method,index) in receipt.money_receipt_methods" :key="index" class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">{{ method?.payment_method }}</td>
            <td class="text-sm">{{ method?.amount }}</td>
            <td class="text-sm">{{ method?.source_name }}</td>
            <td class="text-sm">{{ method?.trx_no }}</td>
            <td class="text-sm">{{ method?.dated }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
    <span>Invoices:</span>
  </div>
  <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap text-center table">
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">Invoice No</th>
          <th class="px-4 py-3">Amount</th>
          <th class="px-4 py-3">Invoice Date</th>
          <th class="px-4 py-3">Collected Amount</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr v-for="(invoice,index) in receipt.money_receipt_invoices" :key="index" class="text-gray-700 dark:text-gray-400">
          <td class="text-sm">{{ invoice?.invoice?.invoice_number }}</td>
          <td class="text-sm">{{ invoice?.invoice?.amount }}</td>
          <td class="text-sm">{{ invoice?.invoice?.issue_date }}</td>
          <td class="text-sm">{{ invoice?.amount }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0 my-6">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap table">
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400">
          <td width="20%" class="text-sm">Remarks</td>
          <td class="text-sm">{{ receipt?.remarks }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap table">
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400">
          <td width="20%" class="text-sm">Attachment</td>
          <td class="text-sm">
<!--            <div>-->
<!--              <a target="_blank" :href="env.BASE_API_URL+'storage/'+contractAttachment?.file_path">{{ contractAttachment?.file_path?.split('/').pop() }}</a>-->
<!--            </div>-->
            <div>
              <a v-if="receipt?.attachment" target="_blank" style="color: blue" :href="env.BASE_API_URL+receipt?.attachment">{{ receipt?.attachment?.split('/').pop() }}</a>
              <span v-else>No attachment available</span>
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once></div>
</template>
<script setup>
import { onMounted } from '@vue/runtime-core';
import { useRoute } from 'vue-router';
import useMoneyReceipt from "../../composables/moneyreceipt/useMoneyReceipt";
import {ref} from "vue";
import Title from "../../services/title";
import env from '../../config/env';

const route = useRoute();
const receiptId = route.params.receiptId;
const { receipt, showMoneyReceipt } = useMoneyReceipt();

const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  openTab.value = tabNumber;
}

const { setTitle } = Title();

setTitle('Money Receipt Details');

onMounted( () => {
    showMoneyReceipt(receiptId);
});
</script>

<style lang="postcss" scoped>
.table, .table th, .table td{
  @apply border border-collapse border-gray-400 text-left pl-3 text-gray-700 font-medium;
}
.text-xs {
  font-size: 0.7rem;
  line-height: 0.7rem;
}
</style>
