<script setup>
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import env from '../../../config/env';
import Title from "../../../services/title";
import useMaterialRequisition from "../../../composables/scm/useMaterialRequisition.js";
import useHelper from "../../../composables/useHelper.js";
import MaterialRequisitionForm from "../../../components/scm/material-requisition/MaterialRequisitionForm.vue";
import { useRoute } from 'vue-router';
import moment from 'moment';

const { materialRequisition, showMaterialRequisition  } = useMaterialRequisition();
const { getAllUnit, numberFormat } = useHelper();
const { setTitle } = Title();
const route = useRoute();
const materialRequisitionId = route.params.materialRequisitionId;
setTitle('Material Requisition Details');

onMounted(() => {
    showMaterialRequisition(materialRequisitionId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 ">Material Requisition</h2>
        <router-link :to="{ name: 'scm.material-requisitions.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61]  border border-transparent rounded-lg active:bg-[#0F6B61]  hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
  <div class="w-full overflow-hidden">
    <div class="w-full overflow-x-auto">
      <table class="w-2/6 whitespace-no-wrap mb-4">
        <tbody class="bg-white ">
        <tr>
          <td class="px-2 w-2">Date</td>
          <td class="px-2">{{ materialRequisition.date ? moment(materialRequisition.date).format('DD-MM-YYYY') : null }}</td>
        </tr>
        <tr>
          <td class="px-2">Note</td>
          <td class="px-2">{{ materialRequisition?.note }}</td>
        </tr>
        <tr>
          <td class="px-2">Remarks</td>
          <td class="px-2">{{ materialRequisition?.remarks }}</td>
        </tr>
        <tr>
          <td class="px-2">Attachment</td>
          <td class="px-2">
            <a style="color: #4e4ed2" target="_blank" :href="env.BASE_API_URL+'storage/'+materialRequisition?.attachment">{{ materialRequisition?.attachment?.split('/').pop() }}</a>
          </td>
        </tr>
        </tbody>
      </table>
      <table class="w-full whitespace-no-wrap">
        <thead>
          <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b  bg-gray-50  ">
            <th class="px-2">Material</th>
            <th class="px-2">Material Category</th>
            <th class="px-2">Size</th>
            <th class="px-2">Unit</th>
            <th class="px-2">Quantity</th>
            <th class="px-2">Unit Price</th>
            <th class="px-2">Amount</th>
          </tr>
        </thead>
        <tbody class="bg-white ">
          <tr v-for="(stockData,index) in materialRequisition?.stockable" :key="index">
            <td class="px-2">{{ stockData?.material?.name }}</td>
            <td class="px-2">{{ stockData?.material_category?.name }}</td>
            <td class="px-2">{{ stockData?.size }}</td>
            <td class="px-2">{{ stockData?.material?.unit }}</td>
            <td class="px-2">{{ stockData?.quantity }}</td>
            <td class="px-2">{{ stockData?.unit_price }}</td>
            <td class="px-2">{{ stockData?.amount.toLocaleString('en-US', { maximumFractionDigits: 2 }) }}</td>
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
        @apply text-gray-700  text-sm;
    }
    .label-item-input {
        @apply bg-gray-100 block w-full mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed ;
    }
    .form-input {
        @apply block mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple ;
    }
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

    span.label-item-input {
        @apply bg-gray-100 py-2 h-9 flex items-center border border-black px-3 w-full mt-1 text-xs rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple 
    }

    table th,tr,td{
      border: 1px solid grey;
    }

</style>