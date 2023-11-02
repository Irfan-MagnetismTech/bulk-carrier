<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Job</h2>
      <!-- <router-link :to="{ name: 'mnt.jobs.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        Job List
      </router-link> -->
      <default-button :title="'Job List'" :to="{ name: 'mnt.jobs.index' }" :icon="icons.DataBase"></default-button>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
          <form @submit.prevent="updateJob(job, jobId)">
              <!-- Booking Form -->
            <job-form :page="page" v-model:form="job" :errors="errors"></job-form>
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
  import useHeroIcon from "../../../assets/heroIcon";
  import DefaultButton from '../../../components/buttons/DefaultButton.vue';
  
  const route = useRoute();
  const jobId = route.params.jobId;
  const { job, jobCycleUnits, showJob, updateJob, errors } = useJob();
  const icons = useHeroIcon();
  
  const { setTitle } = Title();
  const page = 'edit';
  
  setTitle('Edit Job');

  watch(job, (value) => {
    job.value.ops_vessel_name = value?.opsVessel;

    job.value.mnt_ship_department_name = value?.mntItem?.mntItemGroup?.mntShipDepartment;

    job.value.mnt_item_groups = value?.mntItem?.mntItemGroup?.mntShipDepartment?.mntItemGroups;
    job.value.mnt_item_group_name = value?.mntItem?.mntItemGroup;

    job.value.mnt_items = value?.mntItem?.mntItemGroup?.mntItems;
    job.value.mnt_item_name = value?.mntItem;

    // job.value.form_type = 'edit';
  });
  
  onMounted(() => {
      showJob(jobId);
  });
  </script>