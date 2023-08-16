<script setup>
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';

import Title from "../../../services/title";
import useCreditSale from "../../../composables/revenue/useCreditSale.js";
import CreditSaleForm from '../../../components/revenue/credit-sale/CreditSaleForm.vue';
import useHelper from "../../../composables/useHelper.js";
import useAuth from '../../../services/auth';
import { useStore } from "vuex";
const store = useStore();

const { shift } = useAuth();
const { creditSale, storeCreditSale, serviceObject, mobilObject } = useCreditSale();
const { setTitle } = Title();
const { getMajorFuels } = useHelper();

const units = store.state.erpConfiguration.allUnits;
const vehicles = store.state.erpConfiguration.allVehicleTypes;
const paymentMethods = store.state.erpConfiguration.allPaymentMethods;
const formType = 'create';

setTitle('Create Credit Sale');

onMounted(() => {
   

    getMajorFuels()
    .then(allFuels => {
        creditSale.value.fuel = allFuels.value
        creditSale.value.fuel.push(mobilObject)

    })
    .catch(error => {
        // Handle the error
        console.error('An error occurred:', error);
    });
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-5 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Create Credit Sale</h2>
        <router-link :to="{ name: 'revenue.credit-sale.index' }" class="flex items-center justify-between gap-1 px-2 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeCreditSale(creditSale)">
            <credit-sale-form v-model:formType="formType" v-model:shift="shift" v-model:vehicles="vehicles" v-model:units="units" v-model:paymentMethods="paymentMethods" v-model:mobilModel="mobilObject" v-model:creditSale="creditSale" :errors="errors"></credit-sale-form>
            <!-- Submit button -->
            <!-- <div class="flex items-center justify-center">
                <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">Create Credit Sale</button>
            </div>         -->
        </form>
    </div>
</template>
