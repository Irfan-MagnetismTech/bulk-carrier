<script setup>
import { onMounted } from '@vue/runtime-core';
import { useRoute } from 'vue-router';
import useSchedule from "../../../composables/commercial/useSchedule";

const route = useRoute();
const scheduleId = route.params.scheduleId;
const { schedule, showSchedule } = useSchedule();

onMounted(() => {
  showSchedule(scheduleId);
});
</script>

<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
        <span>Schedule Details:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ scheduleId }}</span>
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
                        <td class="px-4 py-3 text-sm">{{ schedule?.service?.code }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="px-4 py-3">Vessel Name</td>
                      <td class="px-4 py-3 text-sm">{{ schedule?.vessel?.name }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="px-4 py-3">Status</td>
                      <td class="px-4 py-3 text-sm">{{ schedule?.status }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
  <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
    <span>Schedule Ports:</span>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-5">
    <!--Card 1-->
    <div v-for="(port,index) in schedule.schedule_ports" class="rounded overflow-hidden shadow-lg bg-gray-300 mb-4">
      <div class="px-6 py-4">
        <div class="font-bold text-xl mb-2">Port #{{ index + 1 }}</div>
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
            <td class="px-4 py-3 text-sm">{{ port?.bound?.toUpperCase() }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3">Voyage No</td>
            <td class="px-4 py-3 text-sm">{{ port?.voyage }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3">ETB Date</td>
            <td class="px-4 py-3 text-sm">{{ port?.etb_date }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3">ETD Date</td>
            <td class="px-4 py-3 text-sm">{{ port?.etd_date }}</td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</template>
