
<script setup>
import { toRefs } from '@vueuse/core';
import { useFuse } from '@vueuse/integrations/useFuse';
import { watch, computed, ref } from 'vue';
const isALlSelected = ref(false);
const numberOfSelected = ref(0);

const props = defineProps({
    data: {
        default: () => [],
    },
});
const { data: containerList } = toRefs(props);
const search = ref('');
const selected = ref('');

const options = ref({
    fuseOptions: {
        keys: ['pod_code', 'stow', 'container', 'size', 'gross_wt', 'iso', 'imo_class_1', 'temp', 'height', 'status', 'pol_code', 'optional', 'mlo', 'delivery_port_code',],
        threshold: 0,
    },
    matchAllWhenSearchEmpty: true,
});
const allCopinos = ref(computed(() => {
    if (containerList.value[selected.value] === undefined) {
        return [];
    }
    return containerList.value[selected.value];
}));

const { results: searchResults } = useFuse(search, allCopinos, options);

watch(searchResults, () => {
  searchResults?.value.forEach(container => {
    container.item.isSelected = false;
  });
});

watch(containerList, () => {
  if(containerList?.value?.updated?.length > 0) {
    selected.value = 'updated';
  } else if(containerList?.value?.inserted?.length > 0){
    selected.value = 'inserted';
  } else{
    selected.value = 'difference';
  }
}, { deep: true });

// Check if all data are selected
function checkSelected() {
  isALlSelected.value = searchResults.value.every(result => result.item.isSelected);
  numberOfSelected.value = searchResults.value.filter(result => result.item.isSelected).length;
}

// Set isSelected to true or false for all data
function checkAll() {
  searchResults?.value.forEach(result => {
    result.item.isSelected = isALlSelected.value;
  });
  checkSelected();
}
</script>

<template>
    <div class="flex items-center justify-between mb-2 select-none">
        <!-- Filter -->
        <div>
            <label for="new" :class="{ 'filter-item-active': selected === 'inserted' }" class="filter-item rounded-l border-r dark:border-gray-200">
                <input type="radio" v-model="selected" id="new" name="filter" value="inserted" class="hidden" />
                <span>New</span>
            </label>
            <label for="update" :class="{ 'filter-item-active': selected === 'updated' }" class="filter-item border-r">
                <input type="radio" v-model="selected" id="update" name="filter" value="updated" class="hidden" />
                <span>Updated</span>
            </label>
            <label for="difference" :class="{ 'filter-item-active': selected === 'difference' }" class="filter-item rounded-r">
                <input type="radio" v-model="selected" id="difference" name="filter" value="difference" class="hidden" />
                <span>Difference</span>
            </label>
          <p v-if="selected === 'updated'" class="text-sm font-semibold tracking-tighter text-gray-400">Selected: {{ numberOfSelected }} of {{ searchResults.length }}</p>
        </div>
        <!-- Search -->
        <div class="relative w-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
            <input type="text" v-model.trim="search" placeholder="Search..." class="search" />
        </div>
    </div>
    <div class="w-full mb-6 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full md:overflow-x-auto">
            <table class="w-full border dark:border-0">
                <thead>
                    <tr>
                        <th v-if="selected === 'updated'">
                          <input v-if="searchResults.length" v-model="isALlSelected" @change="checkAll()" type="checkbox" class="check" />
                        </th>
                        <th>Discharge Port</th>
                        <th>Container ID</th>
                        <th>Account</th>
                        <th>Weight</th>
                        <th>Type</th>
                        <th>Class</th>
                        <th>Setting</th>
                        <th>Status</th>
                        <th>Load Port</th>
                        <th>MLO</th>
                        <th>Del. Port</th>
                    </tr>
                </thead>
                <tbody>
                    <tr :class="{ 'tr-active': result.item.isSelected }" class="text-gray-700 dark:text-gray-400" v-for="result in searchResults" :key="result?.item?.id">
                        <td class="w-5" v-if="selected === 'updated'">
                          <input type="checkbox" class="check" v-model="result.item.isSelected" @change="checkSelected()" />
                        </td>
                        <td>{{ result?.item?.pod_code }}</td>
                        <td>{{ result?.item?.container }}</td>
                        <td>{{ result?.item?.account_id }}</td>
                        <td>{{ result?.item?.gross_wt?.toFixed(2) }}</td>
                        <td>{{ result?.item?.iso }}</td>
                        <td>{{ result?.item?.imco }}</td>
                        <td>{{ result?.item?.temp }}</td>
                        <td>{{ result?.item?.status }}</td>
                        <td>{{ result?.item?.pol_code }}</td>
                        <td>{{ result?.item?.mlo }}</td>
                        <td class="border-r-0">{{ result?.item?.delivery_port_code }}</td>
                    </tr>
                    <tr v-if="searchResults.length === 0">
                        <td colspan="15" class="search-result">No data available.</td>
                    </tr>
                </tbody>
            </table>
        </div>
      <button style="margin-left: 40%" type="submit" v-if="selected === 'updated'" :disabled="isLoading" class="flex justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Data</button>
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
    .filter-item {
        @apply px-3 py-1 leading-loose text-gray-100 capitalize cursor-pointer select-none hover:bg-purple-700 bg-purple-400 dark:bg-gray-700 dark:border-0 dark:text-gray-500;
    }
    .filter-item-active {
        @apply bg-purple-700 dark:bg-gray-700 dark:text-gray-300;
    }
  .tr-active {
    @apply bg-green-200 dark:bg-gray-700 dark:text-gray-50;
  }
}
</style>