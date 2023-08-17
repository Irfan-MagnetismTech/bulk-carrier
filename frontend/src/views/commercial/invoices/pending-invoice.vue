<script setup>
import {ref, watch} from 'vue';
import { useRoute } from 'vue-router';
import useCustomer from "../../../composables/commercial/useCustomer";
import useVoyage from "../../../composables/operation/useVoyage";
import useInvoice from "../../../composables/commercial/useInvoice";
import moment from 'moment';
import useVoyageSlotUsesStatementReport from "../../../composables/commercial/useVoyageSlotUsesStatementReport";
import Title from "../../../services/title";
import useNotification from "../../../composables/useNotification";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
})

const route = useRoute();
const isALlSelected = ref(false);
const numberOfSelected = ref(0);
const message = ref("");
const notification = useNotification();
let activeCustomerIndex = ref(null);

const { customers, getCustomers } = useCustomer();
const { voyages, voyageName, voyageCustomers, getVoyageCustomer, voyage, voyageExchangeRate, exchangeRate, showVoyage, getVoyageName } = useVoyage();
const { formParams, invoices, eligibleCustomerForInvoice, invoiceCustomerList, getInvoiceCustomerList, generateInvoice, isLoading } = useInvoice();
const { voyageInfo, voyageParticulars, isFinalInvoiceModalOpen, isVoyageExchangeRateModalOpen, isFinalInvoiceAlertModalOpen, voyageSlotUsesStatementReport } = useVoyageSlotUsesStatementReport();

function fetchOptions(search) {
  getVoyageName(search);
}

const { setTitle } = Title();

setTitle('Freight Invoice');

watch(voyageInfo, (value) => {
  if (value) {
    formParams.value.rate_type = value.rateType;
    formParams.value.sub_total = value.invoiceHeads.amount;
    formParams.value.billing_party = value.invoiceHeads.billing_party;
    formParams.value.billing_address = value.invoiceHeads.billing_address;
    formParams.value.billing_emails = value.invoiceHeads.billing_emails;
    formParams.value.billing_style = value.invoiceHeads.billing_style;
    voyageParticulars.value = Object.keys(voyageInfo?.value?.invoiceParticulars).length;
  }

  voyageInfo?.value?.unappliedInvoices?.forEach(function(invoice){
    let obj = {
      id: invoice?.id,
      invoice_number: invoice?.invoice_number,
      amount: invoice?.amount,
      left_amount: invoice?.left_amount,
      applied_amount: '',
    };
    formParams.value.advance_invoices.push(obj);
  });

});

function openFinalInvoiceModal(voyageId, customerId, rateType, sector = null, contractId, index,  assigned_contract_composite) {

  formParams.value.discount = 0.0;
  formParams.value.customer_reference = "";
  formParams.value.hrl_reference = "";
  formParams.value.customer_note = "";
  formParams.value.hrl_internal_note = "";

  let data = {
    'voyage_id': voyageId,
    'customer_id': customerId,
    'rate_type': rateType,
    'is_model_open': false,
    'sector': sector,
    'contract_id': contractId,
    'assigned_contract_composite': assigned_contract_composite,
  }
  activeCustomerIndex.value = index;
  voyageSlotUsesStatementReport(data);
  formParams.value.voyage_id = voyageId;
  formParams.value.customer_id = customerId;
  formParams.value.request_for = "final-invoice";
  formParams.value.sector = sector;
  formParams.value.contract_id = contractId;
  formParams.value.advance_invoices = [];
}

function closeFinalInvoiceModal(buttonType) {
  isFinalInvoiceModalOpen.value = 0;
  if(buttonType === 'submit'){
    invoiceCustomerList.value.forEach(function(invoice,index){
      if(index === activeCustomerIndex.value){
        invoiceCustomerList.value[index].invoice_status = 1;
      }
    });
  }
}

function closeVoyageExchangeRateModal() {
  isVoyageExchangeRateModalOpen.value = 0;
}

function checkSelected() {
  isALlSelected.value = invoices?.value?.eligibleCustomers.every(invoiceInfo => invoiceInfo.isSelected);
  numberOfSelected.value = invoices?.value?.eligibleCustomers.filter(invoiceInfo => invoiceInfo.isSelected).length;
}

