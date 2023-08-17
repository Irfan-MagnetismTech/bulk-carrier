<script setup>
import { onMounted } from '@vue/runtime-core';
import useVoyage from '../../../composables/operation/useVoyage';
import {ref,watchEffect} from "vue";
import moment from 'moment';
import useInvoice from "../../../composables/commercial/useInvoice";
import Title from "../../../services/title";
import useHelper from "../../../composables/useHelper";
import Paginate from '../../../components/utils/paginate.vue';
import useService from "../../../composables/commercial/useService";
import useCustomer from "../../../composables/commercial/useCustomer";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useContract from "../../../composables/commercial/useContract";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { voyages, voyageName, isLoading, getVoyageName } = useVoyage();
const { customerCodeName, getCustomerByNameOrCode } = useCustomer();
const { services, getServices } = useService();
const { contractNo, getContractByContractNo } = useContract();
const { invoices, formParams, invoice, showInvoice, updateInvoice, getInvoices, invoiceNo, getInvoiceByInvoiceNo, downloadInvoice, sendInvoice } = useInvoice();
const $helper = new useHelper();

const mailStatus = ref([{mailing_status: 'pending'}, {mailing_status: 'sent'}, {mailing_status: 'failed'}]);

const isFinalInvoiceUpdateModalOpen = ref(false);

const searchParams = ref({
  invoice_data: null,
  invoice_number: '',
  contract_data: null,
  contract_no: '',
  voyage_no: null,
  voyage_id: '',
  service_data: null,
  service_id: '',
  customer_codes_name: null,
  customer_id: '',
  mailing_data: null,
  mailing_status: null,
});

function clearFormData(){
  searchParams.value = {
    invoice_data: null,
    invoice_number: '',
    contract_data: null,
    contract_no: '',
    voyage_no: null,
    voyage_id: '',
    service_data: null,
    service_id: '',
    customer_codes_name: null,
    customer_id: '',
    mailing_data: null,
    mailing_status: null,
  };
}


const { setTitle } = Title();
setTitle('Invoice List');

const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  openTab.value = tabNumber;
}

function downloadCustomerInvoice(invoiceId) {
  downloadInvoice(invoiceId);
}

function sendCustomerInvoice(invoiceId) {
  sendInvoice(invoiceId);
}

function showCustomerInvoice(invoiceId) {
  showInvoice(invoiceId);
  isFinalInvoiceUpdateModalOpen.value = true;
}

function closeInvoiceUpdateModal(buttonType){
  isFinalInvoiceUpdateModalOpen.value = false;
}

function fetchVoyages(search, loading) {
  getVoyageName(search);
}

function fetchInvoiceByNo(search, loading) {
  getInvoiceByInvoiceNo(search);
}

function fetchContractByNo(search, loading){
  getContractByContractNo(search);
}

function fetchCustomer(search, loading){
  getCustomerByNameOrCode(search);
}

function updateInvoiceForm(invoice){
  updateInvoice(invoice);
}

