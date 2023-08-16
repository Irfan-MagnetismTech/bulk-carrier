<script setup>
import PurchaseRateForm from '../../../components/revenue/purchase-rate/PurchaseRateForm.vue';
import {ref} from "vue";
import { onMounted, watch } from '@vue/runtime-core';
import Title from "../../../services/title";
import usePurchaseRate from "../../../composables/revenue/usePurchaseRate.js";
import { useRoute } from 'vue-router';
import moment from 'moment';
import useHelper from '../../../composables/useHelper';

const { history, purchaseRateHistory  } = usePurchaseRate();
const { numberFormat } = useHelper();
const { setTitle } = Title();
const route = useRoute();
const vendorId = route.params.vendorId;
const materialId = route.params.materialId;

setTitle('Purchase Rate History');

onMounted(() => {
    purchaseRateHistory(vendorId, materialId);
});

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row">
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Purchase Rate History - {{ history?.vendor?.name }}</h2>
        <router-link :to="{ name: 'revenue.purchase-rate.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800 w-1/3 mx-auto">
        <ul class="mt-6">
            <li v-for="item, index in history.histories" :key="index" class="flex justify-between mb-6 relative">
                <span>Date: <strong>{{  moment(item.created_at).format('DD/MM/YYYY h:mm A')  }}</strong></span>
                <span>Price: <strong>{{ numberFormat(item.rate) }}BDT</strong></span>
            </li>
        </ul>
    </div>
</template>
<style scoped>
li::after {
    content: "▲";
    position: absolute;
    top: 25px;
    left: 50%;
    color: gray;
    transform: translateX(-50%);
    text-shadow: 0px 1px 2px #b1adad;
}

li:last-child::after {
  display: none;
}

</style>