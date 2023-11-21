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

    const { filteredPurchaseOrders, searchPurchaseOrderForLc } = usePurchaseOrder();

    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      formType: { type: String, required : false },
      csVendors: { type: Object, required: false },
      page: { required: false, default: {}},

    });

    const tableScrollWidth = ref(null);
    const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

    // onMounted(() => {
    //   watchEffect(() => {
    //     if (props.form.scmPoLines) {
    //       const customDataTable = document.getElementById("customDataTable");
    //       if (customDataTable) {
    //         tableScrollWidth.value = customDataTable.scrollWidth;
    //       }
    //     }
    // }, { deep: true });

// });// Code for global search end here

watch(() => props.form.business_unit, (newValue, oldValue) => {
    if (newValue !== oldValue && oldValue != '' && oldValue != null && oldValue != undefined) {
      props.form.scm_po_id = null;
      props.form.scmPo = null;
      props.form.scmVendor = null;
      props.form.scm_vendor_id = null;
      props.form.scmWarehouse = null;
      props.form.scm_warehouse_id = null;
      props.form.acc_cost_center_id = null;

    }
  });


onMounted(() => {
  getLcCostHeads();
  watchEffect(() => {
    fetchPo('');
  });
});
watch(lc_cost_heads, (newVal, oldVal) => {
  newVal.forEach((lc_cost_head, index) => {
    console.log(lc_cost_head,index);
    props.form.scmLcRecordLines.push({
      particular: lc_cost_head,
      amount: 0.0,
    });
  });
});

//watch form.scmLcRecordLines
watch(() => props?.form?.scmLcRecordLines, (newVal, oldVal) => {
  let total = 0.0;
  newVal?.forEach((lc_cost_head, index) => {
    props.form.scmLcRecordLines[index].amount = parseFloat(lc_cost_head?.amount);
    total += parseFloat(props.form.scmLcRecordLines[index].amount);
  });
  props.form.total_cost = parseFloat(total.toFixed(2));
}, { deep: true });


function handleAttachmentChange(e) {
      let fileData = e.target.files[0];
      props.form.attachment = fileData;
}
    

//fetchPo by using searchPurchaseOrder()
function fetchPo(search, loading = false) {
  // loading(true);
  searchPurchaseOrderForLc(search, props.form.business_unit);
}

//watch scmPo
watch(() => props.form.scmPo, (newVal, oldVal) => {
  if (newVal) {
    props.form.scm_po_id = newVal.id;
    if (newVal.scmVendor) {
      props.form.scmVendor = newVal.scmVendor;
      props.form.scm_vendor_id = newVal.scm_vendor_id;
      }
    if (newVal.scmWarehouse) {
      props.form.scmWarehouse = newVal.scmWarehouse;
      props.form.scm_warehouse_id = newVal.scm_warehouse_id;
      props.form.acc_cost_center_id = newVal.scmWarehouse.acc_cost_center_id;
      }
    }
  });

  watchPostEffect(() => {
      props.form.document_value = props.form.cfr_value - props.form.lc_margin;
  });

