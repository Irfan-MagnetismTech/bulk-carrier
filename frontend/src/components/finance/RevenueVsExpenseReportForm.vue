<script setup>

import { onMounted, watch } from 'vue';
import Error from "../Error.vue";
import {useRoute} from "vue-router";
import usePort from "../../composables/usePort";
import useExpenditureReport from "../../composables/finance/useExpenditureReport";
import useService from '../../composables/commercial/useService';
import useVessel from '../../composables/dataencoding/useVessel';
import useTableExportExcel from "../../services/tableExportExcel";


const { report, formParams, downloadProfitabilityPdfReport, getRevenuevsExpenseReport, downloadSingleRevenueVsExpense, downloadSingleRevenueVsExpenseExcel, expenseHeads, expenseEntries } = useExpenditureReport();
const route = useRoute();
const pairId = route.params.pairId;
const { ports, portName, getPortsByNameOrCode } = usePort();
const { services, bounds, getServices, serviceGroupById, serviceGroupByCode } = useService();
const { vessels, getVessels } = useVessel();
const { tableToExcel } = useTableExportExcel();

function fetchOptions(search, loading) {
  getPortsByNameOrCode(search, formParams.value.service_id);
}

function getReport(formData)
{
  console.log("formdata", formData)
  if(!formData.date_from || !formData.date_to)
  {
    alert('You must fill date range!')
  } else  { // if(formData.service_id || formData.port_name)
    getRevenuevsExpenseReport(formData);
  } 
  
}

function downloadPdfReport(formData) {
  console.log(formData)
  downloadProfitabilityPdfReport(formData);
}

function downloadExcelReport(formData) {
  console.log(formData)
  // downloadProfitabilityPdfReport(formData);
}

function downloadExcel(voyagePairId) {
  downloadSingleRevenueVsExpenseExcel(voyagePairId);
  console.log("Excel: ", voyagePairId)
}

function downloadPdf(voyagePairId) {
  downloadSingleRevenueVsExpense(voyagePairId);
  console.log("PDF: ", voyagePairId)
}

