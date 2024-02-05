<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import usePurchaseOrder from "../../../composables/supply-chain/usePurchaseOrder";
import useWorkOrder from "../../../composables/supply-chain/useWorkOrder";
import { useRoute } from 'vue-router';
import RemarksComponent from '../../../components/utils/RemarksComponent.vue';
import env from "../../../config/env";

const { getWorkOrders, showWorkOrder, workOrder, updateWorkOrder,materialObject, errors, isLoading } = useWorkOrder();
const { getPurchaseOrders, showPurchaseOrder, purchaseOrder, updatePurchaseOrder,closePoLines } = usePurchaseOrder();
const { numberFormat } = useHelper();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import { formatDate, formatDateTime } from '../../../utils/helper';
import useHelper from "../../../composables/useHelper";

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const purchaseOrderId = route.params.purchaseOrderId;
const formType = 'show';

setTitle('Purchase Order Details');

const isModalOpen = ref(0);
const details = ref([{type: ''}]);
const currentId = ref(null);
const closingRemarks = ref(null);
const status = ['Open', 'Closed'];
const critical = ['Yes', 'No'];

function showModal(id) {
  isModalOpen.value = 1
  currentId.value = id;
}
function closeModel() {
  isModalOpen.value = 0
  closingRemarks.value = null;
  currentId.value = null;
}

function closePurchaseOrder() {
          closePoLines(purchaseOrderId,currentId.value,closingRemarks.value).then(() => {
            closeModel()
          }).catch((error) => {
            console.error("Error closing PO:", error);
          });
}




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

                    
                    <tr  v-if="(purchaseOrder?.currency != 'USD' && purchaseOrder?.currency != 'BDT' && purchaseOrder?.currency != '' && purchaseOrder?.currency != null)">
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

                    <tr>
                        <th class="w-40"> Status </th>
                        <!-- <td>{{ status[purchaseRequisition?.is_closed] }}</td> -->
                        
                        <td>
                          <span :class="purchaseOrder?.status === 'Pending' ? 'text-yellow-700 bg-yellow-100' : (purchaseOrder?.status == 'WIP' ? 'text-blue-700 bg-blue-100' : 'text-red-700 bg-red-100') " class="px-2 py-1 font-semibold leading-tight rounded-full">{{ purchaseOrder?.status ?? 'Closed' }}</span>
                        </td>
                    </tr>
                    <tr v-if="purchaseOrder.status === 'Closed'">
                        <th class="w-40">Closed At</th>
                        <td>{{ formatDateTime(purchaseOrder?.closed_at) }}</td>
                    </tr>
                    <tr v-if="purchaseOrder.status === 'Closed'">
                        <th class="w-40">Closed By</th>
                        <td>{{ purchaseOrder?.closedBy.name }}</td>
                    </tr>
                    <tr v-if="purchaseOrder.status === 'Closed'">
                        <th class="w-40">Closing Remarks </th>
                        <td>{{ purchaseOrder?.closing_remarks }}</td>
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
                    <td :rowspan="(scmPoLine?.scmPoItems?.length)" v-if="itemIndex == 0" class="!text-center">{{scmPoLine?.scmPr?.ref_no }}</td>
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
                    <td class="!text-right">
                      <button v-if="scmPoItem.status != 'Closed'" @click="showModal(scmPoItem.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700">Close</button>
                                  
                      <span v-else :class="scmPoItem?.status === 'Pending' ? 'text-yellow-700 bg-yellow-100' : (scmPoItem?.status == 'WIP' ? 'text-blue-700 bg-blue-100' : 'text-red-700 bg-red-100') " :title="scmPoItem?.status === 'Closed' ? `
                      Closed At : ${formatDateTime(scmPoItem?.closed_at)}\nClosed By : ${scmPoItem?.closed_by}
                      ` : ''" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ scmPoItem?.status ?? 'Closed' }}</span>
                    </td>

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

    <div v-show="isModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark-disabled:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark-disabled:hover:text-gray-200 hover: hover:text-gray-700"
                  aria-label="close" @click="closeModel">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
              <path
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
          </button>
        </header>
        <!-- Modal body -->
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead v-once>
          <tr style="background-color: #04AA6D;color: white"
              class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark-disabled:border-gray-700 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th colspan="5">Remarks</th>
          </tr>
          </thead>
        </table>
        <div class="dt-responsive table-responsive">
          <table id="dataTable" class="w-full table table-striped table-bordered">
            <tbody>
              <tr>
                <td>
                <RemarksComponent v-model="closingRemarks" :maxlength="300" :fieldLabel="'Closing Remarks'" isRequired="true" hideLebel="true"></RemarksComponent>
                </td>
              </tr>
           </tbody>
          </table>
        </div>
        <footer class="flex flex-col items-center justify-between px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark-disabled:bg-gray-800">
          <button type="button" @click="closeModel" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            CLOSE
          </button>
          <button type="button" @click="closePurchaseOrder" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            CONFIRM
          </button>
        </footer>
      </div>
    </form>
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
