<script setup>
import {ref, watch, onMounted} from 'vue';
import { useRoute } from 'vue-router';
import useCustomer from "../../../composables/commercial/useCustomer";
import useVoyage from "../../../composables/operation/useVoyage";
import useInvoice from "../../../composables/commercial/useInvoice";
import moment from 'moment';
import useVoyageSlotUsesStatementReport from "../../../composables/commercial/useVoyageSlotUsesStatementReport";
import Title from "../../../services/title";
import useNotification from "../../../composables/useNotification";
import usePort from "../../../composables/usePort";
import useTariff from "../../../composables/stevedorage/useTariff";
import Swal from "sweetalert2";

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


const { setTitle } = Title();

setTitle('Tariff Invoice');

const { voyagePorts, getPortsByVoyage } = usePort();

const { customers, getCustomers } = useCustomer();
const { voyages, voyageName, voyageCustomers, getVoyageCustomer, voyage, voyageExchangeRate, exchangeRate, showVoyage, getVoyageName } = useVoyage();
const { formParams, tariffCustomers, tariffInvoiceCustomers, generateInvoice, invoiceData, isInvoiceModalOpen, isLoading } = useTariff();

function fetchOptions(search) {
  getVoyageName(search);
}

watch(() => formParams.value.voyage_id, (newValue, oldValue) => {
  getPortsByVoyage(newValue);
});

// function fetchPortOptions(search, loading) {
//   getPortsByNameOrCode(search);
// }

function openInvoiceModal(assignTariffId) {
  //let formData = {'assigned_tariff_id': assignTariffId};
  formParams.value.assigned_tariff_id = assignTariffId;
  generateInvoice(formParams.value);
}

function closeInvoiceModal() {
  isInvoiceModalOpen.value = false;
}

watch(() => invoiceData.value, (newValue, oldValue) => {
  if (newValue) {
    formParams.value.billing_party = newValue.billing_party;
    formParams.value.billing_address = newValue.billing_address;
    formParams.value.billing_emails = newValue.billing_emails;
    formParams.value.billing_style = newValue.billing_style;
    formParams.value.customer_reference = newValue.customer_reference;
    formParams.value.hrl_reference = newValue.hrl_reference;
    formParams.value.customer_note = newValue.customer_note;
    formParams.value.hrl_internal_note = newValue.hrl_internal_note;
  }
});

function submitInvoiceForm() {
  isInvoiceModalOpen.value = true;
  Swal.fire({
    title: '',
    text: 'Are you sure to generate invoice?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      formParams.value.request_for = 'store-invoice';
      generateInvoice(formParams.value);
    }
  })
}

</script>
<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Generate Tariff Invoice</h2>
  </div>
  <!-- Table -->
  <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <form class="flex items-end justify-between gap-4" @submit.prevent="tariffInvoiceCustomers(formParams)">
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Voyage No</span>
        <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchOptions"
          v-model="formParams.voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required></v-select>
      </label>
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Port</span>
        <v-select v-model="formParams.port_code" value="id" :options="voyagePorts" :reduce="voyagePorts => voyagePorts.code" label="code_name" placeholder="Port code" class="w-full mt-1 placeholder-gray-600"></v-select>
      </label>
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Operation Type</span>
        <select v-model="formParams.operation_type" required class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="" disabled selected>--Choose Option--</option>
          <option value="loading">Loading</option>
          <option value="discharge">Discharge</option>
        </select>
      </label>
      <button type="submit" :disabled="isLoading"
        class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
    </form>
  </div>

  <h4 class="mt-2 font-bold text-left text-gray-700 dark:text-gray-200">
    Customer List
<!--    <span class="float-right">-->
<!--      <button type="submit" v-if="Object.keys(invoiceCustomerList).length" @click="voyageChangeExchangeRate" style="background-color: #3f3f46"  class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 border border-transparent rounded-lg fon2t-medium focus:outline-none">Exchange Rate : {{ voyage?.exchange_rate ?? 0.0 }}</button>-->
<!--      <button type="submit" v-else style="background-color: #3f3f46"  class="flex opacity-50 cursor-not-allowed items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 border border-transparent rounded-lg fon2t-medium focus:outline-none">Exchange Rate : 0.00</button>-->
<!--    </span>-->
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
          <tr style="background-color: #04AA6D;color: white">
            <th>Customer Name</th>
            <th>TTL Box</th>
            <th>Invoice Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
              <tr v-for="(customer,index) in tariffCustomers" :key="index">
                <td>{{ customer?.customer?.customer_name ?? '---' }}</td>
                <td>{{ customer?.ttl_container }}</td>
                <td>
                 {{ customer?.is_invoice_generated ? 'Yes' : 'No' }}
                </td>
                <td>
                  <button v-if="!customer?.is_invoice_generated"
                          @click="openInvoiceModal(customer.id)"
                          class="inline-flex items-center px-3 py-1 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-purple-500 rounded readonly hover:bg-purple-600 focus:outline-none focus:shadow-outline-purple focus:border-purple-300 active:bg-purple-700">
                    Generate Invoice
                  </button>
                  <button v-else title="Invoice Already Generated" class="inline-flex items-center px-3 py-1 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-purple-500 rounded opacity-50 cursor-not-allowed hover:bg-purple-600 focus:outline-none focus:shadow-outline-purple focus:border-purple-300 active:bg-purple-700">
                    Generate Invoice
                  </button>
                </td>
              </tr>
        </tbody>
        <tfoot v-if="!tariffCustomers?.length" class="bg-white dark:bg-gray-800">
          <tr v-if="isLoading">
            <td colspan="4">Loading...</td>
          </tr>
          <tr v-else-if="!tariffCustomers?.length">
            <td colspan="4">No customer found.</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
  <div v-show="isInvoiceModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="submitInvoiceForm" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
            class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700"
            aria-label="close" @click="closeInvoiceModal('cancel')">
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
              <th colspan="5">Generate Invoice</th>
            </tr>
          </thead>
        </table>
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">

            <tr class="text-xs font-bold uppercase bg-yellow-300">
              <td colspan="4" style="text-align: center">Other Information</td>
            </tr>
            <tr class="text-center text-gray-700 dark:text-gray-400">
              <td class="px-4 py-3 text-sm font-bold break-all">Issue Date</td>
              <td class="px-4 py-3 text-sm break-all">{{ invoiceData?.invoice_headers?.issue_date }}</td>
              <td class="px-4 py-3 text-sm font-bold break-all">Service Code</td>
              <td class="px-4 py-3 text-sm break-all">{{ invoiceData?.invoice_headers?.service_code }}</td>
            </tr>
            <tr class="text-center text-gray-700 dark:text-gray-400">
              <td class="px-4 py-3 text-sm font-bold break-all">Voyage No</td>
              <td class="px-4 py-3 text-sm break-all">{{ invoiceData?.invoice_headers?.voyage_no }}</td>
              <td class="px-4 py-3 text-sm font-bold break-all">Vessel Name</td>
              <td class="px-4 py-3 text-sm break-all">{{ invoiceData?.invoice_headers?.vessel_name }}</td>
            </tr>
            <tr class="text-center text-gray-700 dark:text-gray-400">
              <td class="px-4 py-3 text-sm font-bold break-all">Customer Name(Code)</td>
              <td class="px-4 py-3 text-sm break-all">{{ invoiceData?.invoice_headers?.customer_name }} ({{
                  invoiceData?.invoice_headers?.customer_code
              }})</td>
              <td class="px-4 py-3 text-sm font-bold break-all">Currency</td>
              <td class="px-4 py-3 text-sm break-all">{{ invoiceData?.invoice_headers?.currency }}</td>
            </tr>
          </tbody>
        </table>
        <table class="w-full mb-5 whitespace-no-wrap border-collapse contract-assign-table">
          <thead v-once>
            <tr style="background-color: #04AA6D;color: white"
              class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th>Operation Type</th>
              <th>Description</th>
              <th>Per</th>
              <th>Rate</th>
              <th>Rated As <br>(Quantity)</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <template v-for="(operationType, operationTypeKey, operationTypeIndex) in invoiceData?.invoice_lines">
            <tr v-for="(particular, particularIndex) in operationType" class="text-center text-gray-700 dark:text-gray-400">
              <td class="font-bold" :rowspan="operationType.length" v-if="particularIndex===0">{{operationTypeKey}}</td>
              <td class="px-4 py-3 text-sm break-all">{{ particular?.label }}</td>
              <td class="px-4 py-3 text-sm break-all">{{ particular?.size }}</td>
              <td class="px-4 py-3 text-sm break-all" style="text-align: right">{{ particular?.rate }}</td>
              <td class="px-4 py-3 text-sm break-all">{{ particular?.quantity }}</td>
              <td class="px-4 py-3 text-sm break-all" style="text-align: right">{{ Number(particular?.amount).toFixed(2) }}</td>
            </tr>
          </template>
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
          <button type="button" @click="closeInvoiceModal('cancel')" style="color: #1b1e21"
            class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            Cancel
          </button>
          <button @click="closeInvoiceModal('submit')"
            class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Submit
          </button>
        </footer>
      </div>
    </form>
  </div>

<!--  <div v-show="isVoyageExchangeRateModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">-->
<!--    &lt;!&ndash; Modal &ndash;&gt;-->
<!--    <form @submit.prevent="submitExchangeRateForm(formParams)" style="position: absolute;top: 0;">-->
<!--      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">-->
<!--        &lt;!&ndash; Remove header if you don't want a close icon. Use modal body to place modal tile. &ndash;&gt;-->
<!--        <header class="flex justify-end">-->
<!--          <button type="button"-->
<!--                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700"-->
<!--                  aria-label="close" @click="closeVoyageExchangeRateModal">-->
<!--            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">-->
<!--              <path-->
<!--                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"-->
<!--                  clip-rule="evenodd" fill-rule="evenodd"></path>-->
<!--            </svg>-->
<!--          </button>-->
<!--        </header>-->
<!--        &lt;!&ndash; Modal body &ndash;&gt;-->
<!--        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">-->
<!--          <thead v-once>-->
<!--          <tr style="background-color: #04AA6D;color: white"-->
<!--              class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">-->
<!--            <th colspan="5">Exchange Rate</th>-->
<!--          </tr>-->
<!--          </thead>-->
<!--        </table>-->

<!--        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">-->
<!--          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">-->
<!--         <tr class="text-center text-gray-700 dark:text-gray-400">-->
<!--            <td class="px-4 py-3 text-sm font-bold break-all">Exchange Rate</td>-->
<!--            <td colspan="3" class="px-4 py-3 text-sm break-all">-->
<!--              <input type="text" placeholder="Exchange Rate" v-model="formParams.exchange_rate"-->
<!--                     class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" required>-->
<!--            </td>-->
<!--          </tr>-->
<!--          </tbody>-->
<!--        </table>-->
<!--        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800">-->
<!--          <button type="button" @click="closeVoyageExchangeRateModal" style="color: #1b1e21"-->
<!--                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">-->
<!--            Cancel-->
<!--          </button>-->
<!--          <button-->
<!--                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">-->
<!--            Submit-->
<!--          </button>-->
<!--        </footer>-->
<!--      </div>-->
<!--    </form>-->
<!--  </div>-->

</template>
<style lang="postcss" scoped>
@tailwind components;

#modal {
  max-width: 70rem;
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
