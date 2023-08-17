
<script setup>
import {onMounted, ref, watch, watchEffect} from 'vue';
import { useRoute } from 'vue-router';

import useGenerateBlReport from '../../../composables/documentation/useGenerateBlReport';
import useCustomer from "../../../composables/commercial/useCustomer";
import useVoyage from "../../../composables/operation/useVoyage";
import Paginate from '../../../components/utils/paginate.vue';
import Title from "../../../services/title";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import moment from 'moment';
import useService from "../../../composables/commercial/useService";
import usePort from "../../../composables/usePort";

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

const { customers, getCustomers, customerCodeName, getCustomerByNameOrCode } = useCustomer();
const { voyages, voyageName, voyageCustomers, getVoyageName, getVoyageCustomer } = useVoyage();
const { formParams, blLoadInfo, getBillOfLadding, uniqueServicePorts, blNo, getBillOfLadingByBlNo, generateBlReport, generateBl, getServiceUniquePorts, isLoading } = useGenerateBlReport();
const { services, getServices } = useService();
const { ports, portName, getPortsByNameOrCode } = usePort();

function fetchOptions(search, loading) {
  getVoyageName(search);
}

function checkSelected() {
  isALlSelected.value = blLoadInfo.value.data.every(loadInfo => loadInfo.isSelected);
  numberOfSelected.value = blLoadInfo.value.data.filter(loadInfo => loadInfo.isSelected).length;
}

// Code for global search starts here
const columns = ["bl_no", "pol_pod"];
const searchKey = useDebouncedRef('', 800);
const table = "bill_of_ladings";

watch(searchKey, newQuery => {
  getBillOfLadding(props.page, columns, searchKey.value, table);
});
// Code for global search end here

function checkAll() {
  blLoadInfo.value.data.forEach(loadInfo => {
    loadInfo.isSelected = isALlSelected.value;
  });
  checkSelected();
}

function send(singleLoadId = null) {
  let loadInfo_ids = [];
  if(singleLoadId) {
    loadInfo_ids = [singleLoadId];
  } else {
    loadInfo_ids = blLoadInfo.value.data.filter(loadInfo => loadInfo.isSelected).map(loadInfo => loadInfo.id);
  }
  if (loadInfo_ids.length === 0) {
    message.value = "Please select at least one BL.";
    return;
  }
  generateBlReport({ loadInfo_ids });
  message.value = "";
  isALlSelected.value = false;
  checkAll();
}

const { setTitle } = Title();

setTitle('Generate BL');

watch(() => formParams?.value?.voyage_id, (value) => {
  if(value) {
    getServiceUniquePorts(value);
  }
},{deep: true});

const searchParams = ref({
  bl_data: null,
  bl_no: '',
  service_data: null,
  service_id: '',
  voyage_no: null,
  voyage_id: '',
  pol_code_name: null,
  pol_code: '',
  pod_code_name: null,
  pod_code: '',
  customer_codes_name: null,
  customer_id: '',
  issue_date_from: '',
  issue_date_to: '',
});

function clearFormData(){
  searchParams.value = {
    bl_data: null,
    bl_no: '',
    service_data: null,
    service_id: '',
    voyage_no: null,
    voyage_id: '',
    pol_code_name: null,
    pol_code: '',
    pod_code_name: null,
    pod_code: '',
    customer_codes_name: null,
    customer_id: '',
    issue_date_from: '',
    issue_date_to: '',
  };
  document.querySelector('input[name="issue_date_to"]').setAttribute('readonly', true);
}

function fetchBlByNo(search, loading) {
  getBillOfLadingByBlNo(search);
}

function fetchVoyages(search, loading) {
  getVoyageName(search);
}

function fetchPortCode(search, loading) {
  getPortsByNameOrCode(search);
}

function fetchCustomer(search, loading){
  getCustomerByNameOrCode(search);
}

watch(() => searchParams?.value?.issue_date_from, (value) => {
  if(value) {
    document.querySelector('input[name="issue_date_to"]').removeAttribute('readonly');
  }
});

