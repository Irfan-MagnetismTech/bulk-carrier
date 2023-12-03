<script setup>
    import { ref, watch, onMounted,watchEffect,computed, watchPostEffect } from 'vue';
    import Error from "../../Error.vue";
    import useMaterial from "../../../composables/supply-chain/useMaterial.js";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import useVendor from '../../../composables/supply-chain/useVendor';
    import ErrorComponent from "../../utils/ErrorComponent.vue";
    import useBusinessInfo from '../../../composables/useBusinessInfo';
    import usePurchaseOrder from '../../../composables/supply-chain/usePurchaseOrder';

    const { vendors, searchVendor } = useVendor();
    const { getLcCostHeads,lc_cost_heads } = useBusinessInfo();

    const { filteredPurchaseOrders, searchPurchaseOrderForLc,isLoading} = usePurchaseOrder();

    const props = defineProps({
      form: { type: Object, required: true },
    });

</script>
<template>


  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <span class="show-block">{{ form.business_unit }}</span>
  </div>
  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">LC No <span class="text-red-500">*</span></span>
        <span class="show-block">{{ form.lc_no }}</span>
      </label>
      <label class="label-group">
          <span class="label-item-title">LC Date<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.lc_date }}</span>
      </label>
      <label class="label-group">
        <span class="label-item-title">LC Expire Date<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.expire_date }}</span>
      </label>
      <label class="label-group">
          <span class="label-item-title">Weight<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.weight }}</span>
      </label>
      
  </div>
  <div class="input-group">    
    <label class="label-group">
        <span class="label-item-title">No. Of Packet</span>
        <span class="show-block">{{ form.no_of_packet }}</span>
    </label>
    <label class="label-group">
          <span class="label-item-title">PO No<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form?.scmPo?.ref_no }}</span>
      </label>
      <label class="label-group">
        <span class="label-item-title">Invoice Value</span>
        <span class="show-block">{{ form.invoice_value }}</span>
    </label>
      <label class="label-group">
          <span class="label-item-title">Assessment Value<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.assessment_value }}</span>
      </label>
  </div>
  <div class="input-group">    
    <label class="label-group">
        <span class="label-item-title">Issuing Bank</span>
        <span class="show-block">{{ form.issue_bank_name }}</span>
    </label>
    <label class="label-group">
          <span class="label-item-title">Advising Bank<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.advising_bank_name }}</span>
      </label>
      
      <label class="label-group">
        <span class="label-item-title">Beneficiary Bank</span>
        <span class="show-block">{{ form.beneficiary_bank_name }}</span>
    </label>
      <label class="label-group">
          <span class="label-item-title">Discounting Bank<span class="text-red-500">*</span></span>
          <span class="show-block">{{ form.discounting_bank_name }}</span>
      </label>
  </div>
  <div class="input-group !w-1/2">    
    <label class="label-group">
        <span class="label-item-title">Party Name</span>
        <span class="show-block">{{ form.scmVendor?.name }}</span>
    </label>
    <!-- <label class="label-group">
          <span class="label-item-title">Attachment<span class="text-red-500">*</span></span>
          <a download href="" class="block text-blue-500 hover:text-blue-700 ease-linear duration-200 font-semibold mt-2.5">Download Attachment</a>
      </label> -->
  </div>


  <div id="customDataTable">
    <div  class="table-responsive">
        <table class="w-full whitespace-no-wrap" id="table">
          <caption class="table_caption p-2 border-2 mt-7 bg-gray-400 text">Bank Payment Details</caption>
          <tbody>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100 !w-3/4">LC Status / Type</td>
              <td class="align-center text-center !w-1/4">
                <span class="show-block">{{ form.lc_type }}</span>
              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100 !w-3/4">Name of Bank</td>
              <td class="align-center !w-1/4">
                <span class="show-block">{{ form.bank_name }}</span>
              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">CFR Value (BDT)</td>
              <td class="align-center">
                <span class="show-block">{{ form.cfr_value }}</span>
              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">LC Margin (BDT)</td>
              <td class="align-center">
                <span class="show-block">{{ form.lc_margin }}</span>
              </td>
            </tr>
            <tr class="text-center" v-for="(scmLcRecordLine,index) in form.scmLcRecordLines" :key="index">
                <td class="align-center font-bold bg-gray-100">{{scmLcRecordLine.particular}}</td>
                <td class="align-center">
                  <span class="show-block">{{form.scmLcRecordLines[index].amount}}</span>
                </td>
              </tr>
            <tr class="text-right">
              <td class="align-right font-bold bg-gray-100">Total Costs Relating To LC ( a + b + c + d)</td>
              <td class="align-center">
               <span class="show-block">{{ form.total_cost }}</span>
              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">Documents Value (CFR - Margin) (BDT)</td>
              <td class="align-center">
                 <span class="show-block">{{ form.document_value }}</span>
              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">Exchange Rate (BDT/USD)</td>
              <td class="align-center">
               <span class="show-block">{{ form.exchange_rate }}</span>
              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">Market Rate (BDT/USD)</td>
              <td class="align-center">
                <span class="show-block">{{ form.market_rate }}</span>
              </td>
            </tr>
          </tbody>
        </table>
      <!-- </fieldset> -->
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
</style>