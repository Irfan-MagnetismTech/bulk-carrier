<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Movement Requisition Details</h2>
    <default-button :title="'Movement Requistion List'" :to="{ name: 'scm.store-issues.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="movementRequisition?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ movementRequisition?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Movement Requisition No.</th>
                        <td>{{ movementRequisition?.ref_no }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">From Warehouse</th>
                        <td>{{ movementRequisition?.fromWarehouse?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">To Warehouse</th>
                        <td>{{ movementRequisition.toWarehouse.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Requisition Date.</th>
                        <td>{{ movementRequisition?.date }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Delivery Date.</th>
                        <td>{{ movementRequisition?.delivery_date }}</td>
                    </tr>
                    <tr>
                      <th class="w-40">Requested By.</th>
                      <td>{{ movementRequisition?.requested_by }}</td>
                    </tr>
                    <tr>
                      <th class="w-40">Requested For.</th>
                      <td>{{ movementRequisition?.requested_for }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Remarks </th>
                        <td>{{ movementRequisition?.remarks }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="movementRequisition.scmMmrLines?.length">
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
              <tr v-for="(certificate, index) in movementRequisition.scmMmrLines">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  <span v-if="movementRequisition.scmMmrLines[index]?.scmMaterial?.name">{{ movementRequisition.scmMmrLines[index]?.scmMaterial?.name }}</span>
                </td>
                <td>
                  <span v-if="movementRequisition.scmMmrLines[index]?.unit">{{ movementRequisition.scmMmrLines[index]?.unit }}</span>
                </td>
                <td>
                <span>
                    {{ numberFormat(movementRequisition.scmMmrLines[index].quantity) }}
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
import useMovementRequisition from "../../../composables/supply-chain/useMovementRequisition";

const icons = useHeroIcon();

const route = useRoute();
const movementRequisitionId = route.params.movementRequisitionId;

const { getMovementRequisition, showMovementRequisition, movementRequisition, updateMovementRequisition,materialObject, errors, isLoading } = useMovementRequisition();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Movement Requisition Details');


const DEPARTMENTS = ['', 'Store Department', 'Engine Department', 'Provision Department'];

// watch(bunkerRequisition, (value) => {
//    bunkerRequisition.value.opsVoyage = value?.opsVoyage;
// });



onMounted(() => {
    showMovementRequisition(movementRequisitionId);
});

</script>