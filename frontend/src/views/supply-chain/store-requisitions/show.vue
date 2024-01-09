<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Store Requistion Details</h2>
    <default-button :title="'Store Requistion List'" :to="{ name: 'scm.store-requisitions.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="storeRequisition?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ storeRequisition?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Requisition No.</th>
                        <td>{{ storeRequisition?.ref_no }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Warehouse</th>
                        <td>{{ storeRequisition.scmWarehouse?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Department</th>
                        <td>{{ DEPARTMENTS[storeRequisition.department_id] }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Requisition Date</th>
                        <td>{{ storeRequisition?.date }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Remarks </th>
                        <td>{{ storeRequisition?.remarks }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="storeRequisition.scmSrLines?.length">
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
                <th>Specification</th>
                <th>Unit</th>
                <th>Quantity</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(certificate, index) in storeRequisition.scmSrLines">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  <span v-if="storeRequisition.scmSrLines[index]?.scmMaterial?.name">{{ storeRequisition.scmSrLines[index]?.scmMaterial?.name }}</span>
                </td>
                <td>
                  <span v-if="storeRequisition.scmSrLines[index]?.specification">{{ storeRequisition.scmSrLines[index]?.specification }}</span>
                </td>
                <td>
                  <span v-if="storeRequisition.scmSrLines[index]?.unit">{{ storeRequisition.scmSrLines[index]?.unit }}</span>
                </td>
                <td>
                <span>
                    {{ numberFormat(storeRequisition.scmSrLines[index].quantity) }}
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
import useStoreRequisition from "../../../composables/supply-chain/useStoreRequisition";
import StoreRequisitionShow from "../../../components/supply-chain/store-requisitions/StoreRequisitionShow.vue";

const icons = useHeroIcon();

const route = useRoute();
const storeRequisitionId = route.params.storeRequisitionId;

const { getStoreRequisition, showStoreRequisition, storeRequisition, updateStoreRequisition,materialObject, errors, isLoading } = useStoreRequisition();

const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Store Requistion Details');

const DEPARTMENTS = ['', 'Store Department', 'Engine Department', 'Provision Department'];

// watch(bunkerRequisition, (value) => {
//    bunkerRequisition.value.opsVoyage = value?.opsVoyage;
// });

onMounted(() => {
    showStoreRequisition(storeRequisitionId);
});


</script>