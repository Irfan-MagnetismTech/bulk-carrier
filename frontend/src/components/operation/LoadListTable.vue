
<script setup>
import { toRefs } from '@vueuse/core';
import { useFuse } from '@vueuse/integrations/useFuse';
import { computed, ref, watch } from 'vue';
import useLoadList from "../../composables/operation/useLoadList";
import {useRoute} from "vue-router";
const route = useRoute();

const props = defineProps({
    data: {
        default: () => [],
    },
});
const { updateLoadList, isLoading, errors } = useLoadList();

const { data: loadList } = toRefs(props);
const isALlSelected = ref(false);
const numberOfSelected = ref(0);
const search = ref('');
const selected = ref('');
const updateLoadLists = ref([]);
const filters = ['new', 'updated', 'previousPortsContainers'];
const previousSource = ref('tdr');
// const filters = ['new', 'updated', 'missing'];
const voyageId = route.params.voyageId;
const options = ref({
    fuseOptions: {
        keys: ['pod_code', 'stow', 'container', 'size', 'gross_wt', 'iso', 'imo_class_1', 'temp', 'height', 'status', 'pol_code', 'optional', 'mlo', 'delivery_port_code',],
        threshold: 0,
    },
    matchAllWhenSearchEmpty: true,
});
const allLoadLists = ref(computed(() => {
    if (loadList.value[selected.value] === undefined) {
        return [];
    }

    return loadList.value[selected.value];
}));

const { results: searchResults } = useFuse(search, allLoadLists, options);

function capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

watch(searchResults, () => {
  searchResults?.value.forEach(load => {
    load.item.isSelected = false;
  });
});

watch(previousSource, (source) => {
  searchResults?.value.forEach(load => {
    load.item.source = source;
  });
})

watch(loadList, () => {
  console.log("LOADLIST:" + loadList.value.new);

  // loadList?.value?.previousPortsContainers.forEach(result => {
  //   // result.item.type = 'tdr';
  //   console.log(result)
  // });

  if(loadList?.value?.updated?.length > 0) {
    selected.value = 'updated';
    updateLoadLists.value = loadList?.value?.updated;
  } 
  // else if(loadList?.value?.missing?.length > 0){
  //   selected.value = 'missing';
  // } 
  else{
    selected.value = 'new';
  }
});

function changeSelectedValue(keyStatus) {
  selected.value = keyStatus;
}

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

function updateData(listItems, voyageId, type) {
  updateLoadList(listItems, voyageId, type)
}
</script>

