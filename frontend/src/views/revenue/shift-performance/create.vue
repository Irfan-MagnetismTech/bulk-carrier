<script setup>
import ShiftPerformanceForm from '../../../components/revenue/shift-performance/ShiftPerformanceForm.vue';
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useShiftPerformance from "../../../composables/revenue/useShiftPerformance.js";
import { useStore } from "vuex";
import useHelper from '../../../composables/useHelper';

const { shiftPerformance, getUserShiftInfoOfThisDay, storeShiftPerformance  } = useShiftPerformance();
const store = useStore();
const { setTitle } = Title();
const { numberFormat } = useHelper();
const formType = 'create';
const modalShow = ref(false);

setTitle('Create Shift Performance');

onMounted(() => {
        getUserShiftInfoOfThisDay(store.state.auth.userShift)
})
function showPopup() {
    modalShow.value = true
}

function closePopUp() {
    modalShow.value = false
}

function submitForm() {
    storeShiftPerformance(shiftPerformance.value)
}
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Create Shift Performance</h2>
        <router-link :to="{ name: 'revenue.shift-performance.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeShiftPerformance(shiftPerformance)">
            <shift-performance-form v-model:shiftPerformance="shiftPerformance" v-model:formType="formType" :errors="errors"></shift-performance-form>
            <div class="flex justify-center">
                <button type="button" @click="showPopup()" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">Save</button>
            </div>
        </form>

        <div v-if="modalShow==true" class="modal-container">
            
            <div class="modal rounded-md shadow-lg w-10/12 mx-auto">
                <h4 class="text-red-600 text-center font-semibold py-3">
                    Please ensure the data is accurate before confirming. Once the form is submitted, there is no way to undo it. Double-check your information before proceeding.
                </h4>
                <fieldset class="px-3 border border-gray-500 rounded-sm pt-3 pb-5">
                    <legend class="px-2">Miscellaneous Cash Sales:</legend>
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="w-4/12 text-sm">Description</th>
                                    <th class="w-2/12 text-sm">Rate</th>
                                    <th class="w-2/12 text-sm">Credit</th>
                                    <th class="w-2/12 text-sm">Cash</th>
                                    <th class="w-2/12 text-sm">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(materialInfo, index) in shiftPerformance.lubricant" :key="index">
                                    <tr>
                                        <td class="!text-left pl-2 font-semibold text-sm">{{ materialInfo?.material_name }}</td>
                                        <td class="p-1">
                                            <span class=" block w-full form-input text-sm !text-right pr-2 h-5">
                                                {{ numberFormat(materialInfo?.unit_price) }}
                                            </span>
                                        </td>
                                        <td class="p-1">
                                            <span class=" block w-full form-input text-sm !text-right pr-2 h-5">
                                                {{ (materialInfo?.sale_info?.credit?.total_sale) ? numberFormat(Math.abs(materialInfo?.sale_info?.credit?.total_sale)) : null }}
                                            </span>
                                        </td>
                                        <td class="p-1">
                                            <span class=" block w-full form-input text-sm !text-right pr-2 h-5">
                                                {{ (materialInfo?.sale_info?.cash?.total_sale) ? numberFormat(Math.abs(materialInfo?.sale_info?.cash?.total_sale)) : null }}
                                            </span>
                                        </td>
                                        <td class="p-1">
                                            <span class=" block w-full form-input text-sm !text-right pr-2 h-5">
                                                {{ (materialInfo?.combined_amount) ? numberFormat(Math.abs(materialInfo?.combined_amount)) : null }}
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                                <tr>
                                    <td colspan="4" class="!text-right pr-2 font-semibold text-sm">
                                        TOTAL
                                    </td>
                                    <td class="!text-right pr-2 font-semibold text-sm">
                                        
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
                                    <th class="text-sm w-44">Product</th>
                                    <th class="text-sm w-52">Dispenser</th>
                                    <th class="text-sm w-52">Opening</th>
                                    <th class="text-sm w-52">Closing</th>
                                    <th class="text-sm w-44">Test</th>
                                    <th class="text-sm w-44">Sales in Ltr.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(materialInfo, index) in shiftPerformance.fuel" :key="index">
                                    <template v-for="(dispenser, dispenserIndex) in materialInfo.dispensers" :key="dispenserIndex">
                                        <tr>
                                            <td class="!text-left pl-2 font-semibold text-sm" v-if="dispenserIndex == 0" :rowspan="materialInfo.dispensers.length">{{ materialInfo?.name }}</td>
                                            <td class=" text-sm">
                                                {{ dispenser?.name }}
                                            </td>
                                            <td class="">
                                            <!-- <input type="number" readonly v-model="shiftPerformance.fuel[index].dispensers[dispenserIndex].dispenser_opening" class="label-item-input bg-gray-100 !text-center" /> -->
                                            <!-- {{dispenser?.dispenser_opening}} -->
                                                <span class=" block w-full form-input text-sm !text-right pr-2 h-5" v-if="shiftPerformance.fuel[index].dispensers[dispenserIndex].shift_closing">
                                                {{ (shiftPerformance.fuel[index].dispensers[dispenserIndex].shift_closing) ? numberFormat(shiftPerformance.fuel[index].dispensers[dispenserIndex].shift_closing) : null }}
                                                </span>
                                                <span class=" block w-full form-input text-sm !text-right pr-2 h-5" v-else>
                                                    {{ (shiftPerformance.fuel[index].dispensers[dispenserIndex].dispenser_opening) ? numberFormat(shiftPerformance.fuel[index].dispensers[dispenserIndex].dispenser_opening) : null }}
                                                </span>
                                            </td>
                                            <td class="">
                                                <span class=" block w-full form-input text-sm !text-right pr-2 h-5">
                                                    {{ (shiftPerformance.fuel[index].dispensers[dispenserIndex].dispenser_closing) ? numberFormat(shiftPerformance.fuel[index].dispensers[dispenserIndex].dispenser_closing) : null }}
                                                </span>
                                            </td>
                                            <td class="">
                                                <span class=" block w-full form-input text-sm !text-right pr-2 h-5">
                                                    {{ (shiftPerformance.fuel[index].dispensers[dispenserIndex].fuel_test_amount) ? numberFormat(shiftPerformance.fuel[index].dispensers[dispenserIndex].fuel_test_amount) : null }}
                                                </span>
                                            </td>
                                            <td class="">
                                                <span class=" block w-full form-input text-sm !text-right pr-2 h-5">
                                                    {{ numberFormat(shiftPerformance.fuel[index].dispensers[dispenserIndex].sales_in_ltr) }}
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
                                    <th class="text-sm w-64">Description</th>
                                    <th class="text-sm w-36">Petrol</th>
                                    <th class="text-sm w-36">Diesel</th>
                                    <th class="text-sm w-36">Octane</th>
                                    <th class="text-sm w-36">Luboil</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="!text-left pl-2 font-semibold text-sm">Credit Sales in Ltr</td>
                                    <td class="p-1">
                                        <span class="block w-full form-input text-sm !text-right pr-2 h-5">
                                            {{ (shiftPerformance.fuel_summary?.credit?.Petrol) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.credit?.Petrol?.total_quantity)) : null}}
                                        </span>
                                    </td>
                                    <td class="p-1">
                                        <span class="block w-full form-input text-sm !text-right pr-2 h-5">
                                            {{ (shiftPerformance.fuel_summary?.credit?.Diesel) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.credit?.Diesel?.total_quantity)) : null}}
                                        </span>
                                    </td>
                                    <td class="p-1">
                                        <span class="block w-full form-input text-sm !text-right pr-2 h-5">
                                            {{ (shiftPerformance.fuel_summary?.credit?.Octane) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.credit?.Octane?.total_quantity)) : null}}
                                        </span>
                                    </td>
                                    <td class="p-1">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td class="!text-left pl-2 font-semibold text-sm">Cash Sales in Ltr</td>
                                    <td class="p-1">
                                        <span class="block w-full form-input text-sm !text-right pr-2 h-5">
                                            {{ (shiftPerformance.fuel_summary?.cash?.Petrol) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.cash?.Petrol?.total_quantity)) : null}}
                                        </span>
                                    </td>
                                    <td class="p-1">
                                        <span class="block w-full form-input text-sm !text-right pr-2 h-5">
                                            {{ (shiftPerformance.fuel_summary?.cash?.Diesel) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.cash?.Diesel?.total_quantity)) : null}}
                                        </span>
                                    </td>
                                    <td class="p-1">
                                        <span class="block w-full form-input text-sm !text-right pr-2 h-5">
                                            {{ (shiftPerformance.fuel_summary?.cash?.Octane) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.cash?.Octane?.total_quantity)) : null}}
                                        </span>
                                    </td>
                                    <td class="p-1">
                                        
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="!text-left pl-2 font-semibold text-sm">Cash Sales in Tk.</td>
                                    <td class="p-1">
                                        <span class="block w-full form-input text-sm !text-right pr-2 h-5">
                                            {{ (shiftPerformance.fuel_summary?.cash?.Petrol) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.cash?.Petrol?.total_amount)) : null}}
                                        </span>
                                    </td>
                                    <td class="p-1">
                                        <span class="block w-full form-input text-sm !text-right pr-2 h-5">
                                            {{ (shiftPerformance.fuel_summary?.cash?.Diesel) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.cash?.Diesel?.total_amount)) : null}}
                                        </span>
                                    </td>
                                    <td class="p-1">
                                        <span class="block w-full form-input text-sm !text-right pr-2 h-5">
                                            {{ (shiftPerformance.fuel_summary?.cash?.Octane) ? numberFormat(Math.abs(shiftPerformance.fuel_summary?.cash?.Octane?.total_amount)) : null}}
                                        </span>
                                    </td>
                                    <td class="p-1">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td class="!text-left pl-2 font-semibold text-sm">Miscellaneous Cash Sales</td>
                                    <td class="p-1">
                                        <span class="block w-full form-input text-sm !text-right pr-2 h-5">
                                            {{ numberFormat(shiftPerformance.lubricant_total_amount) }}

                                        </span>
                                    </td>
                                    
                                </tr>
                                
                                
                                
                                <tr>
                                    <td class="!text-left pl-2 font-semibold text-sm">Total Cash Sales Tk.</td>
                                    <td class="p-1">
                                        <span class="block w-full form-input text-sm !text-right pr-2 h-5">
                                            {{ numberFormat(shiftPerformance.total_cash_sale) }}
                                        </span>
                                    </td>
                                    <td></td>
                                    <td class="!text-left pl-2 font-semibold text-sm">Difference</td>
                                    <td></td>
                                </tr>
                                
                                <tr>
                                    <td class="!text-left pl-2 font-semibold text-sm">Actual Cash in Hand in Tk.</td>
                                    <td class="p-1">
                                        <span class="block w-full form-input text-sm !text-right pr-2 h-5">
                                            {{ (shiftPerformance.actual_cash_in_hand) ? numberFormat(Math.abs(shiftPerformance.actual_cash_in_hand)) : null}}
                                        </span>
                                    </td>
                                    <td></td>
                                    <td class="p-1">
                                        <span class="block w-full form-input text-sm !text-right pr-2 h-5">
                                            {{ numberFormat((shiftPerformance.difference) || 0) }}
                                        </span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                </fieldset>

                <div class="flex items-center justify-center">
                    <button type="button" @click="closePopUp()" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-red-700 hover:bg-red-700 focus:outline-none focus:shadow-outline-purple mr-3">
                        Close <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>

                    <button type="button" @click="submitForm()" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">Confirm</button>
                </div>
            </div>

        </div>
    </div>
</template>
<style scoped>
table, tr, td, th {
    @apply border border-collapse
}
.modal {
    position: absolute;
    z-index: 10;
    background: #fff;
    left: 50%;
    padding: 10px;
    scroll-behavior: smooth;
    overflow-y: scroll;
    height: calc(100vh - 100px);
    transform: translate(-50%, -50%);
    top: 50%;
}

.modal-container {
    position: absolute;
    z-index: 5;
    background: #ebebeb;
    height: calc(100vh);
    width: calc(100vw);
    top: 0;
    left: 0;
}

</style>