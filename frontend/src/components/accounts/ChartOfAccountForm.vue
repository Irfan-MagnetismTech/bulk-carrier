<script setup>
import Error from "../Error.vue";
import {onMounted, ref, watch, watchEffect} from "vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import Store from "../../store";
import useAccountCommonApiRequest from "../../composables/accounts/useAccountCommonApiRequest";

const { balanceIncomeLineLists, getBalanceIncomeLineLists, balanceIncomeAccountLists, getBalanceIncomeAccountLists, generatedAccountCode, getGeneratedAccountCode, isLoading } = useAccountCommonApiRequest();

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

function getCode(el, loading){
  alert("df")
  loading = true;
  getGeneratedAccountCode(el.target.value);
}

watch(generatedAccountCode, (value) => {
  if(value) {
    props.form.account_code = value;
  }
});

watch(() => props.form.acc_balance_and_income_line_name, (newEntries, oldEntries) => {
    getGeneratedAccountCode(props.form.acc_balance_and_income_line_name.id);
    }, { deep: true }
);

watch(() => props.form, (newEntries, oldEntries) => {
      props.form.acc_balance_and_income_line_id = props.form?.acc_balance_and_income_line_name?.id ?? '';
      props.form.parent_account_id = props.form?.parent_account_name?.id ?? '';
    }, { deep: true }
);

function checkWhitespace(value) {
  if (/^\s+$/.test(props.form.account_name)) {props.form.account_name = '';}
}

onMounted(() => {
  //props.form.business_unit = businessUnit.value;
  watchEffect(() => {
    getBalanceIncomeLineLists(props.form.business_unit);
    getBalanceIncomeAccountLists(props.form.business_unit,props.form.acc_balance_and_income_line_id);
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
        <span class="text-gray-700 dark:text-gray-300">Balance/Income Line <span class="text-red-500">*</span></span>
        <v-select :options="balanceIncomeLineLists" :loading="isLoading" @input="getCode($event)" placeholder="--Choose an option--" v-model="form.acc_balance_and_income_line_name" label="line_text"  class="block w-full rounded form-input">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.acc_balance_and_income_line_name" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
        <Error v-if="errors?.acc_balance_and_income_line_name" :errors="errors.acc_balance_and_income_line_name" />
<!--        <select class="form-input" v-model="form.acc_balance_and_income_line_id" @change="getCode($event)" autocomplete="off" required>-->
<!--          <option value="" disabled selected>Select</option>-->
<!--          <option v-for="balanceIncomeLine in balanceIncomeLineLists" :value="balanceIncomeLine.id" :key="balanceIncomeLine.id">{{ balanceIncomeLine.line_text }}</option>-->
<!--        </select>-->
<!--        <Error v-if="errors?.acc_balance_and_income_line_id" :errors="errors.acc_balance_and_income_line_id" />-->
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Parent Account </span>
        <v-select :options="balanceIncomeAccountLists" :loading="isLoading" placeholder="--Choose an option--" v-model="form.parent_account_name" label="account_name"  class="block w-full rounded form-input">
          <template #search="{attributes, events}">
            <input class="vs__search w-full" style="width: 50%" :required="!form.parent_account_name" v-bind="attributes" v-on="events"/>
          </template>
        </v-select>
        <Error v-if="errors?.parent_account_name" :errors="errors.parent_account_name" />
<!--        <select class="form-input" v-model="form.parent_account_id" autocomplete="off">-->
<!--          <option value="" disabled selected>Select</option>-->
<!--          <option v-for="balanceIncomeAccountLine in balanceIncomeAccountLists" :value="balanceIncomeAccountLine.id" :key="balanceIncomeAccountLine.id">{{ balanceIncomeAccountLine.account_name }}</option>-->
<!--        </select>-->
<!--        <Error v-if="errors?.parent_account_id" :errors="errors.parent_account_id" />-->
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Account Code <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.account_code" placeholder="A/C Code" class="form-input vms-readonly-input" readonly autocomplete="off" required />
        <Error v-if="errors?.account_code" :errors="errors.account_code" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Account Name <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.account_name" @input="checkWhitespace" placeholder="A/C name" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.account_name" :errors="errors.account_name" />
      </label>
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Account Type <span class="text-red-500">*</span></span>
      <select class="form-input" v-model="form.account_type" autocomplete="off" required>
        <option value="" disabled selected>Select</option>
        <option value="1"> Assets </option>
        <option value="2"> Liabilities </option>
        <option value="3"> Equity </option>
        <option value="4"> Revenues </option>
        <option value="5"> Expenses </option>      </select>
      <Error v-if="errors?.account_type" :errors="errors.account_type" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Is Archived <span class="text-red-500">*</span></span>
      <select class="form-input" v-model="form.is_archived" autocomplete="off" required>
        <option value="" disabled selected>Select</option>
        <option value="0"> No </option>
        <option value="1"> Yes </option>
      </select>
      <Error v-if="errors?.is_archived" :errors="errors.is_archived" />
    </label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
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