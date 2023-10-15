<script setup>
import {onMounted, watchEffect} from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useOpeningStock from "../../../composables/supply-chain/useOpeningStock";
import useHelper from "../../../composables/useHelper.js";
import Title from "../../../services/title";
import useDebouncedRef from '../../../composables/useDebouncedRef';
import Paginate from '../../../components/utils/paginate.vue';
import {watch} from "vue";

const { getOpeningStocks, openingStocks, deleteOpeningStock, isLoading } = useOpeningStock();
const { numberFormat } = useHelper();
const { setTitle } = Title();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

setTitle('Opening Stocks');
// Code for global search starts here
const columns = ["date"];
const searchKey = useDebouncedRef('', 600);
const table = "opening_stocks";

watch(searchKey, newQuery => {
  getOpeningStocks(props.page, columns, searchKey.value, table);
});
// Code for global search end here
onMounted(() => {
  watchEffect(() => {
    getOpeningStocks(props.page, columns, searchKey.value, table);
  });
});
</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-2 sm:flex-row" v-once>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Opening Stock</h2>
    <router-link :to="{ name: 'scm.opening-stock.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61]  rounded-lg active:bg-[#0F6B61]  hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
    </router-link>
  </div>
  <div class="flex items-center justify-between mb-2 select-none">
    <span class="w-full text-xs font-medium text-gray-500 whitespace-no-wrap">Showing {{ openingStocks?.from }}-{{ openingStocks?.to }} of {{ openingStocks?.total }}</span>
    <!-- Search -->
    <div class="relative w-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
      </svg>
      <input type="text"  v-model="searchKey" placeholder="Search..." class="search" />
    </div>
  </div>
  <!-- Table -->
  <div class="w-full overflow-hidden">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">SL. </th>
          <th class="px-4 py-3">Date</th>
          <th class="px-4 py-3">Warehouse</th>
          <th class="px-4 py-3 text-center">Action</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(openingStock,index) in (openingStocks?.data ? openingStocks?.data : openingStocks)" :key="index">
          <td class="px-4 py-3 text-sm">{{ openingStocks?.from + index }}</td>
          <td class="px-4 py-3 text-sm">{{ openingStock?.date }}</td>
          <td class="px-4 py-3 text-sm">{{ openingStock?.warehouse }}</td>
          <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
            <action-button :action="'show'" :to="{ name: 'scm.material-requisitions.show', params: { openingStockId: openingStock.id } }"></action-button>
            <action-button :action="'edit'" :to="{ name: 'scm.material-requisitions.edit', params: { openingStockId: openingStock.id } }"></action-button>
            <action-button @click="deleteOpeningStock(openingStock.id)" :action="'delete'"></action-button>
          </td>
        </tr>
        </tbody>
        <tfoot v-if="!(openingStocks?.data ? openingStocks?.data?.length : openingStocks?.length)" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="7">Loading...</td>
        </tr>
        <tr v-else-if="!(openingStocks?.data ? openingStocks?.data?.length : openingStocks?.length)">
          <td colspan="7">No Material Requisition found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="openingStocks" to="scm.opening-stocks.index" :page="page"></Paginate>
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