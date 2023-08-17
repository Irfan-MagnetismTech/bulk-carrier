<script setup>
    import { onMounted, computed, ref, watch, watchEffect } from 'vue';
    import { orderBy } from 'lodash';
    import { onClickOutside } from '@vueuse/core';
    import ActionButton from '../../../components/buttons/ActionButton.vue';
    import moment from 'moment';
    import Heading from '../../../components/Heading.vue';
    import useVoyage from '../../../composables/operation/useVoyage';
    import useReport from '../../../composables/operation/useReport';
    import Title from "../../../services/title";
    import useVoyageExpense from "../../../composables/finance/useVoyageExpense";
    import useHelper from "../../../composables/useHelper";
    import { useRoute } from 'vue-router';
    import usePort from "../../../composables/usePort";
    import useVoyagePairing from "../../../composables/finance/useVoyagePairing";
    import _ from 'lodash';

    const route = useRoute();
    const { ports, portName, getPortsByNameOrCode } = usePort();
    const { pairList, getPairedVoyageName, pairs } = useVoyagePairing();
    const expenseInvoiceId = route.params.expenseInvoiceId;

    const props = defineProps({
        page: {
            type: Number,
            default: '',
        },
    });
    const { expenseInvoice, getInvoiceDetails, categories, getExpenseCategories, updateSingleInvoice, isLoading } = useVoyageExpense();
    const $helper = new useHelper();

    const { setTitle } = Title();

    setTitle('Invoice Details');


    let attachment = ref(null);
    let isModalOpen = ref(false);
    let entrySummery = ref(null);
    let roundVoyageIndex = ref('');

    const expenseFormParams = ref({
      port: '',
      port_code_name: '',
      invoice_id: '',
      invoice_number: '',
      date: '',
      currency: '',
      exchange_rate: '',
      attachment: null,
      expense_entries: [],
    });

    function fetchOptions(search, loading) {
    getPortsByNameOrCode(search);
    }

    function invoiceAttachment (fileData){
      console.log(fileData)
      attachment.value = fileData;
    }

    function fetchVoyages(search, loading) {
        getPairedVoyageName(search);
    }

    function fetchPortWiseExpenseGroup(search, loading) {
        let port = expenseFormParams.value.port;
        if(search.length > 2) {
            getExpenseCategories(search, port);
        }
    }

    function addExpenseEntry(index) {

      let nextIndex = index+1;
      let obj = _.cloneDeep(expenseFormParams.value.expense_entries[index]);
      expenseFormParams.value.expense_entries.splice(nextIndex, 0, obj);

    }

    function removeExpenseEntry(index){
        if(expenseFormParams.value.expense_entries.length>1) {
          expenseFormParams.value.expense_entries.splice(index, 1);
        }
    }

    watch(() => expenseFormParams, (value) => {
        if(value?.value?.port_code_name) {
            value.value.port = value?.value?.port_code_name?.code ?? '';
        }

    }, {deep: true});

    watch(() => pairs, (value) => {
        let index = roundVoyageIndex.value;
        expenseFormParams.value.expense_entries[index].exchange_rate = value?.value?.exchange_rate
        expenseFormParams.value.expense_entries[index].exchange_rate_display = value?.value?.exchange_rate
    }, {deep: true});

    const inputCurrency = ref();
    const inputAmount = ref();
    const exchangeRate = ref();
    const convertedAmount = ref();

    function calculateAmount(index) {
        inputCurrency.value = expenseFormParams.value.currency;
        inputAmount.value = expenseFormParams.value.expense_entries[index].amount;
        exchangeRate.value = (expenseFormParams.value.exchange_rate > 0) ? expenseFormParams.value.exchange_rate : 1;

      if(inputCurrency.value == 'USD') {
          convertedAmount.value = parseFloat(inputAmount.value * exchangeRate.value).toFixed(4)
          expenseFormParams.value.expense_entries[index].exchange_rate_display = exchangeRate.value;

      } else if(inputCurrency.value == 'BDT') {
          convertedAmount.value = parseFloat(inputAmount.value * 1).toFixed(4)
          expenseFormParams.value.expense_entries[index].exchange_rate_display = 1;
      }
      // amount_bdt
      expenseFormParams.value.expense_entries[index].amount_bdt = convertedAmount.value
    }

    function updateCalculation() {
      inputCurrency.value = expenseFormParams.value.currency;
      exchangeRate.value = (expenseFormParams.value.exchange_rate > 0) ? expenseFormParams.value.exchange_rate : 1;

      if(inputCurrency.value == 'USD') {
          // loop through expenseFormParams.value.expense_entries and calculate the exchange rate
          for(let index = 0; index < expenseFormParams.value.expense_entries.length; index++) {
            inputAmount.value = expenseFormParams.value.expense_entries[index].amount;
            convertedAmount.value = parseFloat(inputAmount.value * exchangeRate.value).toFixed(4)
            expenseFormParams.value.expense_entries[index].exchange_rate_display = exchangeRate.value;
            expenseFormParams.value.expense_entries[index].amount_bdt = convertedAmount.value

          }
        } else if(inputCurrency.value == 'BDT') {
          for(let index = 0; index < expenseFormParams.value.expense_entries.length; index++) {
            inputAmount.value = expenseFormParams.value.expense_entries[index].amount;
            convertedAmount.value = parseFloat(inputAmount.value * 1).toFixed(4)
            expenseFormParams.value.expense_entries[index].exchange_rate_display = 1;
            expenseFormParams.value.expense_entries[index].amount_bdt = convertedAmount.value

          }
        }
      
    }

    function checkSum(formData) {
        isModalOpen.value = true;
        entrySummery.value = null;
        let entries = expenseFormParams.value.expense_entries;
        // for (const [key, value] of Object.entries(entries)) {
          entrySummery.value = {
                    total: parseFloat(sumInvoices(entries, 'USD')).toFixed(4),
                    totalBDT: parseFloat(sumInvoices(entries, 'BDT')).toFixed(4),
                    id: expenseFormParams.value.invoice_id,
                    invoice: expenseFormParams.value.invoice_number,
                    actual_date: expenseFormParams.value.date,
                    exchangeRate: expenseFormParams.value.exchange_rate,
                    currency: expenseFormParams.value.currency,
                    attachment: null
            }


            // entrySummery.value.push(details);
        // }

    }

    function saveExpenseEntry(type) {
        entrySummery.value.attachment = attachment.value;

        updateSingleInvoice(expenseFormParams.value.invoice_id, expenseFormParams.value.expense_entries, expenseFormParams.value.port, entrySummery.value, type);
    }
    // Summing the Invoices

    function sumInvoices(items, currency) {
        let sum = 0;
        for (const [key, value] of Object.entries(items)) {

            if(currency == 'USD') {
                sum += parseFloat(value.amount).toFixed(4);
            } else {
                sum += parseFloat(value.amount_bdt).toFixed(4);
            }
        }
        return sum;
    }

    function closeModal() {
        isModalOpen.value = false;
    }


    watch(() => expenseInvoice, (value) => {

      if(value){
        expenseFormParams.value.port_code_name = expenseInvoice.value.invoice_port;
        expenseFormParams.value.port = expenseInvoice.value.invoice_port.code;
        expenseFormParams.value.invoice_id = expenseInvoiceId;
        expenseFormParams.value.invoice_number = expenseInvoice.value.invoice_number;
        expenseFormParams.value.date = expenseInvoice.value.date;
        expenseFormParams.value.currency = expenseInvoice.value.currency;
        expenseFormParams.value.exchange_rate = expenseInvoice.value.exchange_rate;
        expenseFormParams.value.expense_entries = expenseInvoice.value.expense_entries;
        expenseFormParams.value.roundVoyage = expenseInvoice.value.expense_entries[0]?.round_voyage?.combined_name;
      }

    }, {deep: true});

    onMounted(() => {
      getInvoiceDetails(expenseInvoiceId);
    });
    </script>
    <template>
        <!-- Basic information -->
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
          <!-- <span>Delowar Test Shell to deploy</span> -->
          <form @submit.prevent="checkSum(expenseFormParams)">
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
          <div class="mt-3 mb-4 grid grid-cols-6">
              <div class="">
                  <h3>Choose Port</h3>
                 <v-select v-model="expenseFormParams.port_code_name" :options="portName" :id="'port_code_name' + index" name="port_code_name" @search="fetchOptions" value="code"  label="code_name" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600 w-full"></v-select>
                  <input type="hidden" v-model="expenseFormParams.port">
              </div>
              <div class="ml-2">
                <h3>Round Voyage</h3>
                <input type="text" v-model="expenseFormParams.roundVoyage" name="" id="" class="bg-gray-300 !w-full block rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              </div>
              <div class="ml-2">
                <h3>Invoice No</h3>
                <input type="text" v-model="expenseFormParams.invoice_number" name="" id="" class="w-full block rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <input type="hidden" v-model="expenseFormParams.invoice_id">

              </div>
              <div class="ml-2">
                <h3>Invoice Date</h3>
                <input type="date" v-model="expenseFormParams.date" id="" class="!w-full block rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              </div>
              <div class="ml-2">
                <h3>Currency</h3>
                <select v-model="expenseFormParams.currency" @change="updateCalculation(index)" required class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                  <option value="">-- Select Bound --</option>
                  <option value="USD">USD</option>
                  <option value="BDT">BDT</option>
                </select>
              </div>
              <div class="ml-2">
                <h3>Exchange Rate</h3>
                <input type="number" v-model="expenseFormParams.exchange_rate" readonly step="any" name="" id="" class="bg-gray-300 !w-full block rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              </div>
          </div>
          <legend class="px-2 text-gray-700 dark:text-gray-300">Expense Head <span class="text-red-500">*</span></legend>
          <table class="w-full whitespace-no-wrap text-sm" id="table">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th class="px-4 py-3 align-bottom w-64">Expense Group</th>
              <th class="px-4 py-3 align-bottom w-18">Amount <br/><small>USD</small></th>
              <th class="px-4 py-3 align-bottom">Amount <br/><small>BDT</small></th>
              <th class="px-4 py-3 align-bottom w-56">Remarks</th>
              <th class="px-4 py-3 text-center align-bottom">Action</th>
            </tr>
            </thead>

            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
            <tr class="text-gray-700 dark:text-gray-400" v-for="(expenseEntry, index) in expenseFormParams?.expense_entries" :key="index">
              <td class="px-1 py-1">
                <table class="w-full">
                  <tr>
                    <td class="w-full" style="border: 0px">
                       <v-select :options="categories" v-model="expenseFormParams.expense_entries[index].head" @search="fetchPortWiseExpenseGroup" label="category_head" placeholder="Enter Expense Group" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
