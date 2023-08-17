<script setup>
import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useTariff from '../../../composables/stevedorage/useTariff';
import Title from "../../../services/title";
const { tariffs, getTariffs, deleteTariff, updateTariffStatus, isLoading } = useTariff();
const { setTitle } = Title();
import useSweetAlert2 from "../../../composables/useSweetAlert2";
import Swal from "sweetalert2";
const sweetAlert = useSweetAlert2();

setTitle('Tariffs');

function toggleTariffStatus(tariffId,$e){
  let formData = {
    id: tariffId
  };
  Swal.fire({
      title: 'Are you sure?',
      text: "You want to change the status of this tariff!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Change'
    }).then((result) => {
      if (result.isConfirmed) {
        updateTariffStatus(formData);
      } else {
        $e.target.checked = !($e.target.checked);
      }
    })
}

onMounted(() => {
  getTariffs();
});
</script>

<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Tariffs</h2>
        <router-link :to="{ name: 'stevedorage.tariffs.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
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
                        <th class="px-4 py-3">Port Code </th>
                        <th class="px-4 py-3">Tariff Name </th>
                        <th class="px-4 py-3">Cost Type</th>
                        <th class="px-4 py-3">Expire On</th>
                        <th class="px-4 py-3">Short Note</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(tariff,index) in tariffs" :key="tariff.id" v-memo>
                        <td class="px-4 py-3 text-sm">{{ index + 1 }}</td>
                        <td class="px-4 py-3 text-sm">{{ tariff?.port_code }}</td>
                        <td class="px-4 py-3 text-sm">{{ tariff?.name }}</td>
                        <td class="px-4 py-3 text-sm">{{ tariff?.cost_type }}</td>
                        <td class="px-4 py-3 text-sm">{{ tariff?.expire_date }}</td>
                        <td class="px-4 py-3 text-sm">{{ tariff?.short_note }}</td>
                        <td class="px-4 py-3 text-sm">
                          <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer" :checked="tariff.is_archived" @change="toggleTariffStatus(tariff.id,$event)">
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span v-if="tariff?.is_archived" class="ml-2 px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">Archived</span>
                            <span v-else class="ml-2 px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Active</span>
                          </label>
<!--                            <span v-if="tariff?.is_archived" class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">-->
<!--                                Archived-->
<!--                            </span>-->
<!--                            <span v-else class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">-->
<!--                                Active-->
<!--                            </span>-->
                        </td>
                        <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
                            <action-button :action="'edit'" :to="{ name: 'stevedorage.tariffs.edit', params: { tariffId: tariff.id } }"></action-button>
                            <action-button :action="'show'" :to="{ name: 'stevedorage.tariffs.show', params: { tariffId: tariff.id } }"></action-button>
                            <action-button @click="deleteTariff(tariff.id)" :action="'delete'"></action-button>
<!--                            <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: tariff.subject_type,subject_id: tariff.id } }"></action-button>-->
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!tariffs?.length" class="bg-white dark:bg-gray-800">
                <tr v-if="isLoading">
                  <td colspan="8">Loading...</td>
                </tr>
                <tr v-else-if="!tariffs?.length">
                  <td colspan="8">No service found.</td>
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
    @apply p-1.5 text-xs;
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