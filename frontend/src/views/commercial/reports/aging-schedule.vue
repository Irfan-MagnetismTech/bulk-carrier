<script setup>
import { onMounted, watch, watchEffect, ref } from 'vue';
import { useRoute } from 'vue-router';
import moment from 'moment';

import useCustomer from "../../../composables/commercial/useCustomer";
import useAgingScheduleReport from "../../../composables/commercial/useAgingScheduleReport";
import Title from "../../../services/title";
import useInvoice from "../../../composables/commercial/useInvoice";
import useVoyage from "../../../composables/operation/useVoyage";
import useService from "../../../composables/commercial/useService";
import useTableExportExcel from "../../../services/tableExportExcel";
const { tableToExcel } = useTableExportExcel();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const today = new Date().toLocaleDateString('de-DE');

const route = useRoute();

const { customers, getCustomerWithoutPaginate, customerCodeName, getCustomerByNameOrCode } = useCustomer();


const { invoices, invoiceNo, getInvoiceByInvoiceNo } = useInvoice();
const { voyageName, getVoyageName } = useVoyage();
const { services, getServices } = useService();

const { formParams, agingSchedules, voyages, getAgingSchedule, isLoading } = useAgingScheduleReport();

const { setTitle } = Title();

setTitle('Aging Schedule');

const searchParams = ref({
  invoice_data: null,
  invoice_number: '',
  voyage_no: null,
  voyage_id: '',
  service_data: null,
  service_id: '',
  customer_codes_name: null,
  customer_id: '',
  aging: '',
});

function clearFormData(){
  searchParams.value = {
    invoice_data: null,
    invoice_number: '',
    voyage_no: null,
    voyage_id: '',
    service_data: null,
    service_id: '',
    customer_codes_name: null,
    customer_id: '',
    aging: '',
  };
}

function fetchInvoiceByNo(search, loading) {
  getInvoiceByInvoiceNo(search);
}

function fetchVoyages(search, loading) {
  getVoyageName(search);
}

function fetchCustomer(search, loading){
  getCustomerByNameOrCode(search);
}

let totalCurrentAmount = ref(0);
let totalOdAmount = ref(0);
let totalOverDue1 = ref(0);
let totalOverDue2 = ref(0);
let totalOverDue3 = ref(0);
let totalOverDue4 = ref(0);
let totalOverDue5 = ref(0);
let totalOverDue6 = ref(0);


watch(() => agingSchedules.value, (newValue) => {
  //console.log(newValue);
});

const currentAmount = function calculateCurrentAmount(){
  let current_amount = document.getElementsByClassName('current_amount');

  for (let i = 0; i < current_amount.length; i++) {
    if(current_amount[i].innerHTML !== ''){
      totalCurrentAmount.value += parseFloat(current_amount[i].innerHTML);
    }
  }
  //return totalCurrentAmount.value;
}

onMounted(() => {
  watchEffect(() => {
    if(searchParams.value.invoice_data){
      searchParams.value.invoice_number = searchParams.value.invoice_data.invoice_number;
    }
    if(searchParams.value.voyage_no){
      searchParams.value.voyage_id = searchParams.value.voyage_no.id;
    }
    if(searchParams.value.service_data){
      searchParams.value.service_id = searchParams.value.service_data.id;
    }
    if(searchParams.value.customer_codes_name){
      searchParams.value.customer_id = searchParams.value.customer_codes_name.id;
    }
    if(searchParams.value.mailing_data){
      searchParams.value.mailing_status = searchParams.value.mailing_data.mailing_status;
    }

    getCustomerWithoutPaginate();
    getAgingSchedule(props.page, searchParams.value);

  });
  getServices();

    // getCustomerWithoutPaginate();
    // getAgingSchedule();
});


</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Aging Schedule</h2>
    </div>
    <!-- Table -->
