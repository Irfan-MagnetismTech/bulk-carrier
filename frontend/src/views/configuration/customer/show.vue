<script setup>
import CustomerForm from '../../../components/configuration/customer/CustomerForm.vue';
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useCustomer from "../../../composables/configuration/useCustomer.js";
import { useRoute } from 'vue-router';
import moment from 'moment';
import useHelper from '../../../composables/useHelper';

const { customer, showCustomer, updateCustomer  } = useCustomer();
const { setTitle } = Title();
const route = useRoute();
const customerId = route.params.customerId;
const { numberFormat } = useHelper();

setTitle('Customer Details');

onMounted(() => {
    showCustomer(customerId);
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
      input.setAttribute('readonly', true);
    });
});
</script>

<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Customer Details</h2>
        <router-link :to="{ name: 'configuration.customer.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Customer Name <span class="required-style">*</span></span>
                <input type="text" required v-model="customer.name" class="label-item-input" name="customer_name" :id="'customer_name'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Customer Code<span class="required-style">*</span></span>
                <input type="text" required v-model="customer.code" class="label-item-input" name="customer_mobile" :id="'customer_mobile'" />
            </label>
            
            <label class="label-group">
                <span class="label-item-title">Address</span>
                <input type="text" v-model="customer.address" class="label-item-input" name="customer_address" :id="'customer_address'" />
            </label>
        </div>
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Official Email</span>
                <input type="email" v-model="customer.official_email" class="label-item-input" name="official_email" :id="'official_email'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Official Mobile</span>
                <input type="text" v-model="customer.official_contact" class="label-item-input" name="official_contact" :id="'official_contact'" />
            </label>
        </div>
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Billing Email <span class="required-style">*</span></span>
                <input type="email" required v-model="customer.billing_email" class="label-item-input" name="billing_email" :id="'billing_email'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Billing Address <span class="required-style">*</span></span>
                <input type="text" required v-model="customer.billing_address" class="label-item-input" name="billing_address" :id="'billing_address'" />
            </label>
        </div>
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Contact Person<span class="required-style">*</span></span>
                <input type="text" required v-model="customer.contact_person" class="label-item-input" name="contact_person" :id="'contact_person'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Contact Person Mobile <span class="required-style">*</span></span>
                <input type="text" required v-model="customer.contact_person_mobile" class="label-item-input" name="contact_person_mobile" :id="'contact_person_mobile'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Remarks</span>
                <input type="text" v-model="customer.remarks" class="label-item-input" name="remarks" :id="'remarks'" />
            </label>
        </div>
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Opening Date</span>
                <input type="date" v-model="customer.opening_date" class="label-item-input" name="opening_date" :id="'opening_date'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Opening Due</span>
                <input type="number" step="0.01" v-model="customer.opening_due" class="label-item-input" name="opening_due" :id="'opening_due'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Advanced Amount</span>
                <input type="number" step="0.01" v-model="customer.deposited_amount" class="label-item-input" name="opening_due" :id="'opening_due'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Credit Limit <span class="required-style">*</span></span>
                <input type="number" required step="0.01" v-model="customer.credit_limit" class="label-item-input" name="opening_due" :id="'opening_due'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Credit Days <span class="required-style">*</span></span>
                <input type="number" required v-model="customer.credit_days" class="label-item-input" name="opening_due" :id="'opening_due'" />
            </label>
        </div>
        <div class="input-group">

            <div class="label-group">
                <span class="label-item-title">Status <span class="required-style">*</span></span>
                <div class="my-3 flex">
                    <div class="flex items-center">
                        <input type="radio" value="1" disabled v-model="customer.status" class="" name="status" :id="'not_active'" />
                        <label class="ml-2" for="not_active">Active</label>
                    </div>
                    <div class="flex items-center ml-8">
                        <input type="radio" value="0" disabled v-model="customer.status" class="" name="status" :id="'active'" />
                        <label class="ml-2" for="active">Inactive</label>
                    </div>
                </div>
            </div>
            <label class="label-group" v-if="customer.status == 0">
                <span class="label-item-title">Returned Amount </span>
                <input type="number" step="0.01" v-model="customer.returned_amount" class="label-item-input !w-1/2" name="opening_due" :id="'opening_due'" />
            </label>
            <div class="label-group">
                <span class="label-item-title">Is Corporate Customer?</span>
                <div class="my-3 flex">
                    <div class="flex items-center">
                        <input type="radio" value="1" disabled v-model="customer.is_corporate" class="" name="is_corporate" :id="'not_corporate'" />
                        <label class="ml-2" for="not_corporate">Yes</label>
                    </div>
                    <div class="flex items-center ml-8">
                        <input type="radio" value="0" disabled v-model="customer.is_corporate" class="" name="is_corporate" :id="'corporate'" />
                        <label class="ml-2" for="corporate">No</label>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="my-7">
        <h2 class="text-center text-2xl text-gray-700 font-semibold mb-3">
            Customer Acitivity History
        </h2>
        <table class="w-full mb-6">
            <thead>
                <tr>
                    <td>Event</td>
                    <td>Days</td>
                    <td>Returned Amount</td>
                    <td>Occurance Date</td>
                </tr>
            </thead>
            <template v-for="activity, index in customer.customer_activities" :key="index">
                <tr>
                    <td>
                        <span v-if="activity.status == 1">Active</span>
                        <span v-else>Inactive</span>
                    </td>
                    <td>
                        <span v-if="index < (customer.customer_activities.length - 2)">
                            <strong v-if="customer.customer_activities[index+1].days > 0" class="pl-2">({{ customer.customer_activities[index+1].days }} days)</strong>
                        </span>
                    </td>
                    <td class="text-right">{{ numberFormat(activity.returned_amount) }}</td>
                    <td>{{ moment(activity.created_at).format('DD/MM/YYYY hh:mm A') }}</td>
                </tr>
            </template>
        </table>
    </div>
</template>
<style lang="postcss" scoped>
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
    table, tr, td, thead {
        @apply border border-collapse
    }
    thead {
        @apply text-center font-semibold
    }
    td {
        @apply pl-2
    }

</style>