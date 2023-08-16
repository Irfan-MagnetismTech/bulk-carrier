<script setup>
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useBankAccount from "../../../composables/configuration/useBankAccount.js";
import { useRoute } from 'vue-router';

const { bankAccount, showBankAccount, updateBankAccount  } = useBankAccount();
const { setTitle } = Title();
const route = useRoute();
const bankAccountId = route.params.bankAccountId;

setTitle('Bank Account Details');

onMounted(() => {
    showBankAccount(bankAccountId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Bank Account Details</h2>
        <router-link :to="{ name: 'configuration.bank-account.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">

        <div class=" dark:border-gray-700 pb-5">
            <legend>
                <strong>Bank Info</strong>
                <div class="input-group">
                    <label class="label-group">
                        <span class="label-item-title">Bank Name<span class="required-style">*</span></span>
                        <input type="text" readonly  v-model="bankAccount.bank_name" class="label-item-input" name="bank_name" :id="'bank_name'" />
                    </label>
                    <label class="label-group">
                        <span class="label-item-title">Branch Name<span class="required-style">*</span></span>
                        <input type="text" readonly  v-model="bankAccount.branch_name" class="label-item-input" name="branch_name" :id="'branch_name'" />
                    </label>
                </div>
            </legend>
            <legend class="mt-5">
                <strong>Account Owner Info</strong>
                <div class="input-group">
                
                    <label class="label-group">
                        <span class="label-item-title">Account Name<span class="required-style">*</span></span>
                        <input type="text" readonly  v-model="bankAccount.account_name" class="label-item-input" name="account_name" :id="'account_name'" />
                    </label>
                    <label class="label-group">
                        <span class="label-item-title">Account Holder<span class="required-style">*</span></span>
                        <input type="text" readonly  v-model="bankAccount.account_holder" class="label-item-input" name="account_holder" :id="'account_holder'" />
                    </label>

                    <label class="label-group">
                        <span class="label-item-title">Contact Number<span class="required-style">*</span></span>
                        <input type="text" readonly  v-model="bankAccount.contact_number" class="label-item-input" name="contact_number" :id="'contact_number'" />
                    </label>
                </div>
                <div class="input-group">
                    <label class="label-group">
                        <span class="label-item-title">Account Number <span class="required-style">*</span></span>
                        <input type="text" readonly  v-model="bankAccount.account_number" class="label-item-input" name="account_number" :id="'account_number'" />
                    </label>
                    <label class="label-group">
                        <span class="label-item-title">Routing Number <span class="required-style">*</span></span>
                        <input type="text" readonly  v-model="bankAccount.routing_number" class="label-item-input" name="routing_number" :id="'routing_number'" />
                    </label>
                </div>
            </legend>
            <legend class="mt-5">
                <strong>Account Info</strong>
                <div class="input-group">

                    <label class="label-group">
                        <span class="label-item-title">Opening Date<span class="required-style">*</span></span>
                        <input type="date" readonly v-model="bankAccount.opening_date" class="label-item-input" name="opening_date" :id="'opening_date'" />
                    </label>

                    <label class="label-group">
                        <span class="label-item-title">Opening Balance<span class="required-style">*</span></span>
                        <input type="number" step="0.01" readonly v-model="bankAccount.opening_balance" class="label-item-input" name="opening_balance" :id="'opening_balance'" />
                    </label>
                    
                </div>
            </legend>
        </div>
    </div>
</template>
<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm;
    }
    .label-item-title {
        @apply text-gray-700 dark:text-gray-300;
    }
    .label-item-input {
        @apply block w-full mt-1 text-sm text-black rounded dark:text-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700  focus:outline-none outline-none disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
    }
    .form-input {
        @apply block mt-1 text-sm text-black rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 outline-none focus:outline-none 
    }
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

</style>