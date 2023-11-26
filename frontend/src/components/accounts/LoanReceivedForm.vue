<script setup>
import Error from "../Error.vue";
import {onMounted, ref, watch, watchEffect, getCurrentInstance } from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";
import useAccountCommonApiRequest from "../../composables/accounts/useAccountCommonApiRequest";
import ErrorComponent from '../utils/ErrorComponent.vue';

const { allBankLists, allLoanLists, getLoan, getBank, isLoading } = useAccountCommonApiRequest();


const { emit } = getCurrentInstance();

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

// v-select for change unit depend on material start
watch(() => props.form.loan, (value) => {
  if(value){
    props.form.acc_loan_id = value?.id ?? '';
    props.form.total_sanctioned = value?.total_sanctioned ?? '';
    props.form.total_received_amount = value?.total_received_amount ?? '';
  }
});

// v-select for change unit depend on material start
watch(() => props.form.bank, (value) => {
  if(value){
    props.form.received_acc_account_id = value?.id ?? '';
  }
});

function fetchLoans(search, loading) {
  if(search.length < 3) {
    return;
  } else {
    getLoan(search, props.form.business_unit, loading);
  }
}


onMounted(() => {
  //props.form.business_unit = businessUnit.value;
  watchEffect(() => {
    getBank(null,props.form.business_unit);
    getLoan(null,props.form.business_unit);
  });
});

</script>

<template>
  <ErrorComponent :errors="errors"></ErrorComponent>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">      
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Loan Name <span class="text-red-500">*</span></span>
        <v-select :options="allLoanLists" placeholder="--Choose an option--" :loading="isLoading" v-model.trim="form.loan" label="loan_name"  class="block w-full rounded form-input">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.loan" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Total Sanctioned <span class="text-red-500">*</span></span>
        <input type="number" v-model.trim="form.total_sanctioned" placeholder="Total Sanctioned" class="form-input vms-readonly-input" readonly autocomplete="off" required />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Total Received Amount <span class="text-red-500">*</span></span>
        <input type="number" v-model.trim="form.total_received_amount" placeholder="Total Received Amount" class="form-input vms-readonly-input" readonly autocomplete="off" required />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Received Date <span class="text-red-500">*</span></span>
        <input type="date" v-model.trim="form.received_date" class="form-input" autocomplete="off" required />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Payment Type <span class="text-red-500">*</span></span>
        <select class="label-item-input" v-model.trim="form.payment_method" required>
          <option value="" selected disabled>Select Value</option>
          <option value="A/C Payee">A/C Payee</option>
          <option value="Cheque">Cheque</option>
          <option value="Pay Order">Pay Order</option>
          <option value="Draft">Draft</option>
          <option value="Same / Inter Bank">Same / Inter Bank</option></select>
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Received Account Name <span class="text-red-500">*</span></span>
        <v-select :options="allBankLists" placeholder="--Choose an option--" :loading="isLoading" v-model.trim="form.bank" label="bank_name_type"  class="block w-full rounded form-input">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.bank" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Instrument No <span class="text-red-500">*</span></span>
        <input type="text" placeholder="Instrument No" v-model.trim="form.instrument_no" class="form-input" autocomplete="off" required />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Instrument Date <span class="text-red-500">*</span></span>
        <input type="date" v-model.trim="form.instrument_date" class="form-input" autocomplete="off" required />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Received Amount <span class="text-red-500">*</span></span>
        <input type="number" step=".01" v-model.trim="form.received_amount" placeholder="Received Amount" class="form-input" autocomplete="off" required />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Interest Rate (%) <span class="text-red-500">*</span></span>
        <input type="number" step=".01" v-model.trim="form.interest_rate" placeholder="Interest Rate" class="form-input" autocomplete="off" required />
      </label>
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
  @apply block w-full mt-2 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark-disabled:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
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