<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
        <span>Service Details:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ serviceId }}</span>
    </div>
    <!-- Table -->
    <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Field Name</th>
                        <th class="px-4 py-3">Data</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">Service Code</td>
                        <td class="px-4 py-3 text-sm">{{ service.code }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="px-4 py-3">Service Name</td>
                      <td class="px-4 py-3 text-sm">{{ service.name }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="px-4 py-3">Service Route</td>
                      <td class="px-4 py-3 text-sm">{{ service.route }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
  <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
    <span>Sectors:</span>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-5">
    <!--Card 1-->
    <div v-for="(sector,index) in service.sectors" class="rounded overflow-hidden shadow-lg bg-gray-300 mb-4">
      <div class="px-6 py-4">
        <div class="font-bold text-xl mb-2">Sector #{{ index + 1 }}</div>
        <table class="w-full whitespace-no-wrap">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">Field Name</th>
            <th class="px-4 py-3">Data</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3">Bound</td>
            <td class="px-4 py-3 text-sm">{{ sector.bound }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3">POL</td>
            <td class="px-4 py-3 text-sm">{{ sector.pol }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3">POD</td>
            <td class="px-4 py-3 text-sm">{{ sector.pod }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3">Excess Load</td>
            <td class="px-4 py-3 text-sm" v-if="sector.excess_load">Yes</td>
            <td class="px-4 py-3 text-sm" v-else>No</td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</template>
<script setup>
import { onMounted } from '@vue/runtime-core';
import { useRoute } from 'vue-router';
import useBooking from '../../../composables/commercial/useService';
import useService from "../../../composables/commercial/useService";

const route = useRoute();
const serviceId = route.params.serviceId;
const { service, showService } = useService();

onMounted(() => {
    showService(serviceId);
});
</script>