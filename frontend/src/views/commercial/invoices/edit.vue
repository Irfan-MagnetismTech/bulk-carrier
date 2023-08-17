<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
        <span>Update Customer:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ customerId }}</span>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateCustomer(customer, customerId)">
            <!-- Booking Form -->
          <customer-form v-model:form="customer"></customer-form>
            <!-- Submit button -->
        </form>
    </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';

import CustomerForm from '../../../components/commercial/CustomerForm.vue';
import useCustomer from '../../../composables/commercial/useCustomer';

const route = useRoute();
const customerId = route.params.customerId;
const { customer, showCustomer, updateCustomer } = useCustomer();

onMounted(() => {
    showCustomer(customerId);
});
</script>