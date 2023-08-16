<script setup>
import {onMounted, watchEffect} from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useCashAccount from '../../../composables/accounts/useCashAccount';
import Title from "../../../services/title";
import Paginate from '../../../components/utils/paginate.vue';

const {cashAccounts, getCashAccounts, deleteCashAccount, isLoading } = useCashAccount();

const { setTitle } = Title();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

setTitle('List of Cash Accounts');

onMounted(() => {
  watchEffect(() => {
    getCashAccounts(props.page, true);
  });
});

</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200"> Cash Accounts </h2>
    <router-link :to="{ name: 'accounts.cash-accounts.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61]  rounded-lg active:bg-[#0F6B61]  hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
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
          <th class="px-4 py-3"> Cash Account Name </th>
          <th class="px-4 py-3"> Account Code </th>
          <th class="px-4 py-3 text-center">Actions</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(cashAccount, index) in (cashAccounts?.data ? cashAccounts?.data : cashAccounts)" :key="cashAccount.id">
          <td class="px-4 py-3 text-sm">{{ cashAccounts?.from + index }}</td>
          <td class="px-4 py-3 text-sm">{{ cashAccount.name }}</td>
          <td class="px-4 py-3 text-sm"></td>
          <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
            <action-button :action="'edit'" :to="{ name: 'accounts.cash-accounts.edit', params: { cashAccountId: cashAccount.id } }"></action-button>
            <!-- <action-button :action="'show'" :to="{ name: 'accounts.cash-accounts.show', params: { cashAccountId: cashAccount.id } }"></action-button> -->
            <action-button @click="deleteCashAccount(cashAccount.id)" :action="'delete'"></action-button> 
            <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: cashAccount.subject_type,subject_id: cashAccount.id } }"></action-button> -->
          </td>
        </tr>
        </tbody>
        <tfoot v-if="!(cashAccounts?.data ? cashAccounts?.data.length : cashAccounts?.length)" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="4">Loading...</td>
        </tr>
        <tr v-else-if="!(cashAccounts?.data ? cashAccounts?.data.length : cashAccounts?.length)">
          <td colspan="4">No Data found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="cashAccounts" to="accounts.cash-accounts.index" :page="page"></Paginate>
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