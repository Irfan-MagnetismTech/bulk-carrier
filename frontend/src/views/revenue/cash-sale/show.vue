<script setup>
import CashSaleForm from '../../../components/revenue/cash-sale/CashSaleForm.vue';
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useCashSale from "../../../composables/revenue/useCashSale.js";
import { useRoute } from 'vue-router';
import moment from 'moment';
import useAuth from '../../../services/auth';
import useHelper from '../../../composables/useHelper.js';

const { cashSale, showCashSale  } = useCashSale();
const { setTitle } = Title();
const route = useRoute();
const { numberFormat } = useHelper();
const cashSaleId = route.params.cashSaleId;
const units = ref();

setTitle('Cash Sale Details');

const { username } = useAuth();


onMounted(() => {
    showCashSale(cashSaleId);
});

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Cash Sale Details</h2>
        <router-link :to="{ name: 'revenue.cash-sale.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="border p-3 border-gray-200 dark:border-gray-700 pb-5 mb-8">
        
        <div class="w-full">
            <div class="flex">
                <div class="w-8/12 shadow-xl rounded-md p-2">
                    <table class="w-full whitespace-no-wrap">
                        <thead v-once>
                            <tr>
                                <th colspan="8" class="py-2 text-center !text-lg"><strong>FUEL</strong></th>
                            </tr>
                            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-2 py-1 w-56">Name </th>
                                <th class="px-2 py-1 w-28">Unit</th>
                                <th class="px-2 py-1 w-28">Rate</th>
                                <th class="px-2 py-1 w-28">Quantity</th>
                                <th class="px-2 py-1 w-36">Amount</th>
                            </tr>
                        </thead>
                        <tbody class=" divide-y dark:divide-gray-700 dark:bg-gray-800">
                            <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(fuel,index) in cashSale.fuel" :key="index">
                                <template v-if="cashSale?.fuel[index]?.amount > 0">
                                    <td class="px-2 py-1 text-sm">
                                        <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                            {{ cashSale?.fuel[index]?.material?.name }}
                                        </span>
                                    </td>
                                
                                    <td class="px-2 py-1 text-sm">
                                        <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                            {{ cashSale?.fuel[index]?.unit }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-1 text-sm">
                                        <span class="bg-gray-100 py-2 h-9 flex items-center justify-end border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                            {{ numberFormat(cashSale?.fuel[index]?.unit_price) }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-1 text-sm">
                                        <span class="bg-gray-100 py-2 h-9 flex items-center justify-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                            {{ Math.abs(cashSale?.fuel[index]?.quantity) }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-1 text-sm">
                                        <span class="bg-gray-100 py-2 h-9 flex items-center border justify-end border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                            {{ numberFormat(cashSale?.fuel[index]?.amount) }}
                                        </span>
                                    </td>
                                </template>
                            </tr>
                            <tr class="px-3">
                                <td colspan="4" class="!text-right py-3"><strong>Fuel Total</strong></td>
                                <td class="!text-right pr-3 py-3">
                                {{ (cashSale.fuel) ? numberFormat(cashSale.fuel.reduce((acc, item) => acc + Math.abs(item.amount), 0)) : '' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="w-full whitespace-no-wrap my-2">
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
                        <tbody class="divide-y dark:divide-gray-700 dark:bg-gray-800">
                            <tr class="text-gray-700 bg-white dark:text-gray-400 text-center" v-for="(mobil,index) in cashSale.mobil" :key="index">
                                <template v-if="cashSale?.mobil[index]?.amount > 0">
                                
                                    <td class="px-2 py-1 text-sm">
                                        <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                            {{ cashSale?.mobil[index]?.material?.name }}
                                        </span>
                                    </td>    
                                    
                                    <td class="px-2 py-1 text-sm">
                                        <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                            {{ cashSale?.mobil[index]?.unit }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-1 text-sm">
                                        <span class="bg-gray-100 py-2 h-9 flex items-center justify-end border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                            {{ numberFormat(cashSale?.mobil[index]?.unit_price) }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-1 text-sm">
                                        <span class="bg-gray-100 py-2 h-9 flex items-center justify-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                            {{ Math.abs(cashSale?.mobil[index]?.quantity) }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-1 text-sm">
                                        <span class="bg-gray-100 py-2 h-9 flex items-center justify-end border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                            {{ numberFormat(cashSale?.mobil[index]?.amount) }}
                                        </span>
                                    </td>
                                </template>
                                
                            </tr>
                            <tr class="px-3">
                                <td colspan="4" class="!text-right py-3"><strong>Lubricant Total</strong></td>
                                <td class="!text-right pr-3 py-3">
                                {{ (cashSale.mobil) ? numberFormat(cashSale.mobil.reduce((acc, item) => acc + Math.abs(item.amount), 0)) : '' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="w-full whitespace-no-wrap">
                        <thead v-once>
                            <tr>
                                <th colspan="5" class="py-2 text-center !text-lg"><strong>SERVICE</strong></th>
                            </tr>
                            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-2 py-1 w-56">Name</th>
                                <th class="px-2 py-1 w-28">Rate</th>
                                <th class="px-2 py-1 w-28">Qty.</th>
                                <th class="px-2 py-1 w-36">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700 dark:bg-gray-800">
                            <tr class="text-gray-700 bg-white dark:text-gray-400 text-center" v-for="(service,index) in cashSale.services" :key="index">
                                <template v-if="cashSale?.services[index]?.quantity > 0">

                                    <td class="px-2 py-1 text-sm">
                                        <span class="bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                             {{ cashSale?.services[index]?.service?.name }}
                                        </span>
                                    </td>    
                                    
                                    <td class="px-2 py-1 text-sm">
                                        <span class="bg-gray-100 py-2 h-9 flex items-center justify-end border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                            {{ numberFormat(cashSale?.services[index]?.service_rate?.rate) }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-1 text-sm">
                                        <span class="bg-gray-100 py-2 h-9 flex items-center justify-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                            {{ cashSale?.services[index]?.quantity }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-1 text-sm">
                                        <span class="bg-gray-100 py-2 h-9 flex items-center justify-end border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                                            {{ numberFormat(cashSale?.services[index]?.amount) }}
                                        </span>
                                    </td>
                                </template>
                                
                            </tr>
                            <tr class="px-3">
                                <td colspan="3" class="!text-right py-3"><strong>Service Total</strong></td>
                                <td class="!text-right pr-3 py-3">
                                {{ (cashSale.services) ? numberFormat(cashSale.services.reduce((acc, item) => acc + Math.abs(item.amount), 0)) : '' }}
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                    <hr />
                    <table>
                        <tr>
                            <td class="py-5 !text-right" style="width: calc(100vw - 120px)">
                                <strong class="pr-3 inline-block">Grand Total</strong>
                            </td>
                            <td class="py-5 pr-3">
                                {{ numberFormat(cashSale?.total_amount) }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="w-4/12 shadow-xl rounded-md p-3 m-3 max-h-56">
                    <h2 class="text-md font-semibold text-gray-800 dark:text-white">Entry Date: {{ moment(cashSale.created_at).format("DD/MM/YYYY") }}</h2>
                <h2 class="text-md font-semibold text-gray-800 dark:text-white">Time: {{ moment(cashSale.created_at).format("HH:mm A") }}</h2>
                <h2 class="text-md font-semibold text-gray-800 dark:text-white">SHIFT: {{ cashSale?.shift }} </h2>
                <h2 class="text-md font-semibold text-gray-800 dark:text-white">Entry By: {{  username  }}</h2>
                </div>
            </div>
                
                <div class="flex justify-end mt-6 ml-2">
                    <input type="hidden" name=" " :id="' '" v-model="cashSale.total_amount" >
                </div>
        </div>
    </div>
</template>
