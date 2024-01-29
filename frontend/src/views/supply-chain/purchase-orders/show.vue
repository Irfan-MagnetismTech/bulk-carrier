<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import usePurchaseOrder from "../../../composables/supply-chain/usePurchaseOrder";
import useWorkOrder from "../../../composables/supply-chain/useWorkOrder";
import { useRoute } from 'vue-router';

const { getWorkOrders, showWorkOrder, workOrder, updateWorkOrder,materialObject, errors, isLoading } = useWorkOrder();
const { getPurchaseOrders, showPurchaseOrder, purchaseOrder, updatePurchaseOrder } = usePurchaseOrder();
const { numberFormat } = useHelper();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import { formatDate } from "../../../utils/helper";
import useHelper from "../../../composables/useHelper";

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const purchaseOrderId = route.params.purchaseOrderId;
const formType = 'show';

setTitle('Purchase Order Details');

onMounted(() => {
  showPurchaseOrder(purchaseOrderId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Purchase Order Details</h2>
        <default-button :title="'Purchase Order List'" :to="{ name: 'scm.purchase-orders.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <div class="flex md:gap-4">
        <div class="w-full">
            <table class="w-full">
                <thead>
                    <tr>
                        <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Basic Info</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="w-40">Business Unit</th>
                        <td><span :class="purchaseOrder?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ purchaseOrder?.business_unit }}</span></td>
                    </tr>

                    <tr>
                        <th class="w-40">Po Ref</th>
                        <td>{{ purchaseOrder?.ref_no }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Purchase Center</th>
                        <td>{{ purchaseOrder?.purchase_center }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Warehouse</th>
                        <td>{{ purchaseOrder?.scmWarehouse?.name }}</td>
                    </tr>
                    
                    <tr>
                        <th class="w-40">CS No</th>
                        <td>{{ purchaseOrder?.scmWcs?.ref_no ?? 'N/A' }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">PO Date</th>
                        <td>{{ formatDate(purchaseOrder?.date) }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Vendor Name</th>
                        <td>{{ purchaseOrder?.scmVendor?.name }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Currency</th>
                        <td>{{ purchaseOrder?.currency }}</td>
                    </tr>

                    
                    <tr  v-if="(purchaseOrder.currency != 'USD' && purchaseOrder.currency != 'BDT' && purchaseOrder.currency != '' && urchaseOrder.currency != null)">
                        <th class="w-40">Convertion Rate( Foreign To USD )</th>
                        <td>{{ purchaseOrder?.foreign_to_usd }}</td>
                    </tr>

                    
                    <tr  v-if="purchaseOrder.currency != 'BDT'">
                        <th class="w-40">Convertion Rate( USD To BDT )</th>
                        <td>{{ purchaseOrder?.usd_to_bdt }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Remarks</th>
                        <td>{{ purchaseOrder?.remarks }}</td>
                    </tr>

                    <tr  v-for="(scmPoTerm, scmPoTermIndex) in purchaseOrder.scmPoTerms" :key="scmPoTermIndex">
                      <th :rowspan="purchaseOrder?.scmPoTerms?.length ?? 1" v-if="scmPoTermIndex == 0">Terms And Conditions</th>
                      <td>{{ scmPoTerm?.description }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="purchaseOrder?.scmPoLines?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="13">Purchase Order Information</td>
                </tr>
            
              <tr class="w-full">
                <th class="!text-center" rowspan="2">PR No</th>
                <th class="!text-center" rowspan="2">PR Date</th>
              </tr>
              <tr>
                <th class="!text-center">Material - Code</th>
                <th class="!text-center">Unit</th>
                <th class="!text-center">Brand</th>
                <th class="!text-center">Model</th>
                <th class="!text-center">Required Date</th>
                <th class="!text-center">PR Qty</th>
                <th class="!text-center">Qty</th>
                <th class="!text-center">Rate</th>
                <th class="!text-center">Total Price</th>
                <th class="!text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="(scmPoLine, index) in purchaseOrder.scmPoLines" :key="index">
                <template v-for="(scmPoItem, itemIndex) in scmPoLine?.scmPoItems" :key="itemIndex">
                  <tr>
                    <td :rowspan="(scmPoLine?.scmPoItems?.length)" v-if="itemIndex == 0" class="!text-center">{{scmPoLine.scmPr.ref_no }}</td>
                    <td class="!text-center" :rowspan="(scmPoLine?.scmPoItems?.length)" v-if="itemIndex == 0">{{ formatDate(scmPoLine?.scmPr?.raised_date) }}</td>
                    <td class="!text-center">{{ scmPoItem?.scmMaterial?.material_name_and_code }}</td>
                    <td class="!text-center">{{ scmPoItem?.unit }}</td>
                    <td class="!text-center">{{ scmPoItem?.brand }}</td>
                    <td class="!text-center">{{ scmPoItem?.model }}</td>
                    <td class="!text-center">{{ formatDate(scmPoItem?.required_date) }}</td>
                    <td class="!text-right">{{ numberFormat(scmPoItem?.pr_quantity) }}</td>
                    <td class="!text-right">{{ numberFormat(scmPoItem?.quantity) }}</td>
                    <td class="!text-right">{{ numberFormat(scmPoItem?.rate) }}</td>
                    <td class="!text-right">{{ numberFormat(scmPoItem?.total_price) }}</td>
                    <td class="!text-right">{{ numberFormat(scmPoItem?.total_price) }}</td>

                  </tr>
                  
                </template>
                
                
              </template>
              <tr>
                <th colspan="10" class="!text-right">Sub Total</th>
                <td class="!text-right">{{ numberFormat(purchaseOrder?.sub_total) }}</td>
                <td></td>
              </tr>
              
              <tr>
                <th colspan="10" class="!text-right">Total Amount</th>
                <td class="!text-right">{{ numberFormat(purchaseOrder?.total_amount) }}</td>
                <td> </td>
              </tr>

              
              <tr>
                <th colspan="10" class="!text-right">Net Amount</th>
                <td class="!text-right">{{ numberFormat(purchaseOrder?.net_amount) }}</td>
                <td>  </td>
              </tr>



              <!-- <tr v-for="(certificate, index) in materialAdjustment.scmAdjustmentLines">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  <span v-if="materialAdjustment.scmAdjustmentLines[index]?.scmMaterial?.name">{{ materialAdjustment.scmAdjustmentLines[index]?.scmMaterial?.name }}</span>
                </td>
                <td>
                  <span v-if="materialAdjustment.scmAdjustmentLines[index]?.unit">{{ materialAdjustment.scmAdjustmentLines[index]?.unit }}</span>
                </td>
                <td>
                <span>
                    {{ numberFormat(materialAdjustment.scmAdjustmentLines[index].quantity) }}
                </span>
              </td>
              <td>
                <span>
                    {{ numberFormat(materialAdjustment.scmAdjustmentLines[index].rate) }}
                </span>
              </td>
              </tr> -->
            </tbody>
          </table>
        </div>
    </div>
    </div>
</template>

<style lang="postcss" scoped>
  th, td, tr {
    @apply text-left border-gray-500
  }
  
  tfoot td{
    @apply text-center
  }
     
</style>
