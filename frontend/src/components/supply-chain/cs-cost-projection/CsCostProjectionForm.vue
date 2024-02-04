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
    import useMaterialCsCost from '../../../composables/supply-chain/useMaterialCsCost'; 
    import SupplierSelectionForm from '../supplier-selection/SupplierSelectionForm.vue';

    const { vendors, searchVendor } = useVendor();
    const { getLcCostHeads,lc_cost_heads } = useBusinessInfo();
    const { updateQuotations, quotation, localQuotationLines, foreignQuotationLines,showQuotation } = useQuotation();
    import { useRoute } from 'vue-router';
    const { filteredPurchaseOrders, searchPurchaseOrderForLc,isLoading} = usePurchaseOrder();
    const { csVendor, getSelectedVendorInfo,isLoading:csVendorLoader} = useMaterialCsCost();

    const route = useRoute();
    const quotationId = route.params.quotationId;
    const csId = route.params.csId;
    const props = defineProps({
      form: { type: Object, required: true },
      errors: { type: [Object, Array], required: false },
      formType: { type: String, required : false },
      page: { required: false, default: {}},

    });

    const tableScrollWidth = ref(null);
    const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;

 

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





    
onMounted(() => {
  if(props.formType != 'edit'){
    getSelectedVendorInfo(csId);
  }
});




  watch(() => csVendor.value, (newVal, oldVal) => {
    // if(props.formType != 'edit'){
      if (newVal) {
      props.form.scmCs = newVal;
      props.form.scm_cs_id = newVal.id;
      // props.form.selectedVendors.splice(0, props.form.selectedVendors.length);
      newVal.selectedVendors.forEach((vendor,index) => {
        let data = { 
              scmVendor: vendor.scmVendor,
              scm_vendor_id: vendor.scmVendor.id,
              scmCsLandedCost:
              {
                scmCs: newVal,
                scm_cs_id: newVal.id,
                scmVendor: vendor.scmVendor,
                scm_vendor_id: vendor.scmVendor.id,
                scmCsVendor: vendor,
                scm_cs_vendor_id: vendor.id,
                hs_codes: null,
                exchange_rate: 0.0,
                product_price: 0.0,
                freight_charge: 0.0,
                cfr_value: vendor.total_negotiated_price,
                insurance: 0.0,
                assesable_value_b: 0.0,
                landing_charge: 0.0,
                assesable_value_a: 0.0,
                cd: 0.0,
                rd: 0.0,
                sd: 0.0,
                vat: 0.0,
                at: 0.0,
                ait: 0.0,
                total_duty: 0.0,
                others: 0.0,
                total_landed_cost: 0.0,
              },
              scmCsPaymentInfo: {
                scmCs: newVal,
                scm_cs_id: newVal.id,
                scmVendor: vendor.scmVendor,
                scm_vendor_id: vendor.scmVendor.id,
                scmCsVendor: vendor,
                scm_cs_vendor_id: vendor.id,
                payment_type: null,
                payment_status: null,
                total_cost: 0.0,
                market_rate: 0.0,
                name_of_bank: null,
                cfr_value:vendor.total_negotiated_price,
                lc_margin:0.00,
                bank_commission:0.00,
                vat:0.00,
                others:0.00,
                insurence_premium:0.00,
                document_value:0.00,
                exchange_rate:0.00,
                insurence_company: null,
              }
            }
        props.form.selectedVendors.push(data);
      });
      }
    // }
    
    });

  // let createWatch = watch(()=> props.form.selectedVendors, (newVal, oldVal) => {
  //   if (props.formType == 'edit') {
  //     props.form.selectedVendors.forEach((vendor,index) => {
  //       if(vendor.scmCsLandedCost == null){

        
  //       let data = { 
  //             scmVendor: vendor.scmVendor,
  //             scm_vendor_id: vendor.scmVendor.id,
  //             scmCsLandedCost:
  //             {
  //               scmCs: newVal,
  //               scm_cs_id: newVal.id,
  //               scmVendor: vendor.scmVendor,
  //               scm_vendor_id: vendor.scmVendor.id,
  //               scmCsVendor: vendor,
  //               scm_cs_vendor_id: vendor.id,
  //               hs_codes: null,
  //               exchange_rate: 0.0,
  //               product_price: 0.0,
  //               freight_charge: 0.0,
  //               cfr_value: vendor.total_negotiated_price,
  //               insurance: 0.0,
  //               assesable_value_b: 0.0,
  //               landing_charge: 0.0,
  //               assesable_value_a: 0.0,
  //               cd: 0.0,
  //               rd: 0.0,
  //               sd: 0.0,
  //               vat: 0.0,
  //               at: 0.0,
  //               ait: 0.0,
  //               total_duty: 0.0,
  //               others: 0.0,
  //               total_landed_cost: 0.0,
  //             },
  //             scmCsPaymentInfo: {
  //               scmCs: newVal,
  //               scm_cs_id: newVal.id,
  //               scmVendor: vendor.scmVendor,
  //               scm_vendor_id: vendor.scmVendor.id,
  //               scmCsVendor: vendor,
  //               scm_cs_vendor_id: vendor.id,
  //               type: null,
  //               status: null,
  //               total_cost: 0.0,
  //               market_rate: 0.0,
  //               name_of_bank: null,
  //               cfr_value:vendor.total_negotiated_price,
  //               lc_margin:0.00,
  //               bank_commission:0.00,
  //               vat:0.00,
  //               others:0.00,
  //               insurence_premium:0.00,
  //               document_value:0.00,
  //               exchange_rate:0.00,
  //               insurence_company: null,
  //             }
  //           }
  //       props.form.selectedVendors.push(data);
  //     }
  //     });
  //   }
  //   createWatch();
  // });
  watchPostEffect(() => {
      props.form.document_value = props.form.cfr_value - props.form.lc_margin;
  });

  // watch form.selectedVendors

  watch(() => props.form.selectedVendors, (newVal, oldVal) => {
    if (newVal) {
      newVal.forEach((vendor,index) => {
        props.form.selectedVendors[index].scmCsPaymentInfo.document_value =  parseFloat(props.form.selectedVendors[index].scmCsPaymentInfo.cfr_value) -  parseFloat(props.form.selectedVendors[index].scmCsPaymentInfo.lc_margin);
        props.form.selectedVendors[index].scmCsPaymentInfo.total_cost = parseFloat(props.form.selectedVendors[index].scmCsPaymentInfo.others) + parseFloat(props.form.selectedVendors[index].scmCsPaymentInfo.insurence_premium) + parseFloat(props.form.selectedVendors[index].scmCsPaymentInfo.vat) + parseFloat(props.form.selectedVendors[index].scmCsPaymentInfo.bank_commission);

        props.form.selectedVendors[index].scmCsLandedCost.assesable_value_b = parseFloat(props.form.selectedVendors[index].scmCsLandedCost.cfr_value) + parseFloat(props.form.selectedVendors[index].scmCsLandedCost.insurance);
        props.form.selectedVendors[index].scmCsLandedCost.assesable_value_a = parseFloat(props.form.selectedVendors[index].scmCsLandedCost.assesable_value_b) + parseFloat(props.form.selectedVendors[index].scmCsLandedCost.landing_charge);
        props.form.selectedVendors[index].scmCsLandedCost.total_duty = parseFloat(props.form.selectedVendors[index].scmCsLandedCost.cd) + parseFloat(props.form.selectedVendors[index].scmCsLandedCost.rd) + parseFloat(props.form.selectedVendors[index].scmCsLandedCost.sd) + parseFloat(props.form.selectedVendors[index].scmCsLandedCost.vat) + parseFloat(props.form.selectedVendors[index].scmCsLandedCost.at) + parseFloat(props.form.selectedVendors[index].scmCsLandedCost.ait);
        props.form.selectedVendors[index].scmCsLandedCost.total_landed_cost = parseFloat(props.form.selectedVendors[index].scmCsLandedCost.assesable_value_a) + parseFloat(props.form.selectedVendors[index].scmCsLandedCost.total_duty) + parseFloat(props.form.selectedVendors[index].scmCsLandedCost.others);
      });
    }
  },{
    deep: true
  });

