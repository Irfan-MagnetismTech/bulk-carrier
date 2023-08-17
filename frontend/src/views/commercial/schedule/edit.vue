<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import ScheduleForm from '../../../components/commercial/ScheduleForm.vue';
import useSchedule from "../../../composables/commercial/useSchedule";
import Title from "../../../services/title";

const route = useRoute();
const scheduleId = route.params.scheduleId;
const { schedule, showSchedule, updateSchedule, errors } = useSchedule();

const { setTitle } = Title();

setTitle('Edit Schedule');
const page = ref('update');

// watch(schedule, (value) => {
//   if(value?.schedule_ports?.length) {
//     value?.schedule_ports.forEach((schedule, index) => {
//       schedule.value.schedule_ports[index].pol_code_name = schedule?.sector_pol ?? '';
//       schedule.value.schedule_ports[index].pod_code_name = schedule?.sector_pod ?? '';
//     });
//   }
// });

onMounted(() => {
  showSchedule(scheduleId);
});
</script>

<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2">
        <span>Update Schedule:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ scheduleId }}</span>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateSchedule(schedule, scheduleId)">
            <!-- Booking Form -->
          <schedule-form v-model:form="schedule" v-model:page="page" :errors="errors"></schedule-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Schedule</button>
        </form>
    </div>
</template>