function checkAll() {
  invoices?.value?.eligibleCustomers.forEach(invoiceInfo => {
    invoiceInfo.isSelected = isALlSelected.value;
  });
  checkSelected();
}


watch(formParams, (value) => {
      formParams.value.total = parseFloat(formParams.value.sub_total + value.discount + formParams.value.debit_note).toFixed(2);
      console.log(parseFloat(voyageInfo?.value?.invoiceHeads?.equivalent_in_bdt).toFixed(2));
      formParams.value.equivalent_in_bdt = parseFloat(formParams.value.total * voyageInfo?.value?.invoiceHeads?.exchange_rate).toFixed(2);
    }, {deep: true}
);

function send(singleInvoiceId = null) {
  let invoiceInfo_ids = [];
  if (singleInvoiceId) {
    invoiceInfo_ids = [singleInvoiceId];
  } else {
    invoiceInfo_ids = invoices?.value?.eligibleCustomers.filter(invoiceInfo => invoiceInfo.isSelected).map(invoiceInfo => invoiceInfo.customerId);
  }
  if (invoiceInfo_ids.length === 0) {
    message.value = "Please select at least one customer.";
    return;
  }

  formParams.value.voyage_customer_id = invoiceInfo_ids;
  generateInvoice(formParams);
  message.value = "";
  isALlSelected.value = false;
  checkAll();
}

watch(() => invoiceCustomerList, (val) => {

}, {
  deep: true
});


watch(() => formParams.value.advance_invoices, (val) => {
   // check if applied amount is less then left amount
    val.forEach(function(invoice,index){
      if(invoice.applied_amount > invoice.left_amount){
        alert("Applied amount is greater than left amount");
        formParams.value.advance_invoices[index].applied_amount = 0;
      }
    });
}, {
  deep: true
});

function submitInvoiceForm(formData) {
  if (!confirm('Are you sure you want to create this invoice?')) {
    isFinalInvoiceModalOpen.value = 1;
    return;
  }
  voyageSlotUsesStatementReport(formData);
}

function voyageChangeExchangeRate() {
  isVoyageExchangeRateModalOpen.value = 1;
}

function triggerInvoiceCustomerListForm(){
  getInvoiceCustomerList(formParams.value.voyage_id);
  //voyageExchangeRate(formParams.value);
  showVoyage(formParams.value.voyage_id);
}

function submitExchangeRateForm(formData) {

  if (!confirm('Are you sure you want to change this exchange rate?')) {
    isVoyageExchangeRateModalOpen.value = 1;
    return;
  }
  voyageExchangeRate(formParams.value);
  //showVoyage(formParams.value.voyage_id);
}

//watch exchangeRate value
watch(() => exchangeRate.value, (val) => {
  showVoyage(formParams.value.voyage_id);
  isVoyageExchangeRateModalOpen.value = 0;
}, {
  deep: true
});

</script>
<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Generate Final Invoice</h2>
  </div>
  <!-- Table -->
  <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <form class="flex items-end justify-between gap-4" @submit.prevent="triggerInvoiceCustomerListForm">
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Voyage No</span>
        <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchOptions"
          v-model="formParams.voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required></v-select>
      </label>
      <button type="submit" :disabled="isLoading"
        class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
    </form>
  </div>

  <h4 class="mt-2 font-bold text-left text-gray-700 dark:text-gray-200">
    Customer List
    <span class="float-right">
      <button type="submit" v-if="Object.keys(invoiceCustomerList).length" @click="voyageChangeExchangeRate" style="background-color: #3f3f46"  class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 border border-transparent rounded-lg fon2t-medium focus:outline-none">Exchange Rate : {{ voyage?.exchange_rate ?? 0.0 }}</button>
      <button type="submit" v-else style="background-color: #3f3f46"  class="flex opacity-50 cursor-not-allowed items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 border border-transparent rounded-lg fon2t-medium focus:outline-none">Exchange Rate : 0.00</button>
    </span>
  </h4>
  <div class="flex flex-row-reverse items-center justify-between mb-2">
    <div class="flex items-center gap-1 text-xs text-red-500" v-if="message">
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
        class="w-5 h-5 iconify iconify--bx" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
        <path fill="currentColor" d="M11.001 10h2v5h-2zM11 16h2v2h-2z" />
        <path fill="currentColor"
          d="M13.768 4.2C13.42 3.545 12.742 3.138 12 3.138s-1.42.407-1.768 1.063L2.894 18.064a1.986 1.986 0 0 0 .054 1.968A1.984 1.984 0 0 0 4.661 21h14.678c.708 0 1.349-.362 1.714-.968a1.989 1.989 0 0 0 .054-1.968L13.768 4.2zM4.661 19L12 5.137L19.344 19H4.661z" />
      </svg>
      {{ message }}
    </div>
  </div>
  <div class="w-full mb-8 overflow-hidden">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap vesselInfo">
        <thead>
