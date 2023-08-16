<script setup>
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useCustomerVehicle from "../../../composables/configuration/useCustomerVehicle.js";
import { useRoute } from 'vue-router';

const { customerVehicle, showCustomerVehicle, updateCustomerVehicle  } = useCustomerVehicle();
const { setTitle } = Title();
const route = useRoute();
const customerVehicleId = route.params.customerVehicleId;

setTitle('Customer Vehicle Details');

onMounted(() => {
    showCustomerVehicle(customerVehicleId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Customer Vehicle Details</h2>
        <router-link :to="{ name: 'configuration.customer-vehicle.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        <legend>
            <div class="input-group">
                <label class="label-group">
                    <span class="label-item-title">Customer Name<span class="required-style">*</span></span>
                    <v-select :options="customers" disabled placeholder="--Choose an option--" @search="fetchCustomer"  v-model="customerVehicle.customer_name" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                    <input type="hidden" v-model="customerVehicle.customer_id" class="label-item-input" name="customer_id" :id="'customer_id'" />
                </label>
<!--                <label class="label-group">-->
<!--                    <span class="label-item-title">Vehicle Type<span class="required-style">*</span></span>-->
<!--                    <v-select :options="vehicleTypes" disabled placeholder="&#45;&#45;Choose an option&#45;&#45;" v-model="customerVehicle.vehicle_type" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>-->

<!--                </label>-->
                <label class="label-group">
                    <span class="label-item-title">Vehicle Number<span class="required-style">*</span></span>
                    <input type="text" readonly  v-model="customerVehicle.vehicle_number" class="label-item-input" name="vehicle_number" :id="'branch_name'" />
                </label>
            </div>
        </legend>
        <legend class="mt-5">
            <div class="input-group">
            
                <label class="label-group">
                    <span class="label-item-title">Responsible Person Name<span class="required-style">*</span></span>
                    <input type="text" readonly  v-model="customerVehicle.assigned_person" class="label-item-input" name="assigned_person" :id="'assigned_person'" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Responsible Person Number<span class="required-style">*</span></span>
                    <input type="text" readonly  v-model="customerVehicle.assigned_person_contact" class="label-item-input" name="assigned_person_contact" :id="'assigned_person_contact'" />
                </label>

            </div>
        </legend>
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