<script setup>
import {ref,onMounted, watchPostEffect} from "vue";

import Title from "../../../services/title";
import useHelper from "../../../composables/useHelper.js";
import useWorkReceiptReport from "../../../composables/supply-chain/useWorkReceiptReport";
import MaterialReceiptReportShow from "../../../components/supply-chain/material-receipt-reports/MaterialReceiptReportShow.vue";
import { useRoute } from 'vue-router';

const { getWorkReceiptReports, showWorkReceiptReport, workReceiptReport, updateWorkReceiptReport, errors, isLoading } = useWorkReceiptReport();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import { formatDate } from "../../../config/setting";

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const workReceiptReportId = route.params.workReceiptReportId;
const formType = 'edit';

setTitle('Work Receipt Report Details');

onMounted(() => {
    showWorkReceiptReport(workReceiptReportId);
});
</script>
<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
      <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Work Receipt Report Details</h2>
      <default-button :title="'Work Receipt Report List'" :to="{ name: 'scm.work-receipt-reports.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
          <!-- <material-receipt-report-show v-model:form="materialReceiptReport"></material-receipt-report-show> -->
          <div class="w-full">
            <table class="w-full">
                <thead>
                    <tr>
                        <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Basic Info</td>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                        <th class="w-40">WRR Ref</th>
                        <td>{{ workReceiptReport?.ref_no }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Purchase Center</th>
                        <td>{{ workReceiptReport?.purchase_center }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Warehouse</th>
                        <td>{{ workReceiptReport?.scmWarehouse?.name }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Receive Date</th>
                        <td>{{ formatDate(workReceiptReport.date) }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Challan No</th>
                        <td>{{ workReceiptReport.challan_no }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">WO Ref</th>
                        <td>{{ workReceiptReport.scmWo?.ref_no }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">WO Date</th>
                        <td>{{ formatDate(workReceiptReport.scmWo?.date) }}</td>
                    </tr>

                    
                    
                    <tr>
                        <th class="w-40">Remarks</th>
                        <td>{{ workReceiptReport.remarks }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Qc Remarks</th>
                        <td>{{ workReceiptReport.qc_remarks }}</td>
                    </tr>
               
                </tbody>
            </table>
        </div>

        
        <div class="md:flex md:gap-2 ">
          <div class="w-full mt-2">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="!text-center font-bold bg-green-600 uppercase text-white" colspan="8">Work Receive Report Information</th>
                    </tr>
                    
                    <tr>
                        <th class="!text-center">WR No.</th>
                        <th class="!text-center">WR Date</th>
                        <th class="!text-center">Service - Code</th>
                        <th class="!text-center">WR Qty</th>
                        <th class="!text-center">WO Qty</th>
                        <th class="!text-center">Remaining Qty</th>
                        <th class="!text-center">Qty</th>
                        <th class="!text-center">Rate</th>
                    </tr>

                </thead>
                <tbody>
                    <template v-for="(scmWrrLine, index) in workReceiptReport.scmWrrLines" :key="index" >
                        <template v-for="(lineItem, itemIndex) in scmWrrLine.scmWrrLineItems" :key="itemIndex">
                          <tr>
                            <td v-if="itemIndex == 0" :rowspan="scmWrrLine?.scmWrrLineItems?.length ?? 1">{{ scmWrrLine?.scmWr?.ref_no }}</td>
                            <td class="!text-center" v-if="itemIndex == 0" :rowspan="scmWrrLine?.scmWrrLineItems?.length ?? 1">{{ formatDate(scmWrrLine?.scmWr?.raised_date) }}</td>
                            <td>{{ lineItem?.scmService?.service_name_and_code }}</td>
                            <td class="!text-right">{{ lineItem?.wr_qty }}</td>
                            <td class="!text-right">{{ lineItem?.wo_qty }}</td>
                            <td class="!text-right">{{ lineItem?.remaining_quantity }}</td>
                            <td class="!text-right">{{ lineItem?.quantity }}</td>
                            <td class="!text-right">{{ lineItem?.rate }}</td>
                          </tr>
                        <!-- <tr v-if="index != 0">
                            <td><nobr>{{ scmSrLine?.scmWr?.ref_no }}</nobr></td>
                        </tr>
                        <tr class="text-gray-700 dark-disabled:text-gray-400" v-else>
                            <td :rowspan="lines.length">{{ first(values(lines))?.scmService?.name }}</td>
                            <td class="!text-center">{{ scmSrLine?.scmWr?.ref_no }}</td>
                            <td class="!text-center">{{ wcsQuotation.scmWcsVendorServices[indexa][index].rate }}</td>
                        </tr> -->
                        </template>
                    </template>
                    
                </tbody>
            </table>
        </div>

        
        <!-- <div class="w-full mt-2">
            <table class="w-full">
                <thead>
                    <tr>
                        <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Material Details</td>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr v-for="(poMaterial, index) in lcRecord?.scmPo?.scmPoItems" :key="index">
                        <th class="w-40">{{ poMaterial?.scmMaterial?.name }}</th>
                        <td>{{ poMaterial?.scmMaterial?.hs_code }}</td>
                    </tr>

                </tbody>
            </table>
        </div> -->
        </div>

  </div>
</template>









<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm;
    }
    .label-item-title {
        @apply text-gray-700 dark-disabled:text-gray-300 text-sm;
    }
    .label-item-input {
        @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
    }
    .form-fieldset {
      @apply px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400;
    }
    .form-legend {
      @apply px-2 text-gray-700 dark-disabled:text-gray-300;
    }
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

    table tr,td,th {
        @apply border border-gray-300
    }
    .table_tr {
      @apply text-gray-700 dark-disabled:text-gray-400;
    }
    .table_head_tr {
      @apply text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800;
    }
    .table_body {
      @apply bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800;
    }
    .remove_button {
      @apply px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple;
    }
    .add_button {
      @apply px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple;
    }

    th, td, tr {
      @apply text-left border-gray-500
    }
  
    #profileDetailTable th{
      text-align: center;
    }
    #profileDetailTable thead tr{
      @apply bg-gray-200
    }
  
    th.text-center, td.text-center, tr.text-center {
      @apply text-center border-gray-500
    }
  
    th.text-right, td.text-right, tr.text-right {
      @apply text-right border-gray-500
    }
</style>
 