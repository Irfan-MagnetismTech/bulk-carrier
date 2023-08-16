<script setup>
import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useSaleRate from "../../../composables/revenue/useSaleRate";
import Title from "../../../services/title";
import { ref } from "vue";
import { useFuse } from "@vueuse/integrations/useFuse";
import moment from "moment";

const { saleRates, getSaleRates, deleteSaleRate, isLoading } = useSaleRate();

const { setTitle } = Title();
let rateHistory = ref([]);
let isHistoryModalOpen = ref(false);

setTitle('Sale Rates and Tariffs');

onMounted(() => {
  getSaleRates();
});

const openTab = ref(1);

function swapTab(index) {
  openTab.value = index;
}

function getRateHistory(materialId){
  rateHistory.value = [];
  let histories = saleRates.value.sale_rates.filter((item) => item.material_id == materialId);
  histories.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
  rateHistory.value = histories;
  isHistoryModalOpen.value = true;
}

function closeRateHitoryModal(){
  isHistoryModalOpen.value = false;
}

</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-2 sm:flex-row" v-once>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Sale Rates and Tariffs</h2>
    <router-link :to="{ name: 'revenue.sale-rate.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
    </router-link>
  </div>
  <div class="flex items-center">
    <button @click="swapTab(1)" type="button" :style="openTab === 1 ? 'background-color:#0F6B90' : ''" class="focus:font-semibold focus:bg-[#0F6B90] focus:text-white w-32 flex items-center justify-center px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-t-lg active:bg-[#0F6B90] hover:bg-[#0F6B90] !outline-none focus:outline-none focus:shadow-outline-purple">Sale Tariffs</button>
    <button @click="swapTab(2)" type="button" class="focus:font-semibold focus:bg-[#0F6B90] focus:text-white w-32 ml-1 flex items-center justify-center px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-t-lg active:bg-[#0F6B90] hover:bg-[#0F6B90] !outline-none focus:outline-none focus:shadow-outline-purple">Sale Rates</button>
  </div>

  <div class="mb-4">
    <div class="w-full overflow-hidden" v-if="openTab === 1">
      <div class="w-full overflow-x-auto">

        <table class="w-full whitespace-no-wrap">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">SL. </th>
            <th class="px-4 py-3">Material</th>
            <th class="px-4 py-3">Current Sale Tariff</th>
            <th class="px-4 py-3">Effective Date</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(saleTariff,index) in saleRates?.sale_tariffs" :key="saleTariff.id">
            <td class="px-4 py-3 text-sm">{{ index + 1 }}</td>
            <td class="px-4 py-3 text-sm">{{ saleTariff?.material?.name }}</td>
            <td class="px-4 py-3 text-sm">{{ saleTariff?.rate }}</td>
            <td class="px-4 py-3 text-sm">{{ saleTariff?.effective_date ? moment(saleTariff?.effective_date).format('DD-MM-YYYY') : '' }}</td>
          </tr>
          </tbody>
          <tfoot v-if="!saleRates?.sale_tariffs?.length" class="bg-white dark:bg-gray-800">
          <tr v-if="isLoading">
            <td colspan="4">Loading...</td>
          </tr>
          <tr v-else-if="!saleRates?.sale_tariffs?.length">
            <td colspan="4">No Sale Tariff found.</td>
          </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- Table -->
    <div class="w-full overflow-hidden" v-if="openTab === 2">
      <div class="w-full overflow-x-auto">

        <table class="w-full whitespace-no-wrap">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">SL. </th>
            <th class="px-4 py-3">Material</th>
            <th class="px-4 py-3">Sale Rate</th>
            <th class="px-4 py-3">Effective Date</th>
            <th class="px-4 py-3 text-center">Actions</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(saleRate,index) in saleRates?.sale_rates" :key="saleRate.id">
            <td class="px-4 py-3 text-sm">{{ index + 1 }}</td>
            <td class="px-4 py-3 text-sm">{{ saleRate?.material?.name }}</td>
            <td class="px-4 py-3 text-sm">{{ saleRate?.rate }}</td>
            <td class="px-4 py-3 text-sm">{{ saleRate?.effective_date ? moment(saleRate?.effective_date).format('DD-MM-YYYY') : '' }}</td>
            <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
              <action-button :action="'history'" @click="getRateHistory(saleRate?.material?.id)"></action-button>
              <action-button :action="'edit'" :to="{ name: 'revenue.sale-rate.edit', params: { saleRateId: saleRate.id } }"></action-button>
              <action-button @click="deleteSaleRate(saleRate.id)" :action="'delete'"></action-button>
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!saleRates?.sale_rates?.length" class="bg-white dark:bg-gray-800">
          <tr v-if="isLoading">
            <td colspan="5">Loading...</td>
          </tr>
          <tr v-else-if="!saleRates?.sale_rates?.length">
            <td colspan="5">No Sale Rate found.</td>
          </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>

  <div v-show="isHistoryModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true" @click="closeRateHitoryModal" style="cursor: pointer;color: white">
            <path
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd" fill-rule="evenodd"></path>
          </svg>
        </header>
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead>
            <tr>
              <th colspan="2">History</th>
            </tr>
            <tr>
              <th>Date</th>
              <th>Rate</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr v-for="(historyData,index) in rateHistory">
            <td>{{ historyData?.effective_date ? moment(historyData?.effective_date).format('DD-MM-YYYY') : 'Null' }}</td>
            <td>{{ historyData?.rate }}</td>
          </tr>
          </tbody>
        </table>
      </div>
    </form>
  </div>
</template>
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
}

#modal {
  min-width: 30rem;
}


.input-group {
  @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
}
.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}
table, th,td{
  @apply border border-collapse;
}
.search-result {
  @apply px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-300;
}
.search {
  @apply float-right  pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
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