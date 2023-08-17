<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useFuse } from '@vueuse/integrations/useFuse';
import Title from '../../../services/title';
import useVoyage from '../../../composables/operation/useVoyage';
import Heading from '../../../components/Heading.vue';
import DgList from "../../../components/operation/sof/dg-list.vue";
import ReeferList from "../../../components/operation/sof/reefer-list.vue";
import RestowList from "../../../components/operation/sof/restow-list.vue";
import SdrList from "../../../components/operation/sof/sdr-list.vue";
import CdrList from "../../../components/operation/sof/cdr-list.vue";
import SpecialCargoList from "../../../components/operation/sof/special-cargo-list.vue";
import useService from "../../../composables/commercial/useService";
import useVoyageSof from "../../../composables/operation/sof/useVoyageSof";
import CargoSpillageList from "../../../components/operation/sof/CargoSpillage-list.vue";
import SummaryList from "../../../components/operation/sof/summary-list.vue";
import DepartureConditionList from "../../../components/operation/sof/departure-condition-list.vue";


const { setTitle } = Title();
const title = 'Statement of Facts';
const route = useRoute();
const voyageId = route.params.voyageId;
const { voyage, containers, isLoading, getVoyageDetails } = useVoyage();
const { uniqueServicePorts, getServiceUniquePorts } = useService();
const { voyageSof, voyageSofLists, getVoyageSofList } = useVoyageSof();
const search = ref('');
const options = ref({
  fuseOptions: {
    keys: ['stow', 'gross_wt', 'pol_code', 'pod_code', 'container', 'status', 'iso', 'mlo', 'delivery_port_code'],
    threshold: 0,
  },
  matchAllWhenSearchEmpty: true,
});
const { results: searchResults } = useFuse(search, containers, options);
const isContainersShowing = ref(false);

const openTab = ref(1);
const toggleTabs = (tabNumber,sofType) => {
  openTab.value = tabNumber;
  getVoyageSofList(voyageId,sofType);
}

watch(voyage, (value) => {
  if (value === null || value === undefined) {
    setTitle(title);
  } else {
    setTitle(`${value.voyage} - ${title}`);
  }
  getServiceUniquePorts(value?.service?.id);
});

onMounted(() => {
  getVoyageDetails(voyageId);
});
</script>

