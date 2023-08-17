<script setup>
import { onMounted, computed, ref, watchEffect } from '@vue/runtime-core';
import { orderBy } from 'lodash';
import { onClickOutside } from '@vueuse/core'
import moment from 'moment';
import useVoyage from '../../../composables/operation/useVoyage';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useReport from '../../../composables/operation/useReport';
import useVoyageProspect from "../../../composables/operation/useVoyageProspect";

const props = defineProps({
  page: {
    type: Number,
    default: '',
  },
});

const { voyages, isLoading, getVoyages, deleteVoyage } = useVoyage();
const { deleteVoyageProspects} = useVoyageProspect();
const { generateBayPlanPDF, generateVoyageEdi, generateVoyageExcel, generateVoyageSummary, generateCopinoBooking } = useReport();

const orderedVoyages = ref(computed(() => {
    return orderBy(voyages.value, ['voyage'], ['asc']);
}));

const menu = ref(false);
const showingMenuIndex = ref(-1);

onClickOutside(menu, (event) => showingMenuIndex.value = -1);

function toggleMenu(index) {
    if (showingMenuIndex.value === index) {
        showingMenuIndex.value = -1;
    } else {
        showingMenuIndex.value = index;
    }
}

onMounted(() => {
  watchEffect(() => {
    getVoyages(props.page);
  });
});
</script>
<template>
    <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row">
    <slot>
      <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Voyage Prospects</h2>
    </slot>
  </div>
    <!-- Table -->
    <!-- <div class="max-w-screen h-full overflow-x-auto bg-white overflow-y-auto mb-4">
    </div>-->
    <div class="w-full border rounded-lg shadow-xs dark:border-0">
        <div class="w-full">
            <table class="w-full whitespace-no-wrap">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <th class="text-center">#</th>
                        <th class="text-center">Voyage no</th>
                        <th>Sender</th>
                        <th>Recipient</th>
                        <th>Departure Date</th>
                        <th>Arrival Date</th>
                        <th>Berthing Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400" v-for="(voyage, index) in orderedVoyages" :key="voyage.id">
                        <td class="px-1 text-center">{{ index + 1 }}</td>
                        <td class="font-semibold text-center dark:text-gray-300">{{ voyage?.voyage }}</td>
                        <td>{{ voyage?.sender }}</td>
                        <td>{{ voyage?.recipient }}</td>
                        <td>{{ voyage?.departure_date ? moment(voyage?.departure_date).format('DD-MM-YYYY') : null }}</td>
                        <td>{{ voyage?.arrival_date ? moment(voyage?.arrival_date).format('DD-MM-YYYY') : null}}</td>
                        <td>{{ voyage?.berthing_date ? moment(voyage?.berthing_date).format('DD-MM-YYYY') : null }}</td>
                        <td class="flex items-center justify-center space-x-2 text-gray-600">
                          <router-link :to="{ name: 'operation.voyage.prospects.create', params: { voyageId: voyage?.id ?? -1 } }" title="Add or Update" class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                          </router-link>
                          <action-button @click="deleteVoyageProspects(voyage?.id)" :action="'delete'"></action-button>
                        </td>
                    </tr>
                    <tr v-if="voyages.length === 0">
                        <td colspan="8" class="text-center dark:text-gray-400">
                            <span v-if="!isLoading">No records available.</span>
                            <span v-else-if="isLoading" class="flex items-center justify-center w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="w-5 h-5 iconify iconify--eos-icons" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                    <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z" opacity=".5" fill="currentColor" />
                                    <path d="M20 12h2A10 10 0 0 0 12 2v2a8 8 0 0 1 8 8z" fill="currentColor">
                                        <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite" />
                                    </path>
                                </svg>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<style lang="postcss" scoped>
th {
    @apply px-3 py-2 border-r;
}

td {
    @apply px-3 py-2 text-xs border-r;
}

.dropdown-btn {
    @apply bg-gray-900 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-gray-50 text-white font-semibold py-2 px-3 rounded-lg w-full flex items-center justify-center gap-1.5 sm:w-auto dark:bg-blue-500 dark:hover:bg-blue-400;
}
</style>