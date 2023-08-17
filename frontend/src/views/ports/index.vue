<script setup>
import { onMounted, watchEffect, watch } from 'vue';
import usePort from '../../composables/usePort';
import ActionButton from '../../components/buttons/ActionButton.vue';
import Title from "../../services/title";
import {ref} from "vue";
import useDebouncedRef from "../../composables/useDebouncedRef";

const props = defineProps({
    page: {
        type: Number,
        default: '',
    },
});
const { ports, getPorts, deletePort, isLoading } = usePort();
const { setTitle } = Title();
setTitle('Ports');

// Code for global search starts here
const columns = ["name", "code"];
const searchKey = useDebouncedRef('', 800);
const table = "ports";

watch(searchKey, newQuery => {
  getPorts(props.page, columns, searchKey.value, table);
});
// Code for global search end here

onMounted(() => {
    watchEffect(() => {
      getPorts(props.page, columns, searchKey.value, table);
    });
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Ports</h2>
        <router-link :to="{ name: 'ports.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
        </router-link>
    </div>

  <div class="flex items-center justify-between mb-2 select-none">
    <span class="w-full text-xs font-medium text-gray-500 whitespace-no-wrap">Showing {{ ports?.from }}-{{ ports?.to }} of {{ ports?.total }}</span>
    <!-- Search -->
    <div class="relative w-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
      </svg>
      <input type="text"  v-model.trim="searchKey" placeholder="Search..." class="search" />
    </div>
  </div>
    <!-- Table -->
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody v-if="ports?.data?.length">
                    <tr v-for="(port, index) in ports.data" :key="port?.id">
                        <td>{{ ports.from + index }}</td>
                        <td>{{ port?.code }}</td>
                        <td>{{ port?.name }}</td>
                        <td class="items-center justify-center space-x-2 text-gray-600">
                            <action-button :action="'edit'" :to="{ name: 'ports.edit', params: { portId: port.id } }"></action-button>
                            <action-button @click="deletePort(port.id)" :action="'delete'"></action-button>
                          <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: port.subject_type,subject_id: port.id } }"></action-button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!ports?.data?.length" class="bg-white dark:bg-gray-800">
                    <tr v-if="isLoading">
                        <td colspan="4">Loading...</td>
                    </tr>
                    <tr v-else-if="!ports?.data?.length">
                        <td colspan="4">No ports found.</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Pagination -->
        <div v-if="ports?.data?.length" class="grid px-4 py-1 text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
            <span class="flex items-center col-span-3">Showing {{ ports?.from }}-{{ ports?.to }} of {{ ports?.total }}</span>
            <span class="col-span-2"></span>
            <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                <nav aria-label="Table navigation">
                    <ul class="inline-flex items-center">
                        <li v-for="(link, index) in ports?.links" :key="index">
                            <router-link :disabled="ports.current_page === 1" :to="{ name: 'ports.index', query: { page: ports.current_page >= 2 ? ports.current_page - 1 : 1 } }" v-if="link.label.includes('Previous')" class="paginate-btn">
                                <svg aria-hidden="true" class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </router-link>
                            <router-link v-else-if="link.label.includes('Next')" :disabled="ports.current_page === ports.last_page" :to="{ name: 'ports.index', query: { page: ports.current_page <= ports.last_page - 1 ? ports.current_page + 1 : ports.last_page } }" class="paginate-btn">
                                <svg class="w-4 h-4 fill-current" aria-hidden="true" viewBox="0 0 20 20">
                                    <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </router-link>
                            <button v-else-if="link.label == '...'" class="paginate-btn">
                                <span>{{ link?.label }}</span>
                            </button>
                            <router-link v-else :to="{ name: 'ports.index', query: { page: parseInt(link.label) } }" class="paginate-btn" :class="{ 'paginate-active': link?.active }">
                                <span>{{ link?.label }}</span>
                            </router-link>
                        </li>
                    </ul>
                </nav>
            </span>
        </div>
    </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
    thead tr {
        @apply text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
    }
    th {
        @apply px-4 py-3 text-center;
    }
    tbody {
        @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
    }
    tbody tr {
        @apply text-gray-700 dark:text-gray-400;
    }
    tbody tr td {
        @apply px-4 py-3 text-xs text-center;
    }
    tfoot td {
        @apply px-4 py-3 text-xs text-center;
    }
    .paginate-btn {
        @apply px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple;
    }
    .paginate-active {
        @apply text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600;
    }
  table, th,td{
    @apply border border-collapse;
  }
  .search {
    @apply float-right  pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
  }
}
</style>