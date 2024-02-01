<script setup>
import {ref,onMounted, watchPostEffect, watch,watchEffect} from "vue";

import Title from "../../../services/title";
import useHelper from "../../../composables/useHelper.js";
import useLcRecord from "../../../composables/supply-chain/useLcRecord";
import LcRecordShow from "../../../components/supply-chain/lc-records/LcRecordShow.vue";
import { useRoute } from 'vue-router';
import useWcsQuotation from "../../../composables/supply-chain/useWcsQuotation";
import { merge ,cloneDeep,groupBy,first, values} from 'lodash';

// const { showWcsQuotation, wcsQuotation } = useWcsQuotation();
const { getMaterialCs, showMaterialCs, materialCs, updateMaterialCs, errors, isLoading } = useMaterialCs();
const { quotation, localQuotationLines, foreignQuotationLines,showQuotation } = useQuotation();
const { numberFormat } = useHelper();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import { formatDate } from "../../../config/setting";
import env from "../../../config/env";
import useQuotation from "../../../composables/supply-chain/useQuotation";
import useMaterialCs from "../../../composables/supply-chain/useMaterialCs";

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();

const quotationId = route.params.quotationId;
const csId = route.params.csId;
// const wcsQuotationId = route.params.wcsQuotationId;
const formType = 'edit';

setTitle('Show Quotation');

// watch form.scmCsMaterialVendors
watchEffect(() => {
    console.log(quotation.scmCsMaterialVendors);
      let total_negotiated_price = 0;
      let total_offered_price = 0;
      let grand_total_negotiated_price = 0;
      let grand_total_offered_price = 0;
      quotation.scmCsMaterialVendors?.forEach((lines, index) => {
        lines.forEach((line, index) => {
          line.negotiated_amount = line.negotiated_price * line.quantity;
          total_negotiated_price += line.negotiated_amount; 
          line.offer_amount = line.offered_price * line.quantity;
          total_offered_price += line.offer_amount;
        });
      });
      grand_total_negotiated_price = total_negotiated_price + quotation.freight;
      grand_total_offered_price = total_offered_price + quotation.freight;
      quotation.total_negotiated_price = total_negotiated_price;
      quotation.total_offered_price = total_offered_price;
      quotation.grand_total_negotiated_price = grand_total_negotiated_price;
      quotation.grand_total_offered_price = grand_total_offered_price;
    });
onMounted(() => {
    showQuotation(csId,quotationId);
    showMaterialCs(csId);
  
});

watch(() => quotation, () => {
    console.log(quotation.scmCsMaterialVendors);
      let total_negotiated_price = 0;
      let total_offered_price = 0;
      let grand_total_negotiated_price = 0;
      let grand_total_offered_price = 0;
      quotation.scmCsMaterialVendors?.forEach((lines, index) => {
        lines.forEach((line, index) => {
          line.negotiated_amount = line.negotiated_price * line.quantity;
          total_negotiated_price += line.negotiated_amount; 
          line.offer_amount = line.offered_price * line.quantity;
          total_offered_price += line.offer_amount;
        });
      });
      grand_total_negotiated_price = total_negotiated_price + quotation.freight;
      grand_total_offered_price = total_offered_price + quotation.freight;
      quotation.total_negotiated_price = total_negotiated_price;
      quotation.total_offered_price = total_offered_price;
      quotation.grand_total_negotiated_price = grand_total_negotiated_price;
      quotation.grand_total_offered_price = grand_total_offered_price;
    }, {immediate: true});
    
