<script setup>
import { ref, watch } from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useTableExportExcel from "../../../services/tableExportExcel";
import useScmReport from "../../../composables/scm/useScmReport";
import moment from 'moment';
import useHelper from "../../../composables/useHelper";

const { setTitle } = Title();
const { tableToExcel } = useTableExportExcel();
const { materialSummaries, getMaterialSummary } = useScmReport();
const { numberFormat } = useHelper();

setTitle('Material Summary Report');

onMounted(() => {
  getMaterialSummary()
})

</script>


<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Material Summary Report</h2>
    </div>
    <div class="px-4 py-3 mb-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
      <button v-if="materialSummaries" type="button" @click="tableToExcel('material-summary-report-table','Material Summary Report')" class="w-68 mt-5 flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
            <span>Download Excel</span>
      </button>
      <table v-if="materialSummaries" class="my-5 w-full" id="material-summary-report-table">
        <thead>
            <tr>
                <th class="!text-center">Material</th>
                <th class="!text-center">Current Stock</th>
                <th class="!text-center">Min. Stock</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in materialSummaries" :key="item.material_id">
                <td class="!text-center">{{ item.name }}</td>
                <td class="!text-center">{{ numberFormat(item.stock) }}</td>
                <td class="!text-center">{{ numberFormat(item.min_stock) }}</td>
            </tr>
        </tbody>
      </table>

    </div>
    
</template>
<style lang="postcss" scoped>
table, tr, th, td {
  @apply border border-collapse
}
td {
  @apply p-1
}
th {
  @apply py-2
}
td {
  @apply text-right
}
</style>