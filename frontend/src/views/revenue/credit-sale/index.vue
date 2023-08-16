<script setup>
import {onMounted, watchEffect} from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useCreditSale from "../../../composables/revenue/useCreditSale";
import Title from "../../../services/title";
import { ref } from "vue";
import { useFuse } from "@vueuse/integrations/useFuse";
import useHelper from '../../../composables/useHelper.js';
import Paginate from '../../../components/utils/paginate.vue';
import moment from 'moment';
import useDebouncedRef from "../../../composables/useDebouncedRef";
import {watch} from "vue";
const { creditSales, getCreditSales, deleteCreditSale, isLoading } = useCreditSale();
const { numberFormat } = useHelper();
const { setTitle } = Title();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

setTitle('Credit Sales');

// Code for global search starts here
const columns = ["date_time","total_amount"];
const searchKey = useDebouncedRef('', 600);
const table = "sale_transactions";

watch(searchKey, newQuery => {
  getCreditSales(props.page, columns, searchKey.value, table);
});
// Code for global search end here

onMounted(() => {
  watchEffect(() => {
    getCreditSales(props.page, columns, searchKey.value, table);
  });
});

function print(saleInfo) {

let saleDate = (saleInfo.date_time) ? moment(saleInfo.date_time).format('DD/MM/YYYY') : "" 
let saleTime = (saleInfo.date_time) ? moment(saleInfo.date_time).format('hh:mm:ss A') : "" 
let saleTotal = numberFormat(saleInfo.total_amount)

let materials = saleInfo.materials

let materialInfo = '';

for(let index in materials) {
  let fuel = materials[index]
  materialInfo += `
  <tr>
      <td>${ fuel.name ?? fuel.material.name }</td>
      <td>${ Math.abs(fuel.quantity) }</td>
      <td>${ numberFormat(fuel.unit_price) }</td>
      <td>${ numberFormat(fuel.amount) }</td>
  </tr>
  `
}

var newWindowContent = `
      <div id="printableArea" class="w-full">
                      <div>
                          <h2 class="text-lg font-bold text-center" style="text-align: center; margin-bottom: 5px" id="heading">QC Filling Station</h2>
                          <div class="text-center" style="text-align: center" id="subHeading">
                              <span class="m-0 block p-0 text-sm">Dealer : Meghna Petroleum Ltd.</span>
                              <span class="m-0 block p-0 text-sm">College Road, Chittagong.</span>
                              <span class="m-0 block p-0 text-sm">Phone : 02333-356710</span>
                          </div>
                          <h4 class="text-base font-bold text-center" style="text-align: center; margin: 5px auto" id="cashMemo">CASH MEMO</h4>
                          <div class="flex justify-between ml-2" style="overflow: hidden; margin: 10px auto;">
                              <div style="width: 50%; float: left" class="ml-2">${saleDate}</div>
                              <div style="width: 50%; float: left; text-align: right" class="pr-4">${saleTime}</div>
                          </div>
                      </div>
                      <div v-if="previewPanel.materials.length > 0">
                          <h4 class="text-md text-center my-2 font-semibold" style="text-align: center">Fuel &amp; Lubricants</h4>
                          <table class="w-full text-sm">
                              <thead>
                                  <tr>
                                      <td class="text-sm text-center">Item</td>
                                      <td class="w-12 text-sm text-center">Qty.</td>
                                      <td class="w-12 text-sm text-center">Unit Price</td>
                                      <td class="w-20 text-sm text-center">Amount</td>
                                  </tr>
                              </thead>
                              ${materialInfo}
                          </table>
                      </div>
                      
                      <hr>
                      <div class="my-2 ml-3 text-right pr-14" style="text-align: right" id="totalAmount">
                          <strong>Total Amount: ${saleTotal}</strong> 
                      </div>

                  </div>`
  var mediaQuery = `
                <style>
                @media print {
                          body {
                              margin: 0;
                          }
                      }
                    #heading{
                    font-size: 22px;
                    }
                    #subHeading{
                    font-size: 13px;
                    }
                    #cashMemo{
                    display: block;
                    font-size: 15px;
                    }
                    #date{
                    margin-top: 0 !important;
                    }
                    .hideInPrint {
                        display: none;
                    }
                    span {
                        display: block
                    }
                    table {
                        width: 100%;
                        margin: 0 auto;
                    }

                    table, tr, td {
                        border: 1px solid black;
                        border-collapse: collapse;
                    }
                    #totalAmount {
                        width: 100%;
                        margin: 0 auto;
                        font-size: 17px;
                    }

                    td:nth-child(1) {
                        width: 30%;

                    }

                    td:nth-child(2) {
                        width: 10%;
                        text-align: center
                    }

                    td:nth-child(3) {
                        width: 30%;
                        text-align: right;
                    }

                    td:nth-child(4) {
                        width: 30%;
                        text-align: right;
                        font-weight: bold !important;

                    }

                    thead tr td {
                        font-weight: bold;
                        text-align: center !important;
                    }

                    h4, #date {
                        margin: 2px;
                        padding: 0;
                    }

                </style>
                `;
newWindowContent = mediaQuery + newWindowContent;

// <div v-if="previewPanel.services.length > 0">
//                           <h4 class="text-md text-center my-2 font-semibold" style="text-align: center">Services</h4>
//                           <table class="w-full text-sm">
//                               <thead>
//                                   <tr>
//                                       <td class="text-sm text-center">Item</td>
//                                       <td class="w-12 text-sm text-center">Qty.</td>
//                                       <td class="w-12 text-sm text-center">Unit Price</td>
//                                       <td class="w-20 text-sm text-center">Amount</td>
//                                   </tr>
//                               </thead>
//                               <tr v-for="(service,index) in previewPanel.services" :key="index">
//                                   <td>{{ service.name ?? service.service.name }}</td>
//                                   <td>{{ service.quantity }}</td>
//                                   <td>{{ numberFormat(service.unit_price) }}</td>
//                                   <td>{{ numberFormat(service.amount) }}</td>
                                  
//                               </tr>
//                           </table>
//                       </div>
  
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
  <div class="flex flex-col items-center justify-between w-full my-2 sm:flex-row" v-once>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Credit Sales</h2>
    <router-link :to="{ name: 'revenue.credit-sale.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
    </router-link>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <span class="w-full text-xs font-medium text-gray-500 whitespace-no-wrap">Showing {{ creditSales?.from }}-{{ creditSales?.to }} of {{ creditSales?.total }}</span>
    <!-- Search -->
    <div class="relative w-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
      </svg>
      <input type="text" v-model="searchKey" placeholder="Search..." class="search" />
    </div>
  </div>
  <!-- Table -->
  <div class="w-full overflow-hidden">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">SL. </th>
          <th class="px-4 py-3">Date</th>
          <th class="px-4 py-3">Slip No</th>
          <th class="px-4 py-3">Shift</th>
          <th class="px-4 py-3">Sold By</th>
          <th class="px-4 py-3">Customer Name</th>
          <th class="px-4 py-3">Materials</th>
          <th class="px-4 py-3">Total Amount</th>
          <th class="px-4 py-3 text-center">Actions</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(creditSale,index) in (creditSales?.data ? creditSales?.data : creditSales)" :key="creditSale.id">
          <td class="px-4 py-3 text-sm">{{ index + 1 }}</td>
          <td class="px-4 py-3 text-sm">{{ (creditSale.date_time) ? moment(creditSale.date_time).format('DD/MM/YYYY hh:mm A') : '-' }}</td>
          <td class="px-4 py-3 text-sm">{{ creditSale?.sale?.slip_no }}</td>
          <td class="px-4 py-3 text-sm">{{ creditSale?.shift }}</td>
          <td class="px-4 py-3 text-sm">{{ creditSale?.entry_by?.name }}</td>
          <td class="px-4 py-3 text-sm">{{ creditSale?.sale?.customer?.name }}</td>
          <td class="px-4 py-3 text-sm">

            <template v-for="(stockable,index) in creditSale?.materials" :key="index">
              <span class="p-1 bg-gray-100 rounded-md m-1 inline-block" v-if="stockable.material != null">
                {{ stockable?.material?.name }}
              </span>
            </template>

          </td>
          <td class="px-4 py-3 text-sm">{{ numberFormat(creditSale?.total_amount) }}</td>
          <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
            <action-button :action="'edit'" :to="{ name: 'revenue.credit-sale.edit', params: { creditSaleId: creditSale.id } }"></action-button>
            <action-button :action="'show'" :to="{ name: 'revenue.credit-sale.show', params: { creditSaleId: creditSale.id } }"></action-button>
            <action-button @click="print(creditSale)" :action="'print'"></action-button>
            <action-button @click="deleteCreditSale(creditSale.id)" :action="'delete'"></action-button>
          </td>
        </tr>
        </tbody>
        <tfoot v-if="!(creditSales?.data ? creditSales?.data.length : creditSales?.length)" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="10">Loading...</td>
        </tr>
        <tr v-else-if="!(creditSales?.data ? creditSales?.data.length : creditSales?.length)">
          <td colspan="10">No Credit Sale found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="creditSales" to="revenue.credit-sale.index" :page="page"></Paginate>
  </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
  .tab {
    @apply p-2.5 text-xs;
  }
  thead tr {
    @apply font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  th {
    @apply tab text-center;
  }
  tbody {
    @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
  }
  tbody tr {
    @apply text-gray-700 dark:text-gray-400;
  }
  tbody tr td {
    @apply tab text-center;
  }
  tfoot td {
    @apply tab text-center;
  }
}

.input-group {
  @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
}
.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}
table, th,td{
  @apply border border-collapse;
}
.search-result {
  @apply px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-300;
}
.search {
  @apply float-right  pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
}
>>> {
  --vs-controls-color: #374151;
  --vs-border-color: #4b5563;

  --vs-dropdown-bg: #282c34;
  --vs-dropdown-color: #eeeeee;
  --vs-dropdown-option-color: #eeeeee;

  --vs-selected-bg: #664cc3;
  --vs-selected-color: #374151;

  --vs-search-input-color: #4b5563;

  --vs-dropdown-option--active-bg: #664cc3;
  --vs-dropdown-option--active-color: #eeeeee;
}
</style>