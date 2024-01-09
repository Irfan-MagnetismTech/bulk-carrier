<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Store Issue Return Details</h2>
    <default-button :title="'Store Issue Return List'" :to="{ name: 'scm.store-issues.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="storeIssueReturn?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ storeIssueReturn?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Store Issue Return No.</th>
                        <td>{{ storeIssueReturn?.ref_no }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Warehouse</th>
                        <td>{{ storeIssueReturn.scmWarehouse?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Department</th>
                        <td>{{ DEPARTMENTS[storeIssueReturn.department_id] }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Return Date</th>
                        <td>{{ storeIssueReturn?.date }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Remarks </th>
                        <td>{{ storeIssueReturn?.remarks }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="storeIssueReturn.scmSirLines?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Material Information</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th>SL</th>
                <th class="w-72">Material Name</th>
                <th>Unit</th>
                <th>Quantity</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(certificate, index) in storeIssueReturn.scmSirLines">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  <span v-if="storeIssueReturn.scmSirLines[index]?.scmMaterial?.name">{{ storeIssueReturn.scmSirLines[index]?.scmMaterial?.name }}</span>
                </td>
                <td>
                  <span v-if="storeIssueReturn.scmSirLines[index]?.unit">{{ storeIssueReturn.scmSirLines[index]?.unit }}</span>
                </td>
                <td>
                <span>
                    {{ numberFormat(storeIssueReturn.scmSirLines[index].quantity) }}
                </span>
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
import { onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHelper from "../../../composables/useHelper";
import useHeroIcon from "../../../assets/heroIcon";

import useStoreIssueReturn from "../../../composables/supply-chain/useStoreIssueReturn";

const icons = useHeroIcon();

const route = useRoute();
const storeIssueReturnId = route.params.storeIssueReturnId;

const { getStoreIssueReturn, showStoreIssueReturn, storeIssueReturn, updateStoreIssueReturn, materialObject, errors, isLoading } = useStoreIssueReturn();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Store Issue Return Details');


const DEPARTMENTS = ['', 'Store Department', 'Engine Department', 'Provision Department'];

// watch(bunkerRequisition, (value) => {
//    bunkerRequisition.value.opsVoyage = value?.opsVoyage;
// });


onMounted(() => {
    showStoreIssueReturn(storeIssueReturnId);
});


</script>