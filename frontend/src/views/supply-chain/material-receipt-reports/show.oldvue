<script setup>
import {ref,onMounted, watchPostEffect} from "vue";

import Title from "../../../services/title";
import useHelper from "../../../composables/useHelper.js";
import useMaterialReceiptReport from "../../../composables/supply-chain/useMaterialReceiptReport";
import MaterialReceiptReportShow from "../../../components/supply-chain/material-receipt-reports/MaterialReceiptReportShow.vue";
import { useRoute } from 'vue-router';

const { getMaterialReceiptReports, showMaterialReceiptReport, materialReceiptReport, updateMaterialReceiptReport,materialObject, errors, isLoading } = useMaterialReceiptReport();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const materialReceiptReportId = route.params.materialReceiptReportId;
const formType = 'edit';

setTitle('Material Receipt Report Details');

onMounted(() => {
    showMaterialReceiptReport(materialReceiptReportId);
});
</script>
<template>
  <!-- Heading -->
  <div class="flex items-center justify-between w-full my-3" v-once>
      <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Material Receipt Report Details</h2>
      <default-button :title="'MRR List'" :to="{ name: 'scm.material-receipt-reports.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
          <material-receipt-report-show v-model:form="materialReceiptReport"></material-receipt-report-show>
  </div>
</template>









<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm;
    }
    .label-item-title {
        @apply text-gray-700 dark-disabled:text-gray-300 text-sm;
    }
    .label-item-input {
        @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
    }
    .form-fieldset {
      @apply px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400;
    }
    .form-legend {
      @apply px-2 text-gray-700 dark-disabled:text-gray-300;
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
    .table_tr {
      @apply text-gray-700 dark-disabled:text-gray-400;
    }
    .table_head_tr {
      @apply text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800;
    }
    .table_body {
      @apply bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800;
    }
    .remove_button {
      @apply px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple;
    }
    .add_button {
      @apply px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple;
    }
</style>
 