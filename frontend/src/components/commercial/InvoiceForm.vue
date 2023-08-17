<script setup>
  import {onMounted} from "@vue/runtime-core";
  import useVoyage from "../../composables/operation/useVoyage";
  import useCustomer from "../../composables/commercial/useCustomer";
  import {computed, ref, watch, watchEffect} from "vue";
  import NProgress from "nprogress";

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
  const { voyages, getVoyageName } = useVoyage();
  const { customers, getCustomers } = useCustomer();


  const bounds = { 'east': 'east', 'west': 'west', 'north': 'north', 'south': 'south' };

  function addRow() {
    let obj = {
      id: Math.random(),
      invoice_id: '',
      charge_code: 'freight',
      description: '',
      currency: 'usd',
      sub_amount: '',
      exchange_rate: '',
      amount: 0.00,
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
 watchEffect(() => {
    if(props.form.invoice_lines?.length) {
      let total = 0;
      props.form.invoice_lines.forEach((line, index) => {
        let subAmount = props.form.invoice_lines[index].sub_amount ?? 1;
        let exchangeRate = props.form.invoice_lines[index].exchange_rate ?? 1;
        props.form.invoice_lines[index].amount = (subAmount * exchangeRate).toFixed(2) ?? 0;
        total += parseFloat(props.form.invoice_lines[index].amount);
      });
      props.form.sub_total = total.toFixed(2) ?? 0;
      changeAmount();
    }
  });

  onMounted(() => {
    getVoyageName();
    getCustomers(props.page, null);
  });
</script>

<template>
    <!-- Basic information -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Basic Info</legend>
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Voyage</span>
        <v-select :options="voyages" placeholder="--Choose an option--" v-model="form.voyage_id" :reduce="voyage => voyage.id" label="voyage" required></v-select>
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Customer</span>
        <v-select :options="customers" placeholder="--Choose an option--" v-model="form.customer_id" :reduce="customer => customer.id" label="customer_code" required></v-select>
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Invoice Type</span>
        <select v-model="form.type" class="w-full text-sm rounded form-input">
          <option value="" selected disabled>Select</option>
          <option value="advance">Advance</option>
          <option value="final">Final</option>
        </select>
      </label>
    </div>
    <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Bill Type</span>
        <select v-model="form.billType" class="w-full text-sm rounded form-input">
          <option value="" selected disabled>Select</option>
          <option value="freight">Freight</option>
        </select>
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Issue Date</span>
        <input type="date" v-model="form.issue_date" class="w-full text-sm rounded form-input">
      </label>
      <label class="block w-full mt-3 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Currency</span>
        <select v-model="form.currency" class="w-full text-sm rounded form-input">
          <option value="" selected disabled>Select</option>
          <option value="usd">USD</option>
          <option value="bdt">BDT</option>
        </select>
      </label>
    </div>
  </fieldset>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Invoice Details</legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-bottom uppercase">Description</th>
        <th class="px-4 py-3 align-bottom uppercase">Currency</th>
        <th class="px-4 py-3 align-bottom uppercase">Value</th>
        <th class="px-4 py-3 align-bottom uppercase">Ex. Rate</th>
        <th class="px-4 py-3 align-bottom uppercase">USD Amount</th>
        <th class="px-4 py-3 text-center align-bottom uppercase">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(invoiceLine, index) in form.invoice_lines" :key="invoiceLine.id">
        <td class="px-1 py-1" style="width: 20%">
          <input type="text" placeholder="Description" v-model="form.invoice_lines[index].description" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1" style="width: 20%">
          <select v-model="form.invoice_lines[index].currency" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <option value="" selected disabled>Select</option>
            <option value="usd">USD</option>
            <option value="bdt">BDT</option>
          </select>
        </td>
        <td class="px-1 py-1" style="width: 20%">
          <input type="number" placeholder="Sub Amount" step=".01" v-model="form.invoice_lines[index].sub_amount" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1" style="width: 20%">
          <input type="number" placeholder="Exchange Rate" step=".01" v-model="form.invoice_lines[index].exchange_rate" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
        <td class="px-1 py-1" style="width: 20%">
          <input type="number" readonly step=".01" v-model="form.invoice_lines[index].amount" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 bg-gray-300 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
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
        <td colspan="3"></td>
        <td><strong>VAT(%)</strong></td>
        <td class="px-1 py-1" style="width: 20%">
          <input type="number" placeholder="Vat Percentage" step=".01" v-model="form.vat" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
      </tr>
      <tr>
        <td colspan="3"></td>
        <td><strong>AIT(%)</strong></td>
        <td class="px-1 py-1" style="width: 20%">
          <input type="number" placeholder="Ait Percentage" step=".01" v-model="form.ait" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
        </td>
      </tr>
      <tr>
        <td colspan="2"></td>
        <td colspan="2"><strong>TOTAL</strong></td>
        <td class="px-1 py-1" style="width: 20%">
          <input type="number" style="background-color: darkgray" readonly v-model="form.amount" step="0.01" class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <input type="hidden" v-model="form.sub_total">
          <input type="hidden" v-model="form.total">
        </td>
      </tr>
      </tfoot>
    </table>
  </fieldset>
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