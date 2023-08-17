
<script setup>
import {onMounted, ref, watch, watchEffect} from 'vue';
import { useRoute } from 'vue-router';

import useGenerateBlReport from '../../../composables/documentation/useGenerateBlReport';
import useCustomer from "../../../composables/commercial/useCustomer";
import useVoyage from "../../../composables/operation/useVoyage";
import Paginate from '../../../components/utils/paginate.vue';
import Title from "../../../services/title";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
})

const route = useRoute();
const isALlSelected = ref(false);
const numberOfSelected = ref(0);
const message = ref("");

const { customers, getCustomers } = useCustomer();
const { voyages, voyageName, voyageCustomers, getVoyageName, getVoyageCustomer } = useVoyage();
const { formParams, blLoadInfo, generatedBlList, getBillOfLadding, uniqueServicePorts, voyageSectors, generateBlReport, generateBl, getServiceUniquePorts, getVoyageSectors, isLoading } = useGenerateBlReport();

function fetchOptions(search, loading) {
  getVoyageName(search);
}

function checkSelected() {
  isALlSelected.value = generatedBlList.value.every(loadInfo => loadInfo.isSelected);
  numberOfSelected.value = generatedBlList.value.filter(loadInfo => loadInfo.isSelected).length;
}

function checkAll() {
  generatedBlList.value.forEach(loadInfo => {
    loadInfo.isSelected = isALlSelected.value;
  });
  checkSelected();
}

function send(singleLoadId = null) {
  let loadInfo_ids = [];
  if(singleLoadId) {
    loadInfo_ids = [singleLoadId];
  } else {
    loadInfo_ids = generatedBlList.value.filter(loadInfo => loadInfo.isSelected).map(loadInfo => loadInfo.id);
  }
  if (loadInfo_ids.length === 0) {
    message.value = "Please select at least one BL.";
    return;
  }
  console.log(loadInfo_ids);
  generateBlReport({ loadInfo_ids });
  message.value = "";
  isALlSelected.value = false;
  checkAll();
}

const { setTitle } = Title();

setTitle('Generate BL');

watch(() => formParams?.value?.voyage_id, (value) => {
  if(value) {
    getVoyageSectors(value);
  }
},{deep: true});

