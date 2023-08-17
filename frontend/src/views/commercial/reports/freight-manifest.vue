<script setup>
import {onMounted, watch} from 'vue';
import { useRoute } from 'vue-router';
import moment from 'moment';
import useReport from '../../../composables/commercial/useReport';
import useVoyage from "../../../composables/operation/useVoyage";
import Title from "../../../services/title";
import useTableExportExcel from "../../../services/tableExportExcel";
const { voyages, voyageName, getVoyageName } = useVoyage();

const route = useRoute();
const { formParams, voyageInfo, freightCargoManifest } = useReport();
const { tableToExcel } = useTableExportExcel();

function getCargoManifestReportData(formData) {
  freightCargoManifest(formData);
}

const { setTitle } = Title();

setTitle('Freight Manifest');

watch(() => formParams.value.voyage_id, (val) => {
  //find a data from array where voyage_id is equal to val
  const voyage = voyageName.value.find((voyage) => voyage.id === val);
  formParams.value.voyage_name = voyage.voyage;
}, {
  deep: true
});

onMounted(() => {
  getVoyageName();
});

</script>

<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">FREIGHT MANIFEST</h2>
    </div>
    <!-- Table -->
    <div class="px-4 py-3 mb-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="getCargoManifestReportData(formParams)" class="flex items-end justify-between gap-4">
            <!-- Booking Form -->
            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Voyage No</span>
                <v-select :options="voyageName" placeholder="--Choose an option--" v-model="formParams.voyage_id"
                    :reduce="voyageName => voyageName.id" label="voyage"
                    class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                    required></v-select>
                <!--                <input type="text" required name="voyage_no" placeholder="Ex: 5011S,1565SN" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
            </label>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading"
                class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
        </form>
    </div>

  <button v-if="voyageInfo.billOfLadings" @click="tableToExcel('freight-manifest-table','freight-manifest-'+formParams.voyage_name)" class="w-1/6 mb-4 flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
    Download Excel
  </button>

    <div class="flex items-end justify-between gap-4" v-if="voyageInfo.billOfLadings">
        <h1 class="w-full">
            Name of Vessel:
            <strong>{{ voyageInfo?.voyage?.vessel.name }}</strong>
        </h1>
        <h1 class="w-full">
            Voyage No :
            <strong>{{ voyageInfo?.voyage?.voyage }}</strong>
        </h1>
        <h1 class="w-full">
            Sailing Date :
            <strong class="uppercase">
                {{
                    voyageInfo?.voyage?.sailing_date ? moment(voyageInfo?.voyage?.sailing_date).format('DD-MM-YYYY') :
                        null
                }}
            </strong>
        </h1>
    </div>

    <div class="w-full overflow-hidden border shadow-xs dark:border-0 mb-10">
        <div class="w-full overflow-x-auto scroll-show">

            <table v-if="voyageInfo.billOfLadings" class="w-full whitespace-no-wrap" id="freight-manifest-table">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th rowspan="2">Sl</th>
                        <th rowspan="2">B/L No.</th>
                        <th rowspan="2">Shipper</th>
                        <th rowspan="2">Consignee</th>
                        <th rowspan="2">POL</th>
                        <th rowspan="2">POD</th>
                        <th rowspan="2">Sailing Date (POL)</th>
                        <th rowspan="2">S/O</th>
                        <th rowspan="2">Trade</th>
                        <template v-for="(containerStatus, containerStatusKey) in voyageInfo.containerStatuses"
                            :key="containerStatusKey">
                            <th :colspan="containerStatus.length">
                                <strong v-if="containerStatusKey == 'FCL'">LADEN</strong>
                                <strong v-else>EMPTY</strong>
                            </th>
                        </template>
                        <th rowspan="2">Total Units</th>
                        <th rowspan="2">Total Tues</th>
                        <th rowspan="2">GROSS WT (Tons)</th>
                        <th rowspan="2">Mode</th>
                        <th rowspan="2">Curency</th>
                    </tr>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <template v-for="(containerStatus, containerStatusKey) in voyageInfo.containerStatuses"
                            :key="containerStatusKey">
                            <template v-for="(containerType, containerTypeIndex) in containerStatus"
                                :key="containerTypeIndex">
                                <th>{{ containerType }}</th>
                            </template>
                        </template>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr v-for="(billOfLading, index, blKey) in voyageInfo.billOfLadings" :key="blKey">
                        <td>{{ index + 1 }}</td>
                        <td>
                            <nobr>
                                {{ billOfLading.blNo }}
                            </nobr>
                        </td>
                        <td class="text-left"><nobr> {{ billOfLading.shipper }} </nobr></td>
                        <td class="text-left"><nobr> {{ billOfLading.consignee }} </nobr></td>
                        <td><nobr> {{ billOfLading.pol}}</nobr></td>
                        <td><nobr> {{ billOfLading.pod}}</nobr></td>
                        <td>
                            <nobr>
                                {{
                                    voyageInfo?.voyage?.sailing_date ?
                                        moment(voyageInfo?.voyage?.sailing_date).format('DD-MM-YYYY') : null
                                }}
                            </nobr>
                        </td>
                        <td>{{ billOfLading.customerCode }}</td>
                        <td> IPC/MTC </td>
                        <template v-for="(containerStatus, containerStatusKey) in voyageInfo.containerStatuses"
                            :key="containerStatusKey">
                            <template v-for="(containerType, containerTypeIndex) in containerStatus"
                                :key="containerTypeIndex">
                                <td>
                                    <template
                                        v-for="(blContainerStatus, blStatusKey) in billOfLading.blContainerStatuses"
                                        :key="blStatusKey">
                                        <template v-if="containerStatusKey == blStatusKey">
                                            <template v-for="(blTues, blContainerTypeKey) in blContainerStatus"
                                                :key="blContainerTypeKey">
                                                <template v-if="containerType == blContainerTypeKey">
                                                    {{ blTues }}
                                                </template>
                                            </template>
                                        </template>
                                    </template>
                                </td>
                            </template>
                        </template>
                        <td>{{ billOfLading.totalUnits }}</td>
                        <td>{{ billOfLading.totalTues }}</td>
                        <td>{{ billOfLading.totalGrossWeightMt }}</td>
                        <td>
                            <nobr>{{ billOfLading.term }}</nobr>
                        </td>
                        <td>USD</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style lang="postcss" scoped>
#freight-manifest-table,
#freight-manifest-table th,
#freight-manifest-table td {
    @apply border border-collapse text-center text-gray-500 px-1 text-xs;
}

.scroll-show::-webkit-scrollbar {
  height: 8px !important;
  border-radius: 15px !important;
  background: lightgray !important;
}

.scroll-show::-webkit-scrollbar-thumb {
  background: gray !important;
  border-radius: 10px !important;
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