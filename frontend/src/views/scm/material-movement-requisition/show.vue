<script setup>
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import env from '../../../config/env';
import Title from "../../../services/title";
import useMaterialMovementRequisition from "../../../composables/scm/useMaterialMovementRequisition.js";
import useHelper from "../../../composables/useHelper.js";
import MaterialMovementRequisitionForm from "../../../components/scm/material-movement-requisition/MaterialMovementRequisitionForm.vue";
import { useRoute } from 'vue-router';
import moment from 'moment';

const { materialMovementRequisition, showMaterialMovementRequisition  } = useMaterialMovementRequisition();
const { getAllUnit, numberFormat } = useHelper();
const { setTitle } = Title();
const route = useRoute();
const materialMovementRequisitionId = route.params.materialMovementRequisitionId;
setTitle('Material Movement Requisition Details');

onMounted(() => {
    showMaterialMovementRequisition(materialMovementRequisitionId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Material Movement Requisition</h2>
        <router-link :to="{ name: 'scm.material-movement-requisitions.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61]  border border-transparent rounded-lg active:bg-[#0F6B61]  hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
  <div class="w-full overflow-hidden">
    <div class="w-full overflow-x-auto">
      <table class="w-3/6 whitespace-no-wrap mb-4">
        <tbody class="bg-white dark:divide-gray-700 dark:bg-gray-800">
        <tr>
          <td class="px-2 w-2">Date</td>
          <td class="px-2">{{ materialMovementRequisition.requisition_date ? moment(materialMovementRequisition.requisition_date).format('DD-MM-YYYY') : null }}</td>
        </tr>
        <tr>
          <td class="px-2 w-56">Requested Warehouse</td>
          <td class="px-2">{{ materialMovementRequisition?.from_warehouse?.name }}</td>
        </tr>
        <tr>
          <td class="px-2">Remarks</td>
          <td class="px-2">{{ materialMovementRequisition?.remarks }}</td>
        </tr>
        </tbody>
      </table>
      <table class="w-full whitespace-no-wrap">
        <thead>
          <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-2 !text-center">Material</th>
            <th class="px-2 !text-center">Size</th>
            <th class="px-2 !text-center">Unit</th>
            <th class="px-2 !text-center">Quantity</th>
          </tr>
        </thead>
        <tbody class="bg-white dark:divide-gray-700 dark:bg-gray-800">
          <tr v-for="(stockData,index) in materialMovementRequisition?.stockable" :key="index">
            <td class="px-2 !text-center text-xs">{{ stockData?.material?.name }}</td>
            <td class="px-2 !text-center text-xs">{{ stockData?.size }}</td>
            <td class="px-2 !text-center text-xs">{{ stockData?.material?.unit }}</td>
            <td class="px-2 !text-right text-sm">{{ stockData?.quantity }}</td>
          </tr>
        </tbody>
      </table>
    </div>
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

    table th,tr,td{
      border: 1px solid grey;
    }

</style>