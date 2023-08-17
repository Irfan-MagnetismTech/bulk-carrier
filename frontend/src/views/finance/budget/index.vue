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
import useVoyageBudget from "../../../composables/finance/useVoyageBudget";



const props = defineProps({
    page: {
        type: Number,
        default: '',
    },
});

const { budgets, getAllBudgets, deleteBudget, cloneBudget } = useVoyageBudget();

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

setTitle('Budgets');

const cloneVoyageBudget = (budgetId) => {
    console.log("Cloning budget. ID: ", budgetId);
    cloneBudget(budgetId)
}

onMounted(() => {
  watchEffect(() => {
    getAllBudgets(props.page);
  });
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row">
      <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Budgets</h2>
      <!-- <h2 class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Head Wise Entry</h2> -->
        <router-link :to="{ name: 'finance.budget.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>

            </span>
        </router-link>
    </div>

    <!-- Table -->
    <!-- <div class="max-w-screen h-full overflow-x-auto bg-white overflow-y-auto mb-4">
    </div>-->
    <div class="w-full">
        <div class="w-full">
            <table class="w-full whitespace-no-wrap mb-5">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <th>Title</th>
                        <th>Vessel</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Effective From</th>
                        <th>Effective Till</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 text-left dark:text-gray-400" v-for="(budget) in budgets" :key="budget.id">
                        <td class="px-1 text-center">{{ budget?.title }}</td>
                        <td class="font-semibold text-center dark:text-gray-300">{{ budget?.vessel?.name }}</td>
                        <td class="font-semibold text-center dark:text-gray-300">{{ budget?.service?.name }}</td>
                        <td class="font-semibold text-center dark:text-gray-300">{{ (budget?.is_active == 0) ? 'Inactive' : 'Active' }}</td>
                        <td>{{ budget?.start_date ? moment(budget?.start_date).format('DD/MM/YYYY') : null }}</td>
                        <td>{{ budget?.end_date ? moment(budget?.end_date).format('DD/MM/YYYY') : null }}</td>
                        <td class=" text-gray-600">
                          <div class="flex items-center justify-center space-x-2">

                            <div class="tooltip" @click="cloneVoyageBudget(budget?.id ?? -1)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icn h-6 w-6 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <span class="tooltiptext">Copy Budget</span>
                            </div>
                            

                            <action-button :action="'insert'" :to="{ name: 'finance.budget-entry.create', params: { budgetId: budget?.id ?? -1 } }"></action-button>
                            <action-button :action="'edit'" :to="{ name: 'finance.budget.edit', params: { budgetId: budget?.id ?? -1 } }"></action-button>
                            <action-button @click="deleteBudget(budget?.id ?? -1)" :action="'delete'"></action-button>
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

.tooltip {
  position: relative;
  display: inline-block;
}

.tooltip .tooltiptext {
  text-transform: capitalize;
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;
  position: absolute;
  z-index: 1;
  top: -140%;
  left: 50%;
  margin-left: -60px;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: black transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}

/*.tooltip {*/
/*    @apply absolute -top-8 z-50 right-0 px-2 py-1 text-gray-200 bg-gray-700 rounded dark:bg-gray-600;*/
/*}*/
/*.mytooltip {*/
/*  @apply text-gray-200 bg-gray-700 dark:bg-gray-600;*/
/*}*/
.icn {
    @apply w-5 h-5 transition duration-150 ease-in-out hover:translate-y-1 hover:transform;
}
</style>