</script>
<template>


  <!-- Basic information -->
  <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
  </div>
  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">LC No <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.lc_no" required class="form-input" name="scm_warehouse_id" :id="'lc_no'" />
        <!-- <Error v-if="errors?.lc_no" :errors="errors.lc_no" /> -->
      </label>
      <label class="label-group">
          <span class="label-item-title">LC Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="form.lc_date" required class="form-input" name="lc_date" :id="'lc_date'" />
          <!-- <Error v-if="errors?.lc_date" :errors="errors.lc_date"  /> -->
      </label>
      <label class="label-group">
        <span class="label-item-title">LC Expire Date<span class="text-red-500">*</span></span>
           <input type="date" v-model="form.expire_date" required class="form-input" name="expire_date" :id="'expire_date'" />
          <!-- <Error v-if="errors?.expire_date" :errors="errors.expire_date"  /> -->
      </label>
      <label class="label-group">
          <span class="label-item-title">Weight<span class="text-red-500">*</span></span>
          <input type="text" placeholder="*** Metric Ton" v-model="form.weight" required class="form-input" name="weight" :id="'weight'" />
          <!-- <Error v-if="errors?.weight" :errors="errors.weight"  /> -->
      </label>
      
  </div>
  <div class="input-group">    
    <label class="label-group">
        <span class="label-item-title">No. Of Packet</span>
        <input type="text" v-model="form.no_of_packet" required class="form-input" name="no_of_packet" :id="'no_of_packet'" /> 
        <!-- <Error v-if="errors?.no_of_packet" :errors="errors.no_of_packet" /> -->
    </label>
    <label class="label-group">
          <span class="label-item-title">PO No<span class="text-red-500">*</span></span>
            <!-- <v-select :options="filteredPurchaseOrders" @search="fetchPo" placeholder="--Choose an option--" v-model="form.scmPo" label="ref_no" class="block form-input"> -->
            <v-select :options="filteredPurchaseOrders" placeholder="--Choose an option--" v-model="form.scmPo" label="ref_no" class="block form-input">
              <template #search="{attributes,events}">
                <input
                  class="vs__search"
                  :required="!form.scmPo"
                  v-bind="attributes"
                  v-on="events"
                />
              </template>
            </v-select>
          <!-- <Error v-if="errors?.scm_po_id" :errors="errors.scm_po_id"  /> -->
      </label>
      <label class="label-group">
        <span class="label-item-title">Invoice Value</span>
        <input type="text" v-model="form.invoice_value" required class="form-input" name="invoice_value" :id="'invoice_value'" />
        <!-- <Error v-if="errors?.invoice_value" :errors="errors.invoice_value"/> -->
    </label>
      <label class="label-group">
          <span class="label-item-title">Assessment Value<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.assessment_value" required class="form-input" name="assessment_value" :id="'assessment_value'" />
          <!-- <Error v-if="errors?.assessment_value" :errors="errors.assessment_value"  /> -->
      </label>
  </div>
  <div class="input-group">    
    <label class="label-group">
        <span class="label-item-title">Issuing Bank</span>
        <input type="text" v-model="form.issue_bank_name" required class="form-input" name="issue_bank_name" :id="'issue_bank_name'" /> 
        <!-- <Error v-if="errors?.issue_bank_name" :errors="errors.issue_bank_name" /> -->
    </label>
    <label class="label-group">
          <span class="label-item-title">Advising Bank<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.advising_bank_name" required class="form-input" name="advising_bank_name" :id="'advising_bank_name'" />
          <!-- <Error v-if="errors?.advising_bank_id" :errors="errors.advising_bank_name"  /> -->
      </label>
      
      <label class="label-group">
        <span class="label-item-title">Beneficiary Bank</span>
        <input type="text" v-model="form.beneficiary_bank_name" required class="form-input" name="beneficiary_bank_name" :id="'beneficiary_bank_name'" />
        <!-- <Error v-if="errors?.beneficiary_bank_name" :errors="errors.beneficiary_bank_name"/> -->
    </label>
      <label class="label-group">
          <span class="label-item-title">Discounting Bank<span class="text-red-500">*</span></span>
          <input type="text" v-model="form.discounting_bank_name" required class="form-input" name="discounting_bank_name" :id="'discounting_bank_name'" />
          <!-- <Error v-if="errors?.discounting_bank_name" :errors="errors.discounting_bank_name"  /> -->
      </label>
  </div>
  <div class="input-group !w-1/2">    
    <label class="label-group">
        <span class="label-item-title">Party Name</span>
        <input type="text" readonly :value="form.scmVendor?.name" required class="form-input vms-readonly-input" name="scmVendor"/> 
        <!-- <Error v-if="errors?.scm_vendor_id" :errors="errors.scm_vendor_id" /> -->
    </label>
    <label class="label-group">
          <span class="label-item-title">Attachment<span class="text-red-500">*</span></span>
          <input type="file" @input="handleAttachmentChange" class="form-input" name="attachment" :id="'attachment'" /> 
          <!-- <Error v-if="errors?.attachment" :errors="errors.attachment"  /> -->
      </label>
  </div>


  <div id="customDataTable">
    <div  class="table-responsive">
        <table class="w-full whitespace-no-wrap" id="table">
          <caption class="table_caption p-2 border-2 mt-7 bg-gray-400 text">Bank Payment Details</caption>
          <tbody>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100 !w-3/4">LC Status / Type</td>
              <td class="align-center text-center !w-1/4">
                <input type="text" v-model="form.lc_type" required class="form-input text-center" name="lc_type" :id="'lc_type'" />
              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100 !w-3/4">Name of Bank</td>
              <td class="align-center !w-1/4">
                <input type="text" v-model="form.bank_name" required class="form-input text-center" name="bank_name" :id="'bank_name'" />
              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">CFR Value (BDT)</td>
              <td class="align-center">
                <input type="text" v-model="form.cfr_value" required class="form-input text-center" name="cfr_value" :id="'cfr_value'" />

              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">LC Margin (BDT)</td>
              <td class="align-center">
                <input type="text" v-model="form.lc_margin" required class="form-input text-center" name="lc_margin" :id="'lc_margin'" />

              </td>
            </tr>
            <tr class="text-center" v-for="(scmLcRecordLine,index) in form.scmLcRecordLines" :key="index">
                <td class="align-center font-bold bg-gray-100">{{scmLcRecordLine.particular}}</td>
                <td class="align-center">
                  <input type="number" v-model="form.scmLcRecordLines[index].amount" required class="form-input text-center" :name="`lines`[index]"/>
                </td>
              </tr>
            <tr class="text-right">
              <td class="align-right font-bold bg-gray-100">Total Costs Relating To LC ( a + b + c + d)</td>
              <td class="align-center">
                <input type="text" v-model="form.total_cost" required readonly class="form-input text-center vms-readonly-input" name="total_cost" :id="'total_cost'" />

              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">Documents Value (CFR - Margin) (BDT)</td>
              <td class="align-center">
                <input type="text" v-model="form.document_value" readonly required class="form-input text-center vms-readonly-input" name="document_value" :id="'document_value'" />

              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">Exchange Rate (BDT/USD)</td>
              <td class="align-center">
                <input type="text" v-model="form.exchange_rate" required class="form-input text-center" name="exchange_rate" :id="'exchange_rate'" />

              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">Market Rate (BDT/USD)</td>
              <td class="align-center">
                <input type="text" v-model="form.market_rate" required class="form-input text-center" name="market_rate" :id="'market_rate'" />

              </td>
            </tr>
          </tbody>
        </table>
      <!-- </fieldset> -->
    </div>
  </div>
  <ErrorComponent :errors="errors"></ErrorComponent>  
</template>









<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm;
    }
    .label-item-title {
        @apply text-gray-700 dark:text-gray-300 text-sm;
    }
    .label-item-input {
        @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
    .form-fieldset {
      @apply px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400;
    }
    .form-legend {
      @apply px-2 text-gray-700 dark:text-gray-300;
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
      @apply text-gray-700 dark:text-gray-400;
    }
    .table_head_tr {
      @apply text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
    }
    .table_body {
      @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
    }
    .remove_button {
      @apply px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple;
    }
    .add_button {
      @apply px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple;
    }
</style>