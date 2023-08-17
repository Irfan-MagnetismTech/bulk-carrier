<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Services</h2>
        <router-link :to="{ name: 'commercial.services.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
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
                        <th class="px-4 py-3">SL. </th>
                        <th class="px-4 py-3">Code </th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Route</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(service,index) in services" :key="service.id" v-memo>
                        <td class="px-4 py-3 text-sm">{{ index + 1 }}</td>
                        <td class="px-4 py-3 text-sm">{{ service.code }}</td>
                        <td class="px-4 py-3 text-sm">{{ service.name }}</td>
                        <td class="px-4 py-3 text-sm">{{ service.route }}</td>
                        <td class="px-4 py-3 text-sm">
                          <span class="">{{ service?.approved_status }} </span>
                          <br>
                          <span v-if="service?.current_approver" class="text-xs mr-2 mb-2 inline-block py-1 px-2.5 leading-none whitespace-nowrap align-baseline font-bold bg-gray-200 text-gray-700 rounded">Waiting For: {{ service?.current_approver }}</span>
                        </td>
                        <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
                          <button v-if="service?.get_approve_button" @click="approveService(1,service?.approver_composite_key,service?.id,service?.subject_type)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Approve
                          </button>
                            <action-button :action="'edit'" :to="{ name: 'commercial.services.edit', params: { serviceId: service.id } }"></action-button>
                            <action-button :action="'show'" :to="{ name: 'commercial.services.show', params: { serviceId: service.id } }"></action-button>
                            <action-button @click="deleteService(service.id)" :action="'delete'"></action-button>
                            <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: service.subject_type,subject_id: service.id } }"></action-button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!services?.length" class="bg-white dark:bg-gray-800">
                <tr v-if="isLoading">
                  <td colspan="8">Loading...</td>
                </tr>
                <tr v-else-if="!services?.length">
                  <td colspan="8">No service found.</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>
<script setup>
import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useService from '../../../composables/commercial/useService';
import useApprovalManagement from "../../../composables/authorization/useApprovalManagement";
import Title from "../../../services/title";
const { services, getServices, deleteService, isLoading } = useService();
const { approved } = useApprovalManagement();
const { setTitle } = Title();

setTitle('Services');

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
    getServices();
});
</script>
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