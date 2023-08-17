<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import useVoyage from '../../../composables/operation/useVoyage';
import Heading from '../../../components/Heading.vue';

const route = useRoute();
const voyageId = route.params.voyageId;
const voyageCustomerId = route.params.voyageCustomerId;
const { voyage, containers, isLoading, getVoyageDetails, getContainersByVoyage, getVoyageCustomerContainers } = useVoyage();

onMounted(() => {
    //getVoyageDetails(voyageId);
    getVoyageCustomerContainers(voyageCustomerId);
});
</script>

<template>
    <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" data-v-3010b3a4="">
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200"># Container List of {{containers?.customer?.customer_code}} ({{containers?.voyage?.voyage}})</h2>
  </div>
    <!-- Content -->
    <div class="px-4 py-3 mb-4 bg-white divide-y rounded-lg shadow-md dark:divide-gray-600 dark:bg-gray-800">
      <div class="w-full mb-6 overflow-hidden rounded-lg shadow-xs" v-memo>
        <div class="w-full md:overflow-x-auto">
          <table class="w-full whitespace-no-wrap border dark:border-0">
            <thead>
            <tr>
              <th>Slot</th>
              <th>Bay</th>
              <th>Weight</th>
              <th>Pol</th>
              <th>Pod</th>
              <th>Container No</th>
              <th>Status</th>
              <th>ISO</th>
              <!-- <th>Group</th> -->
              <th>MLO</th>
              <th>Slot Partner</th>
              <th>CID</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
            <tr class="text-gray-700 dark:text-gray-400" v-for="(container,index) in containers?.containers" :key="index">
              <td>{{ container?.stow }}</td>
              <td>{{ container?.stow?.slice(0, 2) }}</td>
              <td>{{ container?.gross_wt }}</td>
              <td>{{ container?.pol_code }}</td>
              <td>{{ container?.pod_code }}</td>
              <td>{{ container?.container }}</td>
              <td>{{ container?.status }}</td>
              <td>{{ container?.iso }}</td>
              <!-- <td>{{ result?.item?.group }}</td> -->
              <td>{{ container?.mlo }}</td>
              <td class="border-r-0">{{ container?.slot_partner }}</td>
              <td class="border-r-0">{{ containers?.customer?.customer_code }}</td>
            </tr>
            <tr v-if="containers.length === 0">
              <td colspan="14" class="search-result">No data available</td>
            </tr>
            </tbody>
          </table>
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
    td {
        @apply px-3 py-2 text-xs border-r dark:border-r-gray-600;
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
}
</style>