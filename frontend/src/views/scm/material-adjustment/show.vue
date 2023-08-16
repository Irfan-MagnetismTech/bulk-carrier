<script setup>
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';

import Title from "../../../services/title";
import useMaterialAdjustment from "../../../composables/scm/useMaterialAdjustment.js";
import useHelper from "../../../composables/useHelper.js";
import { useRoute } from 'vue-router';
import moment from 'moment';

const { materialAdjustment, showMaterialAdjustment } = useMaterialAdjustment();
const { getAllUnit, numberFormat } = useHelper();
const { setTitle } = Title();
const route = useRoute();
const materialAdjustmentId = route.params.materialAdjustmentId;
const units = ref()
setTitle('Material Adjustment Details');

onMounted(() => {
    showMaterialAdjustment(materialAdjustmentId);
    getAllUnit()
    .then(allUnits => {
        units.value = allUnits
    })
    .catch(error => {
        // Handle the error
        console.error('An error occurred:', error);
    });
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Material Adjustment</h2>
        <router-link :to="{ name: 'scm.material-adjustments.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61]  border border-transparent rounded-lg active:bg-[#0F6B61]  hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Date <span class="text-red-500">*</span></span>
      <span class="vms-readonly-input block w-full form-input py-2 px-2 h-9">
        {{ moment(materialAdjustment.date).format("DD/MM/YYYY") }}
      </span>
    </label>
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Adjustment Type <span class="text-red-500">*</span></span>
      <span class="vms-readonly-input block w-full form-input py-2 px-2 h-9">
        {{ materialAdjustment.adjustment_type }}
      </span>
    </label>
    
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Remarks</span>
      <span class="vms-readonly-input block w-full form-input py-2 px-2">
        {{ (materialAdjustment.remarks) ? materialAdjustment.remarks : '&nbsp;' }}
      </span>
    </label>
  </div>

  <!-- CS Materials -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Materials <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-bottom !w-72">Warehouse</th>
        <th class="px-4 py-3 align-bottom !w-72">Material</th>
        <th class="px-4 py-3 align-bottom">Unit</th>
        <th class="px-4 py-3 align-bottom">Quantity</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(material, index) in materialAdjustment.stockable" :key="index">
        <td class="p-2">
            <span class="vms-readonly-input block w-full form-input py-2 !text-center h-8">
                {{ materialAdjustment.stockable[index]?.warehouse?.name }}
            </span>
        </td>
        <td class="p-2">
            <span class="vms-readonly-input block w-full form-input py-2 !text-center h-8">
                {{ materialAdjustment.stockable[index].material.name }}
            </span>
        </td>
        <td class="p-2">
            <span class="vms-readonly-input block w-full form-input py-2 !text-center h-8">
                {{ materialAdjustment.stockable[index].unit }}
            </span>
        </td>
        <td class="p-2">
            <span class="vms-readonly-input block w-full form-input py-2 !text-center h-8">
                {{ numberFormat(materialAdjustment.stockable[index].quantity) }}
            </span>
        </td>
      </tr>
      </tbody>
    </table>
  </fieldset>
    </div>
</template>
<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm font-semibold;
    }
    .label-item-title {
        @apply text-gray-700 dark:text-gray-300 text-sm;
    }
    .label-item-input {
        @apply bg-gray-100 block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

    span.label-item-input {
        @apply bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray
    }

</style>