<script setup>
import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useApprovalManagement from "../../../composables/administration/useApprovalManagement";
import Title from "../../../services/title";
import {ref} from "vue";

const { approvals, getApproval, deleteApproval, isLoading } = useApprovalManagement();

const { setTitle } = Title();

setTitle('Approvals');

onMounted(() => {
  getApproval();
});
</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 ">Approval Management</h2>
    <router-link :to="{ name: 'administration.approval.management.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
    </router-link>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <span class="w-full text-xs font-medium text-gray-500 whitespace-no-wrap">Showing: {{ searchResults?.length ?? 0 }} of {{ containers?.length ?? 0 }}</span>
    <!-- Search -->
    <div class="relative w-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
      </svg>
      <input type="text"  v-model.trim="search" placeholder="Search..." class="search" />
    </div>
  </div>
  <div>
    <div class="w-full overflow-hidden">
      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50  ">
            <th class="p-1">SL</th>
            <th class="p-1">Subject</th>
            <th class="p-1">Assigned Approver</th>
            <th class="p-1">Action</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y ">
          <tr v-for="(approver,index) in approvals" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ approver?.model }}</td>
            <td>
              <ul>
                <li v-for="(user,userIndex) in approver?.approval_bodies"><span class="text-xs mr-2 mb-2 inline-block py-1 px-2.5 leading-none whitespace-nowrap align-baseline font-bold bg-gray-200 text-gray-700 rounded">
                  Step - {{ user?.approval_order }} :  {{ user?.approver?.name }} <span style="color: #664cc3">({{ user?.approver_role }})</span>
                </span></li>
              </ul>
            </td>
            <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
              <action-button :action="'edit'" :to="{ name: 'administration.approval.management.edit', params: { approvalId: approver?.id } }"></action-button>
              <action-button @click="deleteApproval(approver?.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!approvals?.length" class="bg-white ">
          <tr v-if="isLoading">
            <td colspan="4">Loading...</td>
          </tr>
          <tr v-else-if="!approvals?.length">
            <td colspan="4">No data found.</td>
          </tr>
          </tfoot>
        </table>
      </div>
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
  @apply float-right  pr-10 text-sm border border-gray-300 rounded   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  ;
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