<script setup>
import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useRole from "../../../composables/administration/useRole";
import Title from "../../../services/title";
import Swal from "sweetalert2";

const { roles, getRoles, deleteRole, isLoading } = useRole();

const { setTitle } = Title();

setTitle('Roles');

function deleteRoleByID(roleId) {
  Swal.fire({
    title: '',
    text: 'Are you sure you want to delete this role?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, Delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteRole(roleId);
    }
  })
}

onMounted(() => {
  getRoles();
});
</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Role List</h2>
    <router-link :to="{ name: 'administration.user.roles.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
    </router-link>
  </div>
  <div>
    <div class="w-full overflow-hidden">
      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="p-1">SL</th>
            <th class="p-1 w-2/12">Role</th>
            <th class="p-1">Permission Name</th>
            <th class="p-1 w-1/12">Action</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr v-for="(roleData,index) in roles" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ roleData?.name }}</td>
            <td style="text-align: left !important;">
              <span v-for="(permission,index) in roleData?.permissions" :key="index" class="text-xs mr-2 mb-2 inline-block py-1 px-2.5 leading-none whitespace-nowrap align-baseline font-bold bg-gray-200 text-gray-700 rounded">
                {{ permission?.name }}
              </span>
            </td>
            <td style="width: 10%" class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
              <action-button :action="'edit'" :to="{ name: 'administration.user.roles.edit', params: { roleId: roleData?.id } }"></action-button>
              <action-button @click="deleteRoleByID(roleData?.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!roles?.length" class="bg-white dark:bg-gray-800">
          <tr v-if="isLoading">
            <td colspan="4">Loading...</td>
          </tr>
          <tr v-else-if="!roles?.length">
            <td colspan="4">No role list found.</td>
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