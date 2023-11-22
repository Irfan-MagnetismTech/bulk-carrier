<script setup>
import { onMounted } from '@vue/runtime-core';
import { useRoute } from 'vue-router';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useActivityLog from "../../../composables/administration/useActivityLog";
import Title from "../../../services/title";
const route = useRoute();
const subject_id = route.params.subject_id;
const subject_type = route.params.subject_type;

const { activities, activity, getActivityLog, isLoading } = useActivityLog();

const { setTitle } = Title();

setTitle('Activity Log');

onMounted(() => {
  getActivityLog(subject_type, subject_id);
});
</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Activity Log</h2>
<!--    <router-link :to="{ name: 'administration.user.permission.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">-->
<!--      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">-->
<!--        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />-->
<!--      </svg>-->
<!--    </router-link>-->
  </div>
  <div>
    <div class="w-full overflow-hidden">
      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap mb-8">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="p-1">SL</th>
            <th class="p-1">Type</th>
            <th class="p-1">User</th>
            <th class="p-1">Old Data</th>
            <th class="p-1">New Data</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr v-for="(activityData,index) in activities" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ activityData?.event }}</td>
            <td>{{ activityData?.causer?.name }}</td>
            <td style="text-align: left !important;">
              <span v-for="(oldData,key,index) in activityData?.properties?.old">
                {{ key }}: {{ oldData }} <br>
              </span>
            </td>
            <td style="text-align: left !important;">
              <span v-for="(newData,key,index) in activityData?.properties?.attributes">
                {{ key }}: {{ newData }} <br>
              </span>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!activities?.length" class="bg-white dark-disabled:bg-gray-800">
          <tr v-if="isLoading">
            <td colspan="5">Loading...</td>
          </tr>
          <tr v-else-if="!activities?.length">
            <td colspan="5">No activity log found.</td>
          </tr>
          </tfoot>
        </table>
      </div>
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
    @apply font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800;
  }
  th {
    @apply tab text-center;
  }
  tbody {
    @apply bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800;
  }
  tbody tr {
    @apply text-gray-700 dark-disabled:text-gray-400;
  }
  tbody tr td {
    @apply tab text-center;
  }
  tfoot td {
    @apply tab text-center;
  }
}

.input-group {
  @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
}
.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark-disabled:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
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