<script setup>
import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import usePermission from "../../../composables/administration/usePermission";
import Title from "../../../services/title";
import {watchEffect, ref, watch} from "vue";
import useDebouncedRef from '../../../composables/useDebouncedRef';

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();

setTitle('Permissions');

const { permissions, getPermissions, deletePermission, isLoading } = usePermission();

// Code for global search starts here
const columns = ["name"];
const searchKey = useDebouncedRef('', 800);
const table = "permissions";

watch(searchKey, newQuery => {
  getPermissions(props.page, columns, searchKey.value, table);
});
// Code for global search end here

onMounted(() => {
  watchEffect(() => {
    getPermissions(props.page, columns, searchKey.value, table);
  });
});

</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Permission List</h2>
<!--    <router-link :to="{ name: 'administration.user.permission.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">-->
<!--      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">-->
<!--        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />-->
<!--      </svg>-->
<!--    </router-link>-->
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <span class="w-full text-xs font-medium text-gray-500 whitespace-no-wrap">Showing {{ permissions?.from }}-{{ permissions?.to }} of {{ permissions?.total }}</span>
    <!-- Search -->
    <div class="relative w-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
      </svg>
      <input type="text"  v-model.trim="searchKey" placeholder="Search..." class="search" />
    </div>
  </div>

  <div>
    <div class="w-full overflow-hidden">
      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap mb-5">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="p-1">SL</th>
            <th class="p-1">Permission Name</th>
<!--            <th class="p-1">Action</th>-->
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr v-for="(permissionData,index) in permissions?.data" :key="index">
            <td>{{ permissions?.from + index }}</td>
            <td>{{ permissionData?.name }}</td>
<!--            <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">-->
<!--              <action-button :action="'edit'" :to="{ name: 'administration.user.permission.edit', params: { permissionId: permissionData?.id } }"></action-button>-->
<!--              <action-button @click="deletePermission(permissionData?.id)" :action="'delete'"></action-button>-->
<!--            </td>-->
          </tr>
          </tbody>
          <tfoot v-if="!permissions?.data?.length" class="bg-white dark:bg-gray-800">
          <tr v-if="isLoading">
            <td colspan="2">Loading...</td>
          </tr>
          <tr v-else-if="!permissions?.data?.length">
            <td colspan="2">No permission list found.</td>
          </tr>
          </tfoot>
        </table>
      </div>
      <div v-if="permissions?.data?.length" class="grid px-4 py-1 text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
        <span class="flex items-center col-span-3">Showing {{ permissions?.from }}-{{ permissions?.to }} of {{ permissions?.total }}</span>
        <span class="col-span-2"></span>
        <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                <nav aria-label="Table navigation">
                    <ul class="inline-flex items-center">
                        <li v-for="(link, index) in permissions?.links" :key="index">
                            <router-link :disabled="permissions.current_page === 1" :to="{ name: 'administration.user.permission.index', query: { page: permissions.current_page >= 2 ? permissions.current_page - 1 : 1 } }" v-if="link.label.includes('Previous')" class="paginate-btn">
                                <svg aria-hidden="true" class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </router-link>
                            <router-link v-else-if="link.label.includes('Next')" :disabled="permissions.current_page === permissions.last_page" :to="{ name: 'administration.user.permission.index', query: { page: permissions.current_page <= permissions.last_page - 1 ? permissions.current_page + 1 : permissions.last_page } }" class="paginate-btn">
                                <svg class="w-4 h-4 fill-current" aria-hidden="true" viewBox="0 0 20 20">
                                    <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </router-link>
                            <button v-else-if="link.label == '...'" class="paginate-btn">
                                <span>{{ link?.label }}</span>
                            </button>
                            <router-link v-else :to="{ name: 'administration.user.permission.index', query: { page: parseInt(link.label) } }" class="paginate-btn" :class="{ 'paginate-active': link?.active }">
                                <span>{{ link?.label }}</span>
                            </router-link>
                        </li>
                    </ul>
                </nav>
            </span>
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
  .paginate-btn {
    @apply px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple;
  }
  .paginate-active {
    @apply text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600;
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

.search-result {
  @apply px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-300;
}
.search {
  @apply float-right  pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
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