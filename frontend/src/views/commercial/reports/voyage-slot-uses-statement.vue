<script setup>

const props = defineProps({
  voyageInfo: {
    required: false,
    default: {}
  },
});

// const { customers, getCustomers, getCustomerWithoutPaginate } = useCustomer();
// const { voyages, voyageCustomers, getVoyageName } = useVoyage();
//
// const { formParams, voyageInfo, voyageSlotUsesStatementReport, isLoading } = useVoyageSlotUsesStatementReport();
//
// function fetchOptions(search) {
//   getVoyageName(search);
// }
//
// onMounted(() => {
//     getCustomerWithoutPaginate();
// });

</script>
<template>

    <div class="w-full my-2 overflow-hidden border rounded-lg shadow-xs dark:border-0">

        <h1 class="py-1 font-bold text-white bg-green-500 text-center">Slot Arrangement</h1>
        <div class="w-full">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td rowspan="2">Bound</td>
                        <td colspan="4">Sector</td>
                        <td colspan="5">Allocation</td>
                        <td colspan="9">Rate</td>
                        <td rowspan="2"> D/F <br />Amount </td>
                    </tr>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <!-- sectors -->
                        <td>POL</td>
                        <td>POD</td>
                        <td>Term</td>
                        <td> Additional <br />Corridor </td>
                        <!-- allocations -->
                        <td>Tues</td>
                        <td>WT (MT)</td>
                        <td> WT/ TEU <br />(MT) </td>
                        <td>RF</td>
                        <td>HC</td>
                        <!-- rates -->
                        <td>DF</td>
                        <td> Excess <br />(LDN) </td>
                        <td> Excess <br />(MT) </td>
                        <td>RF / Plug</td>
                        <td>DG Unit</td>
                        <td>DG-1</td>
                        <td>DG-2</td>
                        <td>DG-3</td>
                        <td>DG-C</td>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr v-for="(sector, sectorKey) in voyageInfo.boundCommittedPorts" :key="sectorKey"
                        class="text-gray-700 dark:text-gray-400">
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0" style="text-transform: capitalize">{{ voyageInfo.bound }}</td>
                        <!-- Sectors -->
                        <td>{{ sector.pol }}</td>
                        <td>{{ sector.pod }}</td>
                        <td>
                            <nobr>{{ sector.term }}</nobr>
                        </td>
                        <td>{{ sector.is_excess ? "Yes" : "No" }}</td>
                        <!-- Allocation -->
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0">{{ voyageInfo?.boundTueAloc }}</td>
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0">{{ voyageInfo?.boundGrossWeightAloc }}
                        </td>
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0">{{ voyageInfo?.boundWeightLimitPerTue }}
                        </td>
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0">{{ voyageInfo?.boundRefferAloc }}</td>
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0">{{ voyageInfo?.boundHcAloc }}</td>
                        <!-- rates  -->
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0">{{ voyageInfo?.boundDeadFreightRate }}
                        </td>
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0">{{ voyageInfo?.boundExcessLadenRate.toFixed(2) }}
                        </td>
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0">{{ voyageInfo?.boundExcessEmptyRate.toFixed(2) }}
                        </td>
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0">{{ voyageInfo?.boundRfPerPlugAmount }}
                        </td>
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0">
                            <span v-if="voyageInfo.boundDgUnit == 'percentage'"> Per Tue <br> (% FRT) </span>
                            <span v-else>{{ voyageInfo.boundDgUnit }}</span>
                        </td>
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0">{{ voyageInfo?.boundDgGroup1Rate }}</td>
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0">{{ voyageInfo?.boundDgGroup2Rate }}</td>
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0">{{ voyageInfo?.boundDgGroup3Rate }}</td>
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0">{{ voyageInfo?.boundDgGroupCRate }}</td>
                        <td :rowspan="voyageInfo.ports.length" v-if="sectorKey == 0">{{ voyageInfo?.boundTotalDfAmount.toFixed(2) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr
                        class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                        <td colspan="19" class="px-2 py-2 text-right">Total Dead Freight</td>
                        <td class="px-2 py-2 text-center">{{ voyageInfo?.boundTotalDfAmount?.toFixed(2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- Allocation -->

    <div class="w-full my-2 border rounded-lg shadow-xs dark:border-0">
        <h1 class="py-1 font-bold text-center text-white bg-green-500">Slot Utilization</h1>
        <div class="w-full">
            <table class="w-full whitespace-no-wrap border rounded-lg">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td rowspan="2">Bound</td>
                        <td colspan="4">Sectors</td>
                        <td colspan="3"> Loads <br />(Tues) </td>
                        <td rowspan="2"> Allocation <br />(Tues) </td>
                        <td colspan="3"> Utilization <br />(Tues) </td>
                        <td colspan="3"> Over Used<br />(Tues) </td>
                        <td colspan="2">Excess Rate </td>
                        <td colspan="3">Excess Value </td>
                    </tr>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td>POL</td>
                        <td>POD</td>
                        <td>term</td>
                        <td> Additional <br />Load </td>
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
                        <td> Rate <br />(LDN) </td>
                        <td> Rate <br />(MTY) </td>

                        <!--  Excess Value -->
                        <td> LDN Value <br />(USD) </td>
                        <td> MTY Value <br />(USD) </td>
                        <td> TTL Value <br />(USD) </td>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr v-for="(sector, index, sectorKey) in voyageInfo.sectorUtilizations" :key="sectorKey">
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0" style="text-transform: capitalize">{{ voyageInfo.bound }}</td>
                        <td>{{ sector.polCode }}</td>
                        <td>{{ sector.podCode }}</td>
                        <td>{{ sector.sectorTerm }}</td>
                        <td>{{ sector.sectorExcessLoad ? "Yes" : "No" }}</td>
                        <td>{{ sector.sectorUtilizeLdnTues }}</td>
                        <td>{{ sector.sectorUtilizeEmptyTues }}</td>
                        <td>{{ sector.sectorTotalUtilizeTues }}</td>
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0">{{ voyageInfo.boundTueAloc }}</td>
                        <!-- Utilized -->
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0">{{voyageInfo.boundUtilizeTuesLdn}}</td>
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0">{{voyageInfo.boundUtilizeTuesEmpty}}</td>
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0">{{voyageInfo.boundUtilizeTuesTotal}}</td>

                        <!-- Over Used -->
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0">{{voyageInfo.boundExcessTuesLaden.toFixed(2)}}</td>
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0">{{voyageInfo.boundExcessTuesEmpty}}</td>
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0">{{voyageInfo.boundExcessTuesTotal}}</td>

                        <!-- Rate and  Value -->
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0">{{voyageInfo.boundExcessLadenRate.toFixed(2)}}</td>
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0">{{voyageInfo.boundExcessEmptyRate.toFixed(2)}}</td>

                        <!-- Excess Value -->
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0"> {{ voyageInfo.boundExcessTuesLadenAmount.toFixed(2) }} </td>
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0"> {{ voyageInfo.boundExcessTuesEmptyAmount.toFixed(2) }} </td>
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0"> {{ voyageInfo.boundExcessTuesTotalAmount.toFixed(2) }} </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr
                        class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                        <td colspan="19" class="px-2 py-2 text-right">Total</td>
                        <td class="px-2 py-2 text-center">{{ voyageInfo?.boundExcessTuesTotalAmount?.toFixed(2) }}</td>
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
                        <td colspan="3"> Utilization <br />(Tues) </td>
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
                        <td>Rate<br />(LDN)</td>
                        <td> Rate <br />(MTY) </td>

                        <!--  Excess Value -->
                        <td> LDN Value <br />(USD) </td>
                        <td> MTY Value <br />(USD) </td>
                        <td> TTL Value <br />(USD) </td>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <template v-for="(voyage, voyageKey) in voyageInfo.voyages" :key="voyageKey">
                        <tr v-for="(excessSector, index, sectorKey) in voyageInfo.excessSectors" :key="sectorKey">
                            <td style="text-transform: capitalize"> {{ voyageInfo.bound }}</td>
                            <td>{{ excessSector.polCode }}</td>
                            <td>{{ excessSector.podCode }}</td>
                            <td>{{ excessSector.excessSectorTuesLdn }}</td>
                            <td>{{ excessSector.excessSectorTuesEmpty }}</td>
                            <td>{{ excessSector.excessSectorTuesTotal }}</td>
                            <td>{{ excessSector.excessSectorRateLdn.toFixed(2) }}</td>
                            <td>{{ excessSector.excessSectorRateEmpty.toFixed(2) }}</td>
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
                        <td class="px-2 py-2 text-center"> {{voyageInfo?.voyageExcessSectorsTotalAmount?.toFixed(2)}} </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- Additional Load -->

    <div class="w-full my-2 border rounded-lg shadow-xs dark:border-0">
        <h1 class="py-1 font-bold text-center text-white bg-green-500">Weight Calculation</h1>
        <div class="w-full">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td rowspan="2"> Bound </td>
                        <td rowspan="2"> POL </td>
                        <td rowspan="2"> POD </td>
                        <td rowspan="2"> Box </td>
                        <td rowspan="2"> Weight <br />Celling </td>
                        <td rowspan="2"> Weight <br />Utilization </td>
                        <td rowspan="2"> Over Used <br />Weight </td>
                        <td rowspan="2"> Over Used <br />In Tues </td>
                        <td colspan="2">Excess Value</td>
                    </tr>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td>Rate</td>
                        <td>Value</td>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr v-for="(sector, index, sectorKey) in voyageInfo.sectors" :key="sectorKey">
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0" style="text-transform: capitalize">{{ voyageInfo.bound }}</td>
                        <td>{{ sector.polCode }}</td>
                        <td>{{ sector.podCode }}</td>
                        <td>{{ voyageInfo.boundTotalContainer }}</td>
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0"> {{ voyageInfo.boundCellingWeight.toFixed(2)}} </td>
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0"> {{ voyageInfo.boundWeightUtilize.toFixed(2)}} </td>
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0"> {{ voyageInfo.boundOverWeight.toFixed(2) }} </td>
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0"> {{ voyageInfo.boundOverWeightInTues}} </td>
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0"> {{ voyageInfo.boundExcessLadenRate.toFixed(2) }} </td>
                        <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0"> {{ voyageInfo.boundOverWeightAmount.toFixed(2) }} </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                        <td colspan="9" class="px-2 py-2 text-right">Total</td>
                        <td class="px-2 py-2 text-center">{{ voyageInfo?.boundOverWeightAmount?.toFixed(2) }} </td>
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
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
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
                    <template v-for="(sector, index, sectorKey) in voyageInfo.sectors" :key="sectorKey">
                        <tr>
                            <td :rowspan="voyageInfo.total_sectors" v-if="sectorKey == 0" style="text-transform: capitalize">{{voyageInfo.bound}}</td>
                            <td>{{ sector.polCode }}</td>
                            <td>{{ sector.podCode }}</td>
                            <td>{{ voyageInfo.boundRfUnit }}</td>
                            <td>{{ sector.sectorUtilizedRefer }}</td>
                            <td>{{ voyageInfo.boundRfPerPlugAmount }}</td>
                            <td>{{ sector.sectorUtilizedReferAmount.toFixed(2) }}</td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr
                        class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                        <td colspan="6" class="px-2 py-2 text-right">Total</td>
                        <td class="px-2 py-2 text-center">{{ voyageInfo?.voyageTotalRfAmount?.toFixed(2) }}</td>
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
                        <td> Charge <br />Head </td>
                        <td>Per</td>
                        <td>Quantity</td>
                        <td>Rate</td>
                        <td>TTL</td>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <template v-for="(sector, index, sectorKey) in voyageInfo.sectors" :key="sectorKey">
                        <tr>
                            <td :rowspan="voyageInfo.total_sectors + voyageInfo.total_sectors * 3" v-if="sectorKey == 0" style="text-transform: capitalize">
                                {{voyageInfo.bound }}</td>
                            <td rowspan="4">{{ sector.polCode }}</td>
                            <td rowspan="4">{{ sector.podCode }}</td>
                            <td>DG-1</td>
                            <td class="capitalize"> {{ voyageInfo.boundDgUnit == 'percentage' ? 'Per Tue (% FRT)' : voyageInfo.boundDgUnit}} </td>
                            <td>{{ sector.sectorDGGroup1 }}</td>
                            <td>{{ voyageInfo.boundDgGroup1Rate }}</td>
                            <td>{{ sector.sectorDGGroup1Amount.toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <td>DG-2</td>
                            <td class="capitalize">{{ voyageInfo.boundDgUnit == 'percentage' ? 'Per Tue (% FRT)' : voyageInfo.boundDgUnit }}</td>
                            <td class="capitalize">{{ sector.sectorDGGroup2 }}</td>
                            <td>{{ voyageInfo.boundDgGroup2Rate }}</td>
                            <td>{{ sector.sectorDGGroup2Amount.toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <td>DG-3</td>
                            <td class="capitalize">{{ voyageInfo.boundDgUnit == 'percentage' ? 'Per Tue (% FRT)' : voyageInfo.boundDgUnit }} </td>
                            <td>{{ sector.sectorDGGroup3 }}</td>
                            <td>{{ voyageInfo.boundDgGroup3Rate }}</td>
                            <td>{{ sector.sectorDGGroup3Amount.toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <td>DG-C</td>
                            <td class="capitalize">{{ voyageInfo.boundDgUnit == 'percentage' ? 'Per Tue (% FRT)' : voyageInfo.boundDgUnit }}</td>
                            <td>{{ sector.sectorDGGroupC }}</td>
                            <td>{{ voyageInfo.boundDgGroupCRate }}</td>
                            <td>{{ sector.sectorDGGroupCAmount.toFixed(2) }}</td>
                        </tr>
                    </template>
                </tbody>
                <tfoot>
                    <tr
                        class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-gray-700">
                        <td colspan="7" class="px-2 py-2 text-right">Total</td>
                        <td class="px-2 py-2 text-center">{{ voyageInfo?.voyageTotalDgAmount?.toFixed(2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- DG Surcharge -->

    <div class="flex items-center justify-center w-full overflow-hidden md:justify-end mb-5">
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
                            <td>{{ voyageInfo?.boundTotalDfAmount?.toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <td>Excess Utilization</td>
                            <td>{{ voyageInfo?.boundExcessTuesTotalAmount?.toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <td>Additional Load</td>
                            <td>{{ voyageInfo?.voyageExcessSectorsTotalAmount?.toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <td>Weight Surcharge</td>
                            <td>{{ voyageInfo?.boundOverWeightAmount?.toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <td>RF Surcharge</td>
                            <td>{{ voyageInfo?.voyageTotalRfAmount?.toFixed(2) }}</td>
                        </tr>
                        <tr>
                            <td>DG Surcharge</td>
                            <td>{{ voyageInfo?.roundTripTotalDgAmount?.toFixed(2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr
                            class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-yellow-500">
                            <td class="px-2 py-2 text-right">Total</td>
                            <td class="px-2 py-2 text-center"> {{ voyageInfo?.voyageGrandTotalAmount?.toFixed(2) }}
                            </td>
                        </tr>
                        <tr class="text-xs font-bold text-gray-600 bg-green-100 dark:text-gray-400 dark:text-gray-50 dark:bg-yellow-500">
                            <td class="px-2 py-2 text-right"> Notes (Dr/CR) </td>
                            <td class="px-2 py-2 text-center"> {{ voyageInfo?.invoice_notes }}</td>
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
>>> {
  --vs-controls-color: #374151;
  --vs-border-color: #4b5563;

  --vs-dropdown-bg: #282c34;
  --vs-dropdown-color: #eeeeee;
  --vs-dropdown-option-color: #eeeeee;

  --vs-selected-bg: #664cc3;
  --vs-selected-color: #374151;

  --vs-search-input-color: #4b5563;

  --vs-dropdown-option--active-bg: #664cc3;
  --vs-dropdown-option--active-color: #eeeeee;
}
</style>