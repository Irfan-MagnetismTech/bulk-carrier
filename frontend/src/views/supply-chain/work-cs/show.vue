<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Work CS Details</h2>
    <default-button :title="'CS List'" :to="{ name: 'scm.material-cs.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="workCs?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ workCs?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">CS No.</th>
                        <td>{{ workCs?.ref_no }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Warehouse</th>
                        <td>{{ workCs?.scmWarehouse?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Effective Date</th>
                        <td>{{formatDate(workCs.effective_date) }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Expire Date.</th>
                        <td>{{ formatDate(workCs?.expire_date) }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Special Instruction </th>
                        <td>{{ workCs?.special_instructions }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Priority </th>
                        <td>{{ workCs?.priority }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Required Days </th>
                        <td>{{ workCs?.required_days }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Purchase Center </th>
                        <td>{{ workCs?.purchase_center }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="workCs.scmWcsServices?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Material Information</td>
                </tr>
            </thead>
            <thead v-once>
              <!-- <tr class="w-full">
                <th>SL</th>
                <th class="w-72">Material Name</th>
                <th>Unit</th>
                <th>Quantity</th>
              </tr> -->
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                  <th style="" class="py-3 align-center">PR </th>
                  <th style="" class="py-3 align-center">Material Name </th>
                  <th class="py-3 align-center">PR Qty</th>
                  <th class="py-3 align-center">Quantity</th>
                </tr>
            </thead>
            <tbody>
              <tr v-for="(certificate, index) in workCs.scmWcsServices">
                <td>
                  <nobr>{{ workCs.scmWcsServices[index]?.scmWr?.ref_no }}</nobr>
                </td>
                <td>
                  <span v-if="workCs.scmWcsServices[index]">{{ workCs.scmWcsServices[index]?.scmService?.service_name_and_code}}</span>
                </td>
                <td>
                  <span v-if="workCs.scmWcsServices[index]">{{ workCs.scmWcsServices[index]?.wr_quantity}}</span>
                </td>
                <td>
                  <span v-if="workCs.scmWcsServices[index]">{{ workCs.scmWcsServices[index]?.quantity}}</span>
                </td>
              </tr>
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
<script setup>
import { ref,onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHelper from "../../../composables/useHelper";
import useHeroIcon from "../../../assets/heroIcon";
import useMaterialCs from "../../../composables/supply-chain/useMaterialCs";
import { formatDate } from '../../../utils/helper';
import useWorkCs from "../../../composables/supply-chain/useWorkCs";
import WorkCsForm from "../../../components/supply-chain/work-cs/WorkCsForm.vue";

const icons = useHeroIcon();

const route = useRoute();
const workCsId = route.params.workCsId;




const { getWorkCs, showWorkCs, workCs, updateWorkCs, errors, isLoading,serviceObj,serviceList, getWrWiseServiceList, wrServiceList} = useWorkCs();
const { numberFormat } = useHelper();

const { setTitle } = Title();



setTitle('Show CS');

onMounted(() => {
  showWorkCs(workCsId);
});



</script>