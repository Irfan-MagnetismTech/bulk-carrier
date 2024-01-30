<script setup>
import useTransaction from '../../../composables/accounts/useTransaction';
import Title from "../../../services/title";
import {onMounted, ref, watch, watchEffect} from "vue";
import useAisReport from "../../../composables/accounts/useAisReport";
import Store from "../../../store";
import useAccountCommonApiRequest from "../../../composables/accounts/useAccountCommonApiRequest";

const { paymentReceiptStatements, getPaymentReceiptStatement, isLoading} = useAisReport();
const { balanceIncomeLineLists, getBalanceIncomeLineLists, balanceIncomeAccountLists, getBalanceIncomeAccountLists } = useAccountCommonApiRequest();

const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const { setTitle } = Title();

let expenses = ref(0.00);
let incomes = ref(0.00);

setTitle('AIS Report - Income Statement');

const searchParams = ref({
  // acc_balance_income_line_id: 35,
  // acc_account_id: '',
  from_date: '2023-01-01',
  till_date: '2023-12-31',
  business_unit: businessUnit.value,
});

function toggleBalanceLineTrID(event) {
  let currentLine = event.target.id;
  document.querySelectorAll(".balance_account_" + currentLine).forEach(function (el) {
    el.style.display = (el.style.display == "none") ? "table-row" : "none";
  });
  document.querySelectorAll(".hide_balance_account_" + currentLine).forEach(function (el) {
    el.style.display = "none";
  });
}

function toggleParentAccountTrID(event) {
  let parentAccountLine = event.target.id;
  document.querySelectorAll(".parent_account_" + parentAccountLine).forEach(function (el) {
    el.style.display = (el.style.display == "none") ? "table-row" : "none";
  });
  document.querySelectorAll(".hide_parent_account_" + parentAccountLine).forEach(function (el) {
    el.style.display = "none";
  });
}

function toggleChildAccountTrID(event) {
  let childAccountLine = event.target.id;
  document.querySelectorAll(".account_" + childAccountLine).forEach(function (el) {
    el.style.display = (el.style.display == "none") ? "table-row" : "none";
  });
}

function fetchAccounts(search, loading) {
  if(search.length < 3) {
    return;
  } else {
    getAccount(search, loading);
  }
}

onMounted(() => {
  watchEffect(() => {
    getBalanceIncomeLineLists(businessUnit.value);
    getBalanceIncomeAccountLists(businessUnit.value,searchParams.value.acc_balance_income_line_id);
  });
});

</script>

<template>
  <form @submit.prevent="getPaymentReceiptStatement(searchParams)">
    <div class="w-full flex items-center justify-between mb-2 my-2 select-none">
      <fieldset class="w-full grid grid-cols-5 gap-1 px-2 pb-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark-disabled:text-gray-300">Receipt & Payment Statement</legend>
