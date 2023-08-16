<script setup>
import {watch, ref} from "vue";
import useAccount from '../../composables/accounts/useAccount';


const { balanceIncomeAccounts, generatedAccountCode, getBalanceIncomeAccounts, getGeneratedAccountCode } = useAccount();

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  balanceIncomeLines : {},
  errors: { type: [Object, Array], required: false },
});

const hasUpdated = ref(0); 
watch(() => props.form.balance_and_income_line_id, (value) => {
      // props.purchaseOrder.vendor_id = value?.id;
      getBalanceIncomeAccounts(value); 
      getGeneratedAccountCode(value);
      props.form.account_code = generatedAccountCode; 
});

// watch(() => generatedAccountCode, (value) => {
//   if(hasUpdated.value == 1){
//     props.form.account_code = value;
//   }
// }, {deep:true});

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="input-group">
            <label class="label-group">
              <span class="label-item-title"> Balance / Income Line </span>
              <select class="label-item-input" v-model="form.balance_and_income_line_id">
                <option value="" selected disabled>Select Value</option>
                <option v-for="(balanceIncomeLine, key) in balanceIncomeLines" :value="key" :key="key">{{ balanceIncomeLine }}</option>
              </select>
            </label>
            <label class="label-group">
              <span class="label-item-title"> Parent Account <span class="required-style">*</span></span>
              <select class="label-item-input" v-model="form.parent_account_id" required>
                <option value="" selected disabled>Select Value</option>
                <option v-for="(balanceIncomeAccount) in balanceIncomeAccounts" :value="balanceIncomeAccount.id" :key="balanceIncomeAccount.id">{{ balanceIncomeAccount.account_name }}</option>
              </select>
            </label>

            <label class="label-group">
                <span class="label-item-title"> Account Code <span class="required-style">*</span></span>
                <input type="text" class="label-item-input" v-model="form.account_code" placeholder="Account Code" required />
            </label>

            <label class="label-group">
                <span class="label-item-title"> Account Name <span class="required-style">*</span></span>
                <input type="text" class="label-item-input" v-model="form.account_name" placeholder="Account Name" required />
            </label>


            <label class="label-group">
              <span class="label-item-title"> Account Type <span class="required-style">*</span></span>
              <select class="label-item-input" v-model="form.account_type" required>
                <option value="" selected disabled>Select Value</option>
                <option value="1"> Assets </option>
                <option value="2"> Liabilities </option>
                <option value="3"> Equity </option>
                <option value="4"> Revenues </option>
                <option value="5"> Expenses </option>
              </select>
            </label>
        </div>
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

</style>