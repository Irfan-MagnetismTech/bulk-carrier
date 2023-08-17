<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
        <span>Customer Details:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ customerId }}</span>
    </div>
    <!-- Table -->
    <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Field Name</th>
                        <th class="px-4 py-3">Data</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">Customer Code</td>
                        <td class="px-4 py-3 text-sm">{{ customer.customer_code }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="px-4 py-3">Similar Code</td>
                      <td class="px-4 py-3 text-sm">{{ customer.similar_codes }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="px-4 py-3">Customer Name</td>
                      <td class="px-4 py-3 text-sm">{{ customer.customer_name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
  <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
    <span>Agents:</span>
  </div>

  <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">Port Code</th>
          <th class="px-4 py-3">Agent Name</th>
          <th class="px-4 py-3">Billing Name</th>
          <th class="px-4 py-3">Billing Email</th>
          <th class="px-4 py-3">Billing Style</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400" v-for="(agent,index) in customer.agents" :key="index">
          <td class="px-4 py-3 text-sm">{{ agent.port_code }}</td>
          <td class="px-4 py-3 text-sm">{{ agent.agent_name }}</td>
          <td class="px-4 py-3 text-sm">{{ agent.billing_name }}</td>
          <td class="px-4 py-3 text-sm">{{ agent.billing_email }}</td>
          <td class="px-4 py-3 text-sm">{{ agent.billing_style }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
    <span>Contact Person:</span>
  </div>

  <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">Name</th>
          <th class="px-4 py-3">Email</th>
          <th class="px-4 py-3">Mobile</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400" v-for="(contact,index) in customer.contacts" :key="index">
          <td class="px-4 py-3 text-sm">{{ contact.name }}</td>
          <td class="px-4 py-3 text-sm">{{ contact.email }}</td>
          <td class="px-4 py-3 text-sm">{{ contact.mobile }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>

  </div>

</template>
<script setup>
import { onMounted } from '@vue/runtime-core';
import { useRoute } from 'vue-router';

import useCustomer from "../../../composables/commercial/useCustomer";

const route = useRoute();
const customerId = route.params.customerId;
const { customer, showCustomer } = useCustomer();

onMounted(() => {
    showCustomer(customerId);
});
</script>

<style lang="postcss" scoped>
.table, .table th, .table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

</style>