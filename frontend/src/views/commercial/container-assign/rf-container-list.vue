<script setup>
import { onMounted } from '@vue/runtime-core';
import useRfContainerRefferAssign from '../../../composables/commercial/useRfContainerRefferAssign';
import useVoyage from "../../../composables/operation/useVoyage";
import Title from "../../../services/title";
import {watch} from "vue";

const { voyages, voyage:voyageDetail, showVoyage, voyageName, getVoyageName } = useVoyage();
const { voyage, formParams, rfContainerParams, isLoading, getRfContainerList, assignRfContainerReffer } = useRfContainerRefferAssign();

const { setTitle } = Title();

setTitle('RF Assign');

function fetchOptions(search, loading) {
  getVoyageName(search);
}

function assignRfContainer(rfContainerParams) {

  let data = {
    rf_containers: rfContainerParams,
    voyage_id: formParams.value.voyage_id,
  };
  assignRfContainerReffer(data);
}

watch(voyage,function (val) {

  showVoyage(formParams.value.voyage_id);

  rfContainerParams.value.length = 0;
  val?.map(function(container, containerIndex) {
    let dataObj = {
      is_refer: container?.is_refer ?? '',
      container_id: container.id,
      container: container?.container,
      iso: container?.iso,
      stow: container?.stow,
      un: container?.un,
      pol_code: container?.pol_code,
      pod_code: container?.pod_code,
      customer_code: container?.customer?.customer_code,
      mlo: container?.mlo,
      tues: container?.tues,
    };
    rfContainerParams.value.push(dataObj);
  });
});

onMounted(() => {
  //getVoyageName();
});
</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Reffer Container List</h2>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <form class="flex items-end justify-between gap-4" @submit.prevent="getRfContainerList(formParams)">
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Voyage <span class="text-red-500">*</span></span>
        <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchOptions" v-model="formParams.voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required></v-select>
      </label>

      <!-- Submit button -->
      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
    </form>
  </div>
  <div>
    <form class="flex items-end justify-between gap-4" @submit.prevent="assignRfContainer(rfContainerParams)">
    <div class="w-full overflow-hidden">
      <div class="w-full overflow-x-auto">
        <table class="w-full mb-8 whitespace-no-wrap">
          <thead v-once>
            <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th class="p-1">SL</th>
              <th class="p-1">Cont Ref</th>
              <th class="p-1">ISO</th>
              <th class="p-1">Stoage</th>
              <th class="p-1">POL</th>
              <th class="p-1">POD</th>
              <th class="p-1">S/O</th>
              <th class="p-1">MLO</th>
              <th class="p-1">Tues</th>
              <th class="p-1">Reffer Status</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr class="text-center text-gray-700 dark:text-gray-400" v-for="(container, index) in rfContainerParams" :key="index">
                <td class="p-1">{{ index + 1 }}</td>
                <td class="p-1 text-left">{{ container.container }}</td>
                <td class="p-1 text-center">{{ container.iso }}</td>
                <td class="p-1 text-center">{{ container.stow }}</td>
                <td class="p-1 text-center">{{ container.pol_code }}</td>
                <td class="p-1 text-center">{{ container.pod_code }}</td>
                <td class="p-1 text-center">---</td>
                <td class="p-1 text-center">{{ container.mlo }}</td>
                <td class="p-1 text-center">{{ container.tues }}</td>
                <td class="p-1 text-center">
                  <select :class="{'custom_text_opacity' : voyageDetail?.invoices?.length }" v-model="rfContainerParams[index].is_refer" class="mt-1 text-xs rounded dark:text-gray-300 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" required>
                    <option value="" disabled selected>Select</option>
                    <option value="1">Operative</option>
                    <option value="0">Non Operative</option>
                  </select>
                </td>
              </tr>
          </tbody>
          <tfoot v-if="!voyage?.length" class="bg-white dark:bg-gray-800">
            <tr v-if="isLoading">
              <td colspan="11">Loading...</td>
            </tr>
            <tr v-else-if="!voyage?.length">
              <td colspan="11">No container list found.</td>
            </tr>
          </tfoot>
        </table>
      </div>
      <button type="submit" :class="{'custom_text_opacity' : voyageDetail?.invoices?.length }" v-if="rfContainerParams?.length" :disabled="isLoading" class="w-full px-4 py-2 mb-5 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
    </div>
    </form>
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
  table, th,td{
    @apply border border-collapse;
  }
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