<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Customers</h2>
        <router-link :to="{ name: 'commercial.customers.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
        </router-link>
    </div>
    <!-- Table -->
    <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">SL. </th>
                        <th class="px-4 py-3">Code </th>
                        <th class="px-4 py-3">Customer Name </th>
                        <th class="px-4 py-3">Mobile</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(customer,index) in customers" :key="customer.id" v-memo>
                  <td class="px-4 py-3 text-sm">{{ index + 1 }}</td>
                  <td class="px-4 py-3 text-sm">{{ customer.customer_code }}</td>
                  <td class="px-4 py-3 text-sm">{{ customer.customer_name }}</td>
                  <td class="px-4 py-3 text-sm">{{ customer.phone }}</td>
                  <td class="flex items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
                    <action-button :action="'edit'" :to="{ name: 'commercial.customers.edit', params: { customerId: customer.id } }"></action-button>
                    <action-button :action="'show'" :to="{ name: 'commercial.customers.show', params: { customerId: customer.id } }"></action-button>
                    <action-button @click="deleteCustomer(customer.id)" :action="'delete'"></action-button>
                  </td>
                </tr>
                <tr v-if="customers.length === 0">
                  <td colspan="5" class="px-4 py-3 text-center text-sm">No data found</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script setup>
import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useCustomer from '../../../composables/commercial/useCustomer';
const { customers, getCustomers, deleteCustomer } = useCustomer();
onMounted(() => {
    getCustomers();
});
</script>