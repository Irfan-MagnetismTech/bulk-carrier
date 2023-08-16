<script setup>
import CreditSaleForm from '../../../components/revenue/credit-sale/CreditSaleForm.vue';
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useCreditSale from "../../../composables/revenue/useCreditSale.js";
import { useRoute } from 'vue-router';
import moment from 'moment';
import useAuth from '../../../services/auth';
import useHelper from '../../../composables/useHelper.js';

const { creditSale, showCreditSale  } = useCreditSale();
const { setTitle } = Title();
const route = useRoute();
const { numberFormat } = useHelper();
const creditSaleId = route.params.creditSaleId;
const units = ref();

setTitle('Credit Sale Details');

const { username } = useAuth();


onMounted(() => {
    showCreditSale(creditSaleId);
});

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Credit Sale Details</h2>
        <router-link :to="{ name: 'revenue.credit-sale.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="border p-3 border-gray-200 dark:border-gray-700 pb-5 mb-8">
        <div class="w-full mb-6">
            <div class="flex justify-between mb-3 bg-gray-200 py-3 px-2 rounded-md shadow-md">
                <h2 class="text-md font-semibold text-gray-500 dark:text-white">Entry Date: {{ moment(creditSale.created_at).format("DD/MM/YYYY") }}</h2>
                <h2 class="text-md font-semibold text-gray-500 dark:text-white">Time: {{ moment(creditSale.created_at).format("HH:mm A") }}</h2>
                <h2 class="text-md font-semibold text-gray-500 dark:text-white">{{ creditSale?.shift }} SHIFT</h2>
                <h2 class="text-md font-semibold text-gray-500 dark:text-white">{{  username  }}</h2>
            </div>
        </div>
        <div class="w-full mb-5">
            <div class="grid grid-cols-3 gap-4">
                <label for="">
                    <h2 class="text-md font-semibold dark:text-white">Sale Date</h2>
                    <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                        {{ moment(creditSale?.date_time).format("DD/MM/YYYY") }}
                    </span>
                </label>
                <label for="">
                    <h2 class="text-md font-semibold dark:text-white">Customer Code</h2>
                    <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                        {{ creditSale?.sale?.customer?.code }}
                    </span>
                </label>
                <label for="">
                    <h2 class="text-md font-semibold dark:text-white">Customer Name</h2>
                    <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                        {{ creditSale?.sale?.customer?.name }}
                    </span>
                </label>
                <label for="">
                    <h2 class="text-md font-semibold dark:text-white">Slip No</h2>
                    <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                        {{ creditSale?.sale?.slip_no }}

                    </span>
                </label>
                
                <label for="">
                    <h2 class="text-md font-semibold dark:text-white">Billing Address</h2>
                    <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                        {{ creditSale?.sale?.billing_address }}
                    </span>
                </label>
                <label for="">
                    <h2 class="text-md font-semibold dark:text-white">Billing Email</h2>
                    <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                        {{ creditSale?.sale?.billing_email }}
                    </span>
                </label>
            </div>
        </div>
        <div class="w-full">
                
                <table class="w-full whitespace-no-wrap">
                    <thead v-once>
                        <tr>
                            <th colspan="8" class="py-2 text-center !text-lg"><strong>FUEL</strong></th>
                        </tr>
                        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-2 py-1 w-56">Name </th>
                            <th class="px-2 py-1">Vehicle No</th>
                            <th class="px-2 py-1 w-28">Unit</th>
                            <th class="px-2 py-1">Rate</th>
                            <th class="px-2 py-1">Quantity</th>
                            <th class="px-2 py-1 w-36">Amount</th>
                        </tr>
                    </thead>
                    <tbody class=" divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <template v-for="(fuel,index) in creditSale.fuel" :key="index">
                            <tr class="text-gray-700 dark:text-gray-400 text-center" v-if="creditSale?.fuel[index]?.quantity > 0">
                                <td class="px-2 py-1 text-sm">
                                    <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                        {{ creditSale?.fuel[index]?.material?.name }}
                                    </span>
                                </td>
                                <td class="px-2 py-1 text-sm">
                                    <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                        {{ creditSale?.fuel[index]?.customer_vehicle?.vehicle_number }}
                                    </span>
                                </td>
                                <td class="px-2 py-1 text-sm">
                                    <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                        {{ creditSale?.fuel[index]?.unit }}
                                    </span>
                                </td>
                                <td class="px-2 py-1 text-sm">
                                    <span class="bg-gray-100 py-2 h-9 flex items-center justify-end border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                        {{ numberFormat(creditSale?.fuel[index]?.unit_price) }}
                                    </span>
                                </td>
                                <td class="px-2 py-1 text-sm">
                                    <span class="bg-gray-100 py-2 h-9 flex items-center justify-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                        {{ creditSale?.fuel[index]?.quantity }}
                                    </span>
                                </td>
                                <td class="px-2 py-1 text-sm">
                                    <span class="bg-gray-100 py-2 h-9 flex items-center border justify-end border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                        {{ numberFormat(creditSale?.fuel[index]?.amount) }}
                                    </span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <table class="w-full whitespace-no-wrap my-5">
                    <thead v-once>
                        <tr>
                            <th colspan="5" class="py-2 text-center !text-lg"><strong>LUBRICANT</strong></th>
                        </tr>
                        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-2 py-1 w-56">Name</th>
                            <th class="px-2 py-1 w-28">Unit</th>
                            <th class="px-2 py-1 w-28">Rate</th>
                            <th class="px-2 py-1 w-28">Qty.</th>
                            <th class="px-2 py-1 w-36">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-100 divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <tr class="text-gray-700 bg-white dark:text-gray-400 text-center" v-for="(mobile,index) in creditSale.mobil" :key="index">
                            <td class="px-2 py-1 text-sm">
                                <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                    {{ creditSale?.mobil[index]?.material?.name }}
                                </span>
                            </td>    
                            
                            <td class="px-2 py-1 text-sm">
                                <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                    {{ creditSale?.mobil[index]?.unit }}
                                </span>
                            </td>
                            <td class="px-2 py-1 text-sm">
                                <span class="bg-gray-100 py-2 h-9 flex items-center justify-end border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                    {{ numberFormat(creditSale?.mobil[index]?.unit_price) }}
                                </span>
                            </td>
                            <td class="px-2 py-1 text-sm">
                                <span class="bg-gray-100 py-2 h-9 flex items-center justify-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                    {{ creditSale?.mobil[index]?.quantity }}
                                </span>
                            </td>
                            <td class="px-2 py-1 text-sm">
                                <span class="bg-gray-100 py-2 h-9 flex items-center justify-end border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                    {{ numberFormat(creditSale?.mobil[index]?.amount) }}
                                </span>
                            </td>
                            
                        </tr>
                        <tr>
                            <td class="py-10 text-sm bg-white" colspan="5">
                            </td>
                        </tr>
                        <tr class="bg-white font-semibold">
                            <td colspan="4" class="px-2 py-1 text-sm text-right">
                                Grand Total
                            </td>
                            <td class="px-2 py-1 text-sm text-right flex items-center">
                                <span class="bg-gray-100 py-2 h-9 flex items-center text-right justify-end mr-2 border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                    {{ numberFormat(creditSale?.total_amount) }}
                                </span>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
                <div class="flex justify-end mt-6 ml-2">
                    <input type="hidden" name=" " :id="' '" v-model="creditSale.total_amount" >
                </div>
        </div>
    </div>
</template>