</script>
<template>
  <div class="input-group">
      <label class="label-group">
        <span class="label-item-title">CS No : <span>{{ props.form?.scmCs?.ref_no }}</span></span>
      </label>
  </div>
 <template v-if="form.selectedVendors.length">
  <div v-for="(vendor,index) in form.selectedVendors">
    <div class="grid md:grid-cols-2 gap-2">
      <div class="p-2 border-2 mt-7 bg-gray-400 text w-full col-span-2 text-center">{{ vendor?.scmVendor?.name }}</div>
        <div id="col-start-1">
            <div class="table-responsive">
                  <table class="w-full whitespace-no-wrap" id="table">
                    <caption class="table_caption p-2 border-2 mt-7 bg-gray-400 text">Bank Payment Details</caption>
                    <tbody>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100 !w-3/4">Payment Type</td>
                        <td class="align-center text-center !w-1/4">
                          <input type="text" v-model="form.selectedVendors[index].scmCsPaymentInfo.payment_type" required class="form-input text-center" name="lc_type" :id="'lc_type'" />
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100 !w-3/4">Payment Status</td>
                        <td class="align-center text-center !w-1/4">
                          <input type="text" v-model="form.selectedVendors[index].scmCsPaymentInfo.payment_status" required class="form-input text-center" name="lc_type" :id="'lc_type'" />
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100 !w-3/4">Name of Bank</td>
                        <td class="align-center !w-1/4">
                          <input type="text" v-model="form.selectedVendors[index].scmCsPaymentInfo.name_of_bank " required class="form-input text-center" name="bank_name" :id="'bank_name'" />
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100">CFR Value (BDT)</td>
                        <td class="align-center">
                          <input type="number" v-model="form.selectedVendors[index].scmCsPaymentInfo.cfr_value" required class="form-input text-center" name="cfr_value" :id="'cfr_value'" min="1" readonly/>
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100">LC Margin (BDT)</td>
                        <td class="align-center">
                          <input type="number" v-model="form.selectedVendors[index].scmCsPaymentInfo.lc_margin" required class="form-input text-center" name="lc_margin" :id="'lc_margin'" min="1"/>
                        </td>
                      </tr>
                      <tr class="text-center">
                          <td class="align-center font-bold bg-gray-100">Bank Commission (a)</td>
                          <td class="align-center">
                            <input type="number" v-model="form.selectedVendors[index].scmCsPaymentInfo.bank_commission" required class="form-input text-center"/>
                          </td>
                        </tr>
                      <tr class="text-center">
                          <td class="align-center font-bold bg-gray-100">VAT (b)</td>
                          <td class="align-center">
                            <input type="number" v-model="form.selectedVendors[index].scmCsPaymentInfo.vat" required class="form-input text-center"/>
                          </td>
                        </tr>
                        <tr class="text-center">
                          <td class="align-center font-bold bg-gray-100">Insurance Premium (c)</td>
                          <td class="align-center">
                            <input type="number" v-model="form.selectedVendors[index].scmCsPaymentInfo.insurence_premium" required class="form-input text-center"/>
                          </td>
                        </tr>
                        <tr class="text-center">
                            <td class="align-center font-bold bg-gray-100">Others (d)</td>
                            <td class="align-center">
                              <input type="number" v-model="form.selectedVendors[index].scmCsPaymentInfo.others" required class="form-input text-center"/>
                            </td>
                          </tr>
                      <tr class="text-right">
                        <td class="align-right font-bold bg-gray-100">Total Costs Relating To LC ( a + b + c + d)</td>
                        <td class="align-center">
                          <input type="number" v-model="form.selectedVendors[index].scmCsPaymentInfo.total_cost" required readonly class="form-input text-center vms-readonly-input" name="total_cost" :id="'total_cost'" />
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100">Documents Value (CFR - Margin) (BDT)</td>
                        <td class="align-center">
                          <input type="number" v-model="form.selectedVendors[index].scmCsPaymentInfo.document_value" readonly required class="form-input text-center vms-readonly-input" name="document_value" :id="'document_value'" />
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100">Exchange Rate (BDT/USD)</td>
                        <td class="align-center">
                          <input type="number" v-model="form.selectedVendors[index].scmCsPaymentInfo.exchange_rate" required class="form-input text-center" name="exchange_rate" :id="'exchange_rate'"  min="1"/>
                        </td>
                      </tr>
                      <!-- <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100">Market Rate (BDT/USD)</td>
                        <td class="align-center">
                          <input type="number" v-model="form.selectedVendors[index].scmCsPaymentInfo.market_rate" required class="form-input text-center" name="market_rate" :id="'market_rate'"  min="1"/>

                        </td>
                      </tr> -->
                    </tbody>
                  </table>
              <!-- </fieldset> -->
            </div>
        </div>
        <div id="customDataTable">
            <div  class="table-responsive">
                  <table class="w-full whitespace-no-wrap" id="table">
                    <caption class="table_caption p-2 border-2 mt-7 bg-gray-400 text">Landed Cost</caption>
                    <tbody>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100 !w-3/4">HS Codes</td>
                        <td class="align-center text-center !w-1/4">
                          <input type="text" v-model="form.selectedVendors[index].scmCsLandedCost.hs_codes" required class="form-input text-center" name="lc_type" :id="'lc_type'" />
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100 !w-3/4">Currency Rate</td>
                        <td class="align-center text-center !w-1/4">
                          <input type="number" step="0.01" v-model="form.selectedVendors[index].scmCsLandedCost.exchange_rate" required class="form-input text-center" name="lc_type" :id="'lc_type'" />
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100 !w-3/4">Product Price as Per PI</td>
                        <td class="align-center text-center !w-1/4">
                          <input type="number" v-model="form.selectedVendors[index].scmCsLandedCost.price_per_pi" required class="form-input text-center" name="lc_type" :id="'lc_type'" />
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100 !w-3/4">CFR / CPT Value</td>
                        <td class="align-center text-center !w-1/4">
                          <input type="number" v-model="form.selectedVendors[index].scmCsLandedCost.cfr_value" required class="form-input text-center" readonly/>
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100 !w-3/4">Add : Insurance</td>
                        <td class="align-center text-center !w-1/4">
                          <input type="number" v-model="form.selectedVendors[index].scmCsLandedCost.insurance" required class="form-input text-center"/>
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100 !w-3/4">Assesable Value Before Landing</td>
                        <td class="align-center text-center !w-1/4">
                          <input type="number" v-model="form.selectedVendors[index].scmCsLandedCost.assesable_value_b" readonly class="form-input text-center vms-readonly-input"/>
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100 !w-3/4">Add : Landing Charge</td>
                        <td class="align-center text-center !w-1/4">
                          <input type="number" v-model="form.selectedVendors[index].scmCsLandedCost.landing_charge" required class="form-input text-center"/>
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100 !w-3/4">Assessable Value (A)</td>
                        <td class="align-center text-center !w-1/4">
                          <input type="number" v-model="form.selectedVendors[index].scmCsLandedCost.assesable_value_a" readonly class="form-input text-center vms-readonly-input"/>
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100 !w-3/4">CD</td>
                        <td class="align-center text-center !w-1/4">
                          <input type="number" v-model="form.selectedVendors[index].scmCsLandedCost.cd" required class="form-input text-center"/>
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100 !w-3/4">RD</td>
                        <td class="align-center !w-1/4">
                          <input type="number" v-model="form.selectedVendors[index].scmCsLandedCost.rd" required class="form-input text-center"/>
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100">SD</td>
                        <td class="align-center">
                          <input type="number" v-model="form.selectedVendors[index].scmCsLandedCost.sd" required class="form-input text-center" min="1"/>
                        </td>
                      </tr>
                      <tr class="text-center">
                        <td class="align-center font-bold bg-gray-100">VAT</td>
                        <td class="align-center">
                          <input type="number" v-model="form.selectedVendors[index].scmCsLandedCost.vat" required class="form-input text-center" min="1"/>
                        </td>
                      </tr>
                      <tr class="text-center">
                          <td class="align-center font-bold bg-gray-100">AIT </td>
                          <td class="align-center">
                            <input type="number" v-model="form.selectedVendors[index].scmCsLandedCost.ait" required class="form-input text-center"/>
                          </td>
                        </tr>
                      <tr class="text-center">
                          <td class="align-center font-bold bg-gray-100">AT</td>
                          <td class="align-center">
                            <input type="number" v-model="form.selectedVendors[index].scmCsLandedCost.at" required class="form-input text-center"/>
                          </td>
                        </tr>
                      <tr class="text-center">
                          <td class="align-center font-bold bg-gray-100">Total Duty & Taxes (B)</td>
                          <td class="align-center">
                            <input type="number" v-model="form.selectedVendors[index].scmCsLandedCost.total_duty" readonly class="form-input text-center vms-readonly-input"/>
                          </td>
                        </tr>
                      <tr class="text-center">
                          <td class="align-center font-bold bg-gray-100">Other Charge (C)</td>
                          <td class="align-center">
                            <input type="number" v-model="form.selectedVendors[index].scmCsLandedCost.others" required class="form-input text-center"/>
                          </td>
                        </tr>
                      <tr class="text-right">
                        <td class="align-right font-bold bg-gray-100">Landed Cost (A + B + C)</td>
                        <td class="align-center">
                          <input type="number" v-model="form.selectedVendors[index].scmCsLandedCost.total_landed_cost" readonly class="form-input text-center vms-readonly-input" name="total_cost" :id="'total_cost'" />

                        </td>
                      </tr>
                    </tbody>
                  </table>  
              <!-- </fieldset> -->
            </div>
        </div>
    </div>
  </div>
</template>

 
  
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