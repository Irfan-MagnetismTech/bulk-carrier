<script setup>
import useTransaction from '../../../composables/accounts/useTransaction';
import Title from "../../../services/title";
import { ref } from "vue";
import useAisReport from "../../../composables/accounts/useAisReport";

const { trialBalances, getTrialBalance, isLoading} = useAisReport();
const { bgColor, allAccount, getAccount } = useTransaction();

const { setTitle } = Title();

setTitle('AIS Report - Trial Balance');

const searchParams = ref({
  account_id: null,
  from_date: '',
  till_date: '',
});

function toggleBalanceLineTrID(event) {
  let currentLine = event.target.id;
  document.querySelectorAll(".balance_account_" + currentLine).forEach(function (el) {
    el.style.display = (el.style.display == "none") ? "table-row" : "none";
  });
  document.querySelectorAll(".hide_parent_account_" + currentLine).forEach(function (el) {
    el.style.display = "none";
  });
}

function toggleParentAccountTrID(event) {
  let parentAccount = event.target.id;
  document.querySelectorAll(".parent_account_" + parentAccount).forEach(function (el) {
    el.style.display = (el.style.display == "none") ? "table-row" : "none";
  });
  document.querySelectorAll(".hide_child_account_" + parentAccount).forEach(function (el) {
    el.style.display = "none";
  });
}

