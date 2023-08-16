<script setup>
import { watch } from 'vue';
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useShiftPerformance from "../../../composables/revenue/useShiftPerformance.js";
import useHelper from '../../../composables/useHelper';
import { useRoute } from 'vue-router';

const { shiftPerformance, showShiftPerformance } = useShiftPerformance();
const { setTitle } = Title();
const route = useRoute();
const { numberFormat } = useHelper();

const shiftPerformanceId = route.params.shiftPerformanceId;

setTitle('Shift Performanace Details');

onMounted(() => {
    showShiftPerformance(shiftPerformanceId);
});

watch(() => shiftPerformance, (value) => {

}, { deep: true });

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Shift Performance Details</h2>
        <router-link :to="{ name: 'revenue.shift-performance.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-10">
        
        <fieldset class="px-3 border border-gray-500 rounded-sm pt-3 pb-5">
            <legend class="px-2">Miscellaneous Cash Sales:</legend>
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="py-2 w-4/12">Description</th>
                            <th class="w-2/12 p-1">Rate</th>
                            <th class="w-2/12 p-1">Credit</th>
                            <th class="w-2/12 p-1">Cash</th>
                            <th class="w-2/12 p-1">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(materialInfo, index) in shiftPerformance.lubricant" :key="index">
                            <tr>
                                <td class="!text-left pl-2 font-semibold text-sm ">{{ materialInfo.material_name }}</td>
                                <td class="p-1">
                                    <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                        {{ numberFormat(materialInfo?.unit_price) }}
                                    </span>
                                </td>
                                <td class="p-1">
                                    <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                        {{ (materialInfo?.sale_info?.cash?.total_sale) ? numberFormat(Math.abs(materialInfo?.sale_info?.cash?.total_sale)) : null }}
                                    </span>
                                </td>
                                <td class="p-1">
                                    <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                        {{ (materialInfo?.sale_info?.credit?.total_sale) ? numberFormat(Math.abs(materialInfo?.sale_info?.credit?.total_sale)) : null }}
                                    </span>
                                </td>
                                <td class="p-1">
                                    <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                        {{ (materialInfo?.combined_amount) ? numberFormat(Math.abs(materialInfo?.combined_amount)) : null }}
                                    </span>
                                </td>
                            </tr>
                        </template>
                        <tr>
                            <td colspan="4" class="!text-center font-semibold py-3">
                                TOTAL
                            </td>
                            <td class="!text-right pr-2 font-semibold">
                                
                                {{ numberFormat(
                                        (shiftPerformance?.lubricant != null) ? Object.values(shiftPerformance.lubricant).reduce((accumulator, mobil) => {
                                            return accumulator + mobil.combined_amount;
                                        }, 0) : 0
                                    ) 
                                }}

                            </td>
                        </tr>
                    </tbody>
                </table>
        </fieldset>

        <fieldset class="px-3 border border-gray-500 rounded-sm py-3 mt-5">
            <legend class="px-2">Meter Reading:</legend>
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="py-2 w-44">Product</th>
                            <th class="py-2">Dispenser</th>
                            <th class="w-52 py-2">Opening</th>
                            <th class="w-52 p-1">Closing</th>
                            <th class="w-44 p-1">Test</th>
                            <th class="w-44 p-1">Sales in Ltr.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(materialInfo, index) in shiftPerformance.fuel" :key="index">
                            <template v-for="(dispenser, dispenserIndex) in materialInfo.dispensers" :key="dispenserIndex">
                                <tr>
                                    <td class="!text-left pl-2 font-semibold text-sm" v-if="dispenserIndex == 0" :rowspan="materialInfo.dispensers.length">{{ materialInfo?.name }}</td>
                                    <td class="p-1">
                                        {{ dispenser?.name }}
                                    </td>
                                    <td class="p-1">
                                       
                                        <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                            {{ (shiftPerformance.fuel[index].dispensers[dispenserIndex].dispenser_opening) ? numberFormat(shiftPerformance.fuel[index].dispensers[dispenserIndex].dispenser_opening) : null }}
                                        </span>
                                    </td>
                                    <td class="p-1">
                                        <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                            {{ (shiftPerformance.fuel[index].dispensers[dispenserIndex].dispenser_closing) ? numberFormat(shiftPerformance.fuel[index].dispensers[dispenserIndex].dispenser_closing) : null }}
                                        </span>
                                    </td>
                                    <td class="p-1">
                                        <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                            {{ (shiftPerformance.fuel[index].dispensers[dispenserIndex].fuel_test_amount) ? numberFormat(shiftPerformance.fuel[index].dispensers[dispenserIndex].fuel_test_amount) : null }}
                                        </span>
                                    </td>
                                    <td class="p-1">
                                        <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                            {{ (shiftPerformance.fuel[index].dispensers[dispenserIndex].total_sales_in_liter) ? numberFormat(shiftPerformance.fuel[index].dispensers[dispenserIndex].total_sales_in_liter) : null }}
                                        </span>
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
        </fieldset>

        <fieldset class="px-3 border border-gray-500 rounded-sm py-3 mt-5">
            <legend class="px-2">Sales Summary:</legend>
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="py-2 w-64">Description</th>
                            <th class="w-36 p-1">Petrol</th>
                            <th class="w-36 p-1">Diesel</th>
                            <th class="w-36 py-2">Octane</th>
                            <th class="w-36 p-1">Luboil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="!text-left pl-2 font-semibold text-sm">Credit Sales in Ltr</td>
                            <td class="p-1">
                                <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                    {{ (shiftPerformance.fuel_summary?.credit?.Petrol) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.credit?.Petrol?.total_quantity)) : null}}
                                </span>
                            </td>
                            <td class="p-1">
                                <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                    {{ (shiftPerformance.fuel_summary?.credit?.Diesel) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.credit?.Diesel?.total_quantity)) : null}}
                                </span>
                            </td>
                            <td class="p-1">
                                <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                    {{ (shiftPerformance.fuel_summary?.credit?.Octane) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.credit?.Octane?.total_quantity)) : null}}
                                </span>
                            </td>
                            <td class="p-1">
                                
                            </td>
                        </tr>
                        <tr>
                            <td class="!text-left pl-2 font-semibold text-sm">Cash Sales in Ltr</td>
                            <td class="p-1">
                                <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                    {{ (shiftPerformance.fuel_summary?.cash?.Petrol) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.cash?.Petrol?.total_quantity)) : null}}
                                </span>
                            </td>
                            <td class="p-1">
                                <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                    {{ (shiftPerformance.fuel_summary?.cash?.Diesel) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.cash?.Diesel?.total_quantity)) : null}}
                                </span>
                            </td>
                            <td class="p-1">
                                <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                    {{ (shiftPerformance.fuel_summary?.cash?.Octane) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.cash?.Octane?.total_quantity)) : null}}
                                </span>
                            </td>
                            <td class="p-1">
                                
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="!text-left pl-2 font-semibold text-sm">Cash Sales in Tk.</td>
                            <td class="p-1">
                                <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                    {{ (shiftPerformance.fuel_summary?.cash?.Petrol) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.cash?.Petrol?.total_amount)) : null}}
                                </span>
                            </td>
                            <td class="p-1">
                                <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                    {{ (shiftPerformance.fuel_summary?.cash?.Diesel) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.cash?.Diesel?.total_amount)) : null}}
                                </span>
                            </td>
                            <td class="p-1">
                                <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                    {{ (shiftPerformance.fuel_summary?.cash?.Octane) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.cash?.Octane?.total_amount)) : null}}
                                </span>
                            </td>
                            <td class="p-1">
                                
                            </td>
                        </tr>
                        <tr>
                            <td class="!text-left pl-2 font-semibold text-sm">Miscellaneous Cash Sales</td>
                            <td class="p-1">
                                <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                    {{ numberFormat(shiftPerformance?.shiftPerformance?.misc_cash_sale) }}
                                </span>
                            </td>
                            
                        </tr>
                        
                        
                        
                        <tr>
                            <td class="!text-left pl-2 font-semibold text-sm">Total Cash Sales Tk.</td>
                            <td class="p-1">
                                <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                    {{ numberFormat(shiftPerformance?.shiftPerformance?.total_cash_sale) }}
                                </span>
                            </td>
                            <td></td>
                            <td class="!text-left pl-2 font-semibold text-sm">Difference</td>
                            <td></td>
                        </tr>
                        
                        <tr>
                            <td class="!text-left pl-2 font-semibold text-sm">Actual Cash in Hand in Tk.</td>
                            <td class="p-1">
                                <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                    {{ numberFormat(shiftPerformance?.shiftPerformance?.actual_cash_in_hand) }}
                                </span>
                            </td>
                            <td></td>
                            <td class="p-1">
                                <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                    {{ numberFormat((shiftPerformance?.shiftPerformance?.total_cash_sale - shiftPerformance?.shiftPerformance?.actual_cash_in_hand) || 0) }}
                                </span>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
        </fieldset>

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
        @apply block w-full p-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
    }
    .form-input {
        @apply block text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

    table, tr, td, th {
        @apply border 
    }
    th, td {
        @apply text-center
    }

</style>