<!--        <tr>-->
<!--          <td colspan="6" style="font-weight: bolder;background-color: rgb(4, 170, 109);color: white">Customer List</td>-->
<!--        </tr>-->
          <tr style="background-color: #04AA6D;color: white">
            <th>Customer Name</th>
            <th>Customer Code</th>
            <th>Contract No</th>
            <th>Rate Type</th>
            <th>Ports</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>

          <template v-for="(customer, index) in invoiceCustomerList" :key="index">
            <template v-if="customer.rate_type != 'Fixed'">
              <tr>
                <td>{{ customer?.customer_name ?? '---' }}
                </td>
                <td>{{ customer?.customer_code }}</td>
                <td>{{ customer?.contract_no ?? 'Not Assigned' }}</td>
                <td>{{ customer?.rate_type ?? '---' }}</td>
                <td>{{ customer?.sector ?? '---' }}</td>
                <td v-if="customer?.contract_no != null">
                  <router-link :to="{ name: 'commercial.voyage-slot-uses-statement', params: { voyageId: formParams.voyage_id, customerId: customer?.customer_id, sector: customer?.sector, rateType: customer?.rate_type } }">
                    <button class="inline-flex items-center px-3 py-1 mr-2 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-purple-500 rounded hover:bg-purple-600 focus:outline-none focus:shadow-outline-purple focus:border-purple-300 active:bg-purple-700">
                      Break Down
                    </button>
                  </router-link>
                  <button v-if="!customer?.invoice_status"
                  @click="openFinalInvoiceModal(formParams.voyage_id, customer?.customer_id, customer?.rate_type, customer?.sector,
                   customer?.contract_id, index, customer?.assigned_contract_composite)"
                          class="inline-flex items-center px-3 py-1 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-purple-500 rounded readonly hover:bg-purple-600 focus:outline-none focus:shadow-outline-purple focus:border-purple-300 active:bg-purple-700">
                    Generate Invoice
                  </button>
                  <button v-else title="Invoice Already Generated" class="inline-flex items-center px-3 py-1 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-purple-500 rounded opacity-50 cursor-not-allowed hover:bg-purple-600 focus:outline-none focus:shadow-outline-purple focus:border-purple-300 active:bg-purple-700">
                    Generate Invoice
                  </button>

                </td>
              </tr>
            </template>
            <template v-else>
                <tr>
                  <td>{{ customer?.customer_name ?? '---' }}</td>
                  <td>{{ customer?.customer_code }}</td>
                  <td>{{ customer?.contract_no ?? 'Not Assigned' }}</td>
                  <td>{{ customer?.rate_type ?? '---' }}</td>
                  <td>---</td>
                  <td v-if="customer?.contract_no != null">
                    <router-link :to="{ name: 'commercial.voyage-slot-uses-statement', params: { voyageId: formParams.voyage_id, customerId: customer?.customer_id, rateType: customer?.rate_type } }">
                      <button class="inline-flex items-center px-3 py-1 mr-2 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-purple-500 rounded hover:bg-purple-600 focus:outline-none focus:shadow-outline-purple focus:border-purple-300 active:bg-purple-700">
                        Break Down
                      </button>
                    </router-link>
                    <button title="Generate Invoice" v-if="!customer?.invoice_status" @click="openFinalInvoiceModal(formParams.voyage_id, customer?.customer_id, customer?.rate_type, customer?.sector, customer?.contract_id, customer?.assigned_contract_composite)" class="inline-flex items-center px-3 py-1 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-purple-500 rounded readonly hover:bg-purple-600 focus:outline-none focus:shadow-outline-purple focus:border-purple-300 active:bg-purple-700">
                      Generate Invoice
                    </button>
                    <button v-else title="Invoice Already Generated" class="inline-flex items-center px-3 py-1 mr-2 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-purple-500 rounded opacity-50 cursor-not-allowed hover:bg-purple-600 focus:outline-none focus:shadow-outline-purple focus:border-purple-300 active:bg-purple-700">
                      Generate Invoice
                    </button>
                  </td>
                  <td v-else>
                  </td>
                </tr>
            </template>
          </template>

        </tbody>
        <tfoot v-if="!invoiceCustomerList?.length" class="bg-white dark:bg-gray-800">
          <tr v-if="isLoading">
            <td colspan="6">Loading...</td>
          </tr>
          <tr v-else-if="!invoiceCustomerList?.length">
            <td colspan="6">No customer found.</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
  <div v-if="voyageParticulars" v-show="isFinalInvoiceModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="submitInvoiceForm(formParams)" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
            class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700"
            aria-label="close" @click="closeFinalInvoiceModal('cancel')">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
              <path
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
          </button>
        </header>
        <!-- Modal body -->
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead v-once>
            <tr style="background-color: #04AA6D;color: white"
              class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th colspan="5">Generate Final Invoice</th>
            </tr>
          </thead>
        </table>

        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead v-once>
          <tr class="text-xs font-bold uppercase" style="background-color: #f10909">
            <td colspan="4" style="text-align: center;color: white">Advance Invoices</td>
          </tr>
          <tr style="background-color: #04AA6D;color: white" class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th>Invoice No.</th>
            <th>Amount</th>
            <th>Left Amount</th>
            <th>Applied Amount</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr v-for="(advanceInvoice,index) in formParams.advance_invoices" :key="index" class="text-center text-gray-700 dark:text-gray-400">
                <td class="text-sm">{{ advanceInvoice?.invoice_number }}</td>
                <td class="text-sm" style="text-align: right">{{ Number(advanceInvoice?.amount).toFixed(2) }}</td>
                <td class="text-sm" style="text-align: right">{{ Number(advanceInvoice?.left_amount).toFixed(2) }}</td>
                <td class="text-sm">
                  <input type="text" placeholder="Collected amount" v-model="formParams.advance_invoices[index].applied_amount" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                </td>
              </tr>
          </tbody>
          <tfoot v-if="!formParams.advance_invoices?.length" class="bg-white dark:bg-gray-800">
          <tr v-if="isLoading">
            <td colspan="4" style="text-align: center" class="p-2 font-bold text-red-700">Loading...</td>
          </tr>
          <tr v-else-if="!formParams.advance_invoices?.length">
            <td colspan="4" style="text-align: center" class="p-2 font-bold text-red-700">No advance invoice data found.</td>
          </tr>
          </tfoot>
        </table>
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">

            <tr class="text-xs font-bold uppercase bg-yellow-300">
              <td colspan="4" style="text-align: center">Other Information</td>
            </tr>
            <tr class="text-center text-gray-700 dark:text-gray-400">
              <td class="px-4 py-3 text-sm font-bold break-all">Issue Date</td>
              <td class="px-4 py-3 text-sm break-all">{{ voyageInfo?.invoiceHeads?.issue_date }}</td>
              <td class="px-4 py-3 text-sm font-bold break-all">Service Code</td>
              <td class="px-4 py-3 text-sm break-all">{{ voyageInfo?.invoiceHeads?.serviceCode }}</td>
            </tr>
            <tr class="text-center text-gray-700 dark:text-gray-400">
              <td class="px-4 py-3 text-sm font-bold break-all">Voyage No</td>
              <td class="px-4 py-3 text-sm break-all">{{ voyageInfo?.invoiceHeads?.voyageNo }}</td>
              <td class="px-4 py-3 text-sm font-bold break-all">Vessel Name</td>
              <td class="px-4 py-3 text-sm break-all">{{ voyageInfo?.invoiceHeads?.vesselName }}</td>
            </tr>
            <tr class="text-center text-gray-700 dark:text-gray-400">
              <td class="px-4 py-3 text-sm font-bold break-all">Customer Name(Code)</td>
              <td class="px-4 py-3 text-sm break-all">{{ voyageInfo?.invoiceHeads?.customerName }} ({{
                  voyageInfo?.invoiceHeads?.customerCode
              }})</td>
              <td class="px-4 py-3 text-sm font-bold break-all">Currency</td>
              <td class="px-4 py-3 text-sm break-all">{{ voyageInfo?.invoiceHeads?.currency }}</td>
            </tr>
          </tbody>
        </table>
        <table class="w-full mb-5 whitespace-no-wrap border-collapse contract-assign-table">
          <thead v-once>
            <tr style="background-color: #04AA6D;color: white"
              class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th>Description</th>
              <th>Per</th>
              <th>Rate</th>
              <th>Rated As <br>(Quantity)</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
            <tr v-for="(particular, index) in voyageInfo?.invoiceParticulars"
              class="text-center text-gray-700 dark:text-gray-400">
              <td class="px-4 py-3 text-sm font-bold break-all">{{ particular?.description }}</td>
              <td class="px-4 py-3 text-sm break-all">{{ particular?.per }}</td>
              <td class="px-4 py-3 text-sm break-all" style="text-align: right">{{ particular?.rate }}</td>
              <td class="px-4 py-3 text-sm break-all">{{ particular?.quantity }}</td>
              <td class="px-4 py-3 text-sm break-all" style="text-align: right">{{ Number(particular?.amount).toFixed(2) }}</td>
            </tr>
            <tr class="text-center text-gray-700 dark:text-gray-400">
              <td colspan="3" class="px-4 py-3 text-sm break-all"></td>
              <td colspan="" class="px-4 py-3 text-sm font-bold break-all">Sub Total</td>
              <td class="px-4 py-3 text-sm break-all" style="text-align: right">{{ Number(voyageInfo?.invoiceHeads?.amount).toFixed(2) }}</td>
            </tr>
            <tr class="text-center text-gray-700 dark:text-gray-400">
              <td colspan="3" class="px-4 py-3 text-sm break-all"></td>
              <td colspan="" class="px-4 py-3 text-sm font-bold break-all">Debit Note (Adv.)</td>
              <td class="px-4 py-3 text-sm break-all">
                <input type="number" step=".01" style="background-color: #d3d1d1" readonly placeholder="Debit note" v-model="formParams.debit_note" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              </td>
            </tr>
            <tr class="text-center text-gray-700 dark:text-gray-400">
              <td colspan="3" class="px-4 py-3 text-sm break-all"></td>
              <td colspan="" class="px-4 py-3 text-sm font-bold break-all">Adjustment</td>
              <td class="px-4 py-3 text-sm break-all">
                <input type="number" step=".01" autocomplete="off" placeholder="Discount amount" v-model="formParams.discount" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              </td>
            </tr>
            <tr class="text-center text-gray-700 dark:text-gray-400">
              <td colspan="3" class="px-4 py-3 text-sm break-all"></td>
              <td colspan="" class="px-4 py-3 text-sm font-bold break-all">Total</td>
              <td class="px-4 py-3 text-sm break-all">
                <input type="number" step=".01" style="background-color: #d3d1d1" readonly v-model="formParams.total" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              </td>
            </tr>
            <tr class="text-center text-gray-700 dark:text-gray-400">
              <td colspan="3" class="px-4 py-3 text-sm break-all"> Exchange Rate : <strong> {{voyageInfo?.invoiceHeads?.exchange_rate}} </strong> </td>
              <td colspan="" class="px-4 py-3 text-sm font-bold break-all">Equivalent In BDT </td>
              <td class="px-4 py-3 text-sm break-all">
                <input type="number" step=".01" style="background-color: #d3d1d1" readonly v-model="formParams.equivalent_in_bdt" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              </td>
            </tr>
          </tbody>
        </table>
        <table class="w-full mb-5 whitespace-no-wrap border-collapse contract-assign-table">
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm font-bold break-all">Billing Party</td>
            <td colspan="3" class="px-4 py-3 text-sm break-all">
              <input type="text" placeholder="Billing Party" v-model="formParams.billing_party"
                     class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
          </tr>
          <tr class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm font-bold break-all">Billing Address</td>
            <td colspan="3" class="px-4 py-3 text-sm break-all">
              <input type="text" placeholder="Billing Address" v-model="formParams.billing_address"
                     class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
          </tr>
          <tr class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm font-bold break-all">Billing Emails</td>
            <td colspan="3" class="px-4 py-3 text-sm break-all">
              <input type="text" placeholder="Billing Emails" v-model="formParams.billing_emails"
                     class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
          </tr>
          <tr class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm font-bold break-all">Billing Style</td>
            <td colspan="3" class="px-4 py-3 text-sm break-all">
              <input type="text" placeholder="Billing Style" v-model="formParams.billing_style"
                     class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
          </tr>
            <tr class="text-center text-gray-700 dark:text-gray-400">
              <td class="px-4 py-3 text-sm font-bold break-all">WO/PO/Cont. Ref</td>
              <td class="px-4 py-3 text-sm break-all">
                <input type="text" placeholder="WO/PO/Cont. Ref" v-model="formParams.customer_reference"
                  class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              </td>
              <td class="px-4 py-3 text-sm font-bold break-all">HRL Reference</td>
              <td class="px-4 py-3 text-sm break-all">
                <input type="text" placeholder="HRL reference" v-model="formParams.hrl_reference"
                  class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              </td>
            </tr>
            <tr class="text-center text-gray-700 dark:text-gray-400">
              <td class="px-4 py-3 text-sm font-bold break-all">Note To Customer</td>
              <td colspan="3" class="px-4 py-3 text-sm break-all">
                <textarea type="text" placeholder="note to customer" v-model="formParams.customer_note"
                  class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
              </td>
            </tr>
            <tr class="text-center text-gray-700 dark:text-gray-400">
              <td class="px-4 py-3 text-sm font-bold break-all">HRL Internal Note</td>
              <td colspan="3" class="px-4 py-3 text-sm break-all">
                <textarea type="text" placeholder="HRL internal note" v-model="formParams.hrl_internal_note"
                  class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
              </td>
            </tr>
            <input type="hidden" v-model="formParams.rate_type">
            <input type="hidden" v-model="formParams.sector">
          </tbody>
        </table>
        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800">
          <button type="button" @click="closeFinalInvoiceModal('cancel')" style="color: #1b1e21"
            class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            Cancel
          </button>
          <button @click="closeFinalInvoiceModal('submit')"
            class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Submit
          </button>
        </footer>
      </div>
    </form>
  </div>

  <div v-show="isVoyageExchangeRateModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="submitExchangeRateForm(formParams)" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700"
                  aria-label="close" @click="closeVoyageExchangeRateModal">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
              <path
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
          </button>
        </header>
        <!-- Modal body -->
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead v-once>
          <tr style="background-color: #04AA6D;color: white"
              class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th colspan="5">Exchange Rate</th>
          </tr>
          </thead>
        </table>

        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
         <tr class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm font-bold break-all">Exchange Rate</td>
            <td colspan="3" class="px-4 py-3 text-sm break-all">
              <input type="text" placeholder="Exchange Rate" v-model="formParams.exchange_rate"
                     class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" required>
            </td>
          </tr>
          </tbody>
        </table>
        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800">
          <button type="button" @click="closeVoyageExchangeRateModal" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            Cancel
          </button>
          <button
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Submit
          </button>
        </footer>
      </div>
    </form>
  </div>
<!--  <div v-show="isFinalInvoiceAlertModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">-->
<!--    <p>szfdsfd</p>-->
<!--  </div>-->
</template>
<style lang="postcss" scoped>
@tailwind components;

#modal {
  max-width: 60rem;
}

.custom-disabled {
  pointer-events: none;
  cursor: default;
  opacity: 0.5;
}

table th,
tr,
td {
  border: 1px solid #ccc;
}

.table2 td {
  text-align: left;
}

tfoot td {
  @apply text-center
}

@layer components {
  th {
    @apply p-10 border-r text-center;
  }

  tbody td,
  thead td,
  thead th {
    @apply px-1 py-1 text-xs border-r text-center;
  }
}
.custom-disabled{
  pointer-events: none;
  cursor: default;
  opacity: 0.5;
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
