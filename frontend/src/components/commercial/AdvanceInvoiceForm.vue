<script setup>
  import {onMounted} from "@vue/runtime-core";
  import useVoyage from "../../composables/operation/useVoyage";
  import useCustomer from "../../composables/commercial/useCustomer";
  import {computed, ref, watch, watchEffect} from "vue";
  import useService from "../../composables/commercial/useService";
  import useInvoice from "../../composables/commercial/useInvoice";

  const props = defineProps({
    form: {
      required: false,
      default: {}
    },
    page: {
      required: false,
      default: {},
    }
  });

  const { customers, getCustomerWithoutPaginate } = useCustomer();
  const { services, getServices } = useService();
  const { customer, getCustomerDetails } = useInvoice();

  let isInvoiceModalOpen = ref(false);

  function closeInvoiceModal() {
    isInvoiceModalOpen.value = false;
  }

  function openInvoiceModal() {
    isInvoiceModalOpen.value = true;
  }

  function selectContract(contract) {
    //assign contract value in v model contract
    props.form.contract_id = contract?.id;
    props.form.contract_no = contract?.contract_no;
    isInvoiceModalOpen.value = false;
  }

  const bounds = { 'east': 'east', 'west': 'west', 'north': 'north', 'south': 'south' };

  function addRow() {
    let obj = {
      id: Math.random(),
      description: '',
      per: '',
      rate: '',
      quantity: '',
      sub_amount: 0.0,
      amount: 0.0,
    };
    props.form.invoice_lines.push(obj);
  }

  function removeRow(index){
    props.form.invoice_lines.splice(index, 1);
  }

  function changeAmount() {
    let subTotal = parseFloat(props.form.sub_total) ?? 0;
    let vat = props.form.vat !== '' ? subTotal * (parseFloat(props.form.vat) / 100) : 0;
    let ait = props.form.ait !== '' ? subTotal * (parseFloat(props.form.ait) / 100) : 0;
    props.form.amount = parseFloat(subTotal + vat + ait).toFixed(2) ?? 0;
    props.form.total = parseFloat(subTotal + vat + ait).toFixed(2) ?? 0;
  }

  watch(() => props.form.customer_id, () => {
    getCustomerDetails(props.form.customer_id);
  });

  watch(() => props.form.invoice_lines, () => {
    if(props.form.invoice_lines?.length) {
      let total = 0;
      props.form.invoice_lines.forEach((line, index) => {
        let rate = props.form.invoice_lines[index].rate ?? 1;
        let quantity = props.form.invoice_lines[index].quantity ?? 1;
        props.form.invoice_lines[index].amount = (rate * quantity).toFixed(2) ?? 0;
        total += parseFloat(props.form.invoice_lines[index].amount);
      });
      props.form.sub_total = total.toFixed(2) ?? 0;
      changeAmount();
    }
  }, { deep: true });

  watch(() => props.form.ait, () => {
    changeAmount();
  }, { deep: true });

  watch(() => props.form.vat, () => {
    changeAmount();
  }, { deep: true });

  onMounted(() => {
    getServices();
    getCustomerWithoutPaginate();
    props.form.type = 'Advance';
  });
</script>

