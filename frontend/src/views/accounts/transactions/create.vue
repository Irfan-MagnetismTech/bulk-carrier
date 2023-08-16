<script setup>
import TransactionForm from '../../../components/accounts/TransactionForm.vue';
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useTransaction from '../../../composables/accounts/useTransaction';

const { transaction, transactions, storeTransaction, bgColor, isLoading, errors } = useTransaction();
const { setTitle } = Title();

setTitle('Create Voucher');

function handleColorSelected(color) {
    bgColor.value = color;
}

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-2 sm:flex-row">
        <h4 class="text-2xl font-semibold text-gray-700 dark:text-gray-200"> Create Voucher </h4>
        <router-link :to="{ name: 'accounts.transactions.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61]  border border-transparent rounded-lg active:bg-[#0F6B61]  hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800" :style="{ 'background-color': bgColor }">
        <form @submit.prevent="storeTransaction(transaction)">
            <transaction-form @bgColor="handleColorSelected" v-model:form="transaction" :errors="errors"></transaction-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading"
                    class="flex button_pm_color items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 border-transparent rounded-lg fon2t-medium hover:bg-teal-700 focus:outline-none focus:shadow-outline-purple">
                Submit
            </button>
        </form>
    </div>
</template>
