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
    import useQuotation from '../../../composables/supply-chain/useQuotation'; 
    const { vendors, searchVendor } = useVendor();
    const { getLcCostHeads,lc_cost_heads } = useBusinessInfo();
    const { updateQuotations, quotation, localQuotationLines, foreignQuotationLines,showQuotation } = useQuotation();
    import { useRoute } from 'vue-router';
    const { filteredPurchaseOrders, searchPurchaseOrderForLc,isLoading} = usePurchaseOrder();
    const route = useRoute();
    const quotationId = route.params.quotationId;
    const csId = route.params.csId;
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
 
  watchEffect(() => {
  });
});


//watch form.scmLcRecordLines
watch(() => [props?.form?.insurence_premium,props?.form?.others,props?.form?.vat,props?.form?.bank_commission], (newVal, oldVal) => {
  let total = 0.0;
  // newVal?.forEach((lc_cost_head, index) => {
  //   props.form.scmLcRecordLines[index].amount = parseFloat(lc_cost_head?.amount);
  //   total += parseFloat(props.form.scmLcRecordLines[index].amount);
  // });
  total = parseFloat(props?.form?.insurence_premium ?? 0) + parseFloat(props?.form?.others ?? 0) + parseFloat(props?.form?.vat ?? 0) + parseFloat(props?.form?.bank_commission ?? 0);
  props.form.total_cost = parseFloat(total.toFixed(2));
  console.log('props.form.total_cost');
}, { deep: true });

// watch(() => [props?.form?.insurence_premium, props?.form?.others, props?.form?.vat, props?.form?.bank_commission], (newVal, oldVal) => {
//   console.log('Watcher triggered with values:', newVal);
  
//   let total = 0.0;

//   console.log('Updated props.form.total_cost:', total);
// }, { deep: true });

function handleAttachmentChange(e) {
      let fileData = e.target.files[0];
      props.form.attachment = fileData;
}
    
onMounted(() => {
  console.error('tag', quotationId)
  
  showQuotation(csId,quotationId);
});

