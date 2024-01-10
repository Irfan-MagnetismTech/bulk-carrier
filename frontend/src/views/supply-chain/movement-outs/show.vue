<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Movement Out Details</h2>
    <default-button :title="'Movement Out List'" :to="{ name: 'scm.movement-outs.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="movementOut?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ movementOut?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Movement Out No.</th>
                        <td>{{ movementOut?.ref_no }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">From Warehouse</th>
                        <td>{{ movementOut?.fromWarehouse?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">To Warehouse</th>
                        <td>{{ movementOut.toWarehouse.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Out Date.</th>
                        <td>{{ formatDate(movementOut?.date) }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Requsiotion Ref </th>
                        <td>{{ movementOut?.scmMmr.ref_no }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="movementOut.scmMoLines?.length">
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
              <tr v-for="(certificate, index) in movementOut.scmMoLines">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  <span v-if="movementOut.scmMoLines[index]?.scmMaterial?.name">{{ movementOut.scmMoLines[index]?.scmMaterial?.name }}</span>
                </td>
                <td>
                  <span v-if="movementOut.scmMoLines[index]?.unit">{{ movementOut.scmMoLines[index]?.unit }}</span>
                </td>
                <td>
                <span>
                    {{ numberFormat(movementOut.scmMoLines[index].quantity) }}
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
import useMovementOut from "../../../composables/supply-chain/useMovementOut";
import { formatDate } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const movementOutId = route.params.movementOutId;

const { getMovementOut, showMovementOut, movementOut, updateMovementOut,materialObject, errors, isLoading } = useMovementOut();
const { numberFormat } = useHelper();

const { setTitle } = Title();


const DEPARTMENTS = ['', 'Store Department', 'Engine Department', 'Provision Department'];

setTitle('Show Movement Out');

onMounted(() => {
    showMovementOut(movementOutId);
});

</script>