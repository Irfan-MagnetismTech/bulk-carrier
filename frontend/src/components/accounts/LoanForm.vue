<script setup>
import Error from "../Error.vue";
import useVessel from "../../composables/operations/useVessel";
import {onMounted, ref, watch, watchEffect, getCurrentInstance } from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";
import useAccountCommonApiRequest from "../../composables/accounts/useAccountCommonApiRequest";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import RemarksComponent from "../utils/RemarksComponent.vue";
const { vessels, searchVessels } = useVessel();

const { allAccountLists, allBankLists, getAccount, allCostCenterLists, getBank, getCostCenter, isLoading } = useAccountCommonApiRequest();

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
watch(() => props.form, (value) => {
  if(value){
    props.form.acc_account_id = props.form?.acc_account_name?.id ?? '';
    props.form.acc_cost_center_id = props.form?.acc_cost_center_name?.id ?? '';
  }
}, {deep: true});

// v-select for change unit depend on material start
watch(() => props.form.bank, (value) => {
  if(value){
    props.form.loanable_id = value?.id ?? '';
  }
}, {deep: true});


onMounted(() => {
  //props.form.business_unit = businessUnit.value;
  watchEffect(() => {
    getCostCenter(null,props.form.business_unit);
    getAccount(null,props.form.business_unit);
    getBank(null,props.form.business_unit);
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
        <span class="text-gray-700 dark-disabled:text-gray-300"> Source Type <span class="text-red-500">*</span></span>
        <select class="label-item-input" v-model.trim="form.loanable_type" required>
          <option disabled> Select </option>
          <option value="Bank" selected> Bank </option>
        </select>
      </label>
      
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Source Name <span class="text-red-500">*</span></span>
        <v-select :options="allBankLists" placeholder="--Choose an option--" :loading="isLoading" v-model.trim="form.bank" label="bank_name_type"  class="block w-full rounded form-input">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.bank" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
      </label>
      
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Loan Type <span class="text-red-500">*</span></span>
        <select class="label-item-input" v-model.trim="form.loan_type" required>
          <option value="" selected disabled> Select </option>
          <option value="AC Payee">AC Payee</option>
          <option value="Pay Order">Pay Order</option>
          <option value="Draft">Draft</option>
          <option value="Same / Inter Bank"> Same / Inter Bank </option>
        </select>
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="label-group">
        <span class="label-item-title"> Loan Number <span class="text-red-500">*</span></span>
        <input type="text" class="label-item-input" placeholder="Loan Number" v-model.trim="form.loan_number" required />
      </label>

      <label class="label-group">
        <span class="label-item-title"> Loan Name <span class="text-red-500">*</span></span>
        <input type="text" class="label-item-input" placeholder="Loan Name" v-model.trim="form.loan_name" required />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Total Sanctioned <span class="text-red-500">*</span></span>
        <input type="number" step=".01" v-model.trim="form.total_sanctioned" placeholder="Total Sanctioned" class="form-input" autocomplete="off" required />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Sanctioned Limit <span class="text-red-500">*</span></span>
        <input type="number" step=".01" v-model.trim="form.sanctioned_limit" placeholder="Sanctioned Limit" class="form-input" autocomplete="off" required />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Total Installment <span class="text-red-500">*</span></span>
        <input type="number" step=".01" v-model.trim="form.total_installment" placeholder="Total Installment" class="form-input" autocomplete="off" required />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Interest Rate (%) <span class="text-red-500">*</span></span>
        <input type="number" step=".01" v-model.trim="form.interest_rate" placeholder="Interest Rate" class="form-input" autocomplete="off" required />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Opening Date <span class="text-red-500">*</span></span>
        <input type="date" v-model.trim="form.opening_date" class="form-input" autocomplete="off" required />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Maturity Date <span class="text-red-500">*</span></span>
        <input type="date" v-model.trim="form.maturity_date" class="form-input" autocomplete="off" required />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> EMI Payment Date <span class="text-red-500">*</span></span>
        <input type="date" v-model.trim="form.emi_date" class="form-input" autocomplete="off" required />
      </label>   
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> EMI Amount <span class="text-red-500">*</span></span>
        <input type="number" step=".01" placeholder="EMI Amount" v-model.trim="form.emi_amount" class="form-input" autocomplete="off" required />

      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Loan Purpose <span class="text-red-500">*</span></span>
        <input type="text" class="label-item-input" placeholder="Loan Purpose" v-model.trim="form.loan_purpose" />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300"> Mortgage </span>
        <input type="text" class="label-item-input" placeholder="Mortgage" v-model.trim="form.mortgage" />
      </label>

    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <!-- <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Remarks</span>
        <textarea type="text" v-model.trim="form.remarks" placeholder="Remarks" class="form-input" autocomplete="off"></textarea>
      </label> -->
      <RemarksComponent v-model.trim="form.remarks" :maxlength="500" :fieldLabel="'Remarks'"></RemarksComponent>
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