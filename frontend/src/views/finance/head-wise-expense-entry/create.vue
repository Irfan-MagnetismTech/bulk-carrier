<script setup>
import usePort from "../../../composables/usePort";
import useVoyageExpense from "../../../composables/finance/useVoyageExpense";
import useVoyagePairing from "../../../composables/finance/useVoyagePairing";
import useVoyage from "../../../composables/operation/useVoyage";
import {watch, ref} from "vue";

const { ports, portName, getPortsByNameOrCode } = usePort();
const { pairList, getPairedVoyageName, pairs, showVoyagePair } = useVoyagePairing();
const { categories, getExpenseCategories, headWiseExpenseFormParams, storeExpenseEntry, isLoading } = useVoyageExpense();
let isModalOpen = ref(false);
let entrySummery = ref([]);
let roundVoyageIndex = ref('');

function fetchOptions(search, loading) {
  getPortsByNameOrCode(search);
}

function invoiceAttachment (fileData,index){
  entrySummery.value[index].attachment = fileData;
}

function fetchVoyages(search, loading) {
  getPairedVoyageName(search);
}

function fetchPortWiseExpenseGroup(search, loading) {
  let port = headWiseExpenseFormParams.value.port;
    getExpenseCategories(search, port);
}

function addExpenseEntry(index) {

  let duplicateExpense = JSON.stringify(headWiseExpenseFormParams.value.entries[index]);
    duplicateExpense = JSON.parse(duplicateExpense);
    duplicateExpense.expense_head = null;
    duplicateExpense.expense_head_id = null;
    duplicateExpense.amount = null;
    duplicateExpense.amount_bdt = null;
    duplicateExpense.remarks = null;

    headWiseExpenseFormParams.value.entries.splice(index+1, 0, duplicateExpense);
}

function removeExpenseEntry(index){
  if(headWiseExpenseFormParams.value.entries.length>1) {
    headWiseExpenseFormParams.value.entries.splice(index, 1);
  }
}

watch(() => headWiseExpenseFormParams, (value) => {
  if(value?.value?.port_code_name) {
      value.value.port = value?.value?.port_code_name?.code ?? '';
  }

}, {deep: true});

watch(() => pairs, (value) => {
  let index = roundVoyageIndex.value;
  headWiseExpenseFormParams.value.entries[index].exchange_rate = value?.value?.exchange_rate
  headWiseExpenseFormParams.value.entries[index].exchange_rate_display = value?.value?.exchange_rate
}, {deep: true});

function getExchangeRate(index) {
  // alert(index);
  let pairId = headWiseExpenseFormParams.value.entries[index].voyage_id;
  roundVoyageIndex.value = index;
  // console.log(pairId);
  showVoyagePair(pairId);
}
const inputCurrency = ref();
const inputAmount = ref();
const exchangeRate = ref();
const convertedAmount = ref();

function calculateAmount(index) {
  inputCurrency.value = headWiseExpenseFormParams.value.entries[index].currency;
  inputAmount.value = headWiseExpenseFormParams.value.entries[index].amount;
  exchangeRate.value = (headWiseExpenseFormParams.value.entries[index].exchange_rate > 0) ? headWiseExpenseFormParams.value.entries[index].exchange_rate : 1;

  if(inputCurrency.value == 'USD') {
    convertedAmount.value = parseFloat(inputAmount.value * exchangeRate.value)
    headWiseExpenseFormParams.value.entries[index].exchange_rate_display = exchangeRate.value;

  } else if(inputCurrency.value == 'BDT') {
    convertedAmount.value = parseFloat(inputAmount.value * 1)
    headWiseExpenseFormParams.value.entries[index].exchange_rate_display = 1;
  }
  // amount_bdt
  headWiseExpenseFormParams.value.entries[index].amount_bdt = convertedAmount.value
}

