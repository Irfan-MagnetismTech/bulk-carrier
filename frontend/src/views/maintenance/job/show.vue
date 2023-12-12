<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useJob from '../../../composables/maintenance/useJob';

const icons = useHeroIcon();

const route = useRoute();
const jobId = route.params.jobId;
const { job, showJob, errors } = useJob();

const { setTitle } = Title();

setTitle('Job Details');

onMounted(() => {
  showJob(jobId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Job Details</h2>
    <default-button :title="'Job List'" :to="{ name: 'mnt.jobs.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Job Information</h2>
          <table class="w-full">
            <!-- <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="2">Personal Info</td>
            </tr>
            </thead> -->
            <tbody>
              <tr>
                <th class="w-40">Business Unit</th>
                <td><span :class="job?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ job?.business_unit }}</span></td>
              </tr>
              <tr>
                <th class="w-40">Vessel</th>
                <td>{{ job?.opsVessel?.name }}</td>
              </tr>
              
              <tr>
                <th class="w-40">Ship Department</th>
                <td>{{ job?.mntItem?.mntItemGroup?.mntShipDepartment?.name }}</td>
              </tr>
              <tr>
                <th class="w-40">Item Group</th>
                <td>{{ job?.mntItem?.mntItemGroup?.name }}</td>
              </tr>


              
              <tr>
                <th class="w-40">Item Code</th>
                <td>{{ job?.mntItem?.item_code }}</td>
              </tr>
              
              <tr>
                <th class="w-40">Item Name</th>
                <td>{{ job?.mntItem?.name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Present Runnig Hour</th>
                <td>{{ job?.present_run_hour }} Hours</td>
              </tr>
                            
              <tr>
                <th class="w-40">Job List</th>
                <td>
                  <table class="w-full">
                    <thead>
                      <th class="text-center"> Job Description </th>
                      <th class="text-center"> Cycle </th>
                      <th class="text-center"> Add To Upcoming List </th>
                      <th class="text-center"> Last Done </th>
                      <th class="text-center"> Remarks </th>
                    </thead>
                    <tbody>
                      <tr v-for="(jobLine, index) in job.mntJobLines" :key="index">
                        <td> {{ jobLine?.job_description  }} </td>
                        <td> {{jobLine?.cycle }} {{ jobLine.cycle_unit }}</td>
                        <td> <p class="text-center">{{ jobLine?.min_limit}} {{ jobLine.cycle_unit }}</p> </td>
                        <td> {{ jobLine.last_done }} </td>
                        <td> {{ jobLine.remarks }} </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
          
        </div>
      </div>
  </div>
</template>
<style lang="postcss" scoped>
  th, td, tr {
    @apply text-left border-gray-500
  }

  #profileDetailTable th{
    text-align: center;
  }
  #profileDetailTable thead tr{
    @apply bg-gray-200
  }

</style>