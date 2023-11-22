<script setup>
import useTransaction from '../../../composables/accounts/useTransaction';
import Title from "../../../services/title";
import { ref } from "vue";
import useAisReport from "../../../composables/accounts/useAisReport";
import useAccountCommonApiRequest from "../../../composables/accounts/useAccountCommonApiRequest";
import Store from "../../../store";

const { ledgers, getLedgers, isLoading} = useAisReport();
const { bgColor } = useTransaction();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const { allAccountLists, getAccount } = useAccountCommonApiRequest();

const { setTitle } = Title();

setTitle('AIS Report - Ledger');

const searchParams = ref({
  acc_account_id: null,
  from_date: '',
  till_date: '',
});


function fetchAccounts(search, loading) {
  if(search.length < 3) {
    return;
  } else {
    getAccount(search, businessUnit.value, loading);
  }
}
</script>

<template>
  <!-- Table -->
  <!--  <button type="button"> Click Me </button>-->
  <form @submit.prevent="getLedgers(searchParams)">
    <div class="w-full flex items-center justify-between mb-2 my-2 select-none">
      <fieldset class="w-full grid grid-cols-4 gap-1 px-2 pb-3 border border-gray-700 rounded ">
        <legend class="px-2 text-gray-700 uppercase ">Search Ledger</legend>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Account <span class="text-red-500">*</span></label>
          <v-select :options="allAccountLists" placeholder="--Choose an option--" @search="fetchAccounts"  v-model="searchParams.acc_account_id" label="account_name" :reduce="allAccountLists=> allAccountLists.acc_account_id" class="block w-full rounded form-input">
            <template #search="{attributes, events}">
              <input class="vs__search" :required="!searchParams.acc_account_id" v-bind="attributes" v-on="events"/>
            </template>
          </v-select>
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
            <tr v-if="ledgers?.currentLedgers?.length" style="background-color: #369382;color: white" id="opening_tr">
              <td colspan="5" class="text-gray-700  font-bold">Opening Balance</td>
              <td class="text-gray-700  font-bold">{{ ledgers?.opening_dr_amount }}</td>
              <td class="text-gray-700  font-bold">{{ ledgers?.opening_cr_amount }}</td>
            </tr>
            <tr class="text-gray-700 " v-for="(ledgerData, index) in ledgers?.currentLedgers" :key="index"
                :class="{'bg-white': index % 2 === 0, 'bg-gray-100': index % 2 !== 0}"
            >
                <td class="text-sm"> {{ ++index }} </td>
                <td class="text-sm"> {{ ledgerData?.transaction?.transaction_date }} </td>
                <td class="text-sm !text-left"> {{ ledgers?.account_name }} </td>
              <td class="text-sm"> {{ ledgerData?.transaction?.voucher_type }} </td>
              <td class="text-sm transaction_col" style="color: blueviolet">
                <router-link :to="{ name: 'acc.transactions.show', params: { transactionId: ledgerData?.transaction?.id } }" target="_blank">
                  {{ ledgerData?.transaction?.id }}
                </router-link>
              </td>
              <td class="text-sm"> {{ ledgerData?.dr_amount }} </td>
              <td class="text-sm"> {{ ledgerData?.cr_amount }} </td>
              </tr>
            <tr v-if="ledgers?.currentLedgers?.length" style="background-color: #369382;" id="close_tr">
              <td colspan="5" class="text-gray-700  font-bold">Closing Balance</td>
              <td class="text-gray-700  font-bold">{{ ledgers?.closing_dr_amount }}</td>
              <td class="text-gray-700  font-bold">{{ ledgers?.closing_cr_amount }}</td>
            </tr>
        </tbody>
        <tfoot v-if="!ledgers?.currentLedgers?.length" class="bg-white ">
          <tr v-if="isLoading">
            <td colspan="7">Loading...</td>
          </tr>
          <tr v-else-if="!ledgers?.currentLedgers?.length">
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