<script setup>
import { ref, watch } from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../../services/title";
import useTableExportExcel from "../../../../services/tableExportExcel";
import useRevenueReport from "../../../../composables/revenue/useRevenueReport";
import moment from 'moment';
import useHelper from "../../../../composables/useHelper";

const { setTitle } = Title();
const { tableToExcel } = useTableExportExcel();
const { shiftSummary, getShiftSummary } = useRevenueReport();
const { numberFormat } = useHelper();

const shifts = ["A", "B"];
const saleTypes = ['cash', 'credit'];
setTitle('Shift Summary');

const formParams = ref({
    shift: "A",
    date: moment().format("YYYY-MM-DD"),
    sale_type: 'cash'
});


const fetchSaleReport = (form) => {
    if(form.material) {
      delete form.material
    }
    getShiftSummary(form)
}

function print() {
  var newWindowContent = document.getElementById('printableArea').innerHTML;
         var mediaQuery = `
                <style>
                table {
                        width: 100%; 
                        margin: 0 auto;
                        margin-top: 5px;
                    }
                    
                    table, tr, td, th {
                        border: 1px solid black;
                        border-collapse: collapse;   
                    }
                    td:nth-child(1) {
                        text-align: center
                    }

                    td:nth-child(2) {
                        text-align: left;
                    }

                    td {
                      text-align: right;
                    }

                    tfoot {
                      font-weight: bold;
                    }

                    .text-center {
                      text-align: center;
                    }

                    .font-semibold {
                      font-weight: 600;
                    } 

                    .underline {
                      text-decoration: underline;
                    }

                    .capitalize {
                      text-transform: capitalize;
                    }

                    p {
                      margin: 0;
                      padding: 1px;
                    }

                    .hidden {
                      display: none;
                    }
                </style>
                `;
         newWindowContent = mediaQuery + newWindowContent;
        

      
      var printWindow = window.open('', '_blank');
      printWindow.document.write('<html><head><title>Print</title></head><body>');
      printWindow.document.write(newWindowContent);
      printWindow.document.write('</body></html>');
      printWindow.document.close();
      printWindow.print();
      printWindow.close();
}
</script>


