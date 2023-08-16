<script setup>
import BankForm from '../../../components/configuration/bank/BankForm.vue';
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useBank from "../../../composables/configuration/useBank.js";
import { useRoute } from 'vue-router';

const { bank, showBank, updateBank  } = useBank();
const { setTitle } = Title();
const route = useRoute();
const bankId = route.params.bankId;

setTitle('Bank Details');

onMounted(() => {
    showBank(bankId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Banks</h2>
        <router-link :to="{ name: 'configuration.bank.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61]  border border-transparent rounded-lg active:bg-[#0F6B61]  hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Bank Name <span class="required-style">*</span></span>
                <input type="text" v-model="bank.name" class="label-item-input" name="bank_name" :id="'bank_name'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Bank Code<span class="required-style">*</span></span>
                <input type="text" v-model="bank.code" class="label-item-input" name="bank_code" :id="'bank_code'" />
            </label>
        </div>
    </div>
    <legend class="mt-5">
            <strong>Branches</strong>
            <table class="w-full mb-12">
                <tr class="mb-3" v-for="(item, index) in bank.branches" :key="index">
                    
                    <td class="font-semibold">
                        <span class="label-item-title">Name<span class="required-style">*</span></span>
                        <input type="text" readonly v-model="bank.branches[index].name" class="bg-gray-100 label-item-input" name="opening_balance" :id="'opening_balance'" />
                    </td>
                    <td class="font-semibold">
                        <span class="label-item-title">Code</span>
                        <input type="text" readonly v-model="bank.branches[index].branch_code" class="bg-gray-100 label-item-input" name="opening_balance" :id="'opening_balance'" />
                    </td>
                    <td class="font-semibold">
                        <span class="label-item-title">Swift Code</span>
                        <input type="text" readonly v-model="bank.branches[index].swift_code" class="bg-gray-100 label-item-input" name="opening_balance" :id="'opening_balance'" />
                    </td>
                    <td class="font-semibold">
                        <span class="label-item-title">Routing Number</span>
                        <input type="text" readonly v-model="bank.branches[index].routing_number" class="bg-gray-100 label-item-input" name="opening_balance" :id="'opening_balance'" />
                    </td>
                </tr>
            </table>
    </legend>
</template>
<style scoped>
    th {
        @apply text-left
    }
    td {
        @apply pl-5
    }
    th, td {
        @apply p-2 border 
    }
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm font-semibold;
    }
    .label-item-title {
        @apply text-gray-700 dark:text-gray-300;
    }
    .label-item-input {
        @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

</style>