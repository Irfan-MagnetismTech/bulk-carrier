<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import usePurchaseOrder from "../../../composables/supply-chain/usePurchaseOrder";
import useWorkOrder from "../../../composables/supply-chain/useWorkOrder";
import { useRoute } from 'vue-router';

const { getWorkOrders, showWorkOrder, workOrder, updateWorkOrder,serviceObject, errors, isLoading } = useWorkOrder();
const { numberFormat } = useHelper();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import { formatDate } from "../../../utils/helper";
import useHelper from "../../../composables/useHelper";

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const workOrderId = route.params.workOrderId;
const formType = 'show';

setTitle('Work Order Details');

onMounted(() => {
    showWorkOrder(workOrderId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Work Order Details</h2>
        <default-button :title="'Work Order List'" :to="{ name: 'scm.work-orders.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="workOrder?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ workOrder?.business_unit }}</span></td>
                    </tr>

                    <tr>
                        <th class="w-40">Wo Ref</th>
                        <td>{{ workOrder?.ref_no }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Purchase Center</th>
                        <td>{{ workOrder?.purchase_center }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Warehouse</th>
                        <td>{{ workOrder?.scmWarehouse?.name }}</td>
                    </tr>
                    
                    <tr>
                        <th class="w-40">CS No</th>
                        <td>{{ workOrder?.scmWcs?.ref_no ?? 'N/A' }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">WO Date</th>
                        <td>{{ formatDate(workOrder?.date) }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Vendor Name</th>
                        <td>{{ workOrder?.scmVendor?.name }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Currency</th>
                        <td>{{ workOrder?.currency }}</td>
                    </tr>

                    
                    <tr  v-if="(workOrder.currency != 'USD' && workOrder.currency != 'BDT' && workOrder.currency != '')">
                        <th class="w-40">Convertion Rate( Foreign To USD )</th>
                        <td>{{ workOrder?.foreign_to_usd }}</td>
                    </tr>

                    
                    <tr  v-if="workOrder.currency != 'BDT'">
                        <th class="w-40">Convertion Rate( USD To BDT )</th>
                        <td>{{ workOrder?.usd_to_bdt }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Remarks</th>
                        <td>{{ workOrder?.remarks }}</td>
                    </tr>

                    <tr  v-for="(scmWoTerm, scmWoTermIndex) in workOrder.scmWoTerms" :key="scmWoTermIndex">
                      <th :rowspan="workOrder?.scmWoTerms?.length ?? 1" v-if="scmWoTermIndex == 0">Terms And Conditions</th>
                      <td>{{ scmWoTerm?.description }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="workOrder?.scmWoLines?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="8">Work Order Information</td>
                </tr>
            
              <tr class="w-full">
                <th class="!text-center" >WR No</th>
                <th class="!text-center" >WR Date</th>
                
                <th class="!text-center">Service - Code</th>
                <th class="!text-center">Required Date</th>
                <th class="!text-center">WR Qty</th>
                <th class="!text-center">Qty</th>
                <th class="!text-center">Rate</th>
                <th class="!text-center">Total Price</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="(scmWoLine, index) in workOrder.scmWoLines" :key="index">
                <template v-for="(scmWoItem, itemIndex) in scmWoLine?.scmWoItems" :key="itemIndex">
                  <tr>
                    <td :rowspan="(scmWoLine?.length ?? 1)" v-if="itemIndex == 0">{{ scmWoLine?.scmWr?.ref_no }}</td>
                    <td class="!text-center" :rowspan="(scmWoLine?.length ?? 1)" v-if="itemIndex == 0">{{ formatDate(scmWoLine.scmWr.raised_date) }}</td>
                    <td>{{ scmWoItem?.scmService?.service_name_and_code }}</td>
                    <td class="!text-center">{{ formatDate(scmWoItem?.required_date) }}</td>
                    <td class="!text-right">{{ numberFormat(scmWoItem?.wr_quantity) }}</td>
                    <td class="!text-right">{{ numberFormat(scmWoItem?.quantity) }}</td>
                    <td class="!text-right">{{ numberFormat(scmWoItem?.rate) }}</td>
                    <!-- Rate -->
                    <td class="!text-right">{{ numberFormat(scmWoItem?.total_price) }}</td>

                  </tr>
                  <!-- <tr>
                    <th class="!text-center">Rem. Qty</th>
                    <td class="!text-right">{{ numberFormat(scmWoItem?.max_quantity) }}</td>
                    <th class="!text-center">Rate</th>
                    <td class="!text-right">{{ numberFormat(scmWoItem?.rate) }}</td>
                  </tr> -->
                  
                </template>
                
                
              </template>
              <tr>
                <th colspan="7" class="!text-right">Sub Total</th>
                <td class="!text-right">{{ numberFormat(workOrder?.sub_total) }}</td>
              </tr>
              
              <tr>
                <th colspan="7" class="!text-right">Total Amount</th>
                <td class="!text-right">{{ numberFormat(workOrder?.total_amount) }}</td>
              </tr>

              
              <tr>
                <th colspan="7" class="!text-right">Net Amount</th>
                <td class="!text-right">{{ numberFormat(workOrder?.net_amount) }}</td>
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
