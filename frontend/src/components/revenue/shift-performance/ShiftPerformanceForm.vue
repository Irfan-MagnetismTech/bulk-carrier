<script setup>
    import { ref, watch, onMounted, computed } from 'vue';
    import Error from "../../Error.vue";
    import useCustomer from '../../../composables/configuration/useCustomer.js';
    import moment from "moment";
    import useShiftPerformance from '../../../composables/revenue/useShiftPerformance.js';
    import useHelper from '../../../composables/useHelper';
    import { useStore } from "vuex";
    import useAuth from '../../../services/auth'

    const store = useStore();

    const paymentMethods = store.state.erpConfiguration.allPaymentMethods;
    const { storeShiftPerformance, updateShiftPerformance } = useShiftPerformance();
    const { numberFormat } = useHelper();
    const { shift } = useAuth();

    const props = defineProps({
        shiftPerformance: { type: Object, required: true },
        formType: { type: String, required: true },
        shiftPerformanceId: { type: String, required: true },
    });
    const now = moment();

    watch(() => props.shiftPerformance, (value) => {
        props.shiftPerformance.closing_time = now.format('YYYY-MM-DD hh:mm A');
        props.shiftPerformance.sale_type = 'cash'
        props.shiftPerformance.shift = shift

        // console.log("test")
        for(let index in props.shiftPerformance.fuel) {
            let dispensers = props.shiftPerformance.fuel[index].dispensers

            for(let dispenserIndex in props.shiftPerformance.fuel[index].dispensers) {
                let dispenser = dispenserIndex[dispenserIndex];

                let opening = (props.shiftPerformance?.fuel[index]?.dispensers[dispenserIndex]?.shift_closing != null) ? props.shiftPerformance?.fuel[index]?.dispensers[dispenserIndex]?.shift_closing : props.shiftPerformance?.fuel[index]?.dispensers[dispenserIndex]?.dispenser_opening;
                let closing = props.shiftPerformance?.fuel[index]?.dispensers[dispenserIndex]?.dispenser_closing;
                let test_amount = props.shiftPerformance?.fuel[index]?.dispensers[dispenserIndex]?.fuel_test_amount;

                props.shiftPerformance.fuel[index].dispensers[dispenserIndex].dispenser_opening = opening

                if(closing > opening) {
                    props.shiftPerformance.fuel[index].dispensers[dispenserIndex].sales_in_ltr = (parseFloat(closing) || 0) - (parseFloat(opening) || 0) - (parseFloat(test_amount) || 0)
                } else {
                    props.shiftPerformance.fuel[index].dispensers[dispenserIndex].sales_in_ltr = 0
                }

            }
        }

        let totalMobilSaleTk = parseFloat((
                                        (props.shiftPerformance?.lubricant != null) ? Object.values(props.shiftPerformance.lubricant).reduce((accumulator, mobil) => {
                                            return accumulator + mobil.combined_amount;
                                        }, 0) : 0
                                    ) 
                                ) || 0;
        props.shiftPerformance.lubricant_total_amount = totalMobilSaleTk;

        let totalFuelSaleTk = (props.shiftPerformance?.fuel_summary?.cash != null) ? (Object.values(props.shiftPerformance.fuel_summary.cash).reduce((accumulator, mobil) => {
                                            return accumulator + mobil.total_amount;
                                        }, 0)  + totalMobilSaleTk) : totalMobilSaleTk;

        props.shiftPerformance.total_cash_sale = totalFuelSaleTk

        let actualCash = parseFloat(props.shiftPerformance.actual_cash_in_hand) || 0;

        props.shiftPerformance.difference = totalFuelSaleTk - actualCash
    }, { deep: true });
    
</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        
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
                                <td class="!text-left pl-2 font-semibold text-sm ">{{ materialInfo?.material_name }}</td>
                                <td class="p-1">
                                    <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                        {{ numberFormat(materialInfo?.unit_price) }}
                                    </span>
                                </td>
                                <td class="p-1">
                                    <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                        {{ (materialInfo?.sale_info?.credit?.total_sale) ? numberFormat(Math.abs(materialInfo?.sale_info?.credit?.total_sale)) : null }}
                                    </span>
                                </td>
                                <td class="p-1">
                                    <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                        {{ (materialInfo?.sale_info?.cash?.total_sale) ? numberFormat(Math.abs(materialInfo?.sale_info?.cash?.total_sale)) : null }}
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
                                       <!-- <input type="number" readonly v-model="shiftPerformance.fuel[index].dispensers[dispenserIndex].dispenser_opening" class="label-item-input bg-gray-100 !text-center" /> -->
                                      <!-- {{dispenser?.dispenser_opening}} -->
                                        <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8" v-if="shiftPerformance.fuel[index].dispensers[dispenserIndex].shift_closing">
                                        {{ (shiftPerformance.fuel[index].dispensers[dispenserIndex].shift_closing) ? numberFormat(shiftPerformance.fuel[index].dispensers[dispenserIndex].shift_closing) : null }}
                                        </span>
                                        <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8" v-else>
                                            {{ (shiftPerformance.fuel[index].dispensers[dispenserIndex].dispenser_opening) ? numberFormat(shiftPerformance.fuel[index].dispensers[dispenserIndex].dispenser_opening) : null }}
                                        </span>
                                    </td>
                                    <td class="p-1">
                                        <input type="number" v-model="shiftPerformance.fuel[index].dispensers[dispenserIndex].dispenser_closing" class="label-item-input !text-right" />
                                    </td>
                                    <td class="p-1">
                                        <input type="number" v-model="shiftPerformance.fuel[index].dispensers[dispenserIndex].fuel_test_amount" class="label-item-input !text-right" />
                                    </td>
                                    <td class="p-1">
                                        <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
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
                                    {{ numberFormat(shiftPerformance.lubricant_total_amount) }}

                                </span>
                            </td>
                            
                        </tr>
                        
                        
                        
                        <tr>
                            <td class="!text-left pl-2 font-semibold text-sm">Total Cash Sales Tk.</td>
                            <td class="p-1">
                                <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
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
                                <input type="text"  class="label-item-input" v-model="shiftPerformance.actual_cash_in_hand" />
                            </td>
                            <td></td>
                            <td class="p-1">
                                <span class="vms-readonly-input block w-full form-input py-2 !text-right pr-2 h-8">
                                    {{ numberFormat((shiftPerformance.difference) || 0) }}
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
        @apply block w-full mt-1 p-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
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