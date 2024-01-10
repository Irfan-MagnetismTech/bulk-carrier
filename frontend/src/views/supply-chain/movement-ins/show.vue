<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Movement In Details</h2>
    <default-button :title="'Movement In List'" :to="{ name: 'scm.store-issues.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="movementIn?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ movementIn?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Movement In No.</th>
                        <td>{{ movementIn?.ref_no }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">From Warehouse</th>
                        <td>{{ movementIn?.fromWarehouse?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">To Warehouse</th>
                        <td>{{ movementIn.toWarehouse.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">In Date.</th>
                        <td>{{ formatDate(movementIn?.date) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="movementIn.scmMiLines?.length">
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
              <tr v-for="(certificate, index) in movementIn.scmMiLines">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  <span v-if="movementIn.scmMiLines[index]?.scmMaterial?.name">{{ movementIn.scmMiLines[index]?.scmMaterial?.name }}</span>
                </td>
                <td>
                  <span v-if="movementIn.scmMiLines[index]?.unit">{{ movementIn.scmMiLines[index]?.unit }}</span>
                </td>
                <td>
                <span>
                    {{ numberFormat(movementIn.scmMiLines[index].quantity) }}
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
import useMovementIn from "../../../composables/supply-chain/useMovementIn";
import { formatDate } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const movementInId = route.params.movementInId;

const { getMovementIn, showMovementIn, movementIn, updateMovementIn,materialObject, errors, isLoading } = useMovementIn();
const { numberFormat } = useHelper();

const { setTitle } = Title();


const DEPARTMENTS = ['', 'Store Department', 'Engine Department', 'Provision Department'];

setTitle('Show Movement In');

onMounted(() => {
    showMovementIn(movementInId);
});

</script>