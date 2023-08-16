<script setup>
import { ref, watch } from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../../services/title";
import useMaterial from "../../../../composables/scm/useMaterial";
import useTableExportExcel from "../../../../services/tableExportExcel";
import useRevenueReport from "../../../../composables/revenue/useRevenueReport";
import moment from 'moment';
import useHelper from "../../../../composables/useHelper";

const { setTitle } = Title();
const { materials, searchMaterial } = useMaterial();
const { tableToExcel } = useTableExportExcel();
const { salesData, getSaleReport } = useRevenueReport();
const { numberFormat } = useHelper();

const shifts = ["A", "B"];
setTitle('Sales Reports');

const formParams = ref({
    shift: "",
    material: "",
    material_id: "",
    duration: "",
    from_date: "",
    till_date: ""
});

const fetchMaterial = (query, loading) => { 
    searchMaterial(query, loading);
    loading(true)
 };

watch(() =>formParams.value.material, (value) => {
    console.log("watching")
    formParams.value.material_id = value?.id
}, { deep: true })


const fetchSaleReport = (form) => {
  let materialInfo = form.material;
    if(form.material) {
      delete form.material
    }
    getSaleReport(form)
    form.material= materialInfo
}
</script>


<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Sales Report</h2>
    </div>
    <div class="px-4 py-3 mb-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
      <form class="flex items-end justify-between gap-1" @submit.prevent="fetchSaleReport(formParams)">

        <label class="block w-full text-sm">
          <span class="text-gray-700 dark:text-gray-300">Material</span>
          <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterial"  v-model="formParams.material" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
          <input type="hidden" v-model="formParams.material_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />

        </label>

        <label class="block w-full text-sm">
          <span class="text-gray-700 dark:text-gray-300">Shift</span>
          <select v-model="formParams.shift" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled selected>--Choose an Option--</option>
            <option v-for="(shift, index) in shifts" :key="index" :value="shift">{{ shift }}</option>
          </select>
        </label>

        <label class="block w-full text-sm">
          <span class="text-gray-700 dark:text-gray-300">Duration<span class="text-red-500">*</span></span>
          <select v-model="formParams.duration" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled>--Choose an Option--</option>
            <option value="today" selected>Today</option>
            <option value="7">7 days</option>
            <option value="15">15 days</option>
            <option value="30">30 days</option>
            <option value="custom">Custom</option>
          </select>
        </label>

        <template v-if="formParams.duration === 'custom'">
          <label class="block w-full text-sm">
            <span class="text-gray-700 dark:text-gray-300">From Date<span class="text-red-500">*</span></span>
            <input type="date" v-model="formParams.from_date" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
          <label class="block w-full text-sm">
            <span class="text-gray-700 dark:text-gray-300">Till Date<span class="text-red-500">*</span></span>
            <input type="date" v-model="formParams.till_date" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          </label>
        </template>

        <!-- Submit button -->
        <button type="submit" :disabled="isLoading"
          class="flex items-center justify-center px-4 py-2 mt-2 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">Search</button>

        <button v-if="salesData?.dateWiseTotals" type="button" @click="tableToExcel('sale-report-table','Sale Report')" class="w-full flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
    <span>Download Excel</span>
  </button>
      </form>

      <table v-if="salesData?.dateWiseTotals" class="my-5 w-full" id="sale-report-table">
        <thead>
          <tr>
            <td class="!border-0"></td>
            <template v-if="formParams.shift==''">
              <td colspan="3" class="!text-center">
                <span class="!text-center my-3 font-semibold">Shift - A</span>
              </td>
              <td colspan="3" class="!text-center">
                <span class="!text-center my-3 font-semibold">Shift - B</span>
              </td>
            </template>
            <template v-else>
              <td colspan="3" class="!text-center">
                <span class="!text-center my-3 font-semibold">Shift - {{ formParams.shift }}</span>
              </td>
            </template>
          </tr>
          <tr>
            <th>Date</th>
            <template v-if="formParams.shift==''">
              <th>Cash</th>
              <th>Credit</th>
              <th>Service</th>
              <th>Cash</th>
              <th>Credit</th>
              <th>Service</th>
            </template>
            <template v-else>
              <th>Cash</th>
              <th>Credit</th>
              <th>Service</th>
            </template>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="(sale, key, index) in salesData.dateWiseTotals" :key="index" >
            <tr>
              <td class="!text-left">{{ moment(key).format('DD/MM/YYYY') }}</td>
              <template v-if="formParams.shift==''">
                <template v-for="(shift, index) in shifts" :key="index">
                  <td>{{ (sale[shift]?.cash?.material) ? numberFormat(sale[shift]?.cash?.material) : '' }}</td>
                  <td>{{ (sale[shift]?.credit?.material) ? numberFormat(sale[shift]?.credit?.material) : '' }}</td>
                  <td>{{ (sale[shift]?.cash?.service) ? numberFormat(sale[shift]?.cash?.service) : ''}}</td>
                </template>
              </template>
              <template v-else>
                  <td>{{ (sale[formParams.shift]?.cash?.material) ? numberFormat(sale[formParams.shift]?.cash?.material) : '' }}</td>
                  <td>{{ (sale[formParams.shift]?.credit?.material) ? numberFormat(sale[formParams.shift]?.credit?.material) : '' }}</td>
                  <td>{{ (sale[formParams.shift]?.cash?.service) ? numberFormat(sale[formParams.shift]?.cash?.service) : ''}}</td>
              </template>

              <td>
                {{
                  numberFormat(
                    shifts.reduce((total, shift) => {
                      const shiftSale = sale[shift];
                      const cashTotal = shiftSale?.cash?.material || 0;
                      const creditTotal = shiftSale?.credit?.material || 0;
                      const serviceTotal = shiftSale?.cash?.service || 0;
                      return total + cashTotal + creditTotal + serviceTotal;
                    }, 0)
                  )
                }}
              </td>
            </tr>
          </template>
        </tbody>
        <tr  v-if="salesData.totalSales">
          <td class="!text-center font-semibold">Grand Total</td>
            <template v-if="formParams.shift==''">
              <template v-for="(shift, index) in shifts" :key="index">
                  <td>{{ (salesData.totalSales[shift]?.cash?.material) ? numberFormat(salesData.totalSales[shift]?.cash?.material) : '' }}</td>
                  <td>{{ (salesData.totalSales[shift]?.credit?.material) ? numberFormat(salesData.totalSales[shift]?.credit?.material) : '' }}</td>
                  <td>{{ (salesData.totalSales[shift]?.cash?.service) ? numberFormat(salesData.totalSales[shift]?.cash?.service) : ''}}</td>
              </template>
            </template>
            <template v-else>
                  <td>{{ (salesData.totalSales[formParams.shift]?.cash?.material) ? numberFormat(salesData.totalSales[formParams.shift]?.cash?.material) : '' }}</td>
                  <td>{{ (salesData.totalSales[formParams.shift]?.credit?.material) ? numberFormat(salesData.totalSales[formParams.shift]?.credit?.material) : '' }}</td>
                  <td>{{ (salesData.totalSales[formParams.shift]?.cash?.service) ? numberFormat(salesData.totalSales[formParams.shift]?.cash?.service) : ''}}</td>
            </template>
            <td>
              <strong>{{ numberFormat(salesData?.grandTotal) }}</strong>
            </td>
        </tr>
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