onMounted(() => {
  getServices();
  getVessels();

});
</script>
<template>
  <!-- Basic information -->
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Service </span>
      <select v-model="formParams.service_id" class="label-item-input">
                <option value>-- Select Service --</option>
                <option v-for="service in services" :value="service.id" :key="service.id">{{ service.code }}</option>
              </select>
    </label>
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Vessel </span>
      <select v-model="formParams.vessel_id" class="label-item-input">
        <option value>-- Select Service --</option>
        <option v-for="vessel in vessels.data" :value="vessel.id" :key="vessel.id">{{ vessel.name }}</option>
      </select>
    </label>
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Start Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="formParams.date_from" required placeholder="Ex: BES" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">End Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="formParams.date_to" required placeholder="Ex: BES" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
    </label>
    <label class=" w-full mt-3 text-sm md:w-1/6 block">
      <button type="button" class="btn-submit-default md:mt-6 w-full justify-center" @click="getReport(formParams)">Process</button>
    </label>
    <label class=" w-full mt-3 text-sm md:w-1/6 block">
      <button type="button" class="btn-submit-default md:mt-6 w-full justify-center" @click="downloadPdfReport(formParams)">PDF
      </button>
    </label>
    <label class=" w-full mt-3 text-sm md:w-1/6 block">
      <button type="button" class="btn-submit-default md:mt-6 w-full justify-center" @click="tableToExcel('profitability-report','Profitability Report')">Excel
      </button>
    </label>
  </div>

  <div class="overflow-x-auto my-3 py-2 scroll-smooth scroll-show">
    <div v-if="report != null" v-for="(roundVoyages, serviceId ) in report.revenueVsExpenses">
      <h4 style="font-weight: bold; text-align: center; margin: 15px auto; color: gray; font-size: 20px;">
        <span style="border: 1px solid gray; padding: 5px 8px; border-radius: 7px">
          {{ report?.services.find(service => service.id === Number(serviceId))?.code }} Service
        </span>
      </h4>

      <table>
        <thead>
            <tr>
                <th>Round Voyage</th>
                <th>First Voyage</th>
                <th>Second Voyage</th>
                <th>Revenue</th>
                <th>Expense</th>
                <th>Profit</th>
                <th>Closing Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
          <tr v-for="(expenseAndRevenueInfo, id) in roundVoyages">
            <td>{{ expenseAndRevenueInfo?.combined_name }}</td>
            <td>{{ expenseAndRevenueInfo?.first_voyage?.voyage }}</td>
            <td>{{ expenseAndRevenueInfo?.second_voyage?.voyage }}</td>
            <td>{{ expenseAndRevenueInfo?.revenue_vs_expense?.revenue }}</td>
            <td>{{ expenseAndRevenueInfo?.revenue_vs_expense?.expense }}</td>
            <td>{{ (parseFloat(expenseAndRevenueInfo?.revenue_vs_expense?.profit.replace(',', '')) > 0) ? expenseAndRevenueInfo?.revenue_vs_expense?.profit : "("+Math.abs(parseFloat(expenseAndRevenueInfo?.revenue_vs_expense?.profit.replace(',', '')))+")" }}</td>
            <td>{{ expenseAndRevenueInfo?.financial_closing_date }}</td>
            <td style="display: flex; align-items: center; justify-content: center">
                <a :href="`/finance/voyage/expense/${expenseAndRevenueInfo?.id}/show`" style="color: #a71111; font-weight: bold;" title="Expenses Details">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                      </svg>                              
                </a>
                <span @click="downloadExcel(expenseAndRevenueInfo?.id)" style="cursor: pointer; color: blue; font-weight: bold;" title="Excel Download (Details)">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="color: green" class="w-6 h-6 text-gray-400 group-hover:text-gray-500 stroke-2 iconify iconify--la text-gray-400 group-hover:text-gray-500 stroke-2" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32" data-v-c2953836=""><path d="M6 3v26h20V9.6l-.3-.3l-6-6l-.3-.3H6zm2 2h10v6h6v16H8V5zm12 1.4L22.6 9H20V6.4zM11 13l3.8 5.5L11 24h2.4l2.6-3.8l2.6 3.8H21l-3.8-5.5L21 13h-2.4L16 16.8L13.4 13H11z" fill="currentColor"></path></svg>
                </span>
                <span @click="downloadPdf(expenseAndRevenueInfo?.id)" style="cursor: pointer; color: blue; font-weight: bold;" title="PDF Download (Details)">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" style="color: red" class="w-5 h-6 text-gray-400 group-hover:text-gray-500 stroke-2 iconify iconify--ba text-gray-400 group-hover:text-gray-500 stroke-2" preserveAspectRatio="xMidYMid meet" viewBox="0 0 16 16" data-v-c2953836=""><g fill="currentColor"><path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"></path><path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102c.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645a19.697 19.697 0 0 0 1.062-2.227a7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136c.075-.354.274-.672.65-.823c.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538c.007.188-.012.396-.047.614c-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686a5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465c.12.144.193.32.2.518c.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416a.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95a11.651 11.651 0 0 0-1.997.406a11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238c-.328.194-.541.383-.647.547c-.094.145-.096.25-.04.361c.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193a11.744 11.744 0 0 1-.51-.858a20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41c.24.19.407.253.498.256a.107.107 0 0 0 .07-.015a.307.307 0 0 0 .094-.125a.436.436 0 0 0 .059-.2a.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198a.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283c-.04.192-.03.469.046.822c.024.111.054.227.09.346z"></path></g></svg>
                </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
        <table id="profitability-report" class="hidden">
          <template v-if="report != null" v-for="(roundVoyages, serviceId ) in report.revenueVsExpenses">
            
                  <tr>
                    <td colspan="7">
                      <h4 style="font-weight: bold; text-align: center; margin: 15px auto; color: gray; font-size: 20px;">
                        <span style="border: 1px solid gray; padding: 5px 8px; border-radius: 7px">
                          {{ report?.services.find(service => service.id === Number(serviceId))?.code }} Service
                        </span>
                      </h4>
                    </td>
                  </tr>
                  <tr>
                      <th>Round Voyage</th>
                      <th>First Voyage</th>
                      <th>Second Voyage</th>
                      <th>Revenue</th>
                      <th>Expense</th>
                      <th>Profit</th>
                      <th>Closing Date</th>
                  </tr>
                <tr v-for="(expenseAndRevenueInfo, id) in roundVoyages">
                  <td>{{ expenseAndRevenueInfo?.combined_name }}</td>
                  <td>{{ expenseAndRevenueInfo?.first_voyage?.voyage }}</td>
                  <td>{{ expenseAndRevenueInfo?.second_voyage?.voyage }}</td>
                  <td>{{ expenseAndRevenueInfo?.revenue_vs_expense?.revenue }}</td>
                  <td>{{ expenseAndRevenueInfo?.revenue_vs_expense?.expense }}</td>
                  <td>{{ (parseFloat(expenseAndRevenueInfo?.revenue_vs_expense?.profit.replace(',', '')) > 0) ? expenseAndRevenueInfo?.revenue_vs_expense?.profit : "("+Math.abs(parseFloat(expenseAndRevenueInfo?.revenue_vs_expense?.profit.replace(',', '')))+")" }}</td>

                  <td>{{ expenseAndRevenueInfo?.financial_closing_date }}</td>
                  
                </tr>
            
          </template>
        </table>

  </div>
</template>
<style lang="postcss" scoped>
table, table th, table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}
table {
  @apply w-full
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
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

.scroll-show::-webkit-scrollbar {
    width: 1.5rem !important;
    height: 2em !important;
    color: red !important;
    background: red !important;
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