function checkSum(formData) {
  isModalOpen.value = true;
  entrySummery.value = [];
  let entries = headWiseExpenseFormParams.value.entries;

  let groupByInvoice = entries.reduce((group, entry) => {
      const { invoice_no } = entry;
      group[invoice_no] = group[invoice_no] ?? [];
      group[invoice_no].push(entry);
      return group;
  }, {});

  for (const [key, value] of Object.entries(groupByInvoice)) {
      let details = {
        total: sumInvoices(value, 'USD'),
        totalBDT: sumInvoices(value, 'BDT'),
        invoice: key,
        date: collectInvoiceDates(value),
        actual_date: value[0].invoice_date,
        exchangeRate: value[0].exchange_rate,
        currency: value[0].currency,
        attachment: ''
      }

      entrySummery.value.push(details);
  }

  // console.log(entrySummery)

}

function saveExpenseEntry(type) {

  storeExpenseEntry(headWiseExpenseFormParams.value.entries, headWiseExpenseFormParams.value.port, entrySummery.value, type);

}
// Summing the Invoices

function sumInvoices(items, currency) {
    let sum = 0;
    for (const [key, value] of Object.entries(items)) {

        if(currency == 'USD') {
          sum += parseFloat(value.amount);
        } else {
          sum += parseFloat(value.amount_bdt);
        }
    }
    return sum;
}

function collectInvoiceDates(items) {
    let date = []
    for (const [key, value] of Object.entries(items)) {
        date.push(value.invoice_date);
    }
    return date;
}


// Finsih Summing


function closeModal() {
  isModalOpen.value = false;
}