watch(() => quotation.value, (newVal, oldVal) => {
    props.form.scmCs = newVal.scmCs;
    props.form.scm_cs_id = newVal.scm_cs_id;
    props.form.scmVendor = newVal.scmVendor;
    props.form.scm_vendor_id = newVal.scm_vendor_id;
    props.form.scmCsVendor = newVal;
    props.form.scm_cs_vendor_id = newVal.id;
  });

  watchPostEffect(() => {
      props.form.document_value = props.form.cfr_value - props.form.lc_margin;
  });
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
  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">CS No <span class="text-red-500">*</span></span>
        <input type="text" :value="form?.scmCs?.ref_no" required class="form-input" name="scm_warehouse_id" :id="'lc_no'" />
        <!-- <Error v-if="errors?.lc_no" :errors="errors.lc_no" /> -->
      </label>
      <label class="label-group">
          <span class="label-item-title">Vendor Name <span class="text-red-500">*</span></span>
          <input type="text" :value="form?.scmVendor?.name" required class="form-input" name="lc_date" :id="'lc_date'" />
          <!-- <Error v-if="errors?.lc_date" :errors="errors.lc_date"  /> -->
      </label>
  </div>

  <div id="customDataTable">
    <div  class="table-responsive">
        <table class="w-full whitespace-no-wrap" id="table">
          <caption class="table_caption p-2 border-2 mt-7 bg-gray-400 text">Bank Payment Details</caption>
          <tbody>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100 !w-3/4">LC Status / Type <span class="text-red-500">*</span></td>
              <td class="align-center text-center !w-1/4">
                <input type="text" v-model="form.lc_type" required class="form-input text-center" name="lc_type" :id="'lc_type'" />
              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100 !w-3/4">Name of Bank <span class="text-red-500">*</span></td>
              <td class="align-center !w-1/4">
                <input type="text" v-model="form.bank_name" required class="form-input text-center" name="bank_name" :id="'bank_name'" />
              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">CFR Value (BDT) <span class="text-red-500">*</span></td>
              <td class="align-center">
                <input type="number" v-model="form.cfr_value" required class="form-input text-center" name="cfr_value" :id="'cfr_value'" min="1"/>

              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">LC Margin (BDT) <span class="text-red-500">*</span></td>
              <td class="align-center">
                <input type="number" v-model="form.lc_margin" required class="form-input text-center" name="lc_margin" :id="'lc_margin'" min="1"/>

              </td>
            </tr>
            <!-- <tr class="text-center" v-for="(scmLcRecordLine,index) in form.scmLcRecordLines" :key="index">
                <td class="align-center font-bold bg-gray-100">{{scmLcRecordLine.particular}}</td>
                <td class="align-center">
                  <input type="number" v-model="form.scmLcRecordLines[index].amount" required class="form-input text-center" :name="`lines`[index]"/>
                </td>
              </tr>    -->
            <tr class="text-center">
                <td class="align-center font-bold bg-gray-100">Bank Commission (a)</td>
                <td class="align-center">
                  <input type="number" v-model="form.bank_commission" required class="form-input text-center"/>
                </td>
              </tr>
            <tr class="text-center">
                <td class="align-center font-bold bg-gray-100">VAT (b)</td>
                <td class="align-center">
                  <input type="number" v-model="form.vat" required class="form-input text-center"/>
                </td>
              </tr>
            <tr class="text-center">
                <td class="align-center font-bold bg-gray-100">Others (c)</td>
                <td class="align-center">
                  <input type="number" v-model="form.others" required class="form-input text-center"/>
                </td>
              </tr>
            <tr class="text-center">
                <td class="align-center font-bold bg-gray-100">Insurence Premium (d)</td>
                <td class="align-center">
                  <input type="number" v-model="form.insurence_premium" required class="form-input text-center"/>
                </td>
              </tr>
            <tr class="text-right">
              <td class="align-right font-bold bg-gray-100">Total Costs Relating To LC ( a + b + c + d)</td>
              <td class="align-center">
                <input type="number" v-model="form.total_cost" required readonly class="form-input text-center vms-readonly-input" name="total_cost" :id="'total_cost'" />

              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">Documents Value (CFR - Margin) (BDT)</td>
              <td class="align-center">
                <input type="number" v-model="form.document_value" readonly required class="form-input text-center vms-readonly-input" name="document_value" :id="'document_value'" />

              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">Exchange Rate (BDT/USD) <span class="text-red-500">*</span></td>
              <td class="align-center">
                <input type="number" v-model="form.exchange_rate" required class="form-input text-center" name="exchange_rate" :id="'exchange_rate'"  min="1"/>

              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">Market Rate (BDT/USD) <span class="text-red-500">*</span></td>
              <td class="align-center">
                <input type="number" v-model="form.market_rate" required class="form-input text-center" name="market_rate" :id="'market_rate'"  min="1"/>

              </td>
            </tr>
          </tbody>
        </table>
      <!-- </fieldset> -->
    </div>
  </div>
  <div id="customDataTable">
    <div  class="table-responsive">
        <table class="w-full whitespace-no-wrap" id="table">
          <caption class="table_caption p-2 border-2 mt-7 bg-gray-400 text">Bank Payment Details</caption>
          <tbody>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100 !w-3/4">LC Status / Type <span class="text-red-500">*</span></td>
              <td class="align-center text-center !w-1/4">
                <input type="text" v-model="form.lc_type" required class="form-input text-center" name="lc_type" :id="'lc_type'" />
              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100 !w-3/4">Name of Bank <span class="text-red-500">*</span></td>
              <td class="align-center !w-1/4">
                <input type="text" v-model="form.bank_name" required class="form-input text-center" name="bank_name" :id="'bank_name'" />
              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">CFR Value (BDT) <span class="text-red-500">*</span></td>
              <td class="align-center">
                <input type="number" v-model="form.cfr_value" required class="form-input text-center" name="cfr_value" :id="'cfr_value'" min="1"/>

              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">LC Margin (BDT) <span class="text-red-500">*</span></td>
              <td class="align-center">
                <input type="number" v-model="form.lc_margin" required class="form-input text-center" name="lc_margin" :id="'lc_margin'" min="1"/>

              </td>
            </tr>
            <!-- <tr class="text-center" v-for="(scmLcRecordLine,index) in form.scmLcRecordLines" :key="index">
                <td class="align-center font-bold bg-gray-100">{{scmLcRecordLine.particular}}</td>
                <td class="align-center">
                  <input type="number" v-model="form.scmLcRecordLines[index].amount" required class="form-input text-center" :name="`lines`[index]"/>
                </td>
              </tr>    -->
            <tr class="text-center">
                <td class="align-center font-bold bg-gray-100">Bank Commission (a)</td>
                <td class="align-center">
                  <input type="number" v-model="form.bank_commission" required class="form-input text-center"/>
                </td>
              </tr>
            <tr class="text-center">
                <td class="align-center font-bold bg-gray-100">VAT (b)</td>
                <td class="align-center">
                  <input type="number" v-model="form.vat" required class="form-input text-center"/>
                </td>
              </tr>
            <tr class="text-center">
                <td class="align-center font-bold bg-gray-100">Others (c)</td>
                <td class="align-center">
                  <input type="number" v-model="form.others" required class="form-input text-center"/>
                </td>
              </tr>
            <tr class="text-center">
                <td class="align-center font-bold bg-gray-100">Insurence Premium (d)</td>
                <td class="align-center">
                  <input type="number" v-model="form.insurence_premium" required class="form-input text-center"/>
                </td>
              </tr>
            <tr class="text-right">
              <td class="align-right font-bold bg-gray-100">Total Costs Relating To LC ( a + b + c + d)</td>
              <td class="align-center">
                <input type="number" v-model="form.total_cost" required readonly class="form-input text-center vms-readonly-input" name="total_cost" :id="'total_cost'" />

              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">Documents Value (CFR - Margin) (BDT)</td>
              <td class="align-center">
                <input type="number" v-model="form.document_value" readonly required class="form-input text-center vms-readonly-input" name="document_value" :id="'document_value'" />

              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">Exchange Rate (BDT/USD) <span class="text-red-500">*</span></td>
              <td class="align-center">
                <input type="number" v-model="form.exchange_rate" required class="form-input text-center" name="exchange_rate" :id="'exchange_rate'"  min="1"/>

              </td>
            </tr>
            <tr class="text-center">
              <td class="align-center font-bold bg-gray-100">Market Rate (BDT/USD) <span class="text-red-500">*</span></td>
              <td class="align-center">
                <input type="number" v-model="form.market_rate" required class="form-input text-center" name="market_rate" :id="'market_rate'"  min="1"/>

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