<script setup>
import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useApprovalManagement from "../../../composables/authorization/useApprovalManagement";
import Title from "../../../services/title";
import useSchedule from "../../../composables/commercial/useSchedule";
const { schedules, getSchedule, deleteSchedule, isLoading } = useSchedule();
const { approved } = useApprovalManagement();
const { setTitle } = Title();

setTitle('Schedule');

function approveService(is_approved,approver_composite_key,approvable_id,approvable_type){
  let data = {
    "is_approved": is_approved,
    "approver_composite_key": approver_composite_key,
    "approvable_id": approvable_id,
    "approvable_type": approvable_type,
  }
  approved(data);
}

onMounted(() => {
  getSchedule();
});
</script>

<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Schedule</h2>
      <router-link :to="{ name: 'commercial.schedule.website.layout' }" target="_blank" class="text-blue-600">
        <i class="fas fa-plus"></i>
        <span>Website Layout</span>
      </router-link>
        <router-link :to="{ name: 'commercial.schedule.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
        </router-link>
    </div>
    <!-- Table -->
    <div class="w-full overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">SL.</th>
                        <th class="px-4 py-3">Service</th>
                        <th class="px-4 py-3">Vessel</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(schedule,index) in schedules" :key="schedule.id" v-memo>
                        <td class="px-4 py-3 text-sm">{{ index + 1 }}</td>
                        <td class="px-4 py-3 text-sm">{{ schedule?.service?.code }}</td>
                        <td class="px-4 py-3 text-sm">{{ schedule?.vessel?.name }}</td>
                        <td class="px-4 py-3 text-sm">
                          <span v-if="schedule?.status === 'Published'" class="text-xs inline-block py-1 px-2.5 leading-none text-center whitespace-nowrap align-baseline font-bold bg-green-500 text-white rounded">{{ schedule?.status }}</span>
                          <span v-if="schedule?.status === 'Not-Published'" class="text-xs inline-block py-1 px-2.5 leading-none text-center whitespace-nowrap align-baseline font-bold bg-red-500 text-white rounded">{{ schedule?.status }}</span>
                        </td>
                        <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
                            <action-button :action="'edit'" :to="{ name: 'commercial.schedule.edit', params: { scheduleId: schedule.id } }"></action-button>
                            <action-button :action="'show'" :to="{ name: 'commercial.schedule.show', params: { scheduleId: schedule.id } }"></action-button>
                            <action-button @click="deleteSchedule(schedule.id)" :action="'delete'"></action-button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!schedules?.length" class="bg-white dark:bg-gray-800">
                <tr v-if="isLoading">
                  <td colspan="5">Loading...</td>
                </tr>
                <tr v-else-if="!schedules?.length">
                  <td colspan="5">No schedule found</td>
                </tr>
                </tfoot>
            </table>
        </div>
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
</style>