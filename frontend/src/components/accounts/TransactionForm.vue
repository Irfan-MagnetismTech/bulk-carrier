
<script setup>
import { ref, defineProps, onMounted, getCurrentInstance, computed, watch } from "vue";
import useTransaction from "../../composables/accounts/useTransaction.js";

// use emit to pass data to parent component
const { emit } = getCurrentInstance();

const { bgColor, allAccount, getAccount } = useTransaction();

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: {
    type: [Object, Array],
    required: false
  }
});

function addVoucherDetail() {
  let obj =    {
    cost_center_id: '',
    account_id: null,
    balance_income_line_id: '',
    account: null,
    ref_bill: '',
    dr_amount: 0.0,
    cr_amount: 0.0,
    remarks: '',
  };
  props.form.ledger_entries.push(obj);
}

function removeVoucherDetail(index){
  props.form.ledger_entries.splice(index, 1);
}

function changeBgColor() {
  if(props.form.voucher_type === 'Receipt') {
    bgColor.value = '#F9D771';
  } else if(props.form.voucher_type === 'Payment') {
    bgColor.value = '#85A25C';
  } else if(props.form.voucher_type === 'Journal') {
    bgColor.value = '#F2F2F2';
  } else if(props.form.voucher_type === 'Contra') {
    bgColor.value = '#BF7E7D';
  }
  emit("bgColor", bgColor.value);
}

//watch props.form.voucher_type
watch(() => props.form.voucher_type, (newValue, oldValue) => {
  if(newValue) {
    changeBgColor();
  }
}, { deep: true });

// watch props.form.leger_entries total_debit_amount and total_credit_amount with Nan check
watch(() => props.form.ledger_entries, (newValue, oldValue) => {
  if(newValue.length > 0) {
    let total_debit_amount = 0.0;
    let total_credit_amount = 0.0;
    newValue.forEach((item) => {
      if(item.dr_amount) {
        total_debit_amount += parseFloat(item.dr_amount);
      }
      if(item.cr_amount) {
        total_credit_amount += parseFloat(item.cr_amount);
      }
    });
    if(!isNaN(total_debit_amount) && !isNaN(total_credit_amount)) {
      props.form.total_debit_amount = total_debit_amount;
      props.form.total_credit_amount = total_credit_amount;
    }
  }
}, { deep: true });

function fetchAccounts(search, loading) {
  if(search.length < 3) {
    return;
  } else {
    getAccount(search, loading);
  }
}

function setAccountId(event, index) {
  props.form.ledger_entries[index].account_id = props.form.ledger_entries[index].account.account_id;
  props.form.ledger_entries[index].balance_income_line_id = props.form.ledger_entries[index].account.balance_income_line_id;
  allAccount.value = [];
}

</script>
<template>
    <div class="border-b border-gray-200 dark:border-green-200">
        <div class="input-group">
            <label class="label-group">
              <span class="label-item-title"> Voucher Type <span class="required-style">*</span></span>
              <select class="label-item-input" v-model="form.voucher_type" @change="changeBgColor()" required>
                <option value="" selected disabled>Select Value</option>
                <option value="Receipt">Receipt</option>
                <option value="Payment">Payment</option>
                <option value="Journal">Journal</option>
                <option value="Contra">Contra</option>
              </select>
            </label>
            <label class="label-group">
                <span class="label-item-title"> Date <span class="required-style">*</span></span>
                <input type="date" class="label-item-input" v-model="form.transaction_date" required />
            </label>
          <label class="label-group">
            <span class="label-item-title"> Payment Type <span class="required-style">*</span></span>
            <select class="label-item-input" v-model="form.instrument_type" required>
              <option value="" selected disabled>Select Value</option>
              <option value="A/C Payee">A/C Payee</option>
              <option value="Cheque">Cheque</option>
              <option value="Cash">Cash</option>
              <option value="Pay Order">Pay Order</option>
              <option value="Draft">Draft</option>
              <option value="Same / Inter Bank">Same / Inter Bank</option></select>
          </label>
        </div>

        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title"> Cheque Number <span class="required-style">*</span></span>
                <input type="text" class="label-item-input" v-model="form.instrument_no" required />
            </label>
            <label class="label-group">
                <span class="label-item-title"> Cheque Date <span class="required-style">*</span></span>
                <input type="date" class="label-item-input" v-model="form.instrument_date" required />
            </label>
          <label class="label-group">

          </label>
        </div>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Ledger Entries</legend>
        <table class="w-full whitespace-no-wrap" id="table">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3 align-bottom">Accounts</th>
            <th class="px-4 py-3 align-bottom">Ref Bill</th>
            <th class="px-4 py-3 align-bottom">Debit</th>
            <th class="px-4 py-3 align-bottom">Credit</th>
            <th class="px-4 py-3 align-bottom">Remarks</th>
            <th class="px-4 py-3 text-center align-bottom">Action</th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400" v-for="(voucherDetail, index) in form.ledger_entries" :key="index">
            <td class="px-1 py-1">
              <input type="hidden" v-model="form.ledger_entries[index].cost_center_id">
              <v-select :options="allAccount" placeholder="--Choose an option--" @search="fetchAccounts"  v-model="form.ledger_entries[index].account" label="account_name" class="block w-full rounded form-input">
                <template #search="{attributes, events}">
                  <input class="vs__search w-full" style="width: 50%" :required="!form.ledger_entries[index].account_id" @change="setAccountId($event,index)" v-bind="attributes" v-on="events"/>
                </template>
              </v-select>
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="form.ledger_entries[index].ref_bill" class="block w-full rounded form-input">
            </td>
            <td class="px-1 py-1">
              <input type="number" step=".05" v-model="form.ledger_entries[index].dr_amount" class="block w-full rounded form-input">
            </td>
            <td class="px-1 py-1">
              <input type="number" step=".05" v-model="form.ledger_entries[index].cr_amount" class="block w-full rounded form-input">
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model="form.ledger_entries[index].remarks" class="block w-full rounded form-input">
            </td>
            <td class="px-1 py-1 text-center">
              <button v-if="index!==0" type="button" @click="removeVoucherDetail(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button>
              <button v-else type="button" @click="addVoucherDetail()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-1 py-1 font-bold text-right" colspan="2">Total Tk.</td>
            <td class="px-1 py-1 font-bold text-right">
              <input type="text" v-model="form.total_debit_amount" class="block w-full rounded form-input vms-readonly-input" readonly>
            </td>
            <td class="px-1 py-1 font-bold text-right">
              <input type="text" v-model="form.total_credit_amount" class="block w-full rounded form-input vms-readonly-input" readonly>
            </td>
            <td class="px-1 py-1 font-bold text-right" colspan="2"></td>
          </tr>
          </tbody>
        </table>
      </fieldset>
    </div>
</template>
<style lang="postcss" scoped>
.input-group {
    @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
}
.label-group {
    @apply block w-full mt-3 text-sm font-semibold;
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
.vs__selected{
  display: none !important;
}
.required-style{
    @apply text-red-400 font-semibold
}

table th,tr,td{
  border: 1px solid #000;
  border-collapse: collapse;
  padding: 5px;
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