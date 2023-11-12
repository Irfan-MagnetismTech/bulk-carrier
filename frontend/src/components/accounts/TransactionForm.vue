<script setup>
import Error from "../Error.vue";
import useVessel from "../../composables/operations/useVessel";
import {onMounted, ref, watch, watchEffect, getCurrentInstance } from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";
import useAccountCommonApiRequest from "../../composables/accounts/useAccountCommonApiRequest";
import useTransaction from "../../composables/accounts/useTransaction";
const { vessels, searchVessels } = useVessel();

const { allAccountLists, getAccount, allCostCenterLists, getCostCenter, isLoading } = useAccountCommonApiRequest();

const { emit } = getCurrentInstance();
const { bgColor } = useTransaction();

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  page: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

function addItem() {
  let obj = {
    acc_transaction_id: '',
    acc_balance_and_income_line_id: '',
    acc_cost_center_id: '',
    acc_account_id: '',
    dr_amount: 0.0,
    cr_amount: 0.0,
    ref_bill: '',
    bill_status: '',
    purpose: '',
    remarks: '',
  };
  props.form.ledgerEntries.push(obj);
}

function removeItem(index){
  props.form.ledgerEntries.splice(index, 1);
}

function fetchVessels(search, loading) {
  loading(true);
  searchVessels(search, loading)
}

// v-select for change unit depend on material start
watch(
    () => props.form,
    (newEntries, oldEntries) => {

      if(newEntries) {
        changeBgColor();
      }

      props.form.acc_cost_center_id = props.form?.acc_cost_center_name?.id ?? '';

      newEntries?.ledgerEntries?.forEach((entry, index) => {
        if(props.form.ledgerEntries[index].acc_account_name){
          props.form.ledgerEntries[index].acc_account_id = props.form.ledgerEntries[index].acc_account_name.id ?? '';
          props.form.ledgerEntries[index].acc_balance_and_income_line_id = props.form.ledgerEntries[index].acc_account_name.acc_balance_and_income_line_id ?? '';
          props.form.ledgerEntries[index].acc_cost_center_id = props.form.acc_cost_center_id ?? '';
        }
      });

      //calculate debit and credit amount
      if(newEntries?.ledgerEntries?.length > 0) {
        let total_debit_amount = 0.0;
        let total_credit_amount = 0.0;
        newEntries?.ledgerEntries?.forEach((item) => {
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
    },
    { deep: true }
);

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

function fetchAccounts(search, loading) {
  if(search.length < 3) {
    return;
  } else {
    getAccount(search, props.form.business_unit, loading);
  }
}

function checkWhitespace(value) {
  if (/^\s+$/.test(props.form.instrument_no)) {props.form.instrument_no = '';}
  if (/^\s+$/.test(props.form.bill_no)) {props.form.bill_no = '';}
  if (/^\s+$/.test(props.form.narration)) {props.form.narration = '';}

  props.form.ledgerEntries?.forEach((entry, index) => {
    if (/^\s+$/.test(props.form.ledgerEntries[index].ref_bill)) {props.form.ledgerEntries[index].ref_bill = '';}
    if (/^\s+$/.test(props.form.ledgerEntries[index].remarks)) {props.form.ledgerEntries[index].remarks = '';}
  });
}

onMounted(() => {
  //props.form.business_unit = businessUnit.value;
  watchEffect(() => {
    getCostCenter(null,props.form.business_unit);
    getAccount(null,props.form.business_unit);
  });
});

</script>

<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Cost Center <span class="text-red-500">*</span></span>
        <v-select :options="allCostCenterLists" placeholder="--Choose an option--" :loading="isLoading" v-model="form.acc_cost_center_name" label="name"  class="block w-full rounded form-input">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.acc_cost_center_name" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
        <Error v-if="errors?.acc_cost_center_name" :errors="errors.acc_cost_center_name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Voucher Type <span class="text-red-500">*</span></span>
        <select class="label-item-input" v-model="form.voucher_type" @change="changeBgColor()" required>
          <option value="" selected disabled>Select Value</option>
          <option value="Receipt">Receipt</option>
          <option value="Payment">Payment</option>
          <option value="Journal">Journal</option>
          <option value="Contra">Contra</option>
        </select>
        <Error v-if="errors?.voucher_type" :errors="errors.voucher_type" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Applied Date <span class="text-red-500">*</span></span>
        <input type="date" v-model="form.transaction_date" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.transaction_date" :errors="errors.transaction_date" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Payment Type <span class="text-red-500">*</span></span>
        <select class="label-item-input" v-model="form.instrument_type" required>
          <option value="" selected disabled>Select Value</option>
          <option value="A/C Payee">A/C Payee</option>
          <option value="Cheque">Cheque</option>
          <option value="Cash">Cash</option>
          <option value="Pay Order">Pay Order</option>
          <option value="Draft">Draft</option>
          <option value="Same / Inter Bank">Same / Inter Bank</option></select>
        <Error v-if="errors?.instrument_type" :errors="errors.instrument_type" />
      </label>
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="label-group">
      <span class="label-item-title"> Cheque Number <span class="text-red-500">*</span></span>
      <input type="text" @input="checkWhitespace" class="label-item-input" placeholder="Cheque no." v-model="form.instrument_no" required />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Check Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="form.instrument_date" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.instrument_date" :errors="errors.instrument_date" />
    </label>
    <label class="label-group">
      <span class="label-item-title"> Bill No. <span class="text-red-500">*</span></span>
      <input type="text" @input="checkWhitespace" class="label-item-input" placeholder="Bill no." v-model="form.bill_no" required />
    </label>
    <label class="label-group">
      <span class="label-item-title"> Narration <span class="text-red-500">*</span></span>
      <input type="text" @input="checkWhitespace" class="label-item-input" placeholder="Narration" v-model="form.narration" required />
    </label>
  </div>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Ledger Entries <span class="text-red-500">*</span></legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-bottom">Accounts <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Ref Bill</th>
        <th class="px-4 py-3 align-bottom">Debit Amount <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Credit Amount <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Remarks</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(ledgerEntry, index) in form.ledgerEntries" :key="ledgerEntry.id">
        <td class="px-1 py-1">
          <v-select :options="allAccountLists" :loading="isLoading" placeholder="--Choose an option--" @search="fetchAccounts" v-model="form.ledgerEntries[index].acc_account_name" label="account_name"  class="block w-full rounded form-input">
            <template #search="{attributes, events}">
              <input class="vs__search w-full" style="width: 50%" :required="!form.ledgerEntries[index].acc_account_name" v-bind="attributes" v-on="events"/>
            </template>
          </v-select>
          <Error v-if="errors?.form.ledgerEntries[index].acc_account_name" :errors="errors.form.ledgerEntries[index].acc_account_name" />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.ledgerEntries[index].ref_bill" @input="checkWhitespace" placeholder="Ref bill" class="form-input" required autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="number" step=".01" v-model="form.ledgerEntries[index].dr_amount" placeholder="Ex: 1500" required class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="number" step=".01" v-model="form.ledgerEntries[index].cr_amount" placeholder="Ex: 1500" required class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.ledgerEntries[index].remarks" @input="checkWhitespace" placeholder="Remarks" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1 text-center">
          <button v-if="index!==0" type="button" @click="removeItem(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <button v-else type="button" @click="addItem()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </td>
      </tr>
      <tr class="text-gray-700 dark:text-gray-400">
        <td class="px-1 py-1 font-bold text-right" colspan="2">Total Amount</td>
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
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}

.label-group {
  @apply block w-full mt-2 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
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