onMounted(() => {
  document.querySelector('input[name="issue_date_to"]').setAttribute('readonly', true);
  getServices();
  watchEffect(() => {
    if(searchParams.value.bl_data){
      searchParams.value.bl_no = searchParams.value.bl_data.bl_no;
    }
    if(searchParams.value.service_data){
      searchParams.value.service_id = searchParams.value.service_data.id;
    }
    if(searchParams.value.voyage_no){
      searchParams.value.voyage_id = searchParams.value.voyage_no.id;
    }
    if(searchParams.value.customer_codes_name){
      searchParams.value.customer_id = searchParams.value.customer_codes_name.id;
    }
    if(searchParams.value.pol_code_name){
      searchParams.value.pol_code = searchParams.value.pol_code_name.code;
    }
    if(searchParams.value.pod_code_name){
      searchParams.value.pod_code = searchParams.value.pod_code_name.code;
    }
    getBillOfLadding(props.page, searchParams.value);
    //getBillOfLadding(props.page, columns, searchKey.value, table);
  });
});

</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">BL List</h2>
  </div>  
<!--  <div class="flex items-center justify-between mb-2 select-none">-->
<!--    <span class="w-full text-xs font-medium text-gray-500 whitespace-no-wrap">Selected: {{ numberOfSelected }} of {{ blLoadInfo?.data?.length }}</span>-->
<!--    &lt;!&ndash; Search &ndash;&gt;-->
<!--    -->
<!--  </div>-->
  <div class="w-full flex items-center justify-between mb-2 select-none">
    <fieldset class="px-2 pb-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Search BL</legend>
      <div class="w-full grid grid-cols-8 gap-1">
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">BL No</label>
          <v-select :options="blNo" placeholder="--Choose an option--" @search="fetchBlByNo" v-model="searchParams.bl_data" label="bl_no" required></v-select>
          <input type="hidden" v-model="searchParams.bl_no">
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Service</label>
          <v-select :options="services" placeholder="--Choose an option--" v-model="searchParams.service_data" label="code" ></v-select>
          <input type="hidden" v-model="searchParams.service_id">
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Voyage No</label>
          <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchVoyages" v-model="searchParams.voyage_no" label="voyage" required></v-select>
          <input type="hidden" v-model="searchParams.voyage_id">
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Pol</label>
          <v-select v-model="searchParams.pol_code_name" @search="fetchPortCode" :options="portName" label="code" placeholder="Port Code" required></v-select>
          <input type="hidden" v-model="searchParams.pol_code">
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Pod</label>
          <v-select v-model="searchParams.pod_code_name" @search="fetchPortCode" :options="portName" label="code" placeholder="Port Code" required></v-select>
          <input type="hidden" v-model="searchParams.pod_code">
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">CID</label>
          <v-select placeholder="--Choose an option--" :options="customerCodeName" @search="fetchCustomer" v-model="searchParams.customer_codes_name" label="customer_code"></v-select>
          <input type="hidden" v-model="searchParams.customer_id">
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">From</label>
          <input type="date" id="issue_date_from" class="search w-full" v-model="searchParams.issue_date_from">
        </div>

        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">To</label>
          <input type="date" id="issue_date_true" name="issue_date_to" class="search w-full" v-model="searchParams.issue_date_to">
        </div>

      </div>

    </fieldset>
  </div>
  <div class="flex flex-row-reverse items-center justify-between mb-2">
