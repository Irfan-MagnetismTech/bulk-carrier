<script setup>
import { onMounted } from '@vue/runtime-core';
import useVoyage from '../../../composables/operation/useVoyage';
import {computed, ref} from "vue";
import useContractAssign from "../../../composables/commercial/useContractAssign";
import Title from "../../../services/title";

const { voyages, voyageName, getVoyages, getVoyageName, deleteVoyage } = useVoyage();

const { form, contractVoyages, getContractVoyages, showVoyageWithNo, isLoading } = useContractAssign();

const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  openTab.value = tabNumber;
}

function getVoyageData(form) {
  //console.log(form.voyage_id);
  showVoyageWithNo(form.voyage_id);
}

function fetchOptions(search, loading) {
  getVoyageName(search);
}

const { setTitle } = Title();

setTitle('Contract Assign');

onMounted(() => {
  getContractVoyages();
});
</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Assign Contract </h2>
  </div>
  <div class="px-2 py-2 mb-5 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <form @submit.prevent="getVoyageData(form)" class="flex items-end justify-between gap-4">
      <!-- Booking Form -->
      <label class="block w-full text-sm">
        <span class="text-gray-700 dark:text-gray-300">Voyage No</span>
        <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchOptions" v-model="form.voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required></v-select>
      </label>
      <!-- Submit button -->
      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
    </form>
  </div>

  <div>
    <div class="w-full overflow-hidden">
      <div class="w-full overflow-x-auto">
        <table class="w-full mb-5 whitespace-no-wrap">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">Vessel</th>
            <th class="px-4 py-3">VVD</th>
            <th class="px-4 py-3">Service</th>
            <th class="px-4 py-3">Route</th>
            <th class="px-4 py-3">TTL Container</th>
            <th class="px-4 py-3">TTL Tues</th>
            <th class="px-4 py-3">Action</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-center text-gray-700 dark:text-gray-400" v-for="voyage in contractVoyages" :key="voyage.id">
            <td class="px-4 py-3 text-sm">{{ voyage.vessel.name }}</td>
            <td class="px-4 py-3 text-sm">{{ voyage.voyage }}</td>
            <td class="px-4 py-3 text-sm"> {{voyage.service.code}} </td>
            <td class="px-4 py-3 text-sm"> {{voyage.service.route}} </td>
            <td class="px-4 py-3 text-sm"> {{voyage.totalContainers}} </td>
            <td class="px-4 py-3 text-sm"> {{voyage.totalTues ?? 0 + voyage.totalKilledTues ?? 0}} </td>

            <td class="flex items-center justify-center px-4 py-3 space-x-2 text-sm text-center text-gray-600">
              <!--              <router-link :to="{ name: 'commercial.contract-assigns.voyage', params: { voyageId : voyage.id } }" title="Assign Contract" :class="{'disabled': voyage?.is_all_contract_assigned}">-->
              <!--                <svg disabled="" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: green">-->
              <!--                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />-->
              <!--                </svg>-->
              <!--              </router-link>-->
              <router-link :to="{ name: 'commercial.voyage.assign-contract.list', params: { voyageId : voyage.id } }" title="Contract List">
                <!--                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">-->
                <!--                  <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />-->
                <!--                </svg>-->
                <svg style="color: #664cc3" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </router-link>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!contractVoyages?.length" class="bg-white dark:bg-gray-800">
          <tr v-if="isLoading">
            <td colspan="7">Loading...</td>
          </tr>
          <tr v-else-if="!contractVoyages?.length">
            <td colspan="7">No assigned contract found.</td>
          </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</template>

<style lang="postcss" scoped>
.disabled{
  opacity: 50%;
  pointer-events: none;
}

thead tr {
  @apply font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
}
th {
  @apply text-center;
}
tbody {
  @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
}
tbody tr {
  @apply text-gray-700 dark:text-gray-400;
}
tbody tr td {
  @apply text-center;
}
tfoot td {
  @apply text-center;
}
table, th,td{
  @apply border border-collapse;
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