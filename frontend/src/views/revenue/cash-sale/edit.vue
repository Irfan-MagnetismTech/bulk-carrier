<script setup>
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import useCashSale from "../../../composables/revenue/useCashSale.js";
import CashSaleForm from '../../../components/revenue/cash-sale/CashSaleForm.vue';
import useHelper from "../../../composables/useHelper.js";
import { useStore } from "vuex";
const store = useStore();

const { cashSale, editCashSale, updateCashSale, serviceObject, mobilObject } = useCashSale();
const { setTitle } = Title();
const { getMajorFuels } = useHelper();
const route = useRoute();
const cashSaleId = route.params.cashSaleId;

const units = store.state.erpConfiguration.allUnits;
const vehicles = store.state.erpConfiguration.allVehicleTypes;
const paymentMethods = store.state.erpConfiguration.allPaymentMethods;
const formType = 'edit';
setTitle('Edit Cash Sale');

onMounted(() => {
    editCashSale(cashSaleId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Edit Cash Sale</h2>
        <router-link :to="{ name: 'revenue.cash-sale.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="">
            <cash-sale-form v-model:formType="formType" v-model:cashSaleId="cashSaleId" v-model:vehicles="vehicles" v-model:units="units" v-model:paymentMethods="paymentMethods" v-model:mobilModel="mobilObject" v-model:cashSale="cashSale" :errors="errors"></cash-sale-form>
            <!-- Submit button -->
                   
        </form>
    </div>
</template>
