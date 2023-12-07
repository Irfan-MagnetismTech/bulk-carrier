<script setup>
    import { ref, watch, onMounted,watchEffect,computed } from 'vue';
    import Error from "../../Error.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import usePurchaseRequisition from '../../../composables/supply-chain/usePurchaseRequisition';
    import useVendor from '../../../composables/supply-chain/useVendor';
    import ErrorComponent from "../../utils/ErrorComponent.vue";
    import cloneDeep from 'lodash/cloneDeep';
    import useBusinessInfo from '../../../composables/useBusinessInfo';
    import usePurchaseOrder from '../../../composables/supply-chain/usePurchaseOrder';

    const { material, materials, getMaterials,searchMaterial } = useMaterial();
    const { warehouses,warehouse,getWarehouses,searchWarehouse } = useWarehouse();
    const { vendors, searchVendor,isLoading: vendorLoader } = useVendor();
    const { currencies, getCurrencies } = useBusinessInfo();
    const { getMaterialList, prMaterialList, isLoading } = usePurchaseOrder();

    const { purchaseRequisitions, searchWarehouseWisePurchaseRequisition } = usePurchaseRequisition();

    const props = defineProps({
      form: { type: Object, required: true },

    });
const customDataTableirf = ref(null);  const dynamicMinHeight = ref(0);
</script>
<template>
<!-- &Get@Well@Soon100& -->

  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <input type="text" readonly v-model="form.business_unit" required class="form-input vms-readonly-input" name="business_unit" :id="'business_unit'" />
  </div>
  <div class="input-group !w-1/4">
      <label class="label-group">
          <span class="label-item-title">Po Ref<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.ref_no }}</span>
      </label>
    </div>
  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">Warehouse <span class="text-red-500">*</span></span>
        <span class="show-block">{{ form.scmWarehouse.name }}</span>
      </label>
      <label class="label-group">
          <span class="label-item-title">PO Date<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.date }}</span>
      </label>
      <label class="label-group">
        <span class="label-item-title">PR No <span class="text-red-500">*</span></span>
        <span class="show-block">{{ form?.scmPr?.ref_no }}</span>
      </label>
      <label class="label-group">
          <span class="label-item-title">PR Date<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.pr_date }}</span>
      </label>
      
  </div>
  <div class="input-group">    
    <label class="label-group">
        <span class="label-item-title">CS No</span>
        <span class="show-block">{{ form?.scmCs?.ref_no }}</span>
    </label>
    <label class="label-group">
          <span class="label-item-title">Vendor Name<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form?.scmVendor?.name }}</span>
      </label>
      
      <label class="label-group">
        <span class="label-item-title">Currency</span>
        <span class="show-block">{{ form.currency }}</span>
    </label>
    <label class="label-group" v-if="form.currency == 'USD'">
        <span class="label-item-title">Convertion Rate( Foreign To BDT )<span class="text-red-500">*</span></span>
        <span class="show-block">{{ form.foreign_to_usd }}</span>
    </label>
  </div>

  <div class="input-group !w-3/4">
    <label class="label-group">
          <span class="label-item-title">Remarks</span>
          <span class="show-block">{{ form.remarks }}</span>
    </label>
  </div>

  <div id="customDataTableMat" ref="customDataTableirf" class="!max-w-screen pb-20" :style="{ minHeight: dynamicMinHeight + 'px!important' }" >
    <div class="table-responsive">
      <fieldset class="form-fieldset">
        <legend class="form-legend">Materials <span class="text-red-500">*</span></legend>
        <table class="w-full whitespace-no-wrap" id="table">
          <thead>
            <tr class="table_head_tr">
            <th class="py-3 align-center min-w-[150px] md:min-w-[200px] lg:min-w-[250px]">Material Name <br/> <span class="!text-[8px]">Material - Code</span></th>
            <th class="py-3 align-center min-w-[30px] md:min-w-[55px] lg:min-w-[80px]">Unit</th>
            <th class="py-3 align-center">Brand</th>
            <th class="py-3 align-center">Model</th>
            <th class="py-3 align-center">Required Date</th>
            <th class="py-3 align-center min-w-[50px] md:min-w-[90px] lg:min-w-[105px]">Qty</th>
            <th class="py-3 align-center min-w-[50px] md:min-w-[90px] lg:min-w-[105px]">Rate</th>
            <th class="py-3 align-cente min-w-[50px] md:min-w-[90px] lg:min-w-[105px]">Total Price</th>
          </tr>
          </thead>

          <tbody class="table_body">
          <tr class="table_tr" v-for="(scmPoLine, index) in form.scmPoLines" :key="index">
            <td class="">
            <span class="show-block">{{ form.scmPoLines[index].scmMaterial.material_name_and_code }}</span>
            </td>
            <td>
              <span class="show-block">{{ form.scmPoLines[index].unit }}</span>
            </td>
            <td>
              <span class="show-block">{{ form.scmPoLines[index].brand }}</span>
            </td>
            <td>
              <span class="show-block">{{ form.scmPoLines[index].model }}</span>
            </td>
            <td>
              <span class="show-block"><nobr>{{ form.scmPoLines[index].required_date }}</nobr></span>
            </td>
            <td>
              <span class="show-block">{{ form.scmPoLines[index].quantity }}</span>
            </td>
            <td>
              <span class="show-block">{{ form.scmPoLines[index].rate }}</span>
            </td>
            <td>
              <span class="show-block">{{ form.scmPoLines[index].total_price }}</span>
            </td>
          </tr>
          <tr>
            <td colspan="7" class="text-right">Sub Total</td>
            <td class="text-right">
              <span class="show-block">{{ form.sub_total }}</span>
            </td>
          </tr>
          <tr>
            <td colspan="7" class="text-right">Less: Discount</td>
            <td class="text-right">
              <span class="show-block">{{ form.discount }}</span>
            </td>
          </tr>
          
          <tr>
            <td colspan="7" class="text-right">Total Amount</td>
            <td class="text-right">
              <span class="show-block">{{ form.total_amount }}</span>
            </td>
          </tr>
          <tr>
            <td colspan="7" class="text-right">Add: VAT</td>
            <td class="text-right">
              <span class="show-block">{{ form.vat }}</span>
            </td>
          </tr>
          
          <tr>
            <td colspan="7" class="text-right">Net Amount</td>
            <td class="text-right">
              <span class="show-block">{{ form.net_amount }}</span>
            </td>
          </tr>
          </tbody>
        </table>
      </fieldset>
    </div>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive max-w-screen">
      <fieldset class="form-fieldset">
        <legend class="form-legend">Terms And Conditions <span class="text-red-500">*</span></legend>
        <table class="w-full whitespace-no-wrap" id="table">
         <tbody class="table_body">
          <tr class="table_tr" v-for="(scmPoTerm, index) in form.scmPoTerms" :key="index">
            <td>
              <span class="show-block">{{ form.scmPoTerms[index].description }}</span>
            </td>
          </tr>
        
          </tbody>
        </table>
      </fieldset>
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

    #customDataTableMat::-webkit-scrollbar:horizontal {
      height: 1rem!important; 
    }
  
    #customDataTableMat::-webkit-scrollbar-thumb:horizontal{
      background-color: rgb(132, 109, 175); 
      border-radius: 12rem!important;
      width: 0.5rem!important;
      height: 0.5rem!important;
      border-radius: 12rem!important;
    }
  
    #customDataTableMat::-webkit-scrollbar-track:horizontal{
      background: rgb(148, 144, 155)!important; 
      border-radius: 12rem!important;
    }
  
    #customDataTableMat::-webkit-scrollbar-button:horizontal {
      background-color: rgb(0, 0, 0); 
      border-radius: 12rem!important;
      width: 1.3rem!important;
    } 
</style>