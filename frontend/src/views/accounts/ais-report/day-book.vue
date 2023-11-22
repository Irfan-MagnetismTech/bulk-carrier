<script setup>
import useTransaction from '../../../composables/accounts/useTransaction';
import Title from "../../../services/title";
import { ref } from "vue";
import useAisReport from "../../../composables/accounts/useAisReport";

const { dayBooks, getDayBooks, isLoading} = useAisReport();
const { bgColor, allAccount, getAccount } = useTransaction();

const { setTitle } = Title();

setTitle('AIS Report - Day Book');

const searchParams = ref({
  account_id: null,
  from_date: '',
  till_date: '',
});


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
  <form @submit.prevent="getDayBooks(searchParams)">
    <div class="w-full flex items-center justify-between mb-2 my-2 select-none">
      <fieldset class="w-full grid grid-cols-4 gap-1 px-2 pb-3 border border-gray-700 rounded ">
        <legend class="px-2 text-gray-700 uppercase ">Search Day Book</legend>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Account</label>
          <v-select :options="allAccount" placeholder="--Choose an option--" @search="fetchAccounts"  v-model="searchParams.account_id" label="account_name" :reduce="allAccount=> allAccount.account_id" class="block w-full rounded form-input"></v-select>
        </div>
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
      <table class="w-full whitespace-no-wrap">
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50  ">
          <th class="px-4 py-3">#</th>
          <th class="px-4 py-3"> Date </th>
          <th class="px-4 py-3"> Particulars </th>
          <th class="px-4 py-3"> Voucher Type </th>
          <th class="px-4 py-3"> Voucher No </th>
          <th class="px-4 py-3"> Debit Amount </th>
          <th class="px-4 py-3"> Credit Amount </th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y ">
            <tr class="text-gray-700 " v-for="(bookData, index) in dayBooks" :key="index"
                :class="{'bg-white': index % 2 === 0, 'bg-gray-100': index % 2 !== 0}"
            >
                <td class="text-sm"> {{ ++index }} </td>
                <td class="text-sm"> {{ bookData?.transaction?.transaction_date }} </td>
                <td class="text-sm !text-left"> {{ bookData?.account?.account_name }} </td>
              <td class="text-sm"> {{ bookData?.transaction?.voucher_type }} </td>
              <td class="text-sm transaction_col" style="color: blueviolet">
                <router-link :to="{ name: 'acc.transactions.show', params: { transactionId: bookData?.transaction?.id } }" target="_blank">
                  {{ bookData?.transaction?.id }}
                </router-link>
              </td>
              <td class="text-sm"> {{ bookData?.dr_amount }} </td>
              <td class="text-sm"> {{ bookData?.cr_amount }} </td>
              </tr>
        </tbody>
        <tfoot v-if="!dayBooks?.length" class="bg-white ">
        <tr v-if="isLoading">
          <td colspan="7">Loading...</td>
        </tr>
        <tr v-else-if="!dayBooks?.length">
          <td colspan="7">No Data found.</td>
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
    @apply p-2.5 text-xs;
  }
  thead tr {
    @apply font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50  ;
  }
  th {
    @apply tab text-center;
  }
  tbody {
    @apply bg-white divide-y ;
  }
  tbody tr {
    @apply text-gray-700 ;
  }
  tbody tr td {
    @apply tab text-center;
  }
  tfoot td {
    @apply tab text-center;
  }
}

.input-group {
  @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
}
.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 ;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed ;
}
.form-input {
  @apply block mt-1 text-sm rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple ;
}
table, th,td{
  @apply border border-collapse;
}
.search-result {
  @apply px-4 py-3 text-sm text-center text-gray-600 ;
}
.search {
  @apply float-right  pr-10 text-sm border border-gray-300 rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  dark:border-0;
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