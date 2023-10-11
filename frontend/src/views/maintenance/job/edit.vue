<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Job</h2>
      <router-link :to="{ name: 'maintenance.job.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        Job List
      </router-link>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
          <form @submit.prevent="updateJob(job, jobId)">
              <!-- Booking Form -->
            <job-form v-model:form="job" :errors="errors"></job-form>
              <!-- Submit button -->
              <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Job</button>
          </form>
      </div>
  </template>
  <script setup>
  import {onMounted, ref, watch} from 'vue';
  import { useRoute } from 'vue-router';
  
  import ItemGroupForm from '../../../components/maintenance/item-group/ItemGroupForm.vue'; 
  import JobForm from '../../../components/maintenance/job/JobForm.vue';
  import Title from "../../../services/title";
  import useJob from '../../../composables/maintenance/useJob';
  
  const route = useRoute();
  const jobId = route.params.jobId;
  const { job, jobCycleUnits, shipDepartmentWiseItems, showJob, updateJob, errors } = useJob();
  
  const { setTitle } = Title();
  
  setTitle('Edit Job');

  watch(job, (value) => {
    job.value.dept_wise_items = value.mnt_ship_department.mnt_item;
    job.value.item_name = value.mnt_item;
  });
  
  onMounted(() => {
      showJob(jobId);
  });
  </script>