<!--        <div>-->
<!--          <label for="" class="text-xs" style="margin-left: .01rem">Balance Income Line <span class="text-red-500">*</span></label>-->
<!--          <v-select :options="balanceIncomeLineLists" :loading="isLoading" placeholder="&#45;&#45;Choose an option&#45;&#45;" v-model="searchParams.acc_balance_income_line_id" label="line_text" :reduce="balanceIncomeLineLists => balanceIncomeLineLists.id" class="block w-full rounded form-input">-->
<!--            <template #search="{attributes, events}">-->
<!--              <input class="vs__search w-full" style="width: 50%" :required="!searchParams.acc_balance_income_line_id" v-bind="attributes" v-on="events"/>-->
<!--            </template>-->
<!--          </v-select>-->
<!--        </div>-->
<!--        <div>-->
<!--          <label for="" class="text-xs" style="margin-left: .01rem">Account Name</label>-->
<!--          <v-select :options="balanceIncomeAccountLists" :loading="isLoading" placeholder="&#45;&#45;Choose an option&#45;&#45;" v-model.trim="searchParams.acc_account_id" label="account_name" :reduce="balanceIncomeAccountLists => balanceIncomeAccountLists.id"  class="block w-full rounded form-input">-->
<!--            <template #search="{attributes, events}">-->
<!--              <input class="vs__search w-full" style="width: 50%" v-bind="attributes" v-on="events"/>-->
<!--            </template>-->
<!--          </v-select>-->
<!--        </div>-->
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">From Date <span class="text-red-500">*</span></label>
          <VueDatePicker v-model.trim="searchParams.from_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Till Date <span class="text-red-500">*</span></label>
          <VueDatePicker v-model.trim="searchParams.till_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
        </div>
        <div>
          <label for="">&nbsp;</label>
          <button type="submit" :disabled="isLoading" class="w-full flex items-center justify-center px-2 mt-1 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
        </div>
      </fieldset>
    </div>
  </form>
  <!-- Table -->
  <template v-if="Object.keys(paymentReceiptStatements).length">
    <div class="w-full mb-10">
      <table class="w-full whitespace-no-wrap top_table">
        <thead>
        <tr class="text-sm font-semibold tracking-wide text-center" style="background-color: #369382;color: #ffff">
          <th class="bg-green-600 text-white"> Receipt & Payment Statement </th>
        </tr>
        </thead>
      </table>
      <div class="w-full overflow-hidden grid grid-cols-2 gap-1">
        <div class="w-full overflow-x-auto">
          <table class="w-full whitespace-no-wrap mb-1">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800" style="background-color: #369382;color: #ffff">
              <th colspan="3" class="bg-green-600 text-white"> Receipt </th>
            </tr>
            </thead>
            <tbody class="bg-white dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <tr class="text-gray-700 dark-disabled:text-gray-400" style="background-color: #DBECDB">
              <td class="text-sm balance_header">Opening Balance</td>
              <td class="text-sm text-right font-bold"></td>
              <td class="text-sm text-right font-bold">{{ paymentReceiptStatements?.ttl_opening.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
            </tr>
            <tr style="background-color: #eff1f1" class="second_label" v-for="(openingBalanceData, openingBalanceDataKey, openingBalanceDataIndex) in paymentReceiptStatements?.opening_balances" :key="openingBalanceDataIndex">
              <td class="relative text-sm balance_line_style balance_line !text-left !font-normal">
                {{ openingBalanceData?.account_name }}
              </td>
              <td class="text-sm text-right">{{ openingBalanceData?.balance?.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
              <td class="text-sm text-right font-bold"></td>
            </tr>
            <template v-for="(receiptData, receiptDataKey, receiptDataIndex) in paymentReceiptStatements?.receipts" :key="receiptDataIndex">
              <template v-for="(accountDataLine, accountDataKey, accountDataLineIndex) in receiptData.accounts">
                <tr class="text-gray-700 dark-disabled:text-gray-400" style="background-color: #DBECDB" v-if="accountDataLineIndex === 0">
                  <td class="text-sm balance_header">{{ receiptData?.line_text }}</td>
                  <td class="text-sm text-right font-bold"></td>
                  <td class="text-sm text-right font-bold">{{ receiptData?.amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
                </tr>
                <tr style="background-color: #eff1f1" class="second_label">
                  <td class="relative text-sm balance_line_style balance_line !text-left !font-normal">
                    {{ accountDataLine?.account_name }}
                  </td>
                  <td class="text-sm text-right">{{ accountDataLine?.amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
                  <td class="text-sm text-right font-bold"></td>
                </tr>
              </template>
            </template>
            </tbody>
          </table>
        </div>
        <div class="w-full overflow-x-auto">
          <table class="w-full whitespace-no-wrap mb-1">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800" style="background-color: #369382;color: #ffff">
              <th colspan="3" class="bg-green-600 text-white"> Payment </th>
            </tr>
            </thead>
            <tbody class="bg-white dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <template v-for="(paymentData, paymentDataKey, paymentDataIndex) in paymentReceiptStatements?.payments" :key="paymentDataIndex">
              <template v-for="(accountDataLine, accountDataKey, accountDataLineIndex) in paymentData.accounts">
                <tr class="text-gray-700 dark-disabled:text-gray-400" style="background-color: #DBECDB" v-if="accountDataLineIndex === 0">
                  <td class="text-sm balance_header">{{ paymentData?.line_text }}</td>
                  <td class="text-sm text-right font-bold"></td>
                  <td class="text-sm text-right font-bold">{{ paymentData?.amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
                </tr>
                <tr style="background-color: #eff1f1" class="second_label">
                  <td class="relative text-sm balance_line_style balance_line !text-left !font-normal">
                    {{ accountDataLine?.account_name }}
                  </td>
                  <td class="text-sm text-right">{{ accountDataLine?.amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
                  <td class="text-sm text-right font-bold"></td>
                </tr>
              </template>
            </template>
            <tr class="text-gray-700 dark-disabled:text-gray-400" style="background-color: #DBECDB">
              <td class="text-sm balance_header">Closing Balance</td>
              <td class="text-sm text-right font-bold"></td>
              <td class="text-sm text-right font-bold">{{ paymentReceiptStatements?.ttl_closing.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
            </tr>
            <tr style="background-color: #eff1f1" class="second_label" v-for="(closingBalanceData, closingBalanceDataKey, closingBalanceDataIndex) in paymentReceiptStatements?.closing_balances" :key="closingBalanceDataIndex">
              <td class="relative text-sm balance_line_style balance_line !text-left !font-normal">
                {{ closingBalanceData?.account_name }}
              </td>
              <td class="text-sm text-right">{{ closingBalanceData?.balance?.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
              <td class="text-sm text-right font-bold"></td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="w-full overflow-hidden grid grid-cols-2 gap-1">
        <table class="w-full">
          <tfoot>
          <tr style="background-color: #E3E3E3">
            <td class="text-sm font-bold text-right bg-green-600 text-white" colspan="2">Total</td>
            <td class="text-sm font-bold text-right bg-green-600 text-white">{{ paymentReceiptStatements?.ttl_receipts?.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
          </tr>
          </tfoot>
        </table>
        <table class="w-full">
          <tfoot>
          <tr style="background-color: #E3E3E3">
            <td class="text-sm font-bold text-right bg-green-600 text-white" colspan="2">Total</td>
            <td class="text-sm font-bold text-right bg-green-600 text-white">{{ paymentReceiptStatements?.ttl_payments?.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
          </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </template>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
  .tab {
    @apply p-1.5 text-xs;
  }
  thead tr {
    @apply font-semibold tracking-wide text-left text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800;
  }
  th {
    @apply text-center;
  }

  tbody tr {
    @apply text-gray-700 dark-disabled:text-gray-400;
  }
  tbody tr td {
    @apply tab;
  }
  tfoot td {
    @apply tab;
  }
  table th {
    @apply tab;
  }
}

.balance_header {
  font-weight: bold;
  text-align: left;
}

.balance_line_style {
  font-weight: bold;
  padding-left: 30px;
  cursor: pointer;
}

.balance_line_parent {
  padding-left: 50px;
  cursor: pointer;
}

.balance_line_parent_child {
  padding-left: 70px;
  cursor: pointer;
}

.balance_line_parent_grand_child {
  padding-left: 90px;
}

table tr,td,th {
  border: 1px solid #cbc5c5;
}

#close_tr td {
  color: #f1ebeb;
}

#opening_tr td {
  color: #f1ebeb;
}

.income_statement_top_div{
  padding: 10px;
  border: 1px solid #727272;
}

.input-group {
  @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
}
.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark-disabled:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
}
table, th,td{
  @apply border border-collapse;
}
.search-result {
  @apply px-4 py-3 text-sm text-center text-gray-600 dark-disabled:text-gray-300;
}
.search {
  @apply float-right  pr-10 text-sm border border-gray-300 rounded dark-disabled:bg-gray-800 dark-disabled:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray dark-disabled:border-0;
}
.transaction_col:hover{
  text-decoration: underline;
}

</style>