<!--    <div class="relative w-full">-->
<!--      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">-->
<!--        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />-->
<!--      </svg>-->
<!--      <input type="text"  v-model.trim="searchKey" placeholder="Search..." class="search" />-->
<!--    </div>-->
    <div class="float-right">
      <button @click="clearFormData" class="w-32 flex items-center justify-center px-2 py-2 text-sm font-medium text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Clear</span>
      </button>
    </div>
    <button @click="send()" class="inline-flex w-60 gap-1 px-3 py-1 text-sm text-center bg-purple-500 rounded btn text-purple-50 hover:bg-purple-600 active:bg-purple-500 disabled:bg-purple-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
  </div>
  <div class="w-full mb-8 overflow-hidden border rounded-lg shadow-xs dark:border-0">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead>
        <tr>
          <th>
            <input v-if="blLoadInfo?.data?.length" v-model="isALlSelected" @change="checkAll()" type="checkbox" class="check" />
          </th>
          <th>BL No</th>
          <th>Service</th>
          <th>Voyage</th>
          <th>Pol</th>
          <th>Pod</th>
          <th>Shipper</th>
          <th data-title="Customer Name">C. Name</th>
          <th data-title="Customer Id">CID</th>
          <th data-title="Issue Date">Date</th>
          <th>Action</th>
        </tr>
        </thead>
        <tbody v-if="(blLoadInfo?.data ? blLoadInfo?.data?.length : blLoadInfo?.length)">
        <tr v-for="(loadInfo, index) in (blLoadInfo?.data ? blLoadInfo?.data : blLoadInfo)" :key="loadInfo?.id">
          <td class="w-5">
            <input type="checkbox" class="check" v-model="loadInfo.isSelected" @change="checkSelected()" />
          </td>
          <td class="border-l dark:border-gray-600"><nobr>{{ loadInfo?.bl_no }}</nobr></td>
          <td class="border-l dark:border-gray-600">{{ loadInfo?.voyage?.service?.code }}</td>
          <td class="border-l dark:border-gray-600">{{ loadInfo?.voyage?.voyage }}</td>
          <td class="border-l dark:border-gray-600">{{ loadInfo?.pol_pod.split('-')[0] }}</td>
          <td class="border-l dark:border-gray-600">{{ loadInfo?.pol_pod.split('-')[1] }}</td>
          <td class="border-l dark:border-gray-600">{{ loadInfo?.customer?.slot_partner?.code }}</td>
          <td class="border-l dark:border-gray-600">{{ loadInfo?.customer?.customer_name }}</td>
          <td class="border-l dark:border-gray-600">{{ loadInfo?.customer?.customer_code }}</td>
          <td class="border-l dark:border-gray-600">{{ moment(loadInfo?.created_at).format('DD-MM-YYYY') }}</td>
          <td class="td-border">
            <a @click="send(loadInfo?.id)" target="_blank">
              <svg style="cursor: pointer" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--prime" width="32" height="32" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M18.53 9L13 3.47a.75.75 0 0 0-.53-.22H8A2.75 2.75 0 0 0 5.25 6v12A2.75 2.75 0 0 0 8 20.75h8A2.75 2.75 0 0 0 18.75 18V9.5a.75.75 0 0 0-.22-.5Zm-5.28-3.19l2.94 2.94h-2.94ZM16 19.25H8A1.25 1.25 0 0 1 6.75 18V6A1.25 1.25 0 0 1 8 4.75h3.75V9.5a.76.76 0 0 0 .75.75h4.75V18A1.25 1.25 0 0 1 16 19.25Z"></path><path fill="currentColor" d="M13.49 14.85a3.15 3.15 0 0 1-1.31-1.66a4.44 4.44 0 0 0 .19-2a.8.8 0 0 0-1.52-.19a5 5 0 0 0 .25 2.4A29 29 0 0 1 9.83 16c-.71.4-1.68 1-1.83 1.69c-.12.56.93 2 2.72-1.12a18.58 18.58 0 0 1 2.44-.72a4.72 4.72 0 0 0 2 .61a.82.82 0 0 0 .62-1.38c-.42-.43-1.67-.31-2.29-.23Zm-4.78 3a4.32 4.32 0 0 1 1.09-1.24c-.68 1.08-1.09 1.27-1.09 1.25Zm2.92-6.81c.26 0 .24 1.15.06 1.46a3.07 3.07 0 0 1-.06-1.45Zm-.87 4.88a14.76 14.76 0 0 0 .88-1.92a3.88 3.88 0 0 0 1.08 1.26a12.35 12.35 0 0 0-1.96.67Zm4.7-.18s-.18.22-1.33-.28c1.25-.08 1.46.21 1.33.29Z"></path></svg>
            </a>
          </td>
        </tr>
        </tbody>
        <tfoot v-if="!(blLoadInfo?.data ? blLoadInfo?.data?.length : blLoadInfo?.length)" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="11" class="text-center">Loading...</td>
        </tr>
        <tr v-else-if="!(blLoadInfo?.data ? blLoadInfo?.data?.length : blLoadInfo?.length)">
          <td colspan="11" class="text-center">No bill of lading found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <!-- Pagination -->
    <Paginate :data="blLoadInfo" to="documentation.generate-bl.report.list" :page="page"></Paginate>
  </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
  th {
    @apply px-4 py-3 border-l dark:border-gray-600;
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
  .search {
    @apply float-right  pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
  }

  table tr,th,td{
    font-size: 11px;
  }
}
</style>