<!--  <button type="button"> Click Me </button>-->
  <div class="w-full flex items-center justify-between mb-2 select-none">
    <fieldset class="w-full grid grid-cols-7 gap-1 px-2 pb-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Search Aging Schedule</legend>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Invoice No</label>
        <v-select :options="invoiceNo" placeholder="--Choose an option--" @search="fetchInvoiceByNo" v-model="searchParams.invoice_data" label="invoice_number" required></v-select>
        <input type="hidden" v-model="searchParams.invoice_number">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Voyage No</label>
        <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchVoyages" v-model="searchParams.voyage_no" label="voyage" required></v-select>
        <input type="hidden" v-model="searchParams.voyage_id">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Service</label>
        <v-select :options="services" placeholder="--Choose an option--" v-model="searchParams.service_data" label="code" ></v-select>
        <input type="hidden" v-model="searchParams.service_id">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Customer ID</label>
        <v-select placeholder="--Choose an option--" :options="customerCodeName" @search="fetchCustomer" v-model="searchParams.customer_codes_name" label="code_name"></v-select>
        <input type="hidden" v-model="searchParams.customer_id">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Overdue (In Days)</label>
        <input type="text" v-model="searchParams.aging" class="block w-full text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
      </div>
      <div>
        <label for="">&nbsp;</label>
        <button @click="clearFormData" class="w-full flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Clear</span>
        </button>
      </div>
      <div>
        <label for="">&nbsp;</label>
        <button type="button" @click="tableToExcel('aging-schedule-table','aging-schedule as on '+ new Date().toLocaleDateString('de-DE'))" class="w-full flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
          <span>Download Excel</span>
        </button>
      </div>
    </fieldset>
  </div>
    <div class="w-full my-2 mb-8 overflow-hidden border rounded-lg shadow-xs dark:border-0">
        <h1 class="py-1 font-bold text-center text-white bg-green-500">Aging Schedule as on {{ today }}</h1>
        <div class="w-full">
            <table class="w-full whitespace-no-wrap" id="aging-schedule-table">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td rowspan="2"> Invoice No </td>
                        <td rowspan="2"> <nobr>Customer Name</nobr> </td>
                        <td rowspan="2"> <nobr> CID </nobr> </td>
                        <td rowspan="2"> <nobr> SVC </nobr> </td>
                        <td rowspan="2"> <nobr> INV. DT </nobr> </td>
                        <td rowspan="2"> <nobr> CR. DYS </nobr> </td>
                        <td rowspan="2"> <nobr> Due DT </nobr> </td>
                        <td rowspan="2"> <nobr> Cur </nobr> </td>
                        <td rowspan="2"> <nobr> INV. AMT </nobr> </td>
                        <td rowspan="2"> <nobr> INV. AGE </nobr> </td>
                        <td rowspan="2"> <nobr> To Due DYS </nobr> </td>
                        <td rowspan="2"> <nobr> Current Amount  </nobr> </td>
                        <td rowspan="2"> <nobr> OD / Amount  </nobr> </td>
                        <td colspan="6"> <nobr> Overdue Aging  </nobr> </td>
                    </tr>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <td>1-15 Days</td>
                        <td>16-30 Days</td>
                        <td>31-45 Days</td>
                        <td>46-60 Days</td>
                        <td>60-90 Days</td>
                        <td> > 90 Days</td>
                    </tr>

                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                  <template v-for="(schedule,key,index) in agingSchedules" :key="index">
                    <tr v-for="(invoice,invoiceIndex) in schedule" :key="invoiceIndex" class="text-gray-700 dark:text-gray-400">
                      <td style="white-space: nowrap"> {{ invoice?.invoiceNumber }}</td>
                      <td>{{ invoice?.customerName }}</td>
                      <td>{{ invoice?.customerCode }}</td>
                      <td>{{ invoice?.lane }}</td>
                      <td> <nobr> {{ invoice?.issueDate ? moment(invoice?.issueDate).format('DD-MM-YYYY') : null }} </nobr> </td>
                      <td>{{ invoice?.creditDays }}</td>
                      <td> <nobr>{{ invoice?.dueDate ? moment(invoice?.dueDate).format('DD-MM-YYYY') : null }}</nobr> </td>
                      <td>{{ invoice?.currency }}</td>
                      <td>{{ invoice?.invoiceAmount }}</td>
                      <td>{{ invoice?.totalOsDays }}</td>
                      <td>{{ invoice?.totalOdDays }}</td>
                      <td class="current_amount" ref="current_amount">{{ (key == '0') ? invoice?.dueAmount : '' }}</td>
                      <td class="od_amount">{{ (key != '0') ? invoice?.invoiceAmount : '' }}</td>
                      <td class="amount_1_15">{{ (key == '1-15') ? invoice?.invoiceAmount : '' }}</td>
                      <td class="amount_16_30">{{ (key == '16-30') ? invoice?.invoiceAmount : '' }}</td>
                      <td class="amount_31_45">{{ (key == '31-45') ? invoice?.invoiceAmount : '' }}</td>
                      <td class="amount_46_60">{{ (key == '46-60') ? invoice?.invoiceAmount : '' }}</td>
                      <td class="amount_61_90">{{ (key == '61-90') ? invoice?.invoiceAmount : '' }}</td>
                      <td class="amount_90">{{ (key == '>90') ? invoice?.invoiceAmount : '' }}</td>
                    </tr>
                  </template>
<!--                <tr class="bg-gray-300 font-bold">-->
<!--                  <td colspan="11"></td>-->
<!--                  <td>{{ isLoading ? 0 : totalCurrentAmount }}</td>-->
<!--                  <td>555555</td>-->
<!--                  <td>555555</td>-->
<!--                  <td>555555</td>-->
<!--                  <td>555555</td>-->
<!--                  <td>555555</td>-->
<!--                  <td>555555</td>-->
<!--                  <td>555555</td>-->
<!--                </tr>-->
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
    th {
        @apply p-10 border-r text-center;
    }

  thead td{
    text-transform: capitalize;
  }

    tbody td,
    thead td,
    thead th {
        @apply px-1 py-1 text-xs border-r text-center;
        font-size: 10px !important;
    }
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