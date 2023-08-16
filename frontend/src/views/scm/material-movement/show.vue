<script setup>
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import env from '../../../config/env';
import Title from "../../../services/title";
import useMaterialMovement from "../../../composables/scm/useMaterialMovement.js";
import useHelper from "../../../composables/useHelper.js";
import MaterialMovementForm from "../../../components/scm/material-movement/MaterialMovementForm.vue";
import { useRoute } from 'vue-router';
import moment from 'moment';

const { materialMovement, showMaterialMovement  } = useMaterialMovement();
const { getAllUnit, numberFormat } = useHelper();
const { setTitle } = Title();
const route = useRoute();
const materialMovementId = route.params.materialMovementId;
const movementType = route.params.movementType;
setTitle('Material Movement Details');

onMounted(() => {
    showMaterialMovement(materialMovementId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Material Movement Details</h2>
        <router-link :to="{ name: 'scm.material-movements.index', params: { movementType: movementType } }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
  <div class="text-center bg-gray-100 p-2 mb-4 rounded-md shadow-md" v-if="materialMovement != ''">
    {{ "Movement From "+materialMovement?.movement_requisition?.from_warehouse?.name+" to "+materialMovement?.movement_requisition?.to_warehouse?.name }}
  </div>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Movement Requisition No.</span>
      <span class="bg-gray-200 py-2 h-9 flex items-center border border-gray-400 px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        {{ materialMovement?.movement_requisition?.movement_requisition_no }}
      </span>
    </label>
    <label class="block w-full mt-3 text-sm md:w-1/6">
      <span class="text-gray-700 dark:text-gray-300">Movement Date</span>
      <span class="bg-gray-200 py-2 h-9 flex items-center border border-gray-400 px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        {{ moment(materialMovement.movement_date).format("DD/MM/YYYY") }}
      </span>
    </label>

    <label class="block w-full mt-3 text-sm md:w-3/6">
      <span class="text-gray-700 dark:text-gray-300">Remarks</span>
      <span class="bg-gray-200 py-2 h-9 flex items-center border border-gray-400 px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        {{ materialMovement?.remarks }}
      </span>
    </label>
  </div>

  <!-- CS Materials -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Materials <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-bottom">Material</th>
        <th class="px-4 py-3 align-bottom">Size</th>
        <th class="px-4 py-3 align-bottom">Unit</th>
        <th class="px-4 py-3 align-bottom">Quantity</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(material, index) in materialMovement.stockable" :key="index">
        <td class="w-1/6 p-2">
          <span class="bg-gray-200 py-2 h-9 flex items-center border border-gray-400 px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            {{ material?.material?.name }}
          </span>
        </td>
        <td class="p-2">
          <span class="bg-gray-200 py-2 h-9 !text-center flex items-center border border-gray-400 px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            {{ material?.material?.size }}
          </span>
        </td>
        <td class="p-2">
          <span class="bg-gray-200 py-2 h-9 !text-center flex items-center border border-gray-400 px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            {{ material?.material?.unit }}
          </span>
        </td>
        <td class="p-2">
          <span class="bg-gray-200 py-2 h-9 !text-right flex items-center border border-gray-400 px-3 w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            {{ numberFormat(Math.abs(material?.quantity) ?? 0) }}
          </span>
        </td>
      
      </tr>
      </tbody>
    </table>
  </fieldset>

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
        @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
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

    table tr,td,th {
        @apply border border-gray-300
    }

    .readonly-select .vs__open-indicator {
      display: none;
    }
    
    .readonly-select .vs__dropdown-toggle {
      pointer-events: none;
    }
    

</style>
    