<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Purchase Requistion Details</h2>
    <default-button :title="'Purchase Requistion List'" :to="{ name: 'scm.purchase-requisitions.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
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
                        <td><span :class="purchaseRequisition?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ purchaseRequisition?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Requisition No.</th>
                        <td>{{ purchaseRequisition?.ref_no }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Purchase Center</th>
                        <td>{{ purchaseRequisition.purchase_center }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Requisition Date</th>
                        <td>{{ formatDate(purchaseRequisition?.raised_date) }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Approved Date</th>
                        <td>{{ formatDate(purchaseRequisition?.approved_date) }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Critical</th>
                        <td>{{ critical[purchaseRequisition?.is_critical ?? 0]  }}</td>
                    </tr>
                    <tr>
                      <th class="w-40">Attachment</th>
                      <td>
                          <a type="button" v-if="typeof purchaseRequisition?.attachment === 'string'" class="text-green-800" target="_blank" :href="env.BASE_API_URL+'/'+purchaseRequisition?.attachment">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-4 h-4">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                        </svg>
                      </a>
                      <a v-else>---</a>    
                      </td>
                    </tr>
                    <tr>
                        <th class="w-40">Remarks </th>
                        <td>{{ purchaseRequisition?.remarks }}</td>
                    </tr>
                    
                    <tr>
                        <th class="w-40">Requested By </th>
                        <td>{{ purchaseRequisition?.created_by }}</td>
                    </tr>

                    <tr>
                        <th class="w-40"> Status </th>
                        <!-- <td>{{ status[purchaseRequisition?.is_closed] }}</td> -->
                        
                        <td>
                          <span :class="purchaseRequisition?.status === 'Pending' ? 'text-yellow-700 bg-yellow-100' : (purchaseRequisition?.status == 'WIP' ? 'text-blue-700 bg-blue-100' : 'text-red-700 bg-red-100') " class="px-2 py-1 font-semibold leading-tight rounded-full">{{ purchaseRequisition?.status ?? 'Closed' }}</span>
                        </td>
                    </tr>
                    <tr v-if="purchaseRequisition.status === 'Closed'">
                        <th class="w-40">Closed At</th>
                        <td>{{ formatDateTime(purchaseRequisition?.closed_at) }}</td>
                    </tr>
                    <tr v-if="purchaseRequisition.status === 'Closed'">
                        <th class="w-40">Closed By</th>
                        <td>{{ purchaseRequisition?.closedBy.name }}</td>
                    </tr>
                    <tr v-if="purchaseRequisition.status === 'Closed'">
                        <th class="w-40">Closing Remarks </th>
                        <td>{{ purchaseRequisition?.closing_remarks }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="purchaseRequisition.scmPrLines?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="12">Material Information</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th>SL</th>
                <th class="w-36 text-center"><nobr>Material Name</nobr></th>
                <th class="!text-center"><nobr>Unit</nobr></th>
                <th><nobr>Model</nobr></th>
                <th><nobr>Brand</nobr></th>
                <th><nobr>Country Name</nobr></th>
                <th><nobr>Drawing No</nobr></th>
                <th><nobr>Part No</nobr></th>
                <th><nobr>Specification</nobr></th>
                <th><nobr>Required Date</nobr></th>
                <th><nobr>Quantity</nobr></th>
                <th><nobr>Status</nobr></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(certificate, index) in purchaseRequisition.scmPrLines">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  <span v-if="purchaseRequisition.scmPrLines[index]?.scmMaterial?.name">{{ purchaseRequisition.scmPrLines[index]?.scmMaterial?.name }}</span>
                </td>
                <td>
                  <span v-if="purchaseRequisition.scmPrLines[index]?.unit">{{ purchaseRequisition.scmPrLines[index]?.unit }}</span>
                </td>
                <td>
                  <span v-if="purchaseRequisition.scmPrLines[index]?.brand">{{ purchaseRequisition.scmPrLines[index]?.brand }}</span>
                </td>
                <td>
                  <span v-if="purchaseRequisition.scmPrLines[index]?.model">{{ purchaseRequisition.scmPrLines[index]?.model }}</span>
                </td>
                <td>
                  <span v-if="purchaseRequisition.scmPrLines[index]?.country_name">{{ purchaseRequisition.scmPrLines[index]?.country_name }}</span>
                </td>
                <td>
                  <span v-if="purchaseRequisition.scmPrLines[index]?.drawing_no">{{ purchaseRequisition.scmPrLines[index]?.drawing_no }}</span>
                </td>
                <td>
                  <span v-if="purchaseRequisition.scmPrLines[index]?.part_no">{{ purchaseRequisition.scmPrLines[index]?.part_no }}</span>
                </td>
                <td>
                  <span v-if="purchaseRequisition.scmPrLines[index]?.specification">{{ purchaseRequisition.scmPrLines[index]?.specification }}</span>
                </td>
                <td>
                  <span v-if="purchaseRequisition.scmPrLines[index]?.required_date"><nobr>{{ formatDate(purchaseRequisition.scmPrLines[index]?.required_date) }}</nobr></span>
                </td>
                <td>
                <span>
                    {{ numberFormat(purchaseRequisition.scmPrLines[index].quantity) }}
                </span>
              </td>
              <td>
                <!-- <button v-if="purchaseRequisition.scmPrLines[index].is_closed == 0" @click="showModal(purchaseRequisition.scmPrLines[index].id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700">Close</button>
                <span v-else :class="purchaseRequisition.scmPrLines[index]?.is_closed === 0 ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ purchaseRequisition.scmPrLines[index].is_closed === 0 ? 'Open' : 'Closed' }}</span> -->
                <button v-if="purchaseRequisition.scmPrLines[index].status != 'Closed'" @click="showModal(purchaseRequisition.scmPrLines[index].id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700">Close</button>
                                  
                <span v-else :class="purchaseRequisition.scmPrLines[index]?.status === 'Pending' ? 'text-yellow-700 bg-yellow-100' : (purchaseRequisition.scmPrLines[index]?.status == 'WIP' ? 'text-blue-700 bg-blue-100' : 'text-red-700 bg-red-100') " :title="purchaseRequisition.scmPrLines[index]?.status === 'Closed' ? `
                Closed At : ${formatDateTime(purchaseRequisition.scmPrLines[index]?.closed_at)}\nClosed By : ${purchaseRequisition.scmPrLines[index]?.closed_by}
                ` : ''" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ purchaseRequisition.scmPrLines[index]?.status ?? 'Closed' }}</span>
              </td>
              </tr>
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
          <button type="button" @click="closePurchaseRequisition" style="color: #1b1e21"
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
<script setup>
import { onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHelper from "../../../composables/useHelper";
import useHeroIcon from "../../../assets/heroIcon";
import useStoreRequisition from "../../../composables/supply-chain/useStoreRequisition";
import usePurchaseRequisition from "../../../composables/supply-chain/usePurchaseRequisition";
import StoreRequisitionShow from "../../../components/supply-chain/store-requisitions/StoreRequisitionShow.vue";
import { formatDate, formatDateTime } from '../../../utils/helper';
import RemarksComponent from '../../../components/utils/RemarksComponent.vue';
import env from "../../../config/env";
const icons = useHeroIcon();

const route = useRoute();
const purchaseRequisitionId = route.params.purchaseRequisitionId;

const { getPurchaseRequisition, showPurchaseRequisition, purchaseRequisition, updatePurchaseRequisition,materialObject, errors, isLoading,closePrLines} = usePurchaseRequisition();
const { numberFormat } = useHelper();

const { setTitle } = Title();
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

function closePurchaseRequisition() {
          closePrLines(purchaseRequisitionId,currentId.value,closingRemarks.value).then(() => {
            closeModel()
          }).catch((error) => {
            console.error("Error closing PR:", error);
          });
}

setTitle('Purchase Requistion Details');

const DEPARTMENTS = ['', 'Store Department', 'Engine Department', 'Provision Department'];

// watch(bunkerRequisition, (value) => {
//    bunkerRequisition.value.opsVoyage = value?.opsVoyage;
// });

onMounted(() => {
  showPurchaseRequisition(purchaseRequisitionId);
});


</script>