<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Shift Summary</h2>
    </div>
    <div class="px-4 py-3 mb-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
      <form class="flex items-end justify-between gap-1" @submit.prevent="fetchSaleReport(formParams)">

        <label class="block w-96 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Sale Type</span>
          <select v-model="formParams.sale_type" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled selected>--Choose an Option--</option>
            <option v-for="(saleType, index) in saleTypes" :key="index" :value="saleType" class="capitalize">
              <span class="capitalize">{{ saleType }}</span>
            </option>
          </select>
        </label>
        <label class="block w-full text-sm">
          <span class="text-gray-700 dark:text-gray-300">Shift</span>
          <select v-model="formParams.shift" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" disabled selected>--Choose an Option--</option>
            <option v-for="(shift, index) in shifts" :key="index" :value="shift">{{ shift }}</option>
          </select>
        </label>

        <label class="block w-full text-sm">
          <span class="text-gray-700 dark:text-gray-300">Date<span class="text-red-500">*</span></span>
          <input type="date" v-model="formParams.date" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
        </label>

        <!-- Submit button -->
        <button type="submit" :disabled="isLoading"
          class="flex items-center justify-center px-4 py-2 mt-2 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">Search</button>

        <button v-if="shiftSummary?.grandTotal > 0 || shiftSummary?.summary != null" type="button" @click="tableToExcel('shift-summary-report-table','Shift Summary')" class="w-96 flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
          <span>Download Excel</span>
        </button>
        <button v-if="shiftSummary?.grandTotal > 0 || shiftSummary?.summary != null" @click="print" type="button" class="w-36 flex items-center justify-center px-4 py-2 mt-2 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B90] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
          <span class="pr-2">Print</span>
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" w-6 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
          </svg>

        </button>
      </form>

      <div id="printableArea" class="my-8 overflow-x-scroll" style="width: calc(100vw - 80px)" v-if="shiftSummary?.grandTotal > 0 || shiftSummary?.summary != null">
        <p class="text-center font-semibold underline">
          <span class="capitalize">{{ formParams.sale_type }}</span> Report
        </p>
        <p class="text-center font-semibold underline">
          Date: {{ moment(formParams.date).format('DD/MM/YYYY') }}
        </p>
        <p class="text-center font-semibold underline">
          Shift: {{ formParams.shift }}
        </p>
        <table v-if="shiftSummary?.sales && formParams.sale_type=='cash'" class="my-5 w-full" id="shift-summary-report-table">
          <thead>
            <tr class="hidden">
              <th colspan="3">
                  <span class="capitalize">{{ formParams.sale_type.charAt(0).toUpperCase() + formParams.sale_type.slice(1) }}</span> Report <br/>
                  Date: {{ moment(formParams.date).format('DD/MM/YYYY') }} <br/>
                  Shift: {{ formParams.shift }}
              </th>
            </tr>
            <tr>
              <th>Item</th>
              <th>Qty</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(sale, key, index) in shiftSummary.sales" :key="index" >
              <tr>
                <td>{{ sale?.material?.name }}</td>
                <td>{{ numberFormat(Math.abs(sale?.sum)) }}</td>
                <td>{{ numberFormat(sale?.amount) }}</td>
              </tr>
            </template>
            <template v-for="(sale, key, index) in shiftSummary.serviceSales" :key="index" >
              <tr>
                <td>{{ sale?.service }}</td>
                <td>{{ numberFormat(Math.abs(sale?.sum)) }}</td>
                <td>{{ numberFormat(sale?.amount) }}</td>
              </tr>
            </template>
            <tr>
              <td colspan="2" style="text-align: right; padding-right: 10px;"><strong>Total</strong></td>
              <td style="text-align: right;"><strong>{{ numberFormat(shiftSummary?.grandTotal) }}</strong></td>
            </tr>
          </tbody>
        </table>

        <table v-if="shiftSummary?.summary != '' && formParams.sale_type=='credit'" class="table-auto my-5 w-full" id="shift-summary-report-table">
          <thead>
            <tr>
              <th class="w-12">
                Sl #
              </th>
              <th>Name of the Party</th>
              <th>Receipt #</th>
              <template v-for="(material, index) in shiftSummary?.materials" :key="index">
                <th>{{ material.name }}</th>
              </template>
              <th>L. Oil</th>

            </tr>
          </thead>

          <tbody>
            <template v-for="(sale, index) in shiftSummary.summary" :key="index" >
              <template v-for="(saleInfo, key, saleInfoIndex) in sale" :key="saleInfoIndex" >
                <tr>
                  <td class="!text-center">{{ index+1 }}</td>
                  <td class="!text-left">{{ saleInfo[0].customer_name }}</td>
                  <td>{{ key }}</td>
                  <template v-for="(material, index) in shiftSummary?.materials" :key="index">
                    <td>
                      {{ Math.abs(saleInfo.find((sale) => sale.material_id === material.id)?.quantity) || null }}
                    </td>
                  </template>
                  <td></td>
                </tr>
              </template>
            </template>
          </tbody>
          <tfoot>
            <tr>
                  <td class="!text-right font-semibold pr-2" colspan="3">Total</td>
                  <template v-for="(material, index) in shiftSummary?.materials" :key="index">
                    <td class="font-semibold">
                      {{ Math.abs(shiftSummary?.qty[material.id]) || null }}
                    </td>
                  </template>
                  <td></td>
                </tr>
          </tfoot>
        </table>

      </div>


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

/* width */
::-webkit-scrollbar {
  width: 5px !important;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1; 
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #888; 
  border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555; 
}
</style>