<script setup>
import { onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import moment from 'moment';
import useReport from '../../../composables/commercial/useReport';
import useGenerateBlReport from '../../../composables/documentation/useGenerateBlReport';
import useVoyage from "../../../composables/operation/useVoyage";
import useInvoice from "../../../composables/commercial/useInvoice";
import Title from "../../../services/title";
import useTableExportExcel from "../../../services/tableExportExcel";
const { voyages, voyageName, getVoyageName } = useVoyage();
const { tableToExcel } = useTableExportExcel();

const route = useRoute();
const { formParams, voyageInfo, freightCargoManifest } = useReport();
const { downloadInvoice } = useInvoice();
const {  generateBlReport } = useGenerateBlReport();


function getCargoManifestReportData(formParams) {

  if(!formParams.voyage_id) {
    alert('Please select voyage no');
    return;
  }

  freightCargoManifest(formParams);
}

const { setTitle } = Title();

setTitle('Freight & Cargo Manifest');

watch(() => formParams.value.voyage_id, (val) => {
  //find a data from array where voyage_id is equal to val
  const voyage = voyageName.value.find((voyage) => voyage.id === val);
  formParams.value.voyage_name = voyage.voyage;
}, {
  deep: true
});

function downloadCustomerInvoice(invoiceId) {
  downloadInvoice(invoiceId);
}

function send(singleLoadId = null) {
  let loadInfo_ids = [];
  
  if(singleLoadId) {
    loadInfo_ids = [singleLoadId];
  } else {
    loadInfo_ids = blLoadInfo.value.data.filter(loadInfo => loadInfo.isSelected).map(loadInfo => loadInfo.id);
  }
  if (loadInfo_ids.length === 0) {
    message.value = "Please select at least one BL.";
    return;
  }
  generateBlReport({ loadInfo_ids });
  message.value = "";
  isALlSelected.value = false;
  checkAll();
}

onMounted(() => {
  getVoyageName();
});
</script>

<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">FREIGHT AND CARGO MANIFEST</h2>
    </div>
    <!-- Table -->
    <div class="px-4 py-3 mb-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="getCargoManifestReportData(formParams)" class="flex items-end justify-between gap-4">
            <!-- Booking Form -->
            <label class="block w-full text-sm">
                <span class="text-gray-700 dark:text-gray-300">Voyage No <span class="custom_required_field">*</span></span>
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

  <button v-if="voyageInfo.billOfLadings" @click="tableToExcel('freight-cargo-manifest-table','freight-cargo-manifest-'+formParams.voyage_name)" class="w-1/6 mb-4 flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
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
                {{ voyageInfo?.voyage?.sailing_date ? moment(voyageInfo?.voyage?.sailing_date).format('DD-MM-YYYY') : null }}
            </strong>
        </h1>
    </div>

    <div class="w-full mb-8 overflow-hidden border shadow-xs dark:border-0">
        <div class="w-full overflow-x-auto scroll-show">
            <table class="w-full whitespace-no-wrap" id="freight-cargo-manifest-table" v-if="voyageInfo.billOfLadings">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <td colspan="9"></td>
                        <template v-for="(containerStatus, containerStatusKey) in voyageInfo.containerStatuses"
                            :key="containerStatusKey">
                            <th :colspan="containerStatus.length">
                                <strong v-if="containerStatusKey == 'FCL'">LADEN</strong>
                                <strong v-else>EMPTY</strong>
                            </th>
                        </template>
                        <td colspan="5"></td>
                        <th colspan="3"> Freight & Surcharge </th>
                        <td colspan="2"> </td>
                    </tr>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th> Sl </th>
                        <th> B/L No  </th>
                        <th style="min-width : 125px!important"> Invoice No  </th>
                        <th> Shipper </th>
                        <th> Consignee </th>
                        <th> POL </th>
                        <th> POD </th>
                        <th> S/O </th>
                        <th> Trade </th>
                        <template v-for="(containerStatus, containerStatusKey) in voyageInfo.containerStatuses"
                            :key="containerStatusKey">
                            <template v-for="(containerType, containerTypeIndex) in containerStatus"
                                :key="containerTypeIndex">
                                <th>{{ containerType }}</th>
                            </template>
                        </template>                        
                        <th> Total Units </th>
                        <th> Total Tues </th>
                        <th> GROSS WT (Tons) </th>
                        <th> Mode </th>
                        <th> Curency </th>
                        <th> Prepaid Amount </th>
                        <th> Collect Amount </th>
                        <th> TTL Amount </th>
                        <th> Payment Location </th>
                        <th> Customer Code </th>                        
                    </tr>
                </thead>

                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr v-for="(billOfLading, index, blKey) in voyageInfo.billOfLadings" :key="blKey">
                        <td>{{ index + 1 }}</td>
                        <td>
                            <span class="text-purple-600 cursor-pointer hover:bg-purple-100" @click="send(billOfLading.bl_id)" target="_blank">
                                <nobr>
                                    <strong> {{ billOfLading.blNo }} </strong>
                                </nobr>
                            </span>
                        </td>
                        <td style="text-align: left;">
                            {{ billOfLading.invoices }}
                        </td>
                        <td class="text-left"><nobr> {{ billOfLading.shipper }} </nobr></td>
                        <td class="text-left"><nobr> {{ billOfLading.consignee }} </nobr></td>
                        <td> {{ billOfLading.pol }} </td>
                        <td> {{ billOfLading.pod }} </td>
                        <td>{{ billOfLading.slot_partner }} </td>
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
                        <td style="text-align:right">{{ billOfLading.f_prepaid }}</td>
                        <td style="text-align:right">{{ billOfLading.f_postpaid }}</td>
                        <td style="text-align:right">{{ billOfLading.grand_ttl_amount }}</td>
                        <td>
                            <strong> {{ billOfLading.f_payment_location_port }} </strong>
                        </td>
                        <td>{{ billOfLading.customerCode }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style lang="postcss" scoped>
#freight-cargo-manifest-table,
#freight-cargo-manifest-table th,
#freight-cargo-manifest-table td {
    @apply border border-collapse text-center text-gray-500 px-1 text-xs;
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