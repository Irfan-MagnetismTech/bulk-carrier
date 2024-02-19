<script setup>
import {ref,onMounted, watchPostEffect} from "vue";

import Title from "../../../services/title";
import useHelper from "../../../composables/useHelper.js";
import useWorkReceiptReport from "../../../composables/supply-chain/useWorkReceiptReport";
import MaterialReceiptReportShow from "../../../components/supply-chain/material-receipt-reports/MaterialReceiptReportShow.vue";
import { useRoute } from 'vue-router';

const { getMaterialReceiptReports, showMaterialReceiptReport, materialReceiptReport, updateMaterialReceiptReport,materialObject, errors, isLoading,poMaterialList,mrrLineObject} = useMaterialReceiptReport();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import { formatDate } from "../../../config/setting";
import useMaterial from "../../../composables/supply-chain/useMaterial";
import useMaterialReceiptReport from "../../../composables/supply-chain/useMaterialReceiptReport";

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const materialReceiptReportId = route.params.materialReceiptReportId;
const formType = 'edit';

setTitle('Material Receipt Report Details');

onMounted(() => {
    showMaterialReceiptReport(materialReceiptReportId);
});
</script>
<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
      <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Material Receipt Report Details</h2>
      <default-button :title="'Material Receipt Report List'" :to="{ name: 'scm.material-receipt-reports.index' }" :icon="icons.DataBase"></default-button>
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
                        <th class="w-40">MRR Ref</th>
                        <td>{{ materialReceiptReport?.ref_no }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Purchase Center</th>
                        <td>{{ materialReceiptReport?.purchase_center }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Warehouse</th>
                        <td>{{ materialReceiptReport?.scmWarehouse?.name }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Receive Date</th>
                        <td>{{ formatDate(materialReceiptReport.date) }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Challan No</th>
                        <td>{{ materialReceiptReport.challan_no }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">PO Ref</th>
                        <td>{{ materialReceiptReport.scmPo?.ref_no }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">PO Date</th>
                        <td>{{ formatDate(materialReceiptReport.scmPo?.date) }}</td>
                    </tr>

                    
                    
                    <tr>
                        <th class="w-40">Remarks</th>
                        <td>{{ materialReceiptReport.remarks }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Qc Remarks</th>
                        <td>{{ materialReceiptReport.qc_remarks }}</td>
                    </tr>
               
                </tbody>
            </table>
        </div>

        
        <div class="md:flex md:gap-2 ">
          <div class="w-full mt-2">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="!text-center font-bold bg-green-600 uppercase text-white" colspan="8">Material Receive Report Information</th>
                    </tr>
                    
                    <tr>
                        <th class="!text-center">PR No.</th>
                        <th class="!text-center">PR Date</th>
                        <th class="!text-center">Material - Code</th>
                        <th class="!text-center">PR Qty</th>
                        <th class="!text-center">PO Qty</th>
                        <th class="!text-center">Remaining Qty</th>
                        <th class="!text-center">Qty</th>
                        <th class="!text-center">Rate</th>
                    </tr>

                </thead>
                <tbody>
                    <template v-for="(scmMrrLine, index) in materialReceiptReport.scmMrrLines" :key="index" >
                        <template v-for="(lineItem, itemIndex) in scmMrrLine.scmMrrLineItems" :key="itemIndex">
                          <tr>
                            <td v-if="itemIndex == 0" :rowspan="scmMrrLine?.scmMrrLineItems?.length ?? 1" class="!text-center">{{ scmMrrLine?.scmPr?.ref_no }}</td>
                            <td class="!text-center" v-if="itemIndex == 0" :rowspan="scmMrrLine?.scmMrrLineItems?.length ?? 1">{{ formatDate(scmMrrLine?.scmPr?.raised_date) }}</td>
                            <td class="!text-center">{{ lineItem?.scmMaterial?.material_name_and_code }}</td>
                            <td class="!text-center">{{ lineItem?.pr_qty }}</td>
                            <td class="!text-center">{{ lineItem?.po_qty }}</td>
                            <td class="!text-center">{{ lineItem?.remaining_quantity }}</td>
                            <td class="!text-center">{{ lineItem?.quantity }}</td>
                            <td class="!text-center">{{ lineItem?.rate }}</td>
                          </tr>
                        <!-- <tr v-if="index != 0">
                            <td><nobr>{{ scmSrLine?.scmMr?.ref_no }}</nobr></td>
                        </tr>
                        <tr class="text-gray-700 dark-disabled:text-gray-400" v-else>
                            <td :rowspan="lines.length">{{ first(values(lines))?.scmService?.name }}</td>
                            <td class="!text-center">{{ scmSrLine?.scmMr?.ref_no }}</td>
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
 