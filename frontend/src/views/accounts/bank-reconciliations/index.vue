<script setup>
import {onMounted, watchEffect} from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useBankReconciliation from '../../../composables/accounts/useBankReconciliation';
import Title from "../../../services/title";
import Paginate from '../../../components/utils/paginate.vue';
import moment from "moment";

const {transaction, transactions, updateBankReconciliation, getBankTransactions, isLoading} = useBankReconciliation();

const { setTitle } = Title();

setTitle('List of Bank Transactions');

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

function updateBankReconciliationDate(event,transactionId){
    if (!confirm('Are you sure you want to posting?')) {
      return;
    }
   const element = event.currentTarget;
   const value = element.closest('tr').querySelector('.transaction_date').value;
   if(value){
    let data = {
      date: value,
      transaction_id: transactionId
    };
    updateBankReconciliation(data);
   }
}

onMounted(() => {
  watchEffect(() => {
    getBankTransactions(props.page, true);
  });
});

</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200"> Bank Reconciliations </h2>
    <router-link :to="{ name: 'accounts.transactions.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61]  rounded-lg active:bg-[#0F6B61]  hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
    </router-link>
  </div>
  <!-- Table -->
  <div class="w-full overflow-hidden">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">#</th>
          <th class="px-4 py-3"> Voucher <br> Date </th>
          <th class="px-4 py-3"> Voucher <br> Type </th>
          <th class="px-4 py-3"> Bill / MR </th>
          <th class="px-4 py-3"> Cheque <br> Type </th>
          <th class="px-4 py-3"> Cheque <br> Number </th>
          <th class="px-4 py-3"> Cheque <br> Date </th>
          <th class="px-4 py-3"> Account Head </th>
          <th class="px-4 py-3"> Debit Amount </th>
          <th class="px-4 py-3"> Credit Amount </th>
          <th class="px-4 py-3"> T. Date </th>
          <th class="px-4 py-3"> Status </th>
          <th class="px-4 py-3 text-center">Actions</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">

          <template v-for="(transaction, index) in (transactions?.data ? transactions?.data : transactions)" :key="transaction.id">
            <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(ledger, ledgerIndex) in transaction.ledger_entries" :key="ledgerIndex"
                :class="{'bg-white': index % 2 === 0, 'bg-gray-100': index % 2 !== 0}"
            >

                <td class="px-4 py-3 text-sm" v-if="ledgerIndex == 0" :rowspan="transaction.total_ledger"> {{ transactions?.from + index }}</td>
                <td class="px-4 py-3 text-sm" v-if="ledgerIndex == 0" :rowspan="transaction.total_ledger"> <nobr> {{ moment(transaction.transaction_date).format('DD-MM-YYYY') }} </nobr> </td>
                <td class="px-4 py-3 text-sm" v-if="ledgerIndex == 0" :rowspan="transaction.total_ledger"> {{ transaction.voucher_type }} </td>
                <td class="px-4 py-3 text-sm !text-left" v-if="ledgerIndex == 0" :rowspan="transaction.total_ledger"> {{ ledger.bill_no }} </td>

                <td class="px-4 py-3 text-sm !text-left" v-if="ledgerIndex == 0" :rowspan="transaction.total_ledger"> {{ transaction.instrument_type }} </td>
                <td class="px-4 py-3 text-sm !text-left" v-if="ledgerIndex == 0" :rowspan="transaction.total_ledger"> {{ transaction.instrument_no }} </td>
                <td class="px-4 py-3 text-sm !text-left" v-if="ledgerIndex == 0" :rowspan="transaction.total_ledger"> <nobr> {{ transaction.instrument_date }} </nobr> </td>

                <td class="px-4 py-3 text-sm !text-left"> {{ ledger.account.account_name }} </td>
                
                <td class="px-4 py-3 text-sm !text-right"> {{ ledger.dr_amount }} </td>
                <td class="px-4 py-3 text-sm !text-right"> {{ ledger.cr_amount }} </td>
                <td class="px-4 py-3 text-sm" v-if="ledgerIndex == 0" :rowspan="transaction.total_ledger">
                  <nobr>
                    <input v-if="!transaction?.bank_reconciliation" type="date" class="transaction_date form-input">
                    <span v-else> {{ moment(transaction?.bank_reconciliation?.date).format('DD-MM-YYYY') }} </span>
                  </nobr>
                </td>
                <td class="px-4 py-3 text-sm" v-if="ledgerIndex == 0" :rowspan="transaction.total_ledger">
                  <span v-if="transaction?.bank_reconciliation?.status" class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                          {{ transaction?.bank_reconciliation?.status }}
                  </span>
                  <span v-else class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">
                    <nobr>Not Posting Yet</nobr>
                  </span>
                </td>
                <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600" v-if="ledgerIndex == 0" :rowspan="transaction.total_ledger">
                  <nobr>
                    <action-button :action="'posting'" :class="{'custom_opacity': transaction?.bank_reconciliation}" @click="updateBankReconciliationDate($event,transaction.id)"></action-button>
                    <action-button :action="'edit'" :to="{ name: 'accounts.transactions.edit', params: { transactionId: transaction.id } }"></action-button>
                    <action-button :action="'show'" :to="{ name: 'accounts.transactions.show', params: { transactionId: transaction.id } }"></action-button>
                    <action-button @click="deleteTransaction(transaction.id)" :action="'delete'"></action-button> 
                    <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: transaction.subject_type,subject_id: transaction.id } }"></action-button> -->
                  </nobr>
                </td>
              </tr>
            </template>
        </tbody>
        <tfoot v-if="!(transactions?.data ? transactions?.data.length : transactions?.length)" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="13">Loading...</td>
        </tr>
        <tr v-else-if="!(transactions?.data ? transactions?.data.length : transactions?.length)">
          <td colspan="13">No Data found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="transactions" to="accounts.bank-reconciliations.index" :page="page"></Paginate>
  </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
  .tab {
    @apply p-2.5 text-xs;
  }
  thead tr {
    @apply font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  th {
    @apply tab text-center;
  }
  tbody {
    @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
  }
  tbody tr {
    @apply text-gray-700 dark:text-gray-400;
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