function toggleChildAccountTrID(event) {
  let childAccount = event.target.id;
  document.querySelectorAll(".child_account_" + childAccount).forEach(function (el) {
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
</script>

<template>
  <!-- Table -->
  <!--  <button type="button"> Click Me </button>-->
  <form @submit.prevent="getTrialBalance(searchParams)">
    <div class="w-full flex items-center justify-between mb-2 my-2 select-none">
      <fieldset class="w-full grid grid-cols-4 gap-1 px-2 pb-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Search Trial Balance</legend>
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
          <button type="submit" :disabled="isLoading" class="w-full flex items-center justify-center px-2 mt-1 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">Submit</button>
        </div>
      </fieldset>
    </div>
  </form>
  <!-- Table -->
  <div class="w-full overflow-hidden">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap mb-8">
        <thead>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th rowspan="2"> Particulars </th>
          <th rowspan="2"> Opening Balance </th>
          <th colspan="2"> Transactions </th>
          <th rowspan="2"> Closing Balance </th>
        </tr>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class=""> Debit </th>
          <th class=""> Credit </th>
        </tr>
        </thead>
        <tbody class="bg-white dark:divide-gray-700 dark:bg-gray-800">
        <template v-for="(trialBalanceData, trialBalanceDataIndex) in trialBalances" :key="trialBalanceDataIndex">
          <template v-for="(trialBalanceDataLine, trialBalanceDataLineIndex) in trialBalanceData.lines">
            <tr class="text-gray-700 dark:text-gray-400" style="background-color: #DBECDB" v-if="trialBalanceDataLineIndex === 0">
              <td class="text-sm balance_header" :id="trialBalanceData?.line_id">{{ trialBalanceData?.line_text }}</td>
              <td class="text-sm text-right font-bold">{{ trialBalanceData?.opening_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ trialBalanceData?.opening_balance_status }}</td>
              <td class="text-sm text-right">{{ trialBalanceData?.current_dr_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
              <td class="text-sm text-right">{{ trialBalanceData?.current_cr_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
              <td class="text-sm text-right font-bold">{{ trialBalanceData?.closing_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ trialBalanceData?.closing_balance_status }}</td>
            </tr>
            <tr style="background-color: #eff1f1" class="second_label">
              <td class="relative text-sm balance_line_style balance_line" :style="{'cursor': trialBalanceDataLine.parent_accounts?.length ? 'pointer' : 'auto'}" :id="trialBalanceDataLine?.line_id" @click="toggleBalanceLineTrID($event)">
                {{ trialBalanceDataLine?.line_text }}
                <svg v-if="trialBalanceDataLine.parent_accounts?.length" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="custom_down_arrow w-3 h-3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                </svg>
              </td>
              <td class="text-sm text-right">{{ trialBalanceDataLine?.opening_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ trialBalanceDataLine?.opening_balance_status }}</td>
              <td class="text-sm text-right">{{ trialBalanceDataLine?.current_dr_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
              <td class="text-sm text-right">{{ trialBalanceDataLine?.current_cr_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
              <td class="text-sm text-right">{{ trialBalanceDataLine?.closing_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ trialBalanceDataLine?.closing_balance_status }}</td>
            </tr>
            <template v-for="(lineParentAccount, lineParentAccountIndex) in trialBalanceDataLine.parent_accounts">
              <tr :class="'balance_account_'+trialBalanceDataLine?.line_id" style="display: none" class="third_label account_row">
                <td class="relative text-sm account_line parent_account" :style="{'cursor': lineParentAccount.child_accounts?.length ? 'pointer' : 'auto'}" :id="lineParentAccount?.line_id" @click="toggleParentAccountTrID($event)">
                  {{ lineParentAccount?.line_text }}
                  <svg v-if="lineParentAccount.child_accounts?.length" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="custom_down_arrow w-3 h-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                  </svg>
                </td>
                <td class="text-sm text-right">{{ lineParentAccount?.opening_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ lineParentAccount?.opening_balance_status }}</td>
                <td class="text-sm text-right">{{ lineParentAccount?.current_dr_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
                <td class="text-sm text-right">{{ lineParentAccount?.current_cr_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
                <td class="text-sm text-right">{{ lineParentAccount?.closing_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ lineParentAccount?.closing_balance_status }}</td>
              </tr>
              <template v-for="(lineChildAccount, lineChildAccountIndex) in lineParentAccount.child_accounts">
                  <tr :class="['parent_account_'+lineParentAccount?.line_id,'hide_parent_account_'+trialBalanceDataLine?.line_id,'hide_child_account_'+trialBalanceDataLine?.line_id]" class="fourth_label account_row" style="display: none;background-color: #CAF1F1">
                    <td class="relative text-sm account child_account" :style="{'cursor': lineChildAccount.grandchild_accounts?.length ? 'pointer' : 'auto'}" :id="lineChildAccount?.line_id" @click="toggleChildAccountTrID($event)">
                      {{ lineChildAccount?.line_text }}
                      <svg v-if="lineChildAccount.grandchild_accounts?.length" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="custom_down_arrow w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 5.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5" />
                      </svg>
                    </td>
                    <td class="text-sm text-right">{{ lineChildAccount?.opening_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ lineChildAccount?.opening_balance_status }}</td>
                    <td class="text-sm text-right">{{ lineChildAccount?.current_dr_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
                    <td class="text-sm text-right">{{ lineChildAccount?.current_cr_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
                    <td class="text-sm text-right">{{ lineChildAccount?.closing_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ lineChildAccount?.closing_balance_status }}</td>
                  </tr>
                <template v-for="(lineGrandChildAccount, lineGrandChildAccountIndex) in lineChildAccount.grandchild_accounts">
                  <tr :class="['child_account_'+lineChildAccount?.line_id,'hide_parent_account_'+trialBalanceDataLine?.line_id,'hide_child_account_'+lineParentAccount?.line_id]" class="fifth_label account_row" style="display: none;background-color: #e0edff">
                    <td class="text-sm account child_account_style">{{ lineGrandChildAccount?.line_text }}</td>
                    <td class="text-sm text-right">{{ lineGrandChildAccount?.opening_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ lineGrandChildAccount?.opening_balance_status }}</td>
                    <td class="text-sm text-right">{{ lineGrandChildAccount?.current_dr_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
                    <td class="text-sm text-right">{{ lineGrandChildAccount?.current_cr_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }}</td>
                    <td class="text-sm text-right">{{ lineGrandChildAccount?.closing_balance_amount.toLocaleString('en-IN', {maximumFractionDigits:2}) }} {{ lineGrandChildAccount?.closing_balance_status }}</td>
                  </tr>
                </template>
              </template>
            </template>
          </template>
        </template>
        </tbody>
        <tfoot v-if="!trialBalances?.length" class="bg-white dark:bg-gray-800">
          <tr v-if="isLoading">
            <td colspan="5">Loading...</td>
          </tr>
          <tr v-else-if="!trialBalances?.length">
            <td colspan="5">No Data found.</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
  .tab {
    @apply p-1.5 text-xs;
  }
  thead tr {
    @apply font-semibold tracking-wide text-left text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  th {
    @apply text-center;
  }

  tbody tr {
    @apply text-gray-700 dark:text-gray-400;
  }
  tbody tr td {
    @apply tab;
  }
  tfoot td {
    @apply tab text-center;
  }
}

.base_header {
  font-weight: bold;
  text-align: left;
}

.balance_header {
  font-weight: bold;
  padding-left: 20px;
  text-align: left;
}

.balance_line_style {
  font-weight: bold;
  padding-left: 50px;
  text-align: left;
}

.account_line {
  padding-left: 80px;
  text-align: left;
}

.account {
  padding-left: 110px;
  text-align: left;
}

.child_account_style {
  padding-left: 130px;
  text-align: left;
}

.account_row
{
  display: none;
}

table tr,td,th {
  border: 1px solid grey;
}

#close_tr td {
  color: #f1ebeb;
}

#opening_tr td {
  color: #f1ebeb;
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
table, th,td{
  @apply border border-collapse;
}
.search-result {
  @apply px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-300;
}
.search {
  @apply float-right  pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
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