</script>
<template>
    <!-- Heading -->
    
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Quotation Details</h2>
        <default-button :title="'Quotation List'" :to="{ name: 'scm.quotations.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        
          
        <!-- <lc-record-show :form="lcRecord"></lc-record-show>       -->
        <div class="w-full">
            <table class="w-full">
                <thead>
                    <tr>
                        <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Basic Info</td>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                        <th class="w-40">CS Ref</th>
                        <td>{{ quotation?.scmCs?.ref_no }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Vendor Name</th>
                        <td>{{ quotation?.scmVendor?.name }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Vendor Contact No</th>
                        <td>{{ quotation?.scmVendor?.scmVendorContactPerson?.phone }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Party Type</th>
                        <td>{{ quotation?.scmVendor?.product_source_type }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Sourcing History</th>
                        <td>{{ quotation?.sourcing }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Date Of RFQ</th>
                        <td>{{ formatDate(quotation?.date_of_rfq) }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Quotation Received Date</th>
                        <td>{{ formatDate(quotation?.quotation_received_date) }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Vendor Quotation No</th>
                        <td>{{ quotation?.quotation_ref }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Offer Validity</th>
                        <td>{{ formatDate(quotation?.quotation_validity) }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Payment Method</th>
                        <td>{{ quotation?.payment_method }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Currency</th>
                        <td>{{ quotation?.currency }}</td>
                    </tr>

                    
                    <tr v-if="materialCs.purchase_center == 'Local' ">
                        <th class="w-40">Carrying Charge Bear By</th>
                        <td>{{ quotation?.carring_cost_bear_by }}</td>
                    </tr>
                    
                    <tr v-if="materialCs.purchase_center == 'Local' ">
                        <th class="w-40">Unloading Charge Bear By</th>
                        <td>{{ quotation?.unloading_cost_bear_by }}</td>
                    </tr>

                    
                    <tr v-if="materialCs.purchase_center == 'Local' ">
                        <th class="w-40">VAT</th>
                        <td>{{ quotation?.vat }}</td>
                    </tr>

                    
                    <tr v-if="materialCs.purchase_center == 'Local' ">
                        <th class="w-40">AIT</th>
                        <td>{{ quotation?.ait }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Warranty</th>
                        <td>{{ quotation?.warranty }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Delivery Time (Days)</th>
                        <td>{{ quotation?.delivery_time }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Inco-term</th>
                        <td>{{ quotation?.delivery_term }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Credit Term</th>
                        <td>{{ quotation?.credit_term }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Terms & Conditions</th>
                        <td>{{ quotation?.terms_and_condition }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Remarks</th>
                        <td>{{ quotation?.remarks }}</td>
                    </tr>                   
                </tbody>
            </table>
        </div>

        
        <div class="md:flex md:gap-2 " >
          <div class="w-full mt-2">
            <table class="w-full" v-if="materialCs.purchase_center == 'Local' ">
                <thead>
                    <tr>
                        <th class="!text-center font-bold bg-green-600 uppercase text-white" colspan="8">Materials</th>
                    </tr>
                    
                    <tr>
                        <th class="!text-center">Material Name </th>
                        <th class="!text-center">PR No </th>
                        <th class="!text-center">Unit</th>
                        <th class="!text-center">Brand</th>
                        <th class="!text-center">Model</th>
                        <th class="!text-center">Warranty Period</th>
                        <th class="!text-center">Offer Price</th>
                        <th class="!text-center">Negotiated Price</th>
                    </tr>

                </thead>
                <tbody>
                    <template v-for="(lines, indexa) in quotation.scmCsMaterialVendors" :key="indexa">
                <template v-for="(scmSrLine, index) in lines" :key="index">
                  <tr v-if="index != 0">
                    <td><nobr>{{ scmSrLine?.scmPr?.ref_no }}</nobr></td>
                  </tr>
                  <tr class="text-gray-700 dark-disabled:text-gray-400" v-else>
                    <td :rowspan="lines.length">{{ first(values(lines))?.scmMaterial?.name }}</td>
                    <td><nobr>{{ scmSrLine?.scmPr?.ref_no }}</nobr></td>
                    <td :rowspan="lines.length">{{ scmSrLine?.scmMaterial?.unit }}</td>

                    <td v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <!-- <input type="text" v-model="quotation.scmCsMaterialVendors[indexa][index].brand" class="form-input" required/> -->
                      {{ quotation.scmCsMaterialVendors[indexa][index]?.brand }}
                    </td>
                    <td v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <!-- <input type="text" v-model="quotation.scmCsMaterialVendors[indexa][index].model" class="form-input" required/> -->
                      {{ quotation.scmCsMaterialVendors[indexa][index]?.model }}
                    </td>
                    <td v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <!-- <input type="text" v-model="quotation.scmCsMaterialVendors[indexa][index].warranty_period" class="form-input" required/> -->
                      {{ quotation.scmCsMaterialVendors[indexa][index]?.warranty_period }}
                    </td>
                    <td v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <!-- <input type="number" v-model="quotation.scmCsMaterialVendors[indexa][index].offered_price" class="form-input"/> -->
                      {{ numberFormat(quotation.scmCsMaterialVendors[indexa][index]?.offered_price) }}
                    </td>
                    <td v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <!-- <input type="number" v-model="quotation.scmCsMaterialVendors[indexa][index].negotiated_price" class="form-input" min="1"/> -->
                      {{ numberFormat(quotation.scmCsMaterialVendors[indexa][index]?.negotiated_price) }}
                    </td>
              
                  </tr>
                </template>
              </template>
                    
                </tbody>
            </table>

            
            <table class="w-full" v-else>
                <thead>
                    <tr>
                        <th class="!text-center font-bold bg-green-600 uppercase text-white" colspan="14">Materials</th>
                    </tr>
                    
                    <tr>
                        <th class="!text-center" rowspan="2">Material Name </th>
                        <th class="!text-center" rowspan="2">PR No </th>
                        <th class="!text-center" rowspan="2">Unit</th>
                        <th class="!text-center" rowspan="2">Brand</th>
                        <th class="!text-center" rowspan="2">Model</th>
                        <th class="!text-center" rowspan="2">Origin</th>
                        <th class="!text-center" rowspan="2">Installation & Commission</th>
                        <th class="!text-center" rowspan="2">Certificate</th>
                        <th class="!text-center" rowspan="2">Warranty Period</th>
                        <th class="!text-center" rowspan="2">Quantity</th>
                        <th class="!text-center" colspan="2">Offer Price</th>
                        <th class="!text-center" colspan="2">Negotiated Price</th>
                    </tr>
                    <tr>
                        <th class="!text-center">Per Unit</th>
                        <th class="!text-center">Total</th>
                        <th class="!text-center">Per Unit</th>
                        <th class="!text-center">Total</th>
                    </tr>

                </thead>
                <tbody>
                    <template v-for="(lines, indexa) in quotation.scmCsMaterialVendors" :key="indexa">
                <template v-for="(scmSrLine, index) in lines" :key="index">
                  <tr v-if="index != 0">
                    <td><nobr>{{ scmSrLine?.scmPr?.ref_no }}</nobr></td>
                  </tr>
                  <tr class="text-gray-700 dark-disabled:text-gray-400" v-else>
                    <td :rowspan="lines.length">{{ first(values(lines))?.scmMaterial?.name }}</td>
                    <td><nobr>{{ scmSrLine?.scmPr?.ref_no }}</nobr></td>
                    <td :rowspan="lines.length">{{ scmSrLine?.scmMaterial?.unit }}</td>
                    <td v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <!-- <input type="text" v-model="quotation.scmCsMaterialVendors[indexa][index].brand" class="form-input"/> -->
                      {{ quotation.scmCsMaterialVendors[indexa][index].brand }}
                    </td>
                    <td v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <!-- <input type="text" v-model="quotation.scmCsMaterialVendors[indexa][index].model" class="form-input"/> -->
                      {{ quotation.scmCsMaterialVendors[indexa][index].model }}
                    </td>
                    <td v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <!-- <input type="text" v-model="quotation.scmCsMaterialVendors[indexa][index].origin" class="form-input"/> -->
                      {{ quotation.scmCsMaterialVendors[indexa][index].origin }}
                    </td>
                    <td v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <!-- <input type="text" v-model="quotation.scmCsMaterialVendors[indexa][index].installation_and_commission" class="form-input"/> -->
                      {{ quotation.scmCsMaterialVendors[indexa][index].installation_and_commission }}
                    </td>
                    <td v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <!-- <input type="text" v-model="quotation.scmCsMaterialVendors[indexa][index].certification" class="form-input"/> -->
                      {{ quotation.scmCsMaterialVendors[indexa][index].certification }}
                    </td>
                    <td v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <!-- <input type="text" v-model="quotation.scmCsMaterialVendors[indexa][index].warranty_period" class="form-input"/> -->
                      {{ quotation.scmCsMaterialVendors[indexa][index].warranty_period }}
                    </td>
                    <td v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <!-- <input type="text" :value="quotation.scmCsMaterialVendors[indexa][index].quantity" class="form-input" readonly/> -->
                      {{ quotation.scmCsMaterialVendors[indexa][index].quantity }}
                    </td>
                    <td class="!text-right" v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <!-- <input type="number" v-model="quotation.scmCsMaterialVendors[indexa][index].offered_price" class="form-input"/> -->
                      {{ numberFormat(quotation.scmCsMaterialVendors[indexa][index].offered_price) }}

                    </td>
                    <td class="!text-right" v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <!-- <input type="number" v-model="quotation.scmCsMaterialVendors[indexa][index].offer_amount" class="form-input"/> -->
                      {{ numberFormat(quotation.scmCsMaterialVendors[indexa][index].quantity * quotation.scmCsMaterialVendors[indexa][index].offered_price) }}
                    </td>
                    <td class="!text-right" v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                      <!-- <input type="number" v-model="quotation.scmCsMaterialVendors[indexa][index].negotiated_price" class="form-input" min="1"/> -->
                      {{ numberFormat(quotation.scmCsMaterialVendors[indexa][index].negotiated_price) }}
                    </td>
                    <td class="!text-right" v-if="quotation.scmCsMaterialVendors[indexa][index]" :rowspan="lines.length">
                        
                      <!-- <input type="number" v-model="quotation.scmCsMaterialVendors[indexa][index].negotiated_amount" class="form-input" min="1"/> -->
                      {{ numberFormat(quotation.scmCsMaterialVendors[indexa][index].quantity * quotation.scmCsMaterialVendors[indexa][index].negotiated_price) }}
                    </td>
                  </tr>
                  
                  <!-- <td class="!w-72">
                    <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterials" v-model="form.scmSrLines[index].scmMaterial" label="material_name_and_code" class="block form-input" @change="setMaterialOtherData(form.scmSrLines[index].scmMaterial,index)"> -->
                  <!--   <v-select :options="materials" placeholder="--Choose an option--" :loading="materialLoading" v-model="form.scmCsMaterialVendors[index].scmPr" label="ref_no" class="block form-input" :disabled="true" @update:modelValue="changeMaterial(form.scmCsMaterialVendors[index].scmMaterial,index)">
                      <template #search="{attributes, events}">
                          <input
                              class="vs__search"
                              :required="!form.scmCsMaterialVendors[index].scmPr"
                              v-bind="attributes"
                              v-on="events"
                              />
                      </template>
                  </v-select>
                  </td>
                  <td class="!w-72">
                    <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterials" v-model="form.scmSrLines[index].scmMaterial" label="material_name_and_code" class="block form-input" @change="setMaterialOtherData(form.scmSrLines[index].scmMaterial,index)"> -->
                   <!--  <v-select :options="materials" placeholder="--Choose an option--" :loading="materialLoading" v-model="form.scmCsMaterialVendors[index].scmMaterial" label="material_name_and_code" class="block form-input" :disabled="true" @update:modelValue="changeMaterial(form.scmCsMaterialVendors[index].scmMaterial,index)">
                      <template #search="{attributes, events}">
                          <input
                              class="vs__search"
                              :required="!form.scmCsMaterialVendors[index].scmMaterial"
                              v-bind="attributes"
                              v-on="events"
                              />
                      </template>
                  </v-select>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <input type="text" readonly v-model="form.scmCsMaterialVendors[index].unit" class=" form-input">
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <input type="text" v-model="form.scmCsMaterialVendors[index].brand" class="form-input">
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <input type="text" v-model="form.scmCsMaterialVendors[index].model" class="form-input">
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <input type="text" v-model="form.scmCsMaterialVendors[index].negotiated_price" class="form-input">
                    </label>
                  </td> -->
                </template>
              </template>
                    
                </tbody>
                <tfoot>
              <tr>
                <td colspan="10" class="!text-right">
                  Total
                </td>
                <td></td>
                <td class="!text-right">
                  <!-- <input type="text" v-model="quotation.total_offered_price" class="form-input" required/> -->
                  {{ numberFormat(quotation.total_offered_price) }}
                </td>
                <td></td>
                <td class="!text-right">
                  <!-- <input type="text" v-model="quotation.total_negotiated_price" class="form-input" required/> -->
                  {{ numberFormat(quotation.total_negotiated_price) }}
                </td>
              </tr>
              <tr>
                <td colspan="10" class="!text-right">
                  Freight
                </td>
                <td></td>
                <td class="!text-right">
                  <!-- <input type="text" v-model="quotation.freight" class="form-input" required/> -->
                  {{ numberFormat(quotation.freight) }}
                </td>
                <td></td>
                <td class="!text-right">
                  <!-- <input type="text" :value="quotation.freight" class="form-input" required/> -->
                  {{ numberFormat(quotation.freight) }}
                </td>
              </tr>
              <tr>
                <td colspan="10" class="!text-right">
                  Grand Total
                </td>
                <td></td>
                <td class="!text-right">
                  <!-- <input type="text" v-model="quotation.grand_total_offered_price" class="form-input" required/> -->
                  {{ numberFormat(quotation.grand_total_offered_price) }}
                </td>
                <td></td>
                <td class="!text-right">
                  <!-- <input type="text" v-model="quotation.grand_total_negotiated_price" class="form-input" required/> -->
                  {{ numberFormat(quotation.grand_total_negotiated_price) }}
                </td>
                
              </tr>
            </tfoot>
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

    th.text-center, td.text-center, tr.text-center {
      @apply text-center border-gray-500
    }
  
    th.text-right, td.text-right, tr.text-right {
      @apply text-right border-gray-500
    }
</style>
 