<script setup>
import MoneyReceiptForm from '../../../components/revenue/money-receipt/MoneyReceiptForm.vue';
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useMoneyReceipt from "../../../composables/revenue/useMoneyReceipt.js";

import { useRoute } from 'vue-router';

const { moneyReceipt, showMoneyReceipt, updateMoneyReceipt } = useMoneyReceipt();
const { setTitle } = Title();
const route = useRoute();

const moneyReceiptId = route.params.moneyReceiptId;
const formType = 'edit';

setTitle('Edit Bill');

onMounted(() => {
    showMoneyReceipt(moneyReceiptId);
});

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Money Receipt</h2>
        <router-link :to="{ name: 'revenue.money-receipt.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateMoneyReceipt(moneyReceipt)">
            <money-receipt-form v-model:moneyReceipt="moneyReceipt" v-model:moneyReceiptId="moneyReceiptId" v-model:formType="formType" :errors="errors"></money-receipt-form>
        </form>
    </div>
</template>
