<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import usePurchaseOrder from "../../../composables/supply-chain/usePurchaseOrder";
import useWorkOrder from "../../../composables/supply-chain/useWorkOrder";
import { useRoute,useRouter } from 'vue-router';
import RemarksComponent from "../../../components/utils/RemarksComponent.vue";
import { formatDateTime } from "../../../utils/helper";

const { getWorkOrders, showWorkOrder, workOrder, updateWorkOrder, closeWoItems, serviceObject, errors, isLoading } = useWorkOrder();
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



const isModalOpen = ref(0);
const details = ref([{type: ''}]);
const currentId = ref(null);
const closingRemarks = ref(null);
const status = ['Open', 'Closed'];

function showModal(id) {
  isModalOpen.value = 1
  currentId.value = id;
}
function closeModel() {
  isModalOpen.value = 0
  closingRemarks.value = null;
  currentId.value = null;
}

function closeWorkOrder() {
    console.log(workOrderId);
    closeWoItems(workOrderId,currentId.value,closingRemarks.value).then(() => {
      closeModel()
    }).catch((error) => {
      console.error("Error closing WO:", error);
    });
}

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

                    <tr>
                        <th class="w-40">Status </th>
                        <td>
                          <!-- {{ workOrder?.status }} -->
                          <span :class="workOrder?.status === 'Pending' ? 'text-yellow-700 bg-yellow-100' : (workOrder?.status == 'WIP' ? 'text-blue-700 bg-blue-100' : 'text-red-700 bg-red-100') " class="px-2 py-1 font-semibold leading-tight rounded-full">{{ workOrder?.status ?? 'Closed' }}</span>
                        </td>
                    </tr>
                    <tr v-if="workOrder.status === 'Closed'">
                        <th class="w-40">Closed At</th>
                        <td>{{ formatDateTime(workOrder?.closed_at) }}</td>
                    </tr>
                    <tr v-if="workOrder.status === 'Closed'">
                        <th class="w-40">Closed By</th>
                        <td>{{ workOrder?.closedBy?.name }}</td>
                    </tr>
                    <tr v-if="workOrder.status === 'Closed'">
                        <th class="w-40">Closing Remarks </th>
                        <td>{{ workOrder?.closing_remarks }}</td>
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
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="9">Work Order Information</td>
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
                <th class="text-center" >Status</th>
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

                    <td class="">
                                  
                      <div class="flex justify-center">
                        <button v-if="scmWoItem.status != 'Closed'" @click="showModal(scmWoItem?.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700">Close</button>
                      <!-- <span v-else :class="scmWoItem?.is_closed === 0 ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ scmWoItem.is_closed === 0 ? 'Open' : 'Closed' }}</span> -->
                      
                      <span v-else :class="scmWoItem?.status === 'Pending' ? 'text-yellow-700 bg-yellow-100' : (scmWoItem?.status == 'WIP' ? 'text-blue-700 bg-blue-100' : 'text-red-700 bg-red-100') " :title="scmWoItem?.status === 'Closed' ? `
                      Closed At : ${formatDateTime(scmWoItem?.closed_at)}\nClosed By : ${scmWoItem?.closedBy?.name}
                      ` : ''" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ scmWoItem?.status ?? 'Closed' }}</span>
                      </div>

                    </td>
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
                <td></td>
              </tr>
              
              <tr>
                <th colspan="7" class="!text-right">Total Amount</th>
                <td class="!text-right">{{ numberFormat(workOrder?.total_amount) }}</td>
                <td></td>
              </tr>

              
              <tr>
                <th colspan="7" class="!text-right">Net Amount</th>
                <td class="!text-right">{{ numberFormat(workOrder?.net_amount) }}</td>
                <td></td>
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
          <button type="button" @click="closeWorkOrder" style="color: #1b1e21"
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

  #profileDetailTable th{
    text-align: center;
  }
  #profileDetailTable thead tr{
    @apply bg-gray-200
  }

  th.text-center, td.text-center, tr.text-center {
    @apply text-center border-gray-500
  }

  th.text-right, td.text-right, tr.text-right {
    @apply text-right border-gray-500
  }

</style>