<!--                       <input type="hidden" v-model="expenseFormParams.expense_entries[index].head.id"  />-->
                    </td>
                  </tr>
                </table>
              </td>

              <td class="px-1 py-1">
                <table class="w-full">
                  <tr>
                    <td class="" style="border: 0px">
                      <input type="text" @keyup="calculateAmount(index)" step="any" v-model="expenseFormParams.expense_entries[index].amount" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    </td>
                  </tr>
                </table>
              </td>

              <td class="px-1 py-1">
                <table class="w-full">
                  <tr>
                    <td class="" style="border: 0px">
                      <input type="text" step="any" readonly v-model="expenseFormParams.expense_entries[index].amount_bdt" class="bg-gray-300 !w-full block rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    </td>
                  </tr>
                </table>
              </td>
              <td class="px-1 py-1">
                <table class="w-full">
                  <tr>
                    <td class="" style="border: 0px">
                      <input type="text" v-model="expenseFormParams.expense_entries[index].remarks" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
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
                <tr class="border-2 border-gray-300">
                  <td class="border-2 border-gray-200 text-center p-1">{{ entrySummery.invoice }}</td>
                  <td class="border-2 border-gray-200 text-center p-1">
                      {{ entrySummery.actual_date }}
                  </td>
                  <td class="border-2 border-gray-200 text-center p-1">{{ entrySummery.total }}</td>
                  <td class="border-2 border-gray-200 text-center p-1">{{ entrySummery.totalBDT }}</td>
                  <td class="border-2 border-gray-200 text-center p-1">
                    <input type="file" @change="invoiceAttachment($event.target.files[0])" name="attachment">
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