onMounted(() => {
  //getVoyageName();
  watchEffect(() => {
    if(searchParams.value.invoice_data){
      searchParams.value.invoice_number = searchParams.value.invoice_data.invoice_number;
    }
    if(searchParams.value.contract_data){
      searchParams.value.contract_no = searchParams.value.contract_data.id;
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
    getInvoices(props.page, searchParams.value);
    getServices();
  });
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Invoice List</h2>
<!--      <router-link :to="{ name: 'commercial.invoice.create' }" title="Create Invoice" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">-->
<!--        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">-->
<!--          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />-->
<!--        </svg>-->
<!--      </router-link>-->
    </div>
  <div class="w-full flex items-center justify-between mb-2 select-none">
    <fieldset class="w-full grid grid-cols-6 gap-1 px-2 pb-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Search Invoice</legend>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Invoice No</label>
        <v-select :options="invoiceNo" placeholder="--Choose an option--" @search="fetchInvoiceByNo" v-model="searchParams.invoice_data" label="invoice_number" required></v-select>
        <input type="hidden" v-model="searchParams.invoice_number">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Contract No</label>
        <v-select :options="contractNo" placeholder="--Choose an option--" @search="fetchContractByNo" v-model="searchParams.contract_data" label="contract_no" required></v-select>
        <input type="hidden" v-model="searchParams.contract_no">
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
        <label for="" class="text-xs" style="margin-left: .01rem">Mail Status</label>
        <v-select placeholder="--Choose an option--" :options="mailStatus" v-model="searchParams.mailing_data" label="mailing_status"></v-select>
        <input type="hidden" v-model="searchParams.mailing_status">
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
    </fieldset>
  </div>

<!--  <div class="px-2 py-2 mb-5 bg-white rounded-lg shadow-md dark:bg-gray-800">-->
<!--    <form @submit.prevent="getInvoices(formParams)" class="flex items-end justify-between gap-4">-->
<!--      &lt;!&ndash; Booking Form &ndash;&gt;-->
<!--      <label class="block w-full text-sm">-->
<!--        <span class="text-gray-700 dark:text-gray-300">Voyage No</span>-->
<!--        <v-select :options="voyages" placeholder="&#45;&#45;Choose an option&#45;&#45;" v-model="formParams.voyage_id" :reduce="voyage => voyage.id" label="voyage" required></v-select>-->
<!--      </label>-->
<!--      <label class="block w-full text-sm">-->
<!--        <span class="text-gray-700 dark:text-gray-300">Type</span>-->
<!--        <select v-model="formParams.type" id="" class="block w-full mt-1 text-sm rounded dark:text-gray-300 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--          <option value="" selected disabled>Select</option>-->
<!--          <option value="advance">Advance</option>-->
<!--          <option value="final">Final</option>-->
<!--        </select>-->
<!--      </label>-->
<!--      &lt;!&ndash; Submit button &ndash;&gt;-->
<!--      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>-->
<!--    </form>-->
<!--  </div>-->
  <div class="">
    <div class="w-full overflow-hidden">
      <div class="w-full overflow-x-auto">
        <table class="w-full mb-10 whitespace-no-wrap">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">Sl</th>
            <th class="px-4 py-3" data-title="Invoice No">In. No.</th>
            <th class="px-4 py-3">Voyage</th>
            <th class="px-4 py-3" data-title="Customer Id">CID</th>
            <th class="px-4 py-3" data-title="Customer Name">C. Name</th>
            <th class="px-4 py-3">Service</th>
            <th class="px-4 py-3" data-title="Currency">Curr.</th>
            <th class="px-4 py-3">Amount</th>
            <th class="px-4 py-3">Action</th>
            <th class="px-4 py-3">Mail Status</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
            <tr v-for="(invoice, index) in (invoices?.data ? invoices?.data : invoices)" :key="invoice.id" class="text-center text-gray-700 dark:text-gray-400">
              <td class="px-4 py-3 text-sm">{{ (invoices?.from) ? invoices?.from + index : index+1 }}</td>
              <td class="px-4 py-3 text-sm">{{ invoice?.invoice_number }}</td>
              <td class="px-4 py-3 text-sm">{{ invoice?.voyage?.voyage }}</td>
              <td class="px-4 py-3 text-sm">{{ invoice?.customer?.customer_code }}</td>
              <td class="px-4 py-3 text-sm">{{ invoice?.customer?.customer_name }}</td>
              <td class="px-4 py-3 text-sm">{{ invoice?.service?.code }}</td>
              <td class="px-4 py-3 text-sm">{{ invoice?.currency }}</td>
              <td class="px-4 py-3 text-sm">{{ $helper.numberFormat(invoice?.total) ?? '0.00' }}</td>
              <td class="flex items-center justify-center px-4 py-3 space-x-2 text-sm text-center text-gray-600">
                <!-- edit invoice -->
                <a @click="showCustomerInvoice(invoice?.id)" title="Edit Invoice" style="cursor: pointer">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                  </svg>
                </a>
                <a title="Send Mail">
                  <svg @click="sendCustomerInvoice(invoice?.id)" class="w-6 h-6 cursor-pointer" viewBox="0 0 32 32"><path fill="currentColor" d="M27.71 4.29a1 1 0 0 0-1.05-.23l-22 8a1 1 0 0 0 0 1.87l9.6 3.84l3.84 9.6a1 1 0 0 0 .9.63a1 1 0 0 0 .92-.66l8-22a1 1 0 0 0-.21-1.05ZM19 24.2l-2.79-7L21 12.41L19.59 11l-4.83 4.83L7.8 13l17.53-6.33Z"/></svg>
                </a>
                <a title="Download Invoice">
                  <svg @click="downloadCustomerInvoice(invoice?.id)" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                  </svg>
                </a>
                <button v-if="invoice?.type == 'Final'" type="button" title="Add Debit or Credit Note" class="flex items-center justify-between px-1 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-sm active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <router-link :to="{ name: 'commercial.invoice.add.debit.credit.note', params: { invoiceId: invoice.id } }" title="Add Debit or Credit Note" class="flex items-center justify-between text-sm font-medium text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Add Note</span>
                  </router-link>
                </button>

<!--                <button type="button" title="Credit Note" class="flex items-center justify-between px-1 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-sm active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">-->
<!--                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">-->
<!--                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />-->
<!--                  </svg>-->
<!--                  <span>CR</span>-->
<!--                </button>-->
              </td>
              <td>
                <span v-if="invoice?.mailing_status === 'pending'" class="text-xs inline-block py-1 px-2.5 leading-none text-center whitespace-nowrap align-baseline font-bold bg-yellow-500 text-white rounded-lg">{{ invoice?.mailing_status.toUpperCase() }}</span>
                <span style="min-width: 75px" v-if="invoice?.mailing_status === 'sent'" class="text-xs inline-block py-1 px-2.5 leading-none text-center whitespace-nowrap align-baseline font-bold bg-green-500 text-white rounded-lg">{{ invoice?.mailing_status.toUpperCase() }}</span>
                <span style="min-width: 75px" v-if="invoice?.mailing_status === 'failed'" class="text-xs inline-block py-1 px-2.5 leading-none text-center whitespace-nowrap align-baseline font-bold bg-red-600 text-white rounded-lg">{{ invoice?.mailing_status.toUpperCase() }}</span>

              </td>
            </tr>
          </tbody>
          <tfoot v-if="!(invoices?.data ? invoices?.data?.length : invoices?.length)" class="bg-white dark:bg-gray-800">
          <tr v-if="isLoading">
            <td colspan="10">Loading...</td>
          </tr>
          <tr v-else-if="!(invoices?.data ? invoices?.data?.length : invoices?.length)">
            <td colspan="10">No invoice found.</td>
          </tr>
          </tfoot>
        </table>
      </div>
    </div>
    <Paginate :data="invoices" to="commercial.invoice.list" :page="page"></Paginate>
  </div>
  <div v-if="invoice?.amount" v-show="isFinalInvoiceUpdateModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="updateInvoiceForm(invoice)" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700"
                  aria-label="close" @click="closeInvoiceUpdateModal('cancel')">
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
            <th colspan="5">Update Invoice</th>
          </tr>
          </thead>
        </table>

        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">

          <tr class="text-xs font-bold uppercase bg-yellow-300">
            <td colspan="6" style="text-align: center">Basic Information</td>
          </tr>
          <tr class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm font-bold break-all">Invoice No.</td>
            <td class="px-4 py-3 text-sm break-all">{{ invoice?.invoice_number }}</td>
            <td class="px-4 py-3 text-sm font-bold break-all">Issue Date</td>
            <td class="px-4 py-3 text-sm break-all">{{ moment(invoice?.issue_date).format('DD-MM-YYYY') }}</td>
            <td class="px-4 py-3 text-sm font-bold break-all">Currency</td>
            <td class="px-4 py-3 text-sm break-all">{{ invoice?.currency}}</td>
          </tr>
          <tr class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm font-bold break-all">Voyage No.</td>
            <td class="px-4 py-3 text-sm break-all">{{ invoice?.voyage?.voyage }}</td>
            <td class="px-4 py-3 text-sm font-bold break-all">Customer Code</td>
            <td class="px-4 py-3 text-sm break-all">{{ invoice?.customer?.customer_code }}</td>
            <td class="px-4 py-3 text-sm font-bold break-all">Total Amount</td>
            <td class="px-4 py-3 text-sm break-all">{{ Number(invoice?.total).toFixed(2) }}</td>
          </tr>
          </tbody>
        </table>
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table">
          <thead v-once>
          <tr style="background-color: #04AA6D;color: white" v-once>
            <th colspan="5">Invoice Lines</th>
          </tr>
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
          <tr v-for="(invoiceLine, index) in invoice?.invoice_lines"
              class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm font-bold break-all">{{ invoiceLine?.description }}</td>
            <td class="px-4 py-3 text-sm break-all">{{ invoiceLine?.per }}</td>
            <td class="px-4 py-3 text-sm break-all" style="text-align: right">{{ invoiceLine?.rate }}</td>
            <td class="px-4 py-3 text-sm break-all">{{ invoiceLine?.quantity }}</td>
            <td class="px-4 py-3 text-sm break-all" style="text-align: right">{{ Number(invoiceLine?.amount).toFixed(2) }}</td>
          </tr>
          </tbody>
        </table>
        <table class="w-full mb-5 whitespace-no-wrap border-collapse contract-assign-table">
          <tbody style="background-color: #c9c8c8" class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm font-bold break-all">Billing Party</td>
            <td colspan="3" class="px-4 py-3 text-sm break-all">
              <input type="text" placeholder="Billing Party" v-model="invoice.billing_party"
                     class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
          </tr>
          <tr class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm font-bold break-all">Billing Address</td>
            <td colspan="3" class="px-4 py-3 text-sm break-all">
              <input type="text" placeholder="Billing Address" v-model="invoice.billing_address"
                     class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
          </tr>
          <tr class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm font-bold break-all">Billing Emails</td>
            <td colspan="3" class="px-4 py-3 text-sm break-all">
              <input type="text" placeholder="Billing Emails" v-model="invoice.billing_emails"
                     class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
          </tr>
          <tr class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm font-bold break-all">Billing Style</td>
            <td colspan="3" class="px-4 py-3 text-sm break-all">
              <input type="text" placeholder="Billing Style" v-model="invoice.billing_style"
                     class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
          </tr>
          <tr class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm font-bold break-all">WO/PO/Cont. Ref</td>
            <td class="px-4 py-3 text-sm break-all">
              <input type="text" placeholder="WO/PO/Cont. Ref" v-model="invoice.customer_reference"
                     class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
            <td class="px-4 py-3 text-sm font-bold break-all">HRL Reference</td>
            <td class="px-4 py-3 text-sm break-all">
              <input type="text" placeholder="HRL reference" v-model="invoice.hrl_reference"
                     class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            </td>
          </tr>
          <tr class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm font-bold break-all">Note To Customer</td>
            <td colspan="3" class="px-4 py-3 text-sm break-all">
                <textarea type="text" placeholder="note to customer" v-model="invoice.customer_note"
                          class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
            </td>
          </tr>
          <tr class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm font-bold break-all">HRL Internal Note</td>
            <td colspan="3" class="px-4 py-3 text-sm break-all">
                <textarea type="text" placeholder="HRL internal note" v-model="invoice.hrl_internal_note"
                          class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></textarea>
            </td>
          </tr>
          </tbody>
        </table>
        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800">
          <button type="button" @click="closeInvoiceUpdateModal('cancel')" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            Cancel
          </button>
          <button @click="closeInvoiceUpdateModal('submit')"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Submit
          </button>
        </footer>
      </div>
    </form>
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
  table, th,td{
    @apply border border-collapse;
  }

  #modal {
    max-width: 60rem;
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
}
</style>