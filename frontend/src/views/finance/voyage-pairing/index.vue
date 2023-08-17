<script setup>
import { onMounted, ref } from 'vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Heading from '../../../components/Heading.vue';
import useVoyagePairing from "../../../composables/finance/useVoyagePairing";
import { useRouter } from 'vue-router';
import moment from 'moment';

const { pairList, getAllPairs, deletePair, getUnpairedVoyages, unpairedVoyages } = useVoyagePairing();
const router = useRouter();

function assignBudget(pairId) {
    // console.log("Assign budget to voyage pair: ", pairId)
    router.push({ name: "finance.budget-assign", params: { pairId: pairId } });
}

onMounted(() => {
  getAllPairs();
  getUnpairedVoyages();
});

const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  openTab.value = tabNumber;
}

</script>
<template>
    <!-- Heading -->

    <Heading :to="{ name: 'finance.voyage-pairing.create' }" type="create" label="Voyage Pairs"></Heading>

    <!-- Table -->
    <!-- <div class="max-w-screen h-full overflow-x-auto bg-white overflow-y-auto mb-4">
    </div>-->

    <div class="w-full">
        <ul class="flex flex-wrap mt-2 -mb-px">
        <li class="mr-2">
          <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(1)" v-bind:class="{'text-purple-600 bg-white': openTab !== 1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 1}">
            Pair List
          </a>
        </li>
        <li class="mr-2">
          <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(2)" v-bind:class="{'text-purple-600 bg-white': openTab !== 2, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 2}">
            Unpaired Voyages
          </a>
        </li>
      </ul>
    </div>

    <div class="w-full mt-1" v-on:click="toggleTabs(1)" v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">
        <div class="w-full">
            <table class="w-full whitespace-no-wrap mb-5">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <th class="text-left">#</th>
                        <th class="text-left">Name</th>
                        <th class="text-left">Voyages</th>
                        <th class="text-left">Financial Closing</th>
                        <th class="text-left">Budget</th>
                        <th class="text-left">Budget Assigned</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr v-for="(pair, index) in pairList.data" :key="pair.id" class="text-xs font-semibold tracking-wide text-left text-gray-500 dark:text-gray-200">
                        <td class="text-left">{{ index + 1 }}</td>
                        <td class="text-left">{{ pair.combined_name }}</td>
                        <td class="text-left">{{ pair.first_voyage.voyage }}, {{ pair.second_voyage.voyage }}</td>
                        <td class="text-left">{{ (pair.financial_closing_date) ? moment(pair?.financial_closing_date).format('DD-MM-YYYY') : null }}</td>
                        <td class="text-left">{{ pair?.budget?.title }}</td>
                        <td class="text-left">{{ (pair?.budget_assigned_date) ? moment(pair?.budget_assigned_date).format('DD-MM-YYYY') : null }}</td>
                        <td class="text-center">
                            <div class="tooltip cursor-pointer" @click="assignBudget(pair?.id ?? -1)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icn" style="height: 1.3rem; width: 1.3rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="tooltiptext">Assign Budget</span>
                            </div>
                            <action-button :to="{ name: 'finance.voyage-pairing.edit', params:{ pairId: pair?.id ?? -1 } }" :action="'edit'" label="Edit"></action-button>
                            <action-button @click="deletePair(pair?.id ?? -1)" :action="'delete'"></action-button>
                        </td>
                    </tr>
                    <tr v-if="pairList?.length === 0">
                        <td colspan="4" class="text-center dark:text-gray-400">
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

    <div class="w-full mt-1 mb-5" v-on:click="toggleTabs(2)" v-bind:class="{'hidden': openTab !== 2, 'block': openTab === 2}">

        <table class="w-full">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                    <th class="text-center">SL</th>
                    <th class="text-center">Voyage no</th>
                    <th class="text-center">Vessel</th>
                    <th class="text-center">Service</th>
                    <th class="text-center">POD</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <tr v-for="(voyage, index) in unpairedVoyages" class="text-xs font-semibold tracking-wide text-left text-gray-500 dark:text-gray-200">
                    <td>{{ index+1 }}</td>
                    <td>{{ voyage.voyage }}</td>
                    <td>{{ voyage.vessel.name }}</td>
                    <td>{{ voyage.service.name }}</td>
                    <td>{{ voyage.port_discharge }}</td>
                </tr>
            </tbody>
        </table>
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