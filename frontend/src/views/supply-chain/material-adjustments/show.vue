<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Material Adjustment Details</h2>
    <default-button :title="'Material Adjustment List'" :to="{ name: 'scm.material-adjustments.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="materialAdjustment?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ materialAdjustment?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Movement In No.</th>
                        <td>{{ materialAdjustment?.ref_no }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Warehouse</th>
                        <td>{{ materialAdjustment?.scmWarehouse?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Date.</th>
                        <td>{{ formatDate(materialAdjustment?.date) }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Type</th>
                        <td>{{ materialAdjustment?.type }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="materialAdjustment.scmAdjustmentLines?.length">
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
                <th>Rate</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(certificate, index) in materialAdjustment.scmAdjustmentLines">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  <span v-if="materialAdjustment.scmAdjustmentLines[index]?.scmMaterial?.name">{{ materialAdjustment.scmAdjustmentLines[index]?.scmMaterial?.name }}</span>
                </td>
                <td>
                  <span v-if="materialAdjustment.scmAdjustmentLines[index]?.unit">{{ materialAdjustment.scmAdjustmentLines[index]?.unit }}</span>
                </td>
                <td>
                <span>
                    {{ numberFormat(materialAdjustment.scmAdjustmentLines[index].quantity) }}
                </span>
              </td>
              <td>
                <span>
                    {{ numberFormat(materialAdjustment.scmAdjustmentLines[index].rate) }}
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
import useMaterialAdjustment from "../../../composables/supply-chain/useMaterialAdjustment";
import MaterialAdjustmentForm from "../../../components/supply-chain/material-adjustments/MaterialAdjustmentForm.vue";
import { formatDate } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const materialAdjustmentId = route.params.materialAdjustmentId;


const { getMaterialAdjustment, showMaterialAdjustment, materialAdjustment, updateMaterialAdjustment,materialObject, errors, isLoading } = useMaterialAdjustment();
const { numberFormat } = useHelper();

const { setTitle } = Title();


const DEPARTMENTS = ['', 'Store Department', 'Engine Department', 'Provision Department'];

setTitle('Show Material Adjustment');

onMounted(() => {
  showMaterialAdjustment(materialAdjustmentId);
});

</script>