<template xmlns="http://www.w3.org/1999/html">
  <!-- Heading -->
  <Heading :label="`${title}: #${voyage?.voyage}`" type="list" :to="{ name: 'operation.voyages.index' }"></Heading>
  <!-- Content -->
  <div class="w-full flex px-4 py-3 mb-4 bg-white rounded-lg shadow-md dark:divide-gray-600 dark:bg-gray-800">
    <div class=" w-full border-gray-200 dark:border-gray-700">
      <ul class="flex flex-wrap -mb-px">
        <li class="mr-1">
          <a href="#" class="inline-flex px-1 py-1 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(1,'summery')" v-bind:class="{'text-purple-600 bg-white': openTab !== 1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 1}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Summary
          </a>
        </li>
        <li class="mr-1">
          <a href="#" class="inline-flex px-1 py-1 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(2,'usage')" v-bind:class="{'text-purple-600 bg-white': openTab !== 2, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 2}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Additional Departure Condition Report
          </a>
        </li>
        <li class="mr-1">
          <a href="#" class="inline-flex px-1 py-1 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(3,'special_cargo')" v-bind:class="{'text-purple-600 bg-white': openTab !== 3, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 3}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Special Cargo List
          </a>
        </li>
        <li class="mr-1">
          <a href="#" class="inline-flex px-1 py-1 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(4,'dg')" v-bind:class="{'text-purple-600 bg-white': openTab !== 4, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 4}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>DG List
          </a>
        </li>
        <li class="mr-1">
          <a href="#" class="inline-flex px-1 py-1 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(5,'reefer')" v-bind:class="{'text-purple-600 bg-white': openTab !== 5, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 5}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Reefer List
          </a>
        </li>
        <li class="mr-1">
          <a href="#" class="inline-flex px-1 py-1 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(6,'restow')" v-bind:class="{'text-purple-600 bg-white': openTab !== 6, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 6}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Restow List
          </a>
        </li>
        <li class="mr-1">
          <a href="#" class="inline-flex px-1 py-1 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(7,'sdr')" v-bind:class="{'text-purple-600 bg-white': openTab !== 7, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 7}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>SDR
          </a>
        </li>
        <li class="mr-1">
          <a href="#" class="inline-flex px-1 py-1 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(8,'cdr')" v-bind:class="{'text-purple-600 bg-white': openTab !== 8, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 8}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>CDR
          </a>
        </li>
        <li class="mr-1">
          <a href="#" class="inline-flex px-1 py-1 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(9,'cargo_spillage')" v-bind:class="{'text-purple-600 bg-white': openTab !== 9, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 9}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Cargo Spillage
          </a>
        </li>
      </ul>

      <div v-if="openTab === 1" v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">
        <summary-list v-bind:uniqueServicePorts="uniqueServicePorts" v-bind:voyageSofListsProp="voyageSofLists"></summary-list>
      </div>

      <div v-if="openTab === 2" v-bind:class="{'hidden': openTab !== 2, 'block': openTab === 2}">
        <departure-condition-list v-bind:uniqueServicePorts="uniqueServicePorts" v-bind:voyageSofListsProp="voyageSofLists"></departure-condition-list>
      </div>

      <div v-if="openTab === 3" v-bind:class="{'hidden': openTab !== 3, 'block': openTab === 3}">
        <special-cargo-list v-bind:uniqueServicePorts="uniqueServicePorts" v-bind:voyageSofListsProp="voyageSofLists"></special-cargo-list>
      </div>

      <div v-if="openTab === 4" v-bind:class="{'hidden': openTab !== 4, 'block': openTab === 4}">
        <dg-list v-bind:uniqueServicePorts="uniqueServicePorts" v-bind:voyageSofListsProp="voyageSofLists"></dg-list>
      </div>

      <div v-if="openTab === 5" v-bind:class="{'hidden': openTab !== 5, 'block': openTab === 5}">
        <reefer-list v-bind:uniqueServicePorts="uniqueServicePorts" v-bind:voyageSofListsProp="voyageSofLists"></reefer-list>
      </div>

      <div v-if="openTab === 6" v-bind:class="{'hidden': openTab !== 6, 'block': openTab === 6}">
        <restow-list v-bind:uniqueServicePorts="uniqueServicePorts" v-bind:voyageSofListsProp="voyageSofLists"></restow-list>
      </div>

      <div v-if="openTab === 7" v-bind:class="{'hidden': openTab !== 7, 'block': openTab === 7}">
        <sdr-list v-bind:uniqueServicePorts="uniqueServicePorts" v-bind:voyageSofListsProp="voyageSofLists"></sdr-list>
      </div>

      <div v-if="openTab === 8" v-bind:class="{'hidden': openTab !== 8, 'block': openTab === 8}">
        <cdr-list v-bind:uniqueServicePorts="uniqueServicePorts" v-bind:voyageSofListsProp="voyageSofLists"></cdr-list>
      </div>

      <div v-if="openTab === 9" v-bind:class="{'hidden': openTab !== 9, 'block': openTab === 9}">
        <cargo-spillage-list v-bind:uniqueServicePorts="uniqueServicePorts" v-bind:voyageSofListsProp="voyageSofLists"></cargo-spillage-list>
      </div>

    </div>
  </div>
</template>

<style lang="postcss" scoped>
@tailwind components;

@layer components {
  .group {
    @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
  }
  .group-item {
    @apply block w-full mt-3 text-sm;
  }
  .group-title {
    @apply font-semibold uppercase dark:text-gray-200;
  }
  .group-content {
    @apply ml-1 capitalize dark:text-gray-300 text-xs;
  }
  .group-empty {
    @apply hidden md:block md:w-full;
  }
  .label-item-title {
    @apply text-gray-700 dark:text-gray-300;
  }
  .label-item-input {
    @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
  }
  .form-input {
    @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
  }
  th {
    @apply px-3 py-2;
  }
  .search-result {
    @apply px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-300;
  }
  .search {
    @apply float-right  pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
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

  td {
    @apply px-3 py-2 text-xs;
  }
  thead tr {
    @apply text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  tbody {
    @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
  }

  table th,tr,td{
   border: 1px solid gray;
  }

  ul li a{
    font-size: 12px;
    font-weight: bold;
  }
}
</style>