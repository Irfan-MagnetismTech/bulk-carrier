<script setup>
import { onMounted } from '@vue/runtime-core';
import useVoyage from '../../../composables/operation/useVoyage';
import {ref} from "vue";
import useInvoice from "../../../composables/commercial/useInvoice";

const { voyages, isLoading, getVoyages } = useVoyage();
const { invoices, formParams, getInvoices, downloadInvoice } = useInvoice();

const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  openTab.value = tabNumber;
}

function downloadCustomerInvoice(invoiceId) {
  downloadInvoice(invoiceId);
}

onMounted(() => {
  getVoyages();
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Advance Invoice List</h2>
      <router-link :to="{ name: 'commercial.advance.invoice.create' }" title="Advance Create Invoice" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
      </router-link>
    </div>
  <div class="px-2 py-2 mb-5 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <form @submit.prevent="getInvoices(formParams)" class="flex items-end justify-between gap-4">
      <!-- Booking Form -->
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Voyage No</span>
        <v-select :options="voyages" placeholder="--Choose an option--" v-model="formParams.voyage_id" :reduce="voyage => voyage.id" label="voyage" required></v-select>
      </label>
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Type</span>
        <select v-model="formParams.type" id="" class="block w-full mt-1 text-sm rounded dark:text-gray-300 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="">Select</option>
          <option value="advance">Advance</option>
          <option value="final">Final</option>
        </select>
      </label>
      <!-- Submit button -->
      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
    </form>
  </div>
  <div class="">
    <div class="w-full overflow-hidden">
      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">Sl</th>
            <th class="px-4 py-3">Customer Name -Code</th>
            <th class="px-4 py-3">Contact No</th>
            <th class="px-4 py-3">Mailing Address</th>
            <th class="px-4 py-3">Currency</th>
            <th class="px-4 py-3">Prepaid Amount</th>
            <th class="px-4 py-3">Action</th>
            <th class="px-4 py-3">Mail Status</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
            <tr v-for="(invoice, index) in invoices" :key="invoice.id" class="text-gray-700 text-center dark:text-gray-400">
              <td class="px-4 py-3 text-sm">{{ index + 1 }}</td>
              <td class="px-4 py-3 text-sm">{{ invoice?.customer?.customer_name }}</td>
              <td class="px-4 py-3 text-sm">{{ invoice?.customer?.phone ?? '---' }}</td>
              <td class="px-4 py-3 text-sm">{{ invoice?.customer?.customer_general_email ?? '---' }}</td>
              <td class="px-4 py-3 text-sm uppercase">{{ invoice?.currency ?? '---' }}</td>
              <td class="px-4 py-3 text-sm">{{ invoice?.amount ?? '0.00' }}</td>
              <td class="flex items-center justify-center px-4 py-3 space-x-2 text-sm text-center text-gray-600">
                <router-link :to="{ name: '' }" title="Edit Invoice">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </router-link>
                <svg @click="downloadCustomerInvoice(invoice.id)" xmlns="http://www.w3.org/2000/svg" title="Download Invoice" class="h-6 w-6 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                </svg>
              </td>
              <td>
                <span class="text-red-500">Pending</span>
              </td>
            </tr>
            <tr v-if="invoices?.length && isLoading">
              <td colspan="8">Loading...</td>
            </tr>
          <tr v-if="!invoices?.length">
            <td colspan="8">No Invoice Found</td>
          </tr>
          </tbody>
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
</style>