<script setup>
import useTransaction from '../../../composables/accounts/useTransaction';
import Title from "../../../services/title";
import { ref, watch } from "vue";
import useAisReport from "../../../composables/accounts/useAisReport";

const { incomeStatements, getIncomeStatement, isLoading} = useAisReport();
const { bgColor, allAccount, getAccount } = useTransaction();

const { setTitle } = Title();

let expenses = ref(0.00);
let incomes = ref(0.00);

setTitle('AIS Report - Income Statement');

const searchParams = ref({
  account_id: null,
  from_date: '2022-01-01',
  till_date: '2022-12-31',
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

// watch(incomeStatements, (newValue, oldValue) => {
//   let expensesTotal = 0;
//   let incomesTotal = 0;
//   newValue?.expense?.forEach((expense) => {
//     //let amountWithoutCommas = expense?.closing_balance_amount?.replace(/,/g, '');
//     expensesTotal += parseFloat(expense?.closing_balance_amount);
//   });
//   newValue?.incomes?.forEach((income) => {
//     //let amountWithoutCommas = income?.closing_balance_amount?.replace(/,/g, '');
//     incomesTotal += parseFloat(income?.closing_balance_amount);
//   });
//   // expenses.value = expensesTotal.toLocaleString('en-IN', {maximumFractionDigits:2})
//   // incomes.value = incomesTotal.toLocaleString('en-IN', {maximumFractionDigits:2});
//   expenses.value = expensesTotal;
//   incomes.value = incomesTotal;
// });

</script>

<template>
  <!-- Table -->
  <!--  <button type="button"> Click Me </button>-->
  <form @submit.prevent="getIncomeStatement(searchParams)">
    <div class="w-full flex items-center justify-between mb-2 my-2 select-none">
      <fieldset class="w-full grid grid-cols-4 gap-1 px-2 pb-3 border border-gray-500 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark-disabled:text-gray-300">Search Income Statement</legend>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">From Date <span class="text-red-500">*</span></label>
          <input type="date" required v-model="searchParams.from_date" class="block w-full rounded form-input">
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Till Date <span class="text-red-500">*</span></label>
          <input type="date" required v-model="searchParams.till_date" class="block w-full rounded form-input">
        </div>
        <div>
          <label for="">&nbsp;</label>
          <button type="submit" :disabled="isLoading" class="w-full flex items-center justify-center px-2 mt-1 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
        </div>
      </fieldset>
    </div>
  </form>
  <!-- Table -->
  <template v-if="incomeStatements?.expense">
    <div class="w-full mb-10 income_statement_top_div">
      <table class="w-full whitespace-no-wrap top_table">
        <thead>
        <tr class="text-sm font-semibold tracking-wide text-center" style="background-color: #369382;color: #ffff">
          <th class="bg-green-600 text-white"> Profit & Loss </th>
        </tr>
        </thead>
      </table>
      <div class="w-full overflow-hidden grid grid-cols-2 gap-1">
        <div class="w-full overflow-x-auto">
          <table class="w-full whitespace-no-wrap mb-1">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800" style="background-color: #369382;color: #ffff">
              <th colspan="3" class="bg-green-600 text-white"> Expenses </th>
            </tr>
            </thead>
            <tbody class="bg-white dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <template v-for="(expenseData, expenseDataIndex) in incomeStatements.expense" :key="expenseDataIndex">
              <template v-for="(expenseDataLine, expenseDataLineIndex) in expenseData.lines">
                <tr class="text-gray-700 dark-disabled:text-gray-400" style="background-color: #DBECDB" v-if="expenseDataLineIndex === 0">
                  <td class="text-sm balance_header">{{ expenseData?.line_text }}</td>
                  <td class="text-sm text-right font-bold"></td>
                  <td class="text-sm text-right font-bold">{{ expenseData?.closing_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ expenseData?.closing_balance_status }}</td>
                </tr>
                <tr style="background-color: #eff1f1" class="second_label">
                  <td class="relative text-sm balance_line_style balance_line" :style="{'cursor': expenseDataLine.parent_accounts?.length ? 'pointer' : 'auto'}" :id="expenseDataLine?.line_id" @click="toggleBalanceLineTrID($event)">
                    {{ expenseDataLine?.line_text }}
                    <svg v-if="expenseDataLine.parent_accounts?.length" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="custom_down_arrow w-3 h-3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                    </svg>
                  </td>
                  <td class="text-sm text-right">{{ expenseDataLine?.closing_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ expenseDataLine?.closing_balance_status }}</td>
                  <td class="text-sm text-right font-bold"></td>
                </tr>
                <template v-for="(lineParentAccount, lineParentAccountIndex) in expenseDataLine.parent_accounts">
                  <tr :class="'balance_account_'+expenseDataLine?.line_id" style="display: none" class="third_label account_row">
                    <td class="relative text-sm balance_line_parent account_name parent_account_line" :style="{'cursor': lineParentAccount.child_accounts?.length ? 'pointer' : 'auto'}" :id="lineParentAccount?.line_id" @click="toggleParentAccountTrID($event)">
                      {{ lineParentAccount?.line_text }}
                      <svg v-if="lineParentAccount.child_accounts?.length" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="custom_down_arrow w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                      </svg>
                    </td>
                    <td class="text-sm text-right">{{ lineParentAccount?.closing_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ lineParentAccount?.closing_balance_status }}</td>
                    <td class="text-sm text-right font-bold"></td>
                  </tr>
                  <template v-for="(lineChildAccount, lineChildAccountIndex) in lineParentAccount.child_accounts">
                    <tr :class="['parent_account_'+lineParentAccount?.line_id,'hide_balance_account_'+expenseDataLine?.line_id]" class="fourth_label account_row" style="display: none;background-color: #CAF1F1">
                      <td class="relative text-sm balance_line_parent_child account_name child_account_line" :style="{'cursor': lineChildAccount.grandchild_accounts?.length ? 'pointer' : 'auto'}" :id="lineChildAccount?.line_id" @click="toggleChildAccountTrID($event)">
                        {{ lineChildAccount?.line_text }}
                        <svg v-if="lineChildAccount.grandchild_accounts?.length" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="custom_down_arrow w-3 h-3">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                        </svg>
                      </td>
                      <td class="text-sm text-right">{{ lineChildAccount?.closing_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ lineChildAccount?.closing_balance_status }}</td>
                      <td class="text-sm text-right font-bold"></td>
                    </tr>
                    <template v-for="(lineGrandChildAccount, lineGrandChildAccountIndex) in lineChildAccount.grandchild_accounts">
                      <tr :class="['account_'+lineChildAccount?.line_id,'hide_balance_account_'+expenseDataLine?.line_id,'hide_parent_account_'+lineParentAccount?.line_id]" class="fifth_label" style="display: none;background-color: #e0edff">
                        <td class="text-sm balance_line_parent_grand_child account_name account_line">{{ lineGrandChildAccount?.line_text }}</td>
                        <td class="text-sm text-right">{{ lineGrandChildAccount?.closing_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ lineGrandChildAccount?.closing_balance_status }}</td>
                        <td class="text-sm text-right font-bold"></td>
                      </tr>
                    </template>
                  </template>
                </template>
              </template>
              <tr v-if="incomeStatements?.sale_performance?.expense_id == expenseData?.line_id && incomeStatements?.sale_performance?.profit_on_sale > 0">
                <td class="text-sm balance_header">Gross Profit on Sale</td>
                <td class="text-sm text-right font-bold"></td>
                <td class="text-sm text-right font-bold">{{ incomeStatements?.sale_performance?.profit_on_sale?.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
              </tr>
              <tr v-if="incomeStatements?.service_performance?.expense_id == expenseData?.line_id && incomeStatements?.service_performance?.profit_on_service > 0">
                <td class="text-sm balance_header">Gross Profit on Service</td>
                <td class="text-sm text-right font-bold"></td>
                <td class="text-sm text-right font-bold">{{ incomeStatements?.service_performance?.profit_on_service?.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
              </tr>
            </template>
            <tr v-if="incomeStatements?.performance?.profit > 0 && incomeStatements?.performance?.profit > 0">
              <td class="text-sm balance_header">Net Profit</td>
              <td class="text-sm text-right font-bold"></td>
              <td class="text-sm text-right font-bold">{{ incomeStatements?.performance?.profit?.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
            </tr>
            </tbody>
          </table>
        </div>
        <div class="w-full overflow-x-auto">
          <table class="w-full whitespace-no-wrap mb-1">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800" style="background-color: #369382;color: #fff">
              <th colspan="3" class="bg-green-600 text-white"> Income </th>
            </tr>
            </thead>
            <tbody class="bg-white dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <template v-for="(incomeData, incomeDataIndex) in incomeStatements.incomes" :key="incomeDataIndex">
              <template v-for="(incomeDataLine, incomeDataLineIndex) in incomeData.lines">
                <tr class="text-gray-700 dark-disabled:text-gray-400" style="background-color: #DBECDB" v-if="incomeDataLineIndex === 0">
                  <td class="text-sm balance_header">{{ incomeData?.line_text }}</td>
                  <td class="text-sm text-right font-bold"></td>
                  <td class="text-sm text-right font-bold">{{ incomeData?.closing_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ incomeData?.closing_balance_status }}</td>
                </tr>
                <tr style="background-color: #eff1f1" class="second_label">
                  <td class="relative text-sm balance_line_style balance_line" :style="{'cursor': incomeDataLine.parent_accounts?.length ? 'pointer' : 'auto'}" :id="incomeDataLine?.line_id" @click="toggleBalanceLineTrID($event)">
                    {{ incomeDataLine?.line_text }}
                    <svg v-if="incomeDataLine.parent_accounts?.length" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="custom_down_arrow w-3 h-3">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                    </svg>
                  </td>
                  <td class="text-sm text-right">{{ incomeDataLine?.closing_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ incomeDataLine?.closing_balance_status }}</td>
                  <td class="text-sm text-right font-bold"></td>
                </tr>
                <template v-for="(lineParentAccount, lineParentAccountIndex) in incomeDataLine.parent_accounts">
                  <tr :class="'balance_account_'+incomeDataLine?.line_id" style="display: none" class="third_label account_row">
                    <td class="relative text-sm balance_line_parent account_name parent_account_line" :style="{'cursor': lineParentAccount.child_accounts?.length ? 'pointer' : 'auto'}" :id="lineParentAccount?.line_id" @click="toggleParentAccountTrID($event)">
                      {{ lineParentAccount?.line_text }}
                      <svg v-if="lineParentAccount.child_accounts?.length" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="custom_down_arrow w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                      </svg>
                    </td>
                    <td class="text-sm text-right">{{ lineParentAccount?.closing_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ lineParentAccount?.closing_balance_status }}</td>
                    <td class="text-sm text-right font-bold"></td>
                  </tr>
                  <template v-for="(lineChildAccount, lineChildAccountIndex) in lineParentAccount.child_accounts">
                    <tr :class="['parent_account_'+lineParentAccount?.line_id,'hide_balance_account_'+incomeDataLine?.line_id]" class="fourth_label account_row" style="display: none;background-color: #CAF1F1">
                      <td class="relative text-sm balance_line_parent_child account_name child_account_line" :style="{'cursor': lineChildAccount.grandchild_accounts?.length ? 'pointer' : 'auto'}" :id="lineChildAccount?.line_id" @click="toggleChildAccountTrID($event)">
                        {{ lineChildAccount?.line_text }}
                        <svg v-if="lineChildAccount.grandchild_accounts?.length" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="custom_down_arrow w-3 h-3">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                        </svg>
                      </td>
                      <td class="text-sm text-right">{{ lineChildAccount?.closing_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ lineChildAccount?.closing_balance_status }}</td>
                      <td class="text-sm text-right font-bold"></td>
                    </tr>
                    <template v-for="(lineGrandChildAccount, lineGrandChildAccountIndex) in lineChildAccount.grandchild_accounts">
                      <tr :class="['account_'+lineChildAccount?.line_id,'hide_balance_account_'+incomeDataLine?.line_id,'hide_parent_account_'+lineParentAccount?.line_id]" class="fifth_label" style="display: none;background-color: #e0edff">
                        <td class="text-sm balance_line_parent_grand_child account_name account_line">{{ lineGrandChildAccount?.line_text }}</td>
                        <td class="text-sm text-right">{{ lineGrandChildAccount?.closing_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ lineGrandChildAccount?.closing_balance_status }}</td>
                        <td class="text-sm text-right font-bold"></td>
                      </tr>
                    </template>
                  </template>
                </template>
              </template>
              <tr v-if="incomeStatements?.sale_performance?.income_id == incomeData?.line_id && incomeStatements?.sale_performance?.loss_on_sale > 0">
                <td class="text-sm balance_header">Gross Loss on Sale</td>
                <td class="text-sm text-right font-bold"></td>
                <td class="text-sm text-right font-bold">{{ incomeStatements?.sale_performance?.loss_on_sale?.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
              </tr>
              <tr v-if="incomeStatements?.service_performance?.income_id == incomeData?.line_id && incomeStatements?.service_performance?.loss_on_service > 0">
                <td class="text-sm balance_header">Gross Loss on Service</td>
                <td class="text-sm text-right font-bold"></td>
                <td class="text-sm text-right font-bold">{{ incomeStatements?.service_performance?.loss_on_service?.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
              </tr>
              <tr v-if="incomeStatements?.performance?.loss > 0 && incomeStatements?.performance?.loss > 0">
                <td class="text-sm balance_header">Net Loss</td>
                <td class="text-sm text-right font-bold"></td>
                <td class="text-sm text-right font-bold">{{ incomeStatements?.performance?.loss?.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
              </tr>
            </template>
            </tbody>
          </table>
        </div>
      </div>
      <div class="w-full overflow-hidden grid grid-cols-2 gap-1">
        <table class="w-full">
          <tfoot>
          <tr style="background-color: #E3E3E3">
            <td class="text-sm font-bold text-right bg-green-600 text-white" colspan="2">Total Expense</td>
            <td class="text-sm font-bold text-right bg-green-600 text-white">{{ incomeStatements?.grand_total_expense?.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
          </tr>
          </tfoot>
        </table>
        <table class="w-full">
          <tfoot>
          <tr style="background-color: #E3E3E3">
            <td class="text-sm font-bold text-right bg-green-600 text-white" colspan="2">Total Income</td>
            <td class="text-sm font-bold text-right bg-green-600 text-white">{{ incomeStatements?.grand_total_income?.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
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