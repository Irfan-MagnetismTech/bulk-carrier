<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Bunker Requistion Details</h2>
    <default-button :title="'Purchase Requistion List'" :to="{ name: 'ops.bunker-requisitions.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="bunkerRequisition?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ bunkerRequisition?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Requisition No.</th>
                        <td>{{ bunkerRequisition?.requisition_no }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Requisition Date</th>
                        <td>{{ formatDate(bunkerRequisition?.date) }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Voyage No</th>
                        <td>{{ bunkerRequisition.opsVoyage?.voyage_sequence }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Vessel Name</th>
                        <td>{{ bunkerRequisition.opsVessel?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Remarks </th>
                        <td>{{ bunkerRequisition?.remarks }}</td>
                    </tr>

                   
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="bunkerRequisition.opsBunkers?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Bunker Information</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th>SL</th>
                <th class="w-72">Bunker Name/Particulars</th>
                <th>Unit</th>
                <th>Quantity</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(certificate, index) in bunkerRequisition.opsBunkers">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  <span v-if="bunkerRequisition.opsBunkers[index]?.scmMaterial?.name">{{ bunkerRequisition.opsBunkers[index]?.scmMaterial?.name }}</span>
                </td>
                <td>
                  <span v-if="bunkerRequisition.opsBunkers[index]?.unit">{{ bunkerRequisition.opsBunkers[index]?.unit }}</span>
                </td>
                <td>
                <span>
                    {{ numberFormat(bunkerRequisition.opsBunkers[index].quantity) }}
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
import BunkerRequisitionForm from '../../../components/operations/BunkerRequisitionForm.vue';
import useBunkerRequisition from '../../../composables/operations/useBunkerRequisition';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHelper from "../../../composables/useHelper";
import useHeroIcon from "../../../assets/heroIcon";
import { formatDate } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const bunkerRequisitionId = route.params.bunkerRequisitionId;
const { bunkerRequisition, showBunkerRequisition, errors } = useBunkerRequisition();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Purchase Requistion Details');


watch(bunkerRequisition, (value) => {
   bunkerRequisition.value.opsVoyage = value?.opsVoyage;
});

onMounted(() => {
  showBunkerRequisition(bunkerRequisitionId);
});
</script>