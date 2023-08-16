<script setup>
import { ref, watch, onMounted, watchEffect } from "vue";
import Title from "../../../../services/title";
import moment from "moment";
import useMaterial from "../../../../composables/scm/useMaterial";
import useTableExportExcel from "../../../../services/tableExportExcel";
import useRevenueReport from "../../../../composables/revenue/useRevenueReport";
import useHelper from "../../../../composables/useHelper";
import useCustomer from "../../../../composables/configuration/useCustomer.js";
import useBilling from "../../../../composables/revenue/useBilling";
const { customers, searchCustomerByCode, customer } = useCustomer();
const { billNo, getBillByBillNo } = useBilling();

const { setTitle } = Title();
const { materials, searchMaterial } = useMaterial();
const { tableToExcel } = useTableExportExcel();
const { salesData, getCustomerReport, agingSchedules, getAgingSchedule } = useRevenueReport();
const { numberFormat } = useHelper();

const today = new Date().toLocaleDateString('de-DE');

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

setTitle('Aging Schedule');

const searchParams = ref({
  bill_data: null,
  bill_no: '',
  customer_codes_name: null,
  customer_id: '',
  aging: '',
});

function clearFormData(){
  searchParams.value = {
    bill_data: null,
    bill_no: '',
    customer_codes_name: null,
    customer_id: '',
    aging: '',
  };
}

function fetchBillByNo(search, loading) {
  getBillByBillNo(search);
}

function fetchCustomer(search, loading) {
  searchCustomerByCode(search);
}

onMounted(() => {
  watchEffect(() => {
    if(searchParams.value.bill_data){
      searchParams.value.bill_no = searchParams.value.bill_data.bill_no;
    }
    if(searchParams.value.customer_codes_name){
      searchParams.value.customer_id = searchParams.value.customer_codes_name.id;
    }

    getAgingSchedule(props.page, searchParams.value);

  });

});


</script>

<template>
  <!-- Heading -->
<!--  <div class="flex flex-col items-center justify-between w-full my-2 sm:flex-row" v-once>-->
<!--    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Aging Schedule</h2>-->
<!--  </div>-->
  <!-- Table -->
  <!--  <button type="button"> Click Me </button>-->
  <div class="w-full flex items-center justify-between mb-2 select-none">
    <fieldset class="w-full grid grid-cols-5 gap-1 px-2 pb-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 py-1 text-gray-700 uppercase dark:text-gray-300">Search Aging Schedule</legend>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Bill No</label>
        <v-select :options="billNo" placeholder="--Choose an option--" @search="fetchBillByNo" v-model="searchParams.bill_data" label="bill_no" required></v-select>
        <input type="hidden" v-model="searchParams.bill_no">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Customer ID</label>
        <v-select placeholder="--Choose an option--" :options="customers" @search="fetchCustomer" v-model="searchParams.customer_codes_name" label="code_name"></v-select>
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
          <td rowspan="2"> Bill No </td>
          <td rowspan="2"> <nobr>Customer Name</nobr> </td>
          <td rowspan="2"> <nobr> CID </nobr> </td>
          <td rowspan="2"> <nobr> BILL. DT </nobr> </td>
          <td rowspan="2"> <nobr> CR. DYS </nobr> </td>
          <td rowspan="2"> <nobr> Due DT </nobr> </td>
          <td rowspan="2"> <nobr> BILL. AMT </nobr> </td>
          <td rowspan="2"> <nobr> BILL. AGE </nobr> </td>
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
          <tr v-for="(bill,billIndex) in schedule" :key="billIndex" class="text-gray-700 dark:text-gray-400">
            <td style="white-space: nowrap"> {{ bill?.billNo }}</td>
            <td>{{ bill?.customerName }}</td>
            <td>{{ bill?.customerCode }}</td>
            <td> <nobr> {{ bill?.billDate ? moment(bill?.billDate).format('DD-MM-YYYY') : null }} </nobr> </td>
            <td>{{ bill?.creditDays }}</td>
            <td> <nobr>{{ bill?.dueDate ? moment(bill?.dueDate).format('DD-MM-YYYY') : null }}</nobr> </td>
            <td>{{ bill?.billAmount }}</td>
            <td>{{ bill?.totalOsDays }}</td>
            <td>{{ bill?.totalOdDays }}</td>
            <td class="current_amount" ref="current_amount">{{ (key == '0') ? bill?.dueAmount : '' }}</td>
            <td class="od_amount">{{ (key != '0') ? bill?.billAmount : '' }}</td>
            <td class="amount_1_15">{{ (key == '1-15') ? bill?.billAmount : '' }}</td>
            <td class="amount_16_30">{{ (key == '16-30') ? bill?.billAmount : '' }}</td>
            <td class="amount_31_45">{{ (key == '31-45') ? bill?.billAmount : '' }}</td>
            <td class="amount_46_60">{{ (key == '46-60') ? bill?.billAmount : '' }}</td>
            <td class="amount_61_90">{{ (key == '61-90') ? bill?.billAmount : '' }}</td>
            <td class="amount_90">{{ (key == '>90') ? bill?.billAmount : '' }}</td>
          </tr>
        </template>
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