<template>
    <div class="flex items-center justify-between mb-2 select-none">
        <!-- Filter -->
      <div class="w-full">
        <label for="new" @click="changeSelectedValue('new')" :class="{ 'filter-item-active': selected === 'new' }" class="filter-item rounded-l border-r dark:border-gray-200">
          <input type="radio" v-model="selected" id="new" name="new" value="new" class="hidden" />
          <span>Load at Port</span>
        </label>
        <!-- <label for="updated" @click="changeSelectedValue('updated')" :class="{ 'filter-item-active': selected === 'updated' }" class="filter-item border-r">
          <input type="radio" v-model="selected" id="updated" name="updated" value="updated" class="hidden" />
          <span>Update</span>
        </label> -->
        <label for="previousPortsContainers" @click="changeSelectedValue('previousPortsContainers')" :class="{ 'filter-item-active': selected === 'previousPortsContainers' }" class="filter-item border-r rounded-r">
          <input type="radio" v-model="selected" id="previousPortsContainers" name="previousPortsContainers" value="previousPortsContainers" class="hidden" />
          <span>Previous Port ROB</span>
        </label>
        
        <!-- <label for="missing" :class="{ 'filter-item-active': selected === 'missing' }" class="filter-item rounded-r">
          <input type="radio" v-model="selected" id="missing" name="filter" value="difference" class="hidden" />
          <span>Difference</span>
        </label> -->
        <p v-if="selected === 'updated'" class="text-sm font-semibold tracking-tighter text-gray-400">Selected: {{ numberOfSelected }} of {{ searchResults.length }}</p>
      </div>
       <!-- <div class="filter-wrapper">
           <label :for="filter" v-for="(filter,filterIndex) in filters" :key="filterIndex" :class="{ 'filter-item-active': selected === filter }" class="filter-item">
               <input type="radio" v-model="selected" :id="filterIndex" name="filter" :value="filter" class="hidden" />
               <span>{{ capitalize(filter) }}</span>
           </label>
       </div> -->
        <!-- Search -->
        <div class="relative w-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
            <input type="text" v-model.trim="search" placeholder="Search..." class="search" />
        </div>
    </div>
    <!-- Table -->
  <form @submit.prevent="updateData(updateLoadLists, voyageId, 'overwrite')" class="flex flex-col items-center justify-center w-full gap-2 mt-2" enctype="multipart/form-data">
  <div class="w-full mb-6 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <!-- <div v-if="selected === 'updated'" class="text-red-500 my-3">
              If you want to update <b><i>MLO</i></b> please do it from <b><i>Container Edit</i></b> rather than bulk update. If you prefer bulk update please <b><i>clear previous data</i></b> first.
            </div> -->
            <table class="w-full border dark:border-0">
                <thead>
                    <tr>
                        <th v-if="selected === 'updated'">
                          <input v-if="searchResults.length" v-model="isALlSelected" @change="checkAll()" type="checkbox" class="check" />
                        </th>
                        <th v-if="selected === 'previousPortsContainers'">
                          <select v-model="previousSource">
                            <option value="tdr">TDR</option>
                            <option value="edi">EDI</option>
                          </select>
                        </th>
                        <th>MLO</th>
                        <th>Container No</th>
                        <th>POL</th>
                        <th>POD</th>
                        <th>Slot</th>
                        <th>Weight</th>
                        <th>ISO</th>
                        <th>IMCO</th>
                        <th>Temparature</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-gray-700 dark:text-gray-400" :class="{ 'bg-red-50': selected === 'missing', 'bg-green-50': selected === 'new' }" v-for="result in searchResults" :key="result?.item?.id">
                        <td class="w-5" v-if="selected === 'updated'">
                          <input type="checkbox" class="check" v-model="result.item.isSelected" @change="checkSelected()" />
                        </td>
                        <td v-if="selected === 'previousPortsContainers'">
                          <select v-model="result.item.source">
                            <option value="tdr" selected>TDR</option>
                            <option value="edi">EDI Only</option>
                          </select>
                        </td>
                        <td>{{ result?.item?.mlo }}</td>
                        <td>{{ result?.item?.container }}</td>
                        <td>{{ result?.item?.pol_code }}</td>
                        <td>{{ result?.item?.pod_code }}</td>
                        <td>{{ result?.item?.stow }}</td>
                        <td>{{ result?.item?.gross_wt?.toFixed(2) }}</td>
                        <td>{{ result?.item?.iso }}</td>
                        <td>{{ result?.item?.imco }}</td>
                        <td>{{ result?.item?.temp }}</td>
                        <td>{{ result?.item?.status }}</td>
                        <td v-if="result?.item?.source === 'tdr' || result?.item?.source === 'edi'">
                          <!-- <input type="text" readonly v-model="result.item.tdr_remarks" placeholder="Remarks"/> -->
                          {{ result.item.tdr_remarks }}
                        </td>
                    </tr>
                    <tr v-if="searchResults.length === 0">
                        <td colspan="15" class="search-result">No data available.</td>
                    </tr>
                </tbody>
            </table>
        </div>
      <button style="margin-left: 40%" type="submit" v-if="selected === 'updated'" :disabled="isLoading" class="flex justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Data</button>
      <button style="margin-left: 40%" type="button" @click="updateData(loadList?.previousPortsContainers, voyageId, 'sourceUpdate')" v-if="selected === 'previousPortsContainers'" :disabled="isLoading" class="flex justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Confirm and Save</button>
    </div>
  </form>
</template>

<style lang="postcss" scoped>
@tailwind components;

@layer components {
    th {
        @apply px-3 py-2 text-xs;
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