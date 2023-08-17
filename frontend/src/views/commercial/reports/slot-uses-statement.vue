<script setup>
import { onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';

import useSlotUsesStatementReport from '../../../composables/commercial/useSlotUsesStatementReport';
import useCustomer from "../../../composables/commercial/useCustomer";
import useVoyage from "../../../composables/operation/useVoyage";

const route = useRoute();

const { customers, getCustomers } = useCustomer();

const { formParams, customerInfo, voyages, slotUsesStatementReport, isLoading } = useSlotUsesStatementReport();

onMounted(() => {
    getCustomers();
});


</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Slot Uses Statement</h2>
    </div>
    <!-- Table -->
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form class="flex items-end justify-between gap-4" @submit.prevent="slotUsesStatementReport(formParams)">
            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Voyage No</span>
                <input type="text" placeholder="Ex: 156,1586" v-model="formParams.voyage_no"
                    class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>

            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Customer Code</span>
                <v-select :options="customers" placeholder="--Choose an option--" v-model="formParams.customer_id"
                    :reduce="customer => customer.id" label="customer_code" required></v-select>
            </label>

            <!-- Submit button -->
            <button type="submit" :disabled="isLoading"
                class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
        </form>
    </div>

    <div class="flex items-end justify-between gap-4">
        <h1 class="w-full">
            A/C:
            <strong>{{ customerInfo.customerCode }} - {{ customerInfo.customerName }}</strong>
        </h1>
        <h1 class="w-full">
            VVD :
            <strong>{{ customerInfo.vesselName }} - {{ customerInfo.voyageNo }}</strong>
        </h1>
        <h1 class="w-full">
            Contract Type :
            <strong class="uppercase">{{ customerInfo.rateType }}</strong>
        </h1>
    </div>

    <div class="w-full my-2 overflow-hidden border rounded-lg shadow-xs dark:border-0">
        <h1 class="py-1 font-bold text-center text-white bg-green-500">Allocation & Rate</h1>
        <div class="w-full">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td rowspan="2">Bound</td>
                        <td colspan="4">Sector</td>
                        <td colspan="5">Allocation</td>
                        <td colspan="9">Rate</td>
                        <td rowspan="2">
                            D/F
                            <br />Amount
                        </td>
                    </tr>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <!-- sectors -->
                        <td>POL</td>
                        <td>POD</td>
                        <td>Term</td>
                        <td>
                            Additional
                            <br />Load
                        </td>
                        <!-- allocations -->
                        <td>Tues</td>
                        <td>WT (MT)</td>
                        <td>
                            WT/ TEU
                            <br />(MT)
                        </td>
                        <td>RF</td>
                        <td>HC</td>
                        <!-- rates -->
                        <td>DF</td>
                        <td>
                            Excess
                            <br />(LDN)
                        </td>
                        <td>
                            Excess
                            <br />(MT)
                        </td>
                        <td>RF / Plug</td>
                        <td>DG Unit</td>
                        <td>DG-1</td>
                        <td>DG-2</td>
                        <td>DG-3</td>
                        <td>DG-C</td>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <template v-for="(voyage, voyageKey) in customerInfo.voyages" :key="voyageKey">
                        <tr v-for="(sector, sectorKey) in voyage.boundCommittedPorts" :key="sectorKey"
                            class="text-gray-700 dark:text-gray-400">
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">{{ voyage.bound }}</td>
                            <!-- Sectors -->
                            <td>{{ sector.pol }}</td>
                            <td>{{ sector.pod }}</td>
                            <td>
                                <nobr>{{ sector.term }}</nobr>
                            </td>
                            <td>{{ sector.is_excess ? "Yes" : "No" }}</td>
                            <!-- Allocation -->
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">{{ voyage.boundTueAloc }}</td>
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">{{ voyage.boundGrossWeightAloc }}
                            </td>
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">{{ voyage.boundWeightLimitPerTue }}
                            </td>
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">{{ voyage.boundRefferAloc }}</td>
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">{{ voyage.boundHcAloc }}</td>
                            <!-- rates  -->
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">{{ voyage.boundDeadFreightRate }}
                            </td>
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">{{ voyage.boundExcessLadenRate }}
                            </td>
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">{{ voyage.boundExcessEmptyRate }}
                            </td>
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">{{ voyage.boundRfPerPlugAmount }}
                            </td>
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">
                                <span v-if="voyage.boundDgUnit == 'percentage'"> Per Tue <br> (% FRT) </span>
                                <span v-else>{{ voyage.boundDgUnit }}</span>
                            </td>
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">{{ voyage.boundDgGroup1Rate }}</td>
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">{{ voyage.boundDgGroup2Rate }}</td>
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">{{ voyage.boundDgGroup3Rate }}</td>
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">{{ voyage.boundDgGroupCRate }}</td>
                            <td :rowspan="voyage.ports.length" v-if="sectorKey == 0">{{
                                voyage.boundTotalDfAmount.toFixed(2)
                            }}</td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr
                        class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                        <td colspan="19" class="px-2 py-2 text-right">Total Dead Freight</td>
                        <td class="px-2 py-2 text-center">{{ customerInfo?.roundTripTotalDfAmount?.toFixed(2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- Allocation -->

    <div class="w-full my-2 border rounded-lg shadow-xs dark:border-0">
        <h1 class="py-1 font-bold text-center text-white bg-green-500">Tues Utilization</h1>
        <div class="w-full">
            <table class="w-full whitespace-no-wrap border rounded-lg">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td rowspan="2">Bound</td>
                        <td colspan="4">Sectors</td>
                        <td colspan="3">
                            Loads
                            <br />(Tues)
                        </td>
                        <td rowspan="2">
                            Allocation
                            <br />(Tues)
                        </td>
                        <td colspan="3">
                            Utilization
                            <br />(Tues)
                        </td>
                        <td colspan="3">
                            Over Used
                            <br />(Tues)
                        </td>
                        <td colspan="2">Excess Rate</td>
                        <td colspan="3">Excess Value</td>
                    </tr>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td>POL</td>
                        <td>POD</td>
                        <td>term</td>
                        <td>
                            Additional
                            <br />Load
                        </td>
                        <!--  Load Load -->
                        <td>FCL</td>
                        <td>MT</td>
                        <td>TTL</td>

                        <!-- Utilized -->
                        <td>LDN</td>
                        <td>MTY</td>
                        <td>TTL</td>

                        <!-- Excess Load -->
                        <td>LDN</td>
                        <td>MTY</td>
                        <td>TTL</td>

                        <!--  Excess Value -->
                        <td>
                            Rate
                            <br />(LDN)
                        </td>
                        <td>
                            Rate
                            <br />(MTY)
                        </td>

                        <!--  Excess Value -->
                        <td>
                            LDN Value
                            <br />(USD)
                        </td>
                        <td>
                            MTY Value
                            <br />(USD)
                        </td>
                        <td>
                            TTL Value
                            <br />(USD)
                        </td>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <template v-for="(voyage, voyageKey) in customerInfo.voyages" :key="voyageKey">
                        <tr v-for="(sector, index, sectorKey) in voyage.sectorUtilizations" :key="sectorKey">
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{ voyage.bound }}</td>
                            <td>{{ sector.polCode }}</td>
                            <td>{{ sector.podCode }}</td>
                            <td>{{ sector.sectorTerm }}</td>
                            <td>{{ sector.sectorExcessLoad ? "Yes" : "No" }}</td>
                            <td>{{ sector.sectorUtilizeLdnTues }}</td>
                            <td>{{ sector.sectorUtilizeEmptyTues }}</td>
                            <td>{{ sector.sectorTotalUtilizeTues }}</td>
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{ voyage.boundTueAloc }}</td>
                            <!-- Utilized -->
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{
                                voyage.boundUtilizeTuesLdn
                            }}
                            </td>
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{
                                voyage.boundUtilizeTuesEmpty
                            }}
                            </td>
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{
                                voyage.boundUtilizeTuesTotal
                            }}
                            </td>

                            <!-- Over Used -->
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{
                                voyage.boundExcessTuesLaden
                            }}
                            </td>
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{
                                voyage.boundExcessTuesEmpty
                            }}
                            </td>
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{
                                voyage.boundExcessTuesTotal
                            }}
                            </td>

                            <!-- Rate and  Value -->
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{
                                voyage.boundExcessLadenRate
                            }}
                            </td>
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{
                                voyage.boundExcessEmptyRate
                            }}
                            </td>

                            <!-- Excess Value -->
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">
                                {{ voyage.boundExcessTuesLadenAmount }}</td>
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">
                                {{ voyage.boundExcessTuesEmptyAmount }}</td>
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">
                                {{ voyage.boundExcessTuesTotalAmount }}</td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr
                        class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                        <td colspan="19" class="px-2 py-2 text-right">Total</td>
                        <td class="px-2 py-2 text-center">{{ customerInfo?.roundTripTotalExcessTuesAmount?.toFixed(2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- Tues Utilization -->


    <div class="w-full my-2 border rounded-lg shadow-xs dark:border-0">
        <h1 class="py-1 font-bold text-center text-white bg-green-500">Additional Load</h1>
        <div class="w-full">
            <table class="w-full whitespace-no-wrap border rounded-lg">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td rowspan="2">Bound</td>
                        <td colspan="2">Sectors</td>
                        <td colspan="3">
                            Utilization
                            <br />(Tues)
                        </td>
                        <td colspan="2">Rate</td>
                        <td colspan="3">Value</td>
                    </tr>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td>POL</td>
                        <td>POD</td>
                        <!-- Utilized -->
                        <td>LDN</td>
                        <td>MTY</td>
                        <td>TTL</td>

                        <!--  Excess Rate -->
                        <td>
                            Rate
                            <br />(LDN)
                        </td>
                        <td>
                            Rate
                            <br />(MTY)
                        </td>

                        <!--  Excess Value -->
                        <td>
                            LDN Value
                            <br />(USD)
                        </td>
                        <td>
                            MTY Value
                            <br />(USD)
                        </td>
                        <td>
                            TTL Value
                            <br />(USD)
                        </td>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <template v-for="(voyage, voyageKey) in customerInfo.voyages" :key="voyageKey">
                        <tr v-for="(excessSector, index, sectorKey) in voyage.excessSectors" :key="sectorKey">
                            <td> {{ voyage.bound }}</td>
                            <td>{{ excessSector.polCode }}</td>
                            <td>{{ excessSector.podCode }}</td>
                            <td>{{ excessSector.excessSectorTuesLdn }}</td>
                            <td>{{ excessSector.excessSectorTuesEmpty }}</td>
                            <td>{{ excessSector.excessSectorTuesTotal }}</td>
                            <td>{{ excessSector.excessSectorRateLdn }}</td>
                            <td>{{ excessSector.excessSectorRateEmpty }}</td>
                            <td>{{ excessSector.excessSectorAmountLdn.toFixed(2) }}</td>
                            <td>{{ excessSector.excessSectorAmountEmpty.toFixed(2) }}</td>
                            <td>{{ excessSector.excessSectorAmountTotal.toFixed(2) }}</td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr
                        class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                        <td colspan="10" class="px-2 py-2 text-right">Total</td>
                        <td class="px-2 py-2 text-center">{{
                            customerInfo?.roundTripTotalExcessSectorsAmount?.toFixed(2)
                        }} </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- Additional Load -->


    <div class="w-full my-2 border rounded-lg shadow-xs dark:border-0">
        <h1 class="py-1 font-bold text-center text-white bg-green-500">OOG Killed Slots</h1>
        <div class="w-full">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td>Bound</td>
                        <td>POL</td>
                        <td>POD</td>
                        <td>Killed Tues</td>
                        <td>Rate</td>
                        <td>Value (USD)</td>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <template v-for="(voyage, voyageKey) in customerInfo.voyages" :key="voyageKey">
                        <tr v-for="(sector, index, sectorKey) in voyage.sectors" :key="sectorKey">
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{ voyage.bound }}</td>
                            <td>{{ sector.polCode }}</td>
                            <td>{{ sector.podCode }}</td>
                            <td>{{ sector.sectorKilledTues }}</td>
                            <td>{{ voyage.boundDeadFreightRate }}</td>
                            <td>{{ sector.sectorKilledTuesAmount.toFixed(2) }}</td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr
                        class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                        <td colspan="5" class="px-2 py-2 text-right">Total</td>
                        <td class="px-2 py-2 text-center">{{ customerInfo?.roundTripTotalKilledTuesAmount?.toFixed(2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- OOG Killed Slots -->

    <div class="w-full my-2 border rounded-lg shadow-xs dark:border-0">
        <h1 class="py-1 font-bold text-center text-white bg-green-500">Weight Calculation</h1>
        <div class="w-full">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td rowspan="2">Bound</td>
                        <td rowspan="2">POL</td>
                        <td rowspan="2">POD</td>
                        <td rowspan="2">Box</td>
                        <td rowspan="2">
                            Weight
                            <br />Celling
                        </td>
                        <td rowspan="2">
                            Weight
                            <br />Utilization
                        </td>
                        <td rowspan="2">
                            Over Used
                            <br />Weight
                        </td>
                        <td rowspan="2">
                            Over Used
                            <br />In Tues
                        </td>
                        <td colspan="2">Excess Value</td>
                    </tr>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td>Rate</td>
                        <td>Value</td>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <template v-for="(voyage, voyageKey) in customerInfo.voyages" :key="voyageKey">
                        <tr v-for="(sector, index, sectorKey) in voyage.sectors" :key="sectorKey">
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{ voyage.bound }}</td>
                            <td>{{ sector.polCode }}</td>
                            <td>{{ sector.podCode }}</td>
                            <td>{{ voyage.boundTotalContainer }}</td>
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{
                                voyage.boundCellingWeight.toFixed(2)
                            }}</td>
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{
                                voyage.boundWeightUtilize.toFixed(2)
                            }}</td>
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{ voyage.boundOverWeight.toFixed(2) }}</td>
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{
                                voyage.boundOverWeightInTues.toFixed(2)
                            }}</td>
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{ voyage.boundExcessLadenRate }}
                            </td>
                            <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{ voyage.boundOverWeightAmount.toFixed(2) }}
                            </td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr
                        class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                        <td colspan="9" class="px-2 py-2 text-right">Total</td>
                        <td class="px-2 py-2 text-center">{{ customerInfo?.roundTripTotalOverWeightAmount?.toFixed(2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- Weight Calculation -->

    <div class="w-full my-2 border rounded-lg shadow-xs dark:border-0">
        <h1 class="py-1 font-bold text-center text-white bg-green-500">RF Surcharge</h1>
        <div class="w-full">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td>Bound</td>
                        <td>POL</td>
                        <td>POD</td>
                        <td>Per</td>
                        <td>Quantity</td>
                        <td>Rate</td>
                        <td>TTL</td>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <template v-for="(voyage, voyageKey) in customerInfo.voyages" :key="voyageKey">
                        <template v-for="(sector, index, sectorKey) in voyage.sectors" :key="sectorKey">
                            <tr>
                                <td :rowspan="voyage.total_sectors" v-if="sectorKey == 0">{{
                                    voyage.bound
                                }}</td>
                                <td>{{ sector.polCode }}</td>
                                <td>{{ sector.podCode }}</td>
                                <td>Plug</td>
                                <td>{{ sector.sectorUtilizedRefer }}</td>
                                <td>{{ voyage.boundRfPerPlugAmount }}</td>
                                <td>{{ sector.sectorUtilizedReferAmount.toFixed(2) }}</td>
                            </tr>
                        </template>
                    </template>
                </tbody>
                <tfoot>
                    <tr
                        class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                        <td colspan="6" class="px-2 py-2 text-right">Total</td>
                        <td class="px-2 py-2 text-center">{{ customerInfo?.roundTripTotalRfAmount?.toFixed(2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- Reffer Surcharge -->


    <div class="w-full my-2 border rounded-lg shadow-xs dark:border-0">
        <h1 class="py-1 font-bold text-center text-white bg-green-500">DG Surcharge</h1>
        <div class="w-full">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td>Bound</td>
                        <td>POL</td>
                        <td>POD</td>
                        <td>
                            Charge
                            <br />Head
                        </td>
                        <td>Per</td>
                        <td>Quantity</td>
                        <td>Rate</td>
                        <td>TTL</td>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <template v-for="(voyage, voyageKey) in customerInfo.voyages" :key="voyageKey">
                        <template v-for="(sector, index, sectorKey) in voyage.sectors" :key="sectorKey">
                            <tr>
                                <td :rowspan="voyage.total_sectors + voyage.total_sectors * 3" v-if="sectorKey == 0">{{
                                    voyage.bound
                                }}</td>
                                <td rowspan="4">{{ sector.polCode }}</td>
                                <td rowspan="4">{{ sector.podCode }}</td>
                                <td>DG-1</td>
                                <td class="capitalize">{{
                                    voyage.boundDgUnit == 'percentage' ? 'Per Tue (% FRT)' :
                                        voyage.boundDgUnit
                                }}</td>
                                <td>{{ sector.sectorDGGroup1 }}</td>
                                <td>{{ voyage.boundDgGroup1Rate }}</td>
                                <td>{{ sector.sectorDGGroup1Amount.toFixed(2) }}</td>
                            </tr>
                            <tr>
                                <td>DG-2</td>
                                <td class="capitalize">{{
                                    voyage.boundDgUnit == 'percentage' ? 'Per Tue (% FRT)' :
                                        voyage.boundDgUnit
                                }}</td>
                                <td class="capitalize">{{ sector.sectorDGGroup2 }}</td>
                                <td>{{ voyage.boundDgGroup2Rate }}</td>
                                <td>{{ sector.sectorDGGroup2Amount.toFixed(2) }}</td>
                            </tr>
                            <tr>
                                <td>DG-3</td>
                                <td class="capitalize">{{
                                    voyage.boundDgUnit == 'percentage' ? 'Per Tue (% FRT)' :
                                        voyage.boundDgUnit
                                }}</td>
                                <td>{{ sector.sectorDGGroup3 }}</td>
                                <td>{{ voyage.boundDgGroup3Rate }}</td>
                                <td>{{ sector.sectorDGGroup3Amount.toFixed(2) }}</td>
                            </tr>
                            <tr>
                                <td>DG-C</td>
                                <td class="capitalize">{{
                                    voyage.boundDgUnit == 'percentage' ? 'Per Tue (% FRT)' :
                                        voyage.boundDgUnit
                                }}</td>
                                <td>{{ sector.sectorDGGroupC }}</td>
                                <td>{{ voyage.boundDgGroupCRate }}</td>
                                <td>{{ sector.sectorDGGroupCAmount.toFixed(2) }}</td>
                            </tr>
                        </template>
                    </template>
                </tbody>
                <tfoot>
                    <tr
                        class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                        <td colspan="7" class="px-2 py-2 text-right">Total</td>
                        <td class="px-2 py-2 text-center">{{ customerInfo?.roundTripTotalDgAmount?.toFixed(2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- DG Surcharge -->

    <div class="flex items-center justify-center w-full overflow-hidden md:justify-end">
        <div class="w-6/12 my-2 overflow-hidden border rounded rounded-lg shadow-xs dark:border-0">
            <h1 class="py-1 font-bold text-center text-white bg-green-500">Summary</h1>
            <div class="w-full rounded-md">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                            <td>Revenue Head</td>
                            <td>Amount</td>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <tr>
                            <td>Dead Freight</td>
                            <td>{{ customerInfo?.roundTripTotalDfAmount?.toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <td>Excess Utilization</td>
                            <td>{{ customerInfo?.roundTripTotalExcessTuesAmount?.toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <td>Additional Load</td>
                            <td>{{ customerInfo?.roundTripTotalExcessSectorsAmount?.toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <td>Weight Surcharge</td>
                            <td>{{ customerInfo?.roundTripTotalOverWeightAmount?.toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <td>OOG Surcharge</td>
                            <td>{{ customerInfo?.roundTripTotalKilledTuesAmount?.toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <td>RF Surcharge</td>
                            <td>{{ customerInfo?.roundTripTotalRfAmount?.toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <td>DG Surcharge</td>
                            <td>{{ customerInfo?.roundTripTotalDgAmount?.toFixed(2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr
                            class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-yellow-500">
                            <td class="px-2 py-2 text-right">Total</td>
                            <td class="px-2 py-2 text-center">{{ customerInfo?.roundTripGrandTotalAmount }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <!-- Summary Surcharge -->
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
    th {
        @apply p-10 border-r text-center;
    }

    tbody td,
    thead td,
    thead th {
        @apply px-1 py-1 text-xs border-r text-center;
    }
}
</style>