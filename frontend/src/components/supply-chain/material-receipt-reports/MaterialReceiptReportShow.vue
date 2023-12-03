<script setup>
    import { ref, watch, onMounted,watchEffect,computed,toRefs } from 'vue';
    import {useStore} from "vuex";
    import Error from "../../Error.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useBusinessInfo from "../../../composables/useBusinessInfo.js";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import env from '../../../config/env';
    import cloneDeep from 'lodash/cloneDeep';
    import useLcRecord from '../../../composables/supply-chain/useLcRecord';
    import ErrorComponent from "../../utils/ErrorComponent.vue";
    import useStockLedger from '../../../composables/supply-chain/useStockLedger';
    import useMaterialReceiptReport from '../../../composables/supply-chain/useMaterialReceiptReport';
    
    const { material, materials, getMaterials, searchMaterial,isLoading:materialLoading } = useMaterial();
    const { searchLcRecord, filteredLcRecords , isLoading: lcLoader} = useLcRecord();
    const { getMaterialWiseCurrentStock, CurrentStock } = useStockLedger();
    const { getMaterialList, materialList , isLoading} = useMaterialReceiptReport();

    const store_category = ref([]);
    const firstInitiated = ref(false);

    const props = defineProps({
      form: { type: Object, required: true },
    });

    const customDataTableirf = ref(null);
    
 



</script>
<template>

  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
     <span class="show-block">{{ form.business_unit }}</span>
  </div>
  <div class="input-group !w-1/4">
      <label class="label-group">
          <span class="label-item-title">MRR Ref<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.ref_no }}</span>
      </label>
  </div>
  <div class="input-group-grid">
      <label class="label-group">
          <span class="label-item-title">Received Date<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.date }}</span>
      </label>
      <label class="label-group" v-if="form.type == 'FOREIGN'">
          <span class="label-item-title">LC Record No<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.scmLcRecord }}</span>
      </label>
      <label class="label-group" v-if="form.type !== 'CASH'">
          <span class="label-item-title">PO No<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form?.scmPo?.ref_no }}</span>
      </label>
      <label class="label-group" v-if="form.type !== 'CASH'">
          <span class="label-item-title">PO Date<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form?.scmPo?.date }}</span>
      </label>
      
      <label class="label-group">
          <span class="label-item-title">PR No<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form?.scmPr?.ref_no }}</span>
      </label>
      <label class="label-group" v-if="form.type !== 'CASH'">
          <span class="label-item-title">CS No<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form?.scmCs?.ref_no ?? 'N/A' }}</span>
      </label>
      <!-- <label class="label-group" v-if="form.type == 'CASH'">
          <span class="label-item-title">IOU No<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.scmIou_no }}</span>
      </label> -->
      <label class="label-group">
          <span class="label-item-title">Challan No<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.challan_no }}</span>
      </label>
      <label class="label-group">
          <span class="label-item-title">Status<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.is_completed == 1 ? 'Settled' : 'Remaining' }}</span>
      </label>
  </div>
  <div class="input-group">
     
  </div>
  <div class="input-group !w-3/4">
    <label class="label-group">
          <span class="label-item-title">Remarks</span>
          <span class="show-block">{{ form.remarks }}</span>
    </label>
  </div>
  <div class="input-group !w-3/4">
    <label class="label-group">
          <span class="label-item-title">QC Remarks <span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.qc_remarks }}</span>
    </label>
  </div>
  
  <div id="customDataTable" class="!max-w-screen !min-w-screen overflow-x-scroll"> 
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Materials <span class="text-red-500">*</span></legend>
        <div class=""> 
        <table class="w-full">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th style="" class="py-3 align-center">Material Name </th>
            <th style="" class="py-3 align-center">Unit</th>
            <th class="py-3 align-center">Brand</th>
            <th class="py-3 align-center">Model</th>
            <th class="py-3 align-center">PO Qty</th>
            <th class="py-3 align-center">PR Qty</th>
            <th class="py-3 align-center">Current Stock</th>
            <th class="py-3 align-center">Qty</th>
            <th class="py-3 align-center">Rate</th>
          </tr>
          </thead>

          <!-- <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800" v-if="form.scmWarehouse != null"> -->
          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmMrrLine, index) in form.scmMrrLines" :key="index">
            <td class="!w-72">
              <span class="show-block">{{ form.scmMrrLines[index].scmMaterial.material_name_and_code }}</span>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmMrrLines[index].unit }}</span>
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmMrrLines[index].brand }}</span>
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmMrrLines[index].model }}</span>
               </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmMrrLines[index].po_qty }}</span>
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmMrrLines[index].pr_qty }}</span>
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmMrrLines[index].current_stock }}</span>
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmMrrLines[index].quantity }}</span>
              </label>
            </td> 
            <td>
              <label class="block w-full mt-2 text-sm">
                 <span class="show-block">{{ form.scmMrrLines[index].rate }}</span>
              </label>
            </td>
          </tr>
          </tbody>
        </table>
        </div>  
      </fieldset>
    </div>
</template>

<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }

    .input-group-grid {
        @apply grid grid-cols-1 md:grid-cols-4 md:gap-2 justify-center w-full;
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
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

 

</style>