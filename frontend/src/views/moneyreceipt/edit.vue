<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2">
        <span>Update Money Receipt:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ bookingId }}</span>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateMoneyReceipt(receipt, receiptId)">
            <!-- Booking Form -->
          <money-receipt-form v-model:form="receipt" :errors="errors"></money-receipt-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Money Receipt</button>
        </form>
    </div>
</template>
<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import MoneyReceiptForm from "../../components/moneyreceipt/MoneyReceiptForm.vue";
import useMoneyReceipt from "../../composables/moneyreceipt/useMoneyReceipt";
const route = useRoute();
const receiptId = route.params.receiptId;
const { receipt, updateMoneyReceipt, showMoneyReceipt, isLoading, errors } = useMoneyReceipt();

watch(receipt, (value) => {
  if(value?.money_receipt_invoices?.length) {
    value.money_receipt_invoices.forEach((moneyReceipt, index) => {
      // value.money_receipt_invoices[index].invoice_amount = moneyReceipt?.invoice?.amount;
      // value.money_receipt_invoices[index].date = moneyReceipt?.invoice?.issue_date;
      value.money_receipt_invoices[index].is_edit_data = true;
      value.money_receipt_invoices[index].invoice_id = moneyReceipt?.invoice?.id;
      value.money_receipt_invoices[index].invoice_number = moneyReceipt?.invoice?.invoice_number;
      value.money_receipt_invoices[index].currency = moneyReceipt?.invoice?.currency;
      value.money_receipt_invoices[index].exchange_rate = parseFloat(moneyReceipt?.invoice?.exchange_rate).toFixed(2);
      value.money_receipt_invoices[index].date = moneyReceipt?.invoice?.issue_date;
      value.money_receipt_invoices[index].invoice_amount = moneyReceipt?.invoice?.amount;
      value.money_receipt_invoices[index].due_amount = (moneyReceipt?.invoice?.amount - moneyReceipt?.amount).toFixed(2);
      value.money_receipt_invoices[index].amount = parseFloat(moneyReceipt?.amount).toFixed(2);
      value.money_receipt_invoices[index].amount_in_bdt = (moneyReceipt?.amount * moneyReceipt?.invoice?.exchange_rate).toFixed(2);
      value.money_receipt_invoices[index].due_amount_in_bdt = (moneyReceipt?.invoice?.amount * moneyReceipt?.invoice?.exchange_rate - moneyReceipt?.amount * moneyReceipt?.invoice?.exchange_rate).toFixed(2);

    });
  }
});

onMounted(() => {
    showMoneyReceipt(receiptId);
});
</script>