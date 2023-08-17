<script setup>
import {onMounted, ref, watch} from 'vue';
import DebitCreditNoteForm from "../../../components/commercial/DebitCreditNoteForm.vue";
import useDebitCreditNote from "../../../composables/commercial/useDebitCreditNote";
import moment from 'moment';
import Title from "../../../services/title";
import useInvoice from "../../../composables/commercial/useInvoice";
import {useRoute} from "vue-router";
const { invoice, showInvoice, isLoading } = useInvoice();
const route = useRoute();
const invoiceId = route.params.invoiceId;

const { setTitle } = Title();
setTitle('Create Debit Or Credit Note');

onMounted(() => {
    showInvoice(invoiceId);
});

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Create Debit/Credit Note</h2>
        <router-link :to="{ name: 'commercial.invoice.list' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
  <div class="px-4 py-3 mb-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 text-gray-700 dark:text-gray-300">Invoice Info</legend>
      <div class="grid grid-cols-2 text-sm">
        <div>
          <span><strong>Invoice No: </strong> {{ invoice?.invoice_number }}</span><br>
          <span><strong>Issue Date: </strong> {{ moment(invoice?.issue_date).format('DD-MM-YYYY') }}</span><br>
          <span><strong>Voyage: </strong> {{ invoice?.voyage?.voyage }}</span>
        </div>
        <div>
          <span><strong>Customer Name: </strong> {{ invoice?.customer?.company_name }}</span><br>
          <span><strong>Customer Code: </strong> {{ invoice?.customer?.customer_code }}</span><br>
          <span><strong>Address: </strong> {{ invoice?.customer?.postal_address }}</span>
        </div>
      </div>
    </fieldset>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
          <!-- Booking Form -->
        <debit-credit-note-form v-model:form="invoice" v-model:invoiceId="invoiceId"></debit-credit-note-form>
          <!-- Submit button -->
    </div>
</template>