<template>
    <!-- Basic information -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Basic Info</legend>
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Issue Date <span class="custom_required_field">*</span></span>
        <input type="date" v-model="form.issue_date" class="w-full text-sm rounded form-input" required>
        <input type="hidden" v-model="form.type">
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Service <span class="custom_required_field">*</span></span>
        <select class="w-full text-sm rounded form-input" v-model="form.service_id" required>
          <option value="" selected disabled>Select Service</option>
          <option v-for="(service,index) in services" :value="service?.id">{{ service?.code }}</option>
        </select>
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Currency <span class="custom_required_field">*</span></span>
        <select v-model="form.currency" readonly class="w-full text-sm rounded form-input" required>
          <option value="USD">USD</option>
        </select>
      </label>
    </div>
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Customer Code <span class="custom_required_field">*</span></span>
        <v-select :options="customers" placeholder="--Choose an option--" v-model="form.customer_id" :reduce="customer => customer.id" label="customer_code" required></v-select>
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Customer Name</span>
        <input type="text" :value="customer?.customer_name" placeholder="Customer name" readonly class="w-full text-sm rounded form-input vms-readonly-input">
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Contract <span class="custom_required_field">*</span></span>
        <input type="text" v-model="form.contract_no" @focus="openInvoiceModal" placeholder="Customer contract" class="w-full text-sm rounded form-input">
        <input type="hidden" v-model="form.contract_id">
      </label>
    </div>
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">WO/PO/Cont. Ref</span>
        <input type="text" v-model="form.customer_reference" class="w-full text-sm rounded form-input">
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Note To Customer</span>
        <input type="text" v-model="form.customer_note" class="w-full text-sm rounded form-input">
      </label>
    </div>
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">HRL Reference</span>
        <input type="text" v-model="form.hrl_reference" class="w-full text-sm rounded form-input">
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">HRL Internal Note</span>
        <input type="text" v-model="form.hrl_internal_note" class="w-full text-sm rounded form-input">
      </label>
    </div>
  </fieldset>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Invoice Details <span class="custom_required_field">*</span></legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 uppercase align-bottom">SL.</th>
        <th class="px-4 py-3 uppercase align-bottom">Description</th>
        <th class="px-4 py-3 uppercase align-bottom">Per</th>
        <th class="px-4 py-3 uppercase align-bottom">Rate</th>
        <th class="px-4 py-3 uppercase align-bottom">Rated As Quantity</th>
        <th class="px-4 py-3 uppercase align-bottom">Amount</th>
        <th class="px-4 py-3 text-center uppercase align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(invoiceLine, index) in form.invoice_lines" :key="invoiceLine.id">
        <td class="px-1 py-1" style="width: 6%">
          <input type="text" required :value="index+1" disabled class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1" style="width: 30%">
          <input type="text" required placeholder="Description" v-model="form.invoice_lines[index].description" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1" style="width: 12%">
          <select v-model="form.invoice_lines[index].per" required class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" selected disabled>Select</option>
            <option value="tue">Tue</option>
            <option value="unit">Unit</option>
          </select>
        </td>
        <td class="px-1 py-1" style="width: 15%">
          <input type="number" v-model="form.invoice_lines[index].rate" required placeholder="Rate" step=".01" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1" style="width: 20%">
          <input type="number" v-model="form.invoice_lines[index].quantity" placeholder="Exchange Rate" step=".01" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1" style="width: 20%">
          <input type="number" readonly step=".01" v-model="form.invoice_lines[index].amount" class="block w-full bg-gray-300 rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1 text-center">
          <button v-if="index!=0" type="button" @click="removeRow(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <button v-else type="button" @click="addRow()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </td>
      </tr>
      </tbody>
      <tfoot style="background-color: #e5d5e8">
      <tr>
        <td colspan="4"></td>
        <td><strong>VAT(%)</strong></td>
        <td class="px-1 py-1" style="width: 20%">
          <input type="number" placeholder="Vat Percentage" step=".01" v-model="form.vat" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
      </tr>
      <tr>
        <td colspan="4"></td>
        <td><strong>AIT(%)</strong></td>
        <td class="px-1 py-1" style="width: 20%">
          <input type="number" placeholder="Ait Percentage" step=".01" v-model="form.ait" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
      </tr>
      <tr>
        <td colspan="4"></td>
        <td colspan="1"><strong>TOTAL</strong></td>
        <td class="px-1 py-1" style="width: 20%">
          <input type="number" style="background-color: darkgray" readonly v-model="form.amount" step="0.01" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <input type="hidden" v-model="form.sub_total">
          <input type="hidden" v-model="form.total">
        </td>
      </tr>
      </tfoot>
    </table>
  </fieldset>

  <div v-show="isInvoiceModalOpen" class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <div class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
      <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
      <header class="flex justify-end">
        <button type="button" class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700" aria-label="close" @click="closeInvoiceModal">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
            <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
          </svg>
        </button>
      </header>
      <!-- Modal body -->
      <table class="w-full mb-5 whitespace-no-wrap border-collapse contract-assign-table table2">
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th colspan="5" class=""></th>
          <th colspan="2" class="">Validity</th>
          <th rowspan="2" style="text-align: center">Short Note</th>
          <th rowspan="2" style="text-align: center">Action</th>
        </tr>
        <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="">CS Code</th>
          <th class="">Name</th>
          <th class="">Lane</th>
          <th class="">Contract Group</th>
          <th class="" style="text-align: center">Per</th>
          <th class="" style="text-align: center">From</th>
          <th class="" style="text-align: center">Till</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr v-for="(customerContract,index) in customer?.contracts">
          <td>{{ customer?.customer_code }}</td>
          <td>{{ customer?.customer_name }}</td>
          <td>{{ customerContract?.service?.code }}</td>
          <td>{{ customerContract?.contract_type }}</td>
          <td>Per Tue</td>
          <td>{{ customerContract?.rate_validity_from }}</td>
          <td>{{ customerContract?.rate_validity_till }}</td>
          <td>{{ customerContract?.short_note ?? "---" }}</td>
          <td>
            <div class="flex items-center justify-center">
              <router-link :to="{ name: 'commercial.contracts.show', params: { contractId: customerContract.id } }" >
                <button @click="openInvoiceModal" title="Contract Details" class="px-2 my-1 py-0.5 text-sm leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  Details
                </button>
              </router-link>
              <button type="button" @click="selectContract(customerContract)" title="Add contract" class="ml-2 px-2 py-0.5 my-1 text-sm leading-5 text-white transition-colors duration-150 bg-green-700 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Add
              </button>
            </div>
          </td>
        </tr>
        </tbody>
        <tfoot v-if="!customer?.contracts?.length" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="9">Loading...</td>
        </tr>
        <tr v-else-if="!customer?.contracts?.length">
          <td colspan="9">No contract found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
  </div>

</template>

<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
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
#modal{
  max-width: 60rem;
}

.contract-assign-table th {
  font-size: 10px;
}

.contract-assign-table th, .contract-assign-table tr,.contract-assign-table td {
  text-align: center;
  font-size: 12px;
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
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