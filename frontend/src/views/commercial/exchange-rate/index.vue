<script setup>
import { onMounted, computed, ref, watchEffect, watch } from 'vue';
import { orderBy } from 'lodash';
import { onClickOutside } from '@vueuse/core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import moment from 'moment';
import Heading from '../../../components/Heading.vue';
import useVoyage from '../../../composables/operation/useVoyage';
import IconExcel from '../../../components/icons/IconExcel.vue';
import IconAnchor from '../../../components/icons/IconAnchor.vue';
import IconFile from '../../../components/icons/IconFile.vue';
import IconCodeDownload from '../../../components/icons/IconCodeDownload.vue';
import IconDownload from '../../../components/icons/IconDownload.vue';
import useReport from '../../../composables/operation/useReport';
import Title from "../../../services/title";
import Paginate from '../../../components/utils/paginate.vue';
import useDebouncedRef from "../../../composables/useDebouncedRef";
import useVessel from "../../../composables/dataencoding/useVessel";
import useService from "../../../composables/commercial/useService";

const props = defineProps({
    page: {
        type: Number,
        default: 1,
    },
});

const { voyages, isLoading, getVoyages, voyageName, voyageExchangeRate, getVoyageName, clearVoyage, deleteVoyage } = useVoyage();
const { vesselName, getVesselName } = useVessel();
const { services, getServices } = useService();
const { generateBayPlanPDF, generateVoyageEdi, generateVoyageExcel, generateVoyageSummary, generateCopinoBooking, downloadDraftTdr } = useReport();

const { setTitle } = Title();
setTitle('Exchange Rate');

// Code for global search starts here
const columns = ["voyage"];
const searchKey = useDebouncedRef('', 800);
const table = "voyages";

watch(searchKey, newQuery => {
  getVoyages(props.page, columns, searchKey.value, table);
});
// Code for global search end here


const menu = ref(false);
const showingMenuIndex = ref(-1);

onClickOutside(menu, (event) => showingMenuIndex.value = -1);

function toggleMenu(index) {
    if (showingMenuIndex.value === index) {
        showingMenuIndex.value = -1;
    } else {
        showingMenuIndex.value = index;
    }
}

function updateExchangeRate(event,voyageId){
  let formData = {
    voyage_id: voyageId,
    exchange_rate: event.target.value,
  }
  voyageExchangeRate(formData);
}

const searchParams = ref({
  voyage_no: null,
  voyage_id: '',
  vessel_no: null,
  vessel_id: '',
});

function clearFormData(){
  searchParams.value = {
    voyage_no: null,
    voyage_id: '',
    vessel_no: null,
    vessel_id: '',
  };
}

function fetchVoyages(search, loading) {
  getVoyageName(search);
}

function fetchVessels(search, loading) {
  getVesselName(search);
}

onMounted(() => {
  getServices();
  watchEffect(() => {

    if(searchParams.value.voyage_no){
      searchParams.value.voyage_id = searchParams.value.voyage_no.id;
    }
    if(searchParams.value.service_data){
      searchParams.value.service_id = searchParams.value.service_data.id;
    }

    getVoyages(props.page, searchParams.value);

  });
});
</script>
<template>
    <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row">
    <slot>
      <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Exchange Rate</h2>
    </slot>
  </div>

  <div class="w-full flex items-center justify-between mb-2 select-none">
    <fieldset class="w-full grid grid-cols-4 gap-1 px-2 pb-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Search Exchange Rate</legend>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Vessel</label>
        <v-select :options="vesselName" placeholder="--Choose an option--" @search="fetchVessels" v-model="searchParams.vessel_name" label="name"></v-select>
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Voyage</label>
        <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchVoyages" v-model="searchParams.voyage_no" label="voyage"></v-select>
        <input type="hidden" v-model="searchParams.voyage_id">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Service</label>
        <v-select :options="services" placeholder="--Choose an option--" v-model="searchParams.service_data" label="code" ></v-select>
        <input type="hidden" v-model="searchParams.service_id">
      </div>
      <div>
        <label for="">&nbsp;</label>
        <button @click="clearFormData" class="w-full flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Clear</span>
        </button>
      </div>
    </fieldset>
  </div>

<!--  <div class="flex items-center justify-between mb-2 select-none">-->
<!--    <span class="w-full text-xs font-medium text-gray-500 whitespace-no-wrap">Showing {{ voyages?.from }}-{{ voyages?.to }} of {{ voyages?.total }}</span>-->
<!--    &lt;!&ndash; Search &ndash;&gt;-->
<!--    <div class="relative w-full">-->
<!--      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">-->
<!--        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />-->
<!--      </svg>-->
<!--      <input type="text"  v-model.trim="searchKey" placeholder="Search with voyage no..." class="search" />-->
<!--    </div>-->
<!--  </div>-->
    <!-- Table -->
    <!-- <div class="max-w-screen h-full overflow-x-auto bg-white overflow-y-auto mb-4">
    </div>-->
    <div class="w-full">
        <div class="w-full">
            <table class="w-full whitespace-no-wrap mb-5">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <th class="text-center">#</th>
                        <th class="text-center">Voyage no</th>
                        <th class="text-center">Vessel</th>
                        <th class="text-center">Service</th>
                        <th class="text-center">Exchange Rate</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400 hover:bg-green-300 transition-all ease-in-out duration-200" v-for="(voyage, index) in (voyages?.data ? voyages?.data : voyages)" :key="voyage.id">
                        <td class="px-1 text-center">{{ index + 1 }}</td>
                        <td class="font-semibold text-center dark:text-gray-300">{{ voyage?.voyage }}</td>
                        <td class="font-semibold text-center dark:text-gray-300">{{ voyage?.vessel?.name }}</td>
                        <td class="font-semibold text-center dark:text-gray-300">{{ voyage?.service?.name }} ({{ voyage?.service?.code }})</td>
                        <td class="text-center space-x-2 text-gray-600">
                          <input :value="voyage?.exchange_rate" type="number" @blur="updateExchangeRate($event,voyage?.id)" placeholder="Exchange rate" step=".01" class="mt-1 text-xs rounded dark:text-gray-300 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                        </td>
                    </tr>
                    <tr v-if="(voyages?.data ? voyages?.data?.length : voyages?.length) === 0">
                        <td colspan="8" class="text-center dark:text-gray-400">
                            <span v-if="!isLoading">No records available.</span>
                            <span v-else-if="isLoading" class="flex items-center justify-center w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="w-5 h-5 iconify iconify--eos-icons" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                    <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z" opacity=".5" fill="currentColor" />
                                    <path d="M20 12h2A10 10 0 0 0 12 2v2a8 8 0 0 1 8 8z" fill="currentColor">
                                        <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite" />
                                    </path>
                                </svg>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
      <Paginate :data="voyages" to="commercial.voyage.exchange-rate" :page="page"></Paginate>
    </div>
</template>
<style lang="postcss" scoped>
th {
    @apply px-3 py-2;
}

td {
    @apply px-3 py-2 text-xs;
}

.dropdown-btn {
    @apply bg-gray-900 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-gray-50 text-white font-semibold py-2 px-3 rounded-lg w-full flex items-center justify-center gap-1.5 sm:w-auto dark:bg-blue-500 dark:hover:bg-blue-400;
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

table th,td{
  border: 1px solid gray;
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