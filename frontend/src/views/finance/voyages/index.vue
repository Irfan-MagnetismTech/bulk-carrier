<script setup>
import { onMounted, computed, ref, watchEffect } from 'vue';
import { orderBy } from 'lodash';
import { onClickOutside } from '@vueuse/core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import moment from 'moment';
import Heading from '../../../components/Heading.vue';
import useVoyage from '../../../composables/operation/useVoyage';
import useReport from '../../../composables/operation/useReport';
import Title from "../../../services/title";
import useVoyagePairing from "../../../composables/finance/useVoyagePairing";


const props = defineProps({
    page: {
        type: Number,
        default: '',
    },
});

const { pairList, getAllPairs } = useVoyagePairing();

const orderedVoyages = ref(computed(() => {
    return orderBy(pairList?.value?.data, ['voyage'], ['asc']);
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

const { setTitle } = Title();

setTitle('Financially Closed Voyages');

onMounted(() => {
  watchEffect(() => {
    getAllPairs(props.page);
  });
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row">
      <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Voyages</h2>
      <!-- <h2 class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Head Wise Entry</h2> -->
        <router-link :to="{ name: 'finance.head-wise-voyage-expense.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <span>Expense Entry</span>
        </router-link>
    </div>

    <!-- Table -->
    <!-- <div class="max-w-screen h-full overflow-x-auto bg-white overflow-y-auto mb-4">
    </div>-->
    <div class="w-full">
        <div class="w-full">
            <table class="w-full whitespace-no-wrap mb-5">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <th class="text-center">#</th>
                        <th class="text-center">Round Voyage</th>
                        <th>Financial Closing Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400" v-for="(voyage, index) in orderedVoyages" :key="voyage.id">
                        <td class="px-1 text-center">{{ index + 1 }}</td>
                        <td class="font-semibold text-center dark:text-gray-300">{{ voyage?.combined_name }}</td>
                        <td>{{ voyage?.financial_closing_date ? moment(voyage?.financial_closing_date).format('DD-MM-YYYY') : null }}</td>
                        <td class=" text-gray-600">
                          <div class="flex items-center justify-center space-x-2">
                            <router-link :to="{ name: 'finance.voyage-expense.show', params: { pairId: voyage?.id } }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                              <span>Show Expense</span>
                            </router-link>
                            
                          </div>
                        </td>
                    </tr>
                    <tr v-if="pairList?.value?.data?.length === 0">
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
    @apply px-3 py-2;
}

td {
    @apply px-3 py-2 text-xs;
}

.dropdown-btn {
    @apply bg-gray-900 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-gray-50 text-white font-semibold py-2 px-3 rounded-lg w-full flex items-center justify-center gap-1.5 sm:w-auto dark:bg-blue-500 dark:hover:bg-blue-400;
}

table, th,td{
  @apply border border-collapse;
}
</style>