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
const { materialLedgers, getMaterialLedgers } = useScmReport();
const { numberFormat } = useHelper();

setTitle('Material Ledger Summary');

const formParams = ref({
    duration: "",
    from_date: "",
    till_date: ""
});


const fetchMaterialLedgerReport = (form) => {
    getMaterialLedgers(form)
}
</script>


<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Material Ledger Summary</h2>
    </div>
    <div class="px-4 py-3 mb-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
      <form class="flex gap-1" @submit.prevent="fetchMaterialLedgerReport(formParams)">
        <label class="block w-64 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Duration<span class="text-red-500">*</span></span>
          <select v-model="formParams.duration" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled>--Choose an Option--</option>
            <option value="today" selected>Today</option>
            <option value="7">7 days</option>
            <option value="15">15 days</option>
            <option value="30">30 days</option>
            <option value="custom">Custom</option>
          </select>
        </label>

        <template v-if="formParams.duration === 'custom'">
          <label class="block w-64 text-sm">
            <span class="text-gray-700 dark:text-gray-300">From Date<span class="text-red-500">*</span></span>
            <input type="date" v-model="formParams.from_date" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
          <label class="block w-64 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Till Date<span class="text-red-500">*</span></span>
            <input type="date" v-model="formParams.till_date" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
        </template>

        <!-- Submit button -->
        <button type="submit" :disabled="isLoading"
          class="flex items-center justify-center px-4 py-2 mt-5 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">Search</button>

        <button v-if="materialLedgers" type="button" @click="tableToExcel('material-ledger-report-table','Material Ledger Report')" class="w-68 mt-5 flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
            <span>Download Excel</span>
        </button>
      </form>

      <table v-if="materialLedgers" class="my-5 w-full" id="material-ledger-report-table">
        <thead>
            <tr>
                <th class="!text-center">Date</th>
                <th class="!text-center">Material</th>
                <th class="!text-center">Stock In</th>
                <th class="!text-center">Stock Out</th>
            </tr>
        </thead>
        <tbody>
            <template v-for="(items, date, ledgerIndex) in materialLedgers" :key="ledgerIndex">
                <tr v-for="(item, index, key) in items" :key="key">
                  <td class="!text-center" :rowspan="Object.keys(items).length" v-if="key==0">{{ moment(date).format('DD/MM/YYYY') }}</td>
                  <td class="!text-center">{{ item.name }}</td>
                  <td class="pr-3">
                      <span v-if="item.positive_quantity>0">{{ item.positive_quantity }} {{ item.unit }}</span>
                  </td>
                  <td class="pr-3">
                      <span v-if="item.negative_quantity<0">{{ Math.abs(item.negative_quantity) }} {{ item.unit }}</span>
                  </td>
                </tr>
              </template>
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