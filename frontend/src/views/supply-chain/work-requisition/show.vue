<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useWorkRequisition from "../../../composables/supply-chain/useWorkRequisition";
import useHelper from "../../../composables/useHelper.js";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import { useRoute } from 'vue-router';
import { formatDate } from "../../../config/setting";
import env from "../../../config/env";
import RemarksComponent from "../../../components/utils/RemarksComponent.vue";
import { formatDateTime } from "../../../utils/helper";

const icons = useHeroIcon();

const { getWorkRequisitions, showWorkRequisition, workRequisition, updateWorkRequisition, closeWrLines,workObject, errors, isLoading } = useWorkRequisition();
const page = ref('create');
const { setTitle } = Title();
const { numberFormat } = useHelper();
const route = useRoute();
const workRequisitionId = route.params.workRequisitionId;


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

function closeWorkRequisition() {
          closeWrLines(workRequisitionId,currentId.value,closingRemarks.value).then(() => {
            closeModel()
          }).catch((error) => {
            console.error("Error closing WR:", error);
          });
}


setTitle('Work Requisition Details');
onMounted(() => {
    showWorkRequisition(workRequisitionId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Work Requisition Details</h2>
        <default-button :title="'WR List'" :to="{ name: 'scm.work-requisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <div class="flex md:gap-4">
        <div class="w-full">
          <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Work Requisition Information</h2>
          <table class="w-full">
            <tbody>
              <tr>
                <th class="w-80">Business Unit</th>
                <td><span :class="workRequisition?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ workRequisition?.business_unit }}</span></td>
              </tr>
              <tr>
                <th class="w-40">Warehouse Name</th>
                <td>{{ workRequisition?.scmWarehouse?.name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Raised Date</th>
                <td>{{ formatDate(workRequisition?.raised_date) }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Approved Date</th>
                <td>{{ formatDate(workRequisition?.approved_date) }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Attachment</th>
                <td>
                    <a type="button" v-if="typeof workRequisition?.attachment === 'string'" class="text-green-800" target="_blank" :href="env.BASE_API_URL+'/'+workRequisition?.attachment">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                  </svg>
                </a>
                <a v-else>---</a>    
                </td>
              </tr>

              <tr>
                <th class="w-40">Remarks</th>
                <td>{{ workRequisition?.remarks }}</td>
              </tr>

              <tr>
                        <th class="w-40">Status </th>
                        <td>
                          <!-- {{ workRequisition?.status }} -->
                          <span :class="workRequisition?.status === 'Pending' ? 'text-yellow-700 bg-yellow-100' : (workRequisition?.status == 'WIP' ? 'text-blue-700 bg-blue-100' : 'text-red-700 bg-red-100') " class="px-2 py-1 font-semibold leading-tight rounded-full">{{ workRequisition?.status ?? 'Closed' }}</span>
                        </td>
                    </tr>
                    <tr v-if="workRequisition.status === 'Closed'">
                        <th class="w-40">Closed At</th>
                        <td>{{ formatDateTime(workRequisition?.closed_at) }}</td>
                    </tr>
                    <tr v-if="workRequisition.status === 'Closed'">
                        <th class="w-40">Closed By</th>
                        <td>{{ workRequisition?.closedBy?.name }}</td>
                    </tr>
                    <tr v-if="workRequisition.status === 'Closed'">
                        <th class="w-40">Closing Remarks </th>
                        <td>{{ workRequisition?.closing_remarks }}</td>
                    </tr>

              <tr>
                <td colspan="2">
                    <table class="w-full whitespace-no-wrap" id="table">
                        <thead>
                          <tr>
                            <th colspan="6" class="!text-center font-bold tracking-wide text-gray-500  bg-gray-200 dark-disabled:text-gray-400 dark-disabled:bg-gray-800"> Work Information </th>
                          </tr>
                            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-200 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                                <th class="text-center w-2/12" >Service Name</th>
                                <th class="text-center w-3/12" >Description</th>
                                <th class="text-center w-3/12" >Remarks</th>
                                <th class="text-center w-1/12" >Quantity</th>
                                <th class="text-center w-2/12" >Required Date</th>
                                <th class="text-center w-1/12" >Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(scmWrLine, index) in workRequisition.scmWrLines" :key="index">
                                <td>{{ scmWrLine?.scmService?.name }}</td>
                                <td>{{ scmWrLine?.scmService?.description }}</td>
                                <td>{{ scmWrLine?.remarks }}</td>
                                <td class="text-right">{{ numberFormat(scmWrLine?.quantity) }}</td>
                                <td class="text-center">{{ formatDate(scmWrLine?.required_date) }}</td>
                                <td>
                                  
                                  <button v-if="scmWrLine.status != 'Closed'" @click="showModal(scmWrLine.id)" class="px-2 py-1 font-semibold leading-tight rounded-full text-white bg-purple-600 hover:bg-purple-700">Close</button>
                                  <!-- <span v-else :class="scmWrLine?.is_closed === 0 ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ scmWrLine.is_closed === 0 ? 'Open' : 'Closed' }}</span> -->
                                  
                                  <span v-else :class="scmWrLine?.status === 'Pending' ? 'text-yellow-700 bg-yellow-100' : (scmWrLine?.status == 'WIP' ? 'text-blue-700 bg-blue-100' : 'text-red-700 bg-red-100') " :title="scmWrLine?.status === 'Closed' ? `
                                  Closed At : ${formatDateTime(scmWrLine?.closed_at)}\nClosed By : ${scmWrLine?.closedBy?.name}
                                  ` : ''" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ scmWrLine?.status ?? 'Closed' }}</span>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
              </tr>







              
              <!-- <tr>
                <th class="w-40">Survey Type</th>
                <td>{{ survey?.mntSurveyType?.survey_type_name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Short Code</th>
                <td>{{ survey?.short_code }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Survey Name</th>
                <td>{{ survey?.survey_name }}</td>
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
          <button type="button" @click="closeWorkRequisition" style="color: #1b1e21"
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
