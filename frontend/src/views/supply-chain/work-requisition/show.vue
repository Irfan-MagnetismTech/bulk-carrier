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

const icons = useHeroIcon();

const { getWorkRequisitions, showWorkRequisition, workRequisition, updateWorkRequisition,workObject, errors, isLoading } = useWorkRequisition();
const page = ref('create');
const { setTitle } = Title();
const route = useRoute();
const workRequisitionId = route.params.workRequisitionId;

setTitle('Work Requisition Details');
onMounted(() => {
    showWorkRequisition(workRequisitionId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Work Requisition Details</h2>
        <default-button :title="'PR List'" :to="{ name: 'scm.work-requisitions.index' }" :icon="icons.DataBase"></default-button>
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
                <td colspan="2">
                    <table class="w-full whitespace-no-wrap" id="table">
                        <thead>
                            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-200 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                                <th class="text-center w-3/12" >Service Name</th>
                                <th class="text-center w-3/12" >Description</th>
                                <th class="text-center w-3/12" >Remarks</th>
                                <th class="text-center w-1/12" >Quantity</th>
                                <th class="text-center w-2/12" >Required Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(scmWrLine, index) in workRequisition.scmWrLines" :key="index">
                                <td>{{ scmWrLine?.scmService?.name }}</td>
                                <td>{{ scmWrLine?.scmService?.description }}</td>
                                <td>{{ scmWrLine?.remarks }}</td>
                                <td class="text-right">{{ scmWrLine?.quantity }}</td>
                                <td class="text-center">{{ formatDate(scmWrLine?.required_date) }}</td>
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