</script>
<template>
  <!-- Basic information -->
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <!-- <span>Delowar Test Shell to deploy</span> -->
    <form @submit.prevent="checkSum(headWiseExpenseFormParams)">
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <div class="mt-3 mb-4 flex">
        <div class="w-2/4">
            <h3>Choose Port</h3>
           <v-select v-model="headWiseExpenseFormParams.port_code_name" :id="'port_code_name' + index" name="port_code_name" @search="fetchOptions" value="code" :options="portName" label="code_name" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600 w-full"></v-select>
                <input type="hidden" v-model="headWiseExpenseFormParams.port">
        </div>

    </div>
    <legend class="px-2 text-gray-700 dark:text-gray-300">Expense Head <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap text-sm" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-bottom w-48">Expense Group</th>
        <th class="px-4 py-3 align-bottom w-48">R.V.</th>
        <th class="px-4 py-3 align-bottom w-6">Invoice Date</th>
        <th class="px-4 py-3 align-bottom">Invoice No</th>
        <th class="px-4 py-3 align-bottom w-18">Currency</th>
        <th class="px-4 py-3 align-bottom w-18">Amount <br/><small>USD</small></th>
        <th class="px-4 py-3 align-bottom">Exchange <br/>Rate</th>
        <th class="px-4 py-3 align-bottom">Amount <br/><small>BDT</small></th>
        <th class="px-4 py-3 align-bottom">Remarks</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(expenseEntry, index) in headWiseExpenseFormParams.entries" :key="expenseEntry.id">
        <td class="px-1 py-1">
          <table class="w-full">
            <tr>
              <td class="w-full" style="border: 0px">
                 <v-select v-model="headWiseExpenseFormParams.entries[index].expense_head" :id="'name' + index" @search="fetchPortWiseExpenseGroup" value="id" :options="categories" label="category_head" placeholder="Enter Expense Group" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                <input type="hidden" v-model="headWiseExpenseFormParams.entries[index].expense_head_id" :id="'port_code' + index" />
              </td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table class="w-full">
            <tr>
              <td class="" style="border: 0px">
                <v-select @close="getExchangeRate(index)" :options="pairList" placeholder="--Choose an option--" @search="fetchVoyages" v-model="headWiseExpenseFormParams.entries[index].voyage_id" label="combined_name" :reduce="pairList => pairList.id" required>
                </v-select>
              </td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table class="w-full">
            <tr>
              <td class="" style="border: 0px">
                <input type="date" v-model="headWiseExpenseFormParams.entries[index].invoice_date" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              </td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table class="w-full">
            <tr>
              <td class="" style="border: 0px">
                <input type="text" v-model="headWiseExpenseFormParams.entries[index].invoice_no" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              </td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table class="w-full">
            <tr>
              <td class="" style="border: 0px">
                <select @change="calculateAmount(index)" v-model="headWiseExpenseFormParams.entries[index].currency" required class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                  <option value="">-- Select Bound --</option>
                  <option value="USD">USD</option>
                  <option value="BDT">BDT</option>
                </select>
              </td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table class="w-full">
            <tr>
              <td class="" style="border: 0px">
                <input type="text" @keyup="calculateAmount(index)" step="any" v-model="headWiseExpenseFormParams.entries[index].amount" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              </td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table class="w-full">
            <tr>
              <td class="" style="border: 0px">
                <input type="text" readonly v-model="headWiseExpenseFormParams.entries[index].exchange_rate_display" class="block w-full bg-gray-300 rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <input type="hidden" readonly v-model="headWiseExpenseFormParams.entries[index].exchange_rate" class="block w-full bg-gray-300 rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              </td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table class="w-full">
            <tr>
              <td class="" style="border: 0px">
                <input type="text" step="any" v-model="headWiseExpenseFormParams.entries[index].amount_bdt" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              </td>
            </tr>
          </table>
        </td>
        <td class="px-1 py-1">
          <table class="w-full">
            <tr>
              <td class="" style="border: 0px">
                <input type="text" v-model="headWiseExpenseFormParams.entries[index].remarks" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              </td>
            </tr>
          </table>
        </td>

        <td class="px-1 py-3 text-center grid grid-cols-2 gap-x-2 h-auto border-none w-20">
          <button type="button" @click="removeExpenseEntry(index)" class="px-1 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <button type="button" @click="addExpenseEntry(index)" class="px-1 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </td>
      </tr>
      </tbody>
    </table>
  </fieldset>
      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Process</button>
    </form>
  </div>

  <div v-if="isModalOpen" class="absolute w-full h-screen bg-gray-200 top-0 left-0 z-40 flex items-center justify-center">
    <div class="relative bg-white w-8/12 mx-auto rounded-md p-3">
      <p class="text-center py-3 px-2 font-semibold text-gray-600 text-xl border-b-1 border-gray-200">
        Input Invoice Summary
      </p>
      <table class="border-1 border-collapse w-full">
        <thead>
          <tr class="border-2 border-gray-300">
            <th class="border-2 border-gray-200">Invoice</th>
            <th class="border-2 border-gray-200">Date</th>
            <th class="border-2 border-gray-200">Total</th>
            <th class="border-2 border-gray-200">Total <small>(BDT)</small></th>
            <th class="border-2 border-gray-200">Attachment</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(value, key, index) in entrySummery" class="border-2 border-gray-300">
            <td class="border-2 border-gray-200 text-center p-1">{{ value.invoice }}</td>
            <td class="border-2 border-gray-200 text-center p-1">
              <span v-for="date in Array.from(new Set(value.date))" class="m-1 p-1 bg-gray-200 rounded-md block">
                {{ date }}
              </span>
            </td>
            <td class="border-2 border-gray-200 text-center p-1">{{ value.total }}</td>
            <td class="border-2 border-gray-200 text-center p-1">{{ value.totalBDT }}</td>
            <td class="border-2 border-gray-200 text-center p-1">
              <input type="file" @change="invoiceAttachment($event.target.files[0],key)" name="attachment">
            </td>
          </tr>
        </tbody>
      </table>

      <div class="flex justify-between">
        <button @click="saveExpenseEntry('save')" type="button" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Save Only</button>
        <button @click="saveExpenseEntry('print')" type="button" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Save & Print</button>
        <button @click="closeModal()" type="button" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Back</button>
      </div>

    </div>
  </div>
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
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

table th{
  text-transform: capitalize;
}
input[type='date']
{
    width: 8rem;
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