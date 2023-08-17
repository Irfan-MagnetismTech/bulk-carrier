<script setup>
import { toRefs } from '@vueuse/core';
import { useFuse } from '@vueuse/integrations/useFuse';
import { ref } from 'vue';

const props = defineProps({
    data: {
        default: () => [],
    },
});
const { data } = toRefs(props);
const search = ref('');
const options = ref({
    fuseOptions: {
        keys: ['slot', 'bay', 'weight', 'load', 'discharge', 'container', 'full', 'iso', 'size', 'height', 'group', 'carrier'],
        threshold: 0,
    },
    matchAllWhenSearchEmpty: true,
});
const { results: searchResults } = useFuse(search, data, options);

function addStr(str=null)
{
    if (str === null) {
        return 'N/A';
    }
    if(str.length==6)
    { 
        return '0'+str;
    } else {
        return str;
    }
}
</script>

<template>
    <div class="flex items-center justify-between mb-2 select-none">
        <!-- Search -->
        <div class="relative w-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
            <input type="text" v-model.trim="search" placeholder="Search..." class="search" />
        </div>
    </div>
    <!-- Table -->
    <div class="w-full mb-6 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full md:overflow-x-auto">
            <table class="w-full whitespace-no-wrap border dark:border-0">
                <thead>
                    <tr>
                        <th>Slot</th>
                        <th>Bay</th>
                        <th>Weight</th>
                        <th>Load Port</th>
                        <th>Discharge Port</th>
                        <th>Container ID</th>
                        <th>Status</th>
                        <th>ISO</th>
                        <!-- <th>Group</th> -->
                        <th>MLO</th>
                        <th>Del. Port</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400" v-for="result in searchResults" :key="result?.item?.id">
                        <td>{{ addStr(result?.item?.slot) }}</td>
                        <td>{{ addStr(result?.item?.slot).slice(0, 3) }}</td>
                        <td>{{ result?.item?.weight ?? 'N/A' }}</td>
                        <td>{{ result?.item?.load ?? 'N/A' }}</td>
                        <td>{{ result?.item?.discharge ?? 'N/A' }}</td>
                        <td>{{ result?.item?.container ?? 'N/A' }}</td>
                        <td>{{ result?.item?.full ?? 'N/A' }}</td>
                        <td>{{ result?.item?.iso ?? 'N/A' }}</td>
                        <!-- <td>{{ result?.item?.group ?? 'N/A' }}</td> -->
                        <td>{{ result?.item?.carrier ?? 'N/A' }}</td>
                        <td class="border-r-0">{{ result?.item?.delivery ?? '' }}</td>
                    </tr>
                    <tr v-if="searchResults.length === 0">
                        <td colspan="13" class="search-result">No data available</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style lang="postcss" scoped>
@tailwind components;

@layer components {
    th {
        @apply px-3 py-2;
    }
    td {
        @apply px-3 py-2 text-xs border-r;
    }
    thead tr {
        @apply text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
    }
    tbody {
        @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
    }
    .search-result {
        @apply px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-300;
    }
    .search {
        @apply float-right w-1/5 pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
    }
    .filter-wrapper {
        @apply border border-gray-300 rounded dark:border-none dark:bg-gray-700;
    }
    .filter-item {
        @apply px-3 py-1 leading-loose text-gray-400 capitalize cursor-pointer select-none hover:text-purple-500 hover:font-semibold dark:hover:text-gray-300;
    }
    .filter-item-active {
        @apply font-semibold text-purple-500 dark:text-gray-300;
    }
}
</style>