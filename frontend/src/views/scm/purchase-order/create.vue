<script setup>
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';

import Title from "../../../services/title";
import usePurchaseOrder from "../../../composables/scm/usePurchaseOrder.js";
import PurchaseOrderForm from '../../../components/scm/purchase-order/PurchaseOrderForm.vue';
import { useStore } from "vuex";

const store = useStore();
const { materialObject, purchaseOrder, payOrderObject, storePurchaseOrder, defaultVendor, getDefaultVendor  } = usePurchaseOrder();
const units = store.state.erpConfiguration.allUnits;
const { setTitle } = Title();

setTitle('Create Purchase Order');
onMounted(() => {
    getDefaultVendor();
});

function submitForm(purchaseOrder) {
    if(purchaseOrder.hasError == 1) {
        alert('Please fill the form correctly.')
        return false;
    } else {
        storePurchaseOrder(purchaseOrder)
    }
}
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Create Purchase Order</h2>
        <router-link :to="{ name: 'scm.purchase-order.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61]  border border-transparent rounded-lg active:bg-[#0F6B61]  hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="submitForm(purchaseOrder)">
            <purchase-order-form v-model:defaultVendor="defaultVendor" v-model:payOrderModel="payOrderObject" v-model:units="units" v-model:materialModel="materialObject" v-model:purchaseOrder="purchaseOrder" :errors="errors"></purchase-order-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61]  border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B61]  hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">Create Purchase Order</button>
        </form>
    </div>
</template>