onMounted(() => {
  watchEffect(() => {
    getBillOfLadding(props.page);
  });
});

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Generate BL Report</h2>
    </div>
    <!-- Table -->
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form class="flex items-end justify-between gap-4" @submit.prevent="generateBl(formParams)">
            <label class="block w-full text-sm">
              <span class="text-gray-700 dark:text-gray-300">Voyage</span>
              <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchOptions" v-model="formParams.voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required></v-select>
            </label>
            <label class="block w-full text-sm">
              <span class="text-gray-700 dark:text-gray-300">Sector</span>
              <select v-model="formParams.sector" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="" selected disabled>--Choose an option--</option>
                <option :value="sector" v-for="(sector,index) in voyageSectors" :key="index">{{ sector }}</option>
              </select>
            </label>
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
        </form>
    </div>
  <h4 class="p-2 font-bold text-center text-gray-700 dark:text-gray-200">
    Report List
  </h4>
  <div class="flex flex-row-reverse items-center justify-between mb-2">
    <button @click="send()" class="inline-flex gap-1 px-3 py-1 text-sm text-center bg-purple-500 rounded btn text-purple-50 hover:bg-purple-600 active:bg-purple-500 disabled:bg-purple-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
      </svg>
      Download Selected BL
    </button>
    <div class="flex items-center gap-1 text-xs text-red-500" v-if="message">
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="w-5 h-5 iconify iconify--bx" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
        <path fill="currentColor" d="M11.001 10h2v5h-2zM11 16h2v2h-2z" />
        <path fill="currentColor" d="M13.768 4.2C13.42 3.545 12.742 3.138 12 3.138s-1.42.407-1.768 1.063L2.894 18.064a1.986 1.986 0 0 0 .054 1.968A1.984 1.984 0 0 0 4.661 21h14.678c.708 0 1.349-.362 1.714-.968a1.989 1.989 0 0 0 .054-1.968L13.768 4.2zM4.661 19L12 5.137L19.344 19H4.661z" />
      </svg>
      {{ message }}
    </div>
    <p class="text-sm font-semibold tracking-tighter text-gray-400">Selected: {{ numberOfSelected }} of {{ generatedBlList?.length }}</p>
  </div>
  <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0 mb-8">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead>
        <tr>
          <th>
            <input v-if="generatedBlList?.length" v-model="isALlSelected" @change="checkAll()" type="checkbox" class="check" />
          </th>
          <th>Voyage</th>
          <th>Customer</th>
          <th>POL-POD</th>
          <th>BL No</th>
          <th>Action</th>
        </tr>
        </thead>
        <tbody v-if="generatedBlList?.length">
        <tr v-for="(loadInfo, index) in generatedBlList" :key="loadInfo?.id">
          <td class="w-5">
            <input type="checkbox" class="check" v-model="loadInfo.isSelected" @change="checkSelected()" />
          </td>
          <td class="border-l dark:border-gray-600">{{ loadInfo?.voyage?.voyage }}</td>
          <td class="border-l dark:border-gray-600">{{ loadInfo?.customer?.customer_name }}</td>
          <td class="border-l dark:border-gray-600">{{ loadInfo?.pol_pod }}</td>
          <td class="border-l dark:border-gray-600">{{ loadInfo?.bl_no }}</td>
          <td class="td-border">
            <svg style="cursor: pointer" @click="send(loadInfo?.id)" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--prime" width="32" height="32" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M18.53 9L13 3.47a.75.75 0 0 0-.53-.22H8A2.75 2.75 0 0 0 5.25 6v12A2.75 2.75 0 0 0 8 20.75h8A2.75 2.75 0 0 0 18.75 18V9.5a.75.75 0 0 0-.22-.5Zm-5.28-3.19l2.94 2.94h-2.94ZM16 19.25H8A1.25 1.25 0 0 1 6.75 18V6A1.25 1.25 0 0 1 8 4.75h3.75V9.5a.76.76 0 0 0 .75.75h4.75V18A1.25 1.25 0 0 1 16 19.25Z"></path><path fill="currentColor" d="M13.49 14.85a3.15 3.15 0 0 1-1.31-1.66a4.44 4.44 0 0 0 .19-2a.8.8 0 0 0-1.52-.19a5 5 0 0 0 .25 2.4A29 29 0 0 1 9.83 16c-.71.4-1.68 1-1.83 1.69c-.12.56.93 2 2.72-1.12a18.58 18.58 0 0 1 2.44-.72a4.72 4.72 0 0 0 2 .61a.82.82 0 0 0 .62-1.38c-.42-.43-1.67-.31-2.29-.23Zm-4.78 3a4.32 4.32 0 0 1 1.09-1.24c-.68 1.08-1.09 1.27-1.09 1.25Zm2.92-6.81c.26 0 .24 1.15.06 1.46a3.07 3.07 0 0 1-.06-1.45Zm-.87 4.88a14.76 14.76 0 0 0 .88-1.92a3.88 3.88 0 0 0 1.08 1.26a12.35 12.35 0 0 0-1.96.67Zm4.7-.18s-.18.22-1.33-.28c1.25-.08 1.46.21 1.33.29Z"></path></svg>
          </td>
        </tr>
        </tbody>
        <tfoot v-if="!generatedBlList?.length" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="6" class="text-center">Loading...</td>
        </tr>
        <tr v-else-if="!generatedBlList?.length">
          <td colspan="6" class="text-center">No bill of lading found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
  </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
  th {
    @apply px-4 py-3;
  }
  td {
    @apply px-4 py-3 text-xs border-r-0;
  }
  thead tr {
    @apply text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  tbody tr {
    @apply text-gray-700 dark:text-gray-400;
  }
  .check {
    @apply rounded text-purple-500 focus:ring-purple-500 dark:bg-gray-600;
  }
  .email {
    @apply text-purple-500 hover:underline dark:text-purple-600;
  }
  .tel {
    @apply hover:underline;
  }
  .pill {
    @apply px-2 py-1 font-semibold leading-tight rounded-full;
  }
  .pill-success {
    @apply text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100;
  }
  .pill-warning {
    @apply text-orange-700 bg-orange-100 dark:text-white dark:bg-orange-600;
  }
  .tr-active {
    @apply bg-green-200 dark:bg-gray-700 dark:text-gray-50;
  }
  .bold {
    @apply font-semibold dark:text-gray-200 capitalize;
  }
  .td-border {
    @apply border-l dark:border-gray-700;
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
}
</style>
