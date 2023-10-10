<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Run Hour</h2>
      <router-link :to="{ name: 'maintenance.run-hour.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        Run Hour List
      </router-link>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
          <form @submit.prevent="updateRunHour(runHour, runHourId)">
              <!-- Booking Form -->
            <run-hour-form v-model:form="runHour" :errors="errors"></run-hour-form>
              <!-- Submit button -->
              <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Run Hour</button>
          </form>
      </div>
  </template>
  <script setup>
  import {onMounted, ref, watch} from 'vue';
  import { useRoute } from 'vue-router';
  
  import RunHourForm from '../../../components/maintenance/run-hour/RunHourForm.vue'; 
  import Title from "../../../services/title";
  import useRunHour from '../../../composables/maintenance/useRunHour';
  
  const route = useRoute();
  const runHourId = route.params.runHourId;
  const { runHour, showRunHour, updateRunHour, errors } = useRunHour();
  
  const { setTitle } = Title();
  
  setTitle('Edit Run Hour');
  
  onMounted(() => {
      showRunHour(runHourId);
  });
  </script>