<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';

import useReport from '../../../composables/commercial/useReport';
import useCustomer from "../../../composables/commercial/useCustomer";

const route = useRoute();
// const voyageId = route.params.voyageId;
// const customerCode = route.params.customerCode;
const { voyageInfo, formParams, customerLoadCalculation } = useReport();
const { customers, getCustomers, isLoading } = useCustomer();

// function fetchOptions(search, loading) {
//   getCustomerCode(search);
// }

onMounted(() => {
  getCustomers();
});
</script>

<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Load Summary</h2>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="customerLoadCalculation(formParams)" class="flex items-end justify-between gap-4">
            <!-- Booking Form -->
            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Voyage No (Round) </span>
                <input type="text" required v-model="formParams.voyage_no" placeholder="Ex: 156,1586" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>
            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Customer Code</span>
              <v-select :options="customers" placeholder="--Choose an option--" v-model="formParams.customer_id" :reduce="customer => customer.id" label="customer_code" required></v-select>
            </label>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
        </form>
    </div>
    <!-- Table -->
    <div class="w-full overflow-hidden border shadow-xs dark:border-0">
        <div class="w-full overflow-x-auto">
            <table class="table w-full whitespace-no-wrap" id="table">
                <caption class="text-gray-200 bg-gray-700">
                    <strong>Load Summary</strong>
                </caption>
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-dark:border-gray-700 bg-gray-80 dark:text-gray-400 dark:bg-gray-800">
                        <th rowspan="2">Bound</th>
                        <th rowspan="2">POD</th>
                        <th rowspan="2">POL</th>
                        <th rowspan="2">TUES</th>
                        <th rowspan="2">WEIGHT (MT)</th>
                        <template v-for="(containerStatus, containerStatusKey) in voyageInfo.containerStatuses" :key="containerStatusKey">
                            <th :colspan="containerStatus.length + 1">
                                <strong v-if="containerStatusKey == 'FCL'">LADEN</strong>
                                <strong v-else>EMTPY</strong>
                            </th>
                        </template>
                    </tr>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-dark:border-gray-700 bg-gray-80 dark:text-gray-400 dark:bg-gray-800">
                        <template v-for="(containerStatus, containerStatusKey) in voyageInfo.containerStatuses" :key="containerStatusKey">
                            <template v-for="(containerType, containerTypeIndex) in containerStatus" :key="containerTypeIndex">
                                <th>{{ containerType }}</th>
                            </template>
                            <th>TTL</th>
                        </template>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <template v-for="(voyage, voyageKey) in voyageInfo.loads" :key="voyageKey">
                        <tr v-for="(sector, sectorIndex) in voyage.sectors" :key="sectorIndex">
                            <td>{{ voyage.bound }}</td>
                            <td>{{ sector.polCode }}</td>
                            <td>{{ sector.podCode }}</td>
                            <td>{{ sector.totalTues }}</td>
                            <td>{{ sector.totalGrossWtMt.toFixed(2) }}</td>
                            <template v-for="(containerStatus, containerStatusKey) in voyageInfo.containerStatuses" :key="containerStatusKey">
                                <template v-for="(containerType, containerTypeIndex) in containerStatus" :key="containerTypeIndex">
                                    <td>
                                        <template v-for="(sectorContainerStatus, sectorContainerStatusKey) in sector.sectorContainerStatuses" :key="sectorContainerStatusKey">
                                            
                                            <template v-if="containerStatusKey == sectorContainerStatusKey">
                                                <template v-for="(podContainerTues, podContainerType) in sectorContainerStatus" :key="podContainerType">
                                                    <template v-if="containerType == podContainerType">{{ podContainerTues }}</template>
                                                </template>
                                            </template>
                                        </template>
                                    </td>
                                </template>

                                <td>
                                    <template v-for="(sectorContainerStatusSum, podContainerSumKey) in sector.sectorContainerStatusSum" :key="podContainerSumKey">
                                        <template v-if="containerStatusKey == podContainerSumKey">
                                            {{ sectorContainerStatusSum }}
                                            <br />
                                        </template>
                                    </template>
                                </td>
                            </template>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="w-full my-4 overflow-x-auto">
            <table class="table w-full whitespace-no-wrap" id="table">
                <caption class="text-gray-200 bg-gray-700">
                    <strong>Bound Wise Utilization (Tues & Weight)</strong>
                </caption>
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-dark:border-gray-700 bg-gray-80 dark:text-gray-400 dark:bg-gray-800">
                        <th>Bound</th>
                        <th>LDN Tues</th>
                        <th>MTY Tues</th>
                        <th>TTL Tues</th>
                        <th>WEIGHT (MT)</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <template v-for="(voyage, voyageKey) in voyageInfo.loads" :key="voyageKey">
                        <tr>
                            <td>{{ voyage.bound }}</td>
                            <td>{{ voyage.totalTuesLDN }}</td>
                            <td>{{ voyage.totalTuesMTY }}</td>
                            <td>{{ voyage.totalTues }}</td>
                            <td>{{ voyage.totalGrossWeightMt?.toFixed(2) }}</td>
                        </tr>   
                    </template>
                </tbody>
            </table>

            <table class="table w-full mt-4 whitespace-no-wrap">
                <caption class="text-gray-200 bg-gray-700">
                    <strong>Excess Utilization</strong>
                </caption>
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-dark:border-gray-700 bg-gray-80 dark:text-gray-400 dark:bg-gray-800">
                        <th>Bound</th>
                        <th>Tues Aloc.</th>
                        <th>Tues Uti.</th>
                        <th>WT Aloc. (MT)</th>
                        <th>WT Uti. (MT)</th>
                        <th>Tue Exc.</th>
                        <th>WT Exc.</th>
                        <th>WT Equ Tue.</th>
                        <th>TTL Bill Tue.</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <template v-for="(voyage, voyageKey) in voyageInfo.loads" :key="voyageKey">
                        <tr>
                            <td>{{ voyage.bound }}</td>
                            <td>{{ voyage.totalTuesAloc }}</td>
                            <td>{{ voyage.totalTues }}</td>
                            <td>{{ (voyage.totalGrossWeightAloc).toFixed(2) }}</td>
                            <td>{{ voyage.totalGrossWeightMt }}</td>
                            <td>
                                <strong class="text-red-500">{{ voyage.excessTues }}</strong>
                            </td>
                            <td>
                                <strong class="text-red-500">{{ (voyage.excessWeight).toFixed(2) }}</strong>
                            </td>
                            <td>
                                <strong class="text-red-500">{{ (voyage.excessWeightInTues).toFixed(2) }}</strong>
                            </td>
                            <td>
                                <strong class="text-green-500">{{ (voyage.billableTues).toFixed(2) }}</strong>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <table class="table w-full mt-4 whitespace-no-wrap">
                <caption class="text-gray-200 bg-gray-700">
                    <strong>Receivable Freight</strong>
                </caption>
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-dark:border-gray-700 bg-gray-80 dark:text-gray-400 dark:bg-gray-800">
                        <th rowspan="2">Bound</th>
                        <th colspan="2">Dead Freight</th>
                        <th colspan="2">Excess-LDN</th>
                        <th colspan="2">Excess-MTY</th>
                        <th colspan="2">Excess-Weight</th>
                        <th rowspan="2">TTL Amount</th>
                    </tr>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-dark:border-gray-700 bg-gray-80 dark:text-gray-400 dark:bg-gray-800">
                        <th>Tues</th>
                        <th>Rate</th>
                        <th>Tues</th>
                        <th>Rate</th>
                        <th>Tues</th>
                        <th>Rate</th>
                        <th>Tues</th>
                        <th>Rate</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <template v-for="(voyage, voyageKey) in voyageInfo.loads" :key="voyageKey">
                        <tr>
                            <td>{{ voyage.bound }}</td>
                            <td>{{ voyage.totalTuesAloc }}</td>
                            <td>{{ voyage.boundDeadFreightRate }}</td>
                            <td>{{ voyage.excessTues }}</td>
                            <td>{{ voyage.boundExcessLadenRate }}</td>
                            <td>Later</td>
                            <td>Later</td>
                            <td>{{ voyage.excessWeightInTues }}</td>
                            <td>{{ voyage.boundExcessLadenRate }}</td>
                            <td>{{ voyage.totalReceivableAmount.toFixed(2) }}</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style lang="postcss" scoped>
.table,
.table th,
.table td {
    @apply border border-collapse text-center text-gray-500 px-1;
}
</style>