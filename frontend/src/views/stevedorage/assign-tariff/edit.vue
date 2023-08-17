<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Assigned Contract</h2>
<!--    <router-link :to="{ name: 'commercial.contract-assigns.voyage', params: { voyageId : contractAssign?.voyage_id } }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">-->
<!--      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
<!--        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />-->
<!--      </svg>-->
<!--    </router-link>-->
  </div>
<!--  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">-->
<!--    <form @submit.prevent="storeContractAssign(contractAssign)">-->
<!--      <fieldset class="px-4 pb-4 mt-3 border rounded bord2er-gray-700 dark:border-gray-400">-->
<!--        <legend class="text-gray-700 dark:text-gray-300">Voyage Information</legend>-->
<!--        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">-->
<!--          <label class="block w-full mt-3 text-sm">-->
<!--            <span class="text-gray-700 dark:text-gray-300">Voyage No</span>-->
<!--            <input type="text" disabled :value="voyage.voyage" class="block disabled:bg-gray-200 disabled:cursor-not-allowed w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
<!--          </label>-->

<!--          <label class="block w-full mt-3 text-sm">-->
<!--            <span class="text-gray-700 dark:text-gray-300">Sailing Date</span>-->
<!--            <input type="text" disabled readonly :value="voyage.sailing_date" class="block disabled:bg-gray-200 disabled:cursor-not-allowed w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
<!--          </label>-->

<!--          <label class="block w-full mt-3 text-sm">-->
<!--            <span class="text-gray-700 dark:text-gray-300">Arrival Date</span>-->
<!--            <input type="text" disabled readonly :value="voyage.arrival_date" class="block disabled:bg-gray-200 disabled:cursor-not-allowed w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
<!--          </label>-->
<!--        </div>-->
<!--        <div class="flex flex-col items-center justify-center w-full md:flex-row md:gap-2">-->
<!--          <label class="block w-full mt-3 text-sm">-->
<!--            <span class="text-gray-700 dark:text-gray-300">Vessel</span>-->
<!--            <input type="text" disabled readonly :value="voyage.vessel.name" class="block disabled:bg-gray-200 disabled:cursor-not-allowed w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
<!--          </label>-->

<!--          <label class="block w-full mt-3 text-sm">-->
<!--            <span class="text-gray-700 dark:text-gray-300">Departure Date</span>-->
<!--            <input type="text" disabled readonly :value="voyage.departure_date" class="block disabled:bg-gray-200 disabled:cursor-not-allowed w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
<!--          </label>-->

<!--          <label class="block w-full mt-3 text-sm">-->
<!--            <span class="text-gray-700 dark:text-gray-300">Berthing Date</span>-->
<!--            <input type="text" disabled readonly :value="voyage.berthing_date" class="block disabled:bg-gray-200 disabled:cursor-not-allowed w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />-->
<!--          </label>-->
<!--        </div>-->
<!--      </fieldset>-->

<!--      &lt;!&ndash; MLO List &ndash;&gt;-->
<!--      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">-->
<!--        <legend class="px-2 text-gray-700 dark:text-gray-300">Customer List</legend>-->
<!--        <table class="w-full whitespace-no-wrap" id="table">-->
<!--          <thead v-once>-->
<!--          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">-->
<!--            <th class="px-4 py-3 align-bottom">Customer Code</th>-->
<!--            <th class="px-4 py-3 align-bottom">Customer Name</th>-->
<!--            <th class="px-4 py-3 align-bottom">Contract</th>-->
<!--            <th class="px-4 py-3 text-center align-bottom">Contract Date</th>-->
<!--          </tr>-->
<!--          </thead>-->

<!--          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">-->
<!--&lt;!&ndash;          <tr class="text-center text-gray-700 dark:text-gray-400">&ndash;&gt;-->
<!--&lt;!&ndash;            <td class="px-4 py-3 text-sm">&ndash;&gt;-->
<!--&lt;!&ndash;              <input type="text" readonly :value="mlo.mlo" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />&ndash;&gt;-->
<!--&lt;!&ndash;              <input type="hidden" v-model="form[key].voy_cus_id" />&ndash;&gt;-->
<!--&lt;!&ndash;            </td>&ndash;&gt;-->
<!--&lt;!&ndash;            <td class="px-4 py-3 text-sm">&ndash;&gt;-->
<!--&lt;!&ndash;              <input type="text" readonly :value="mlo.customer_name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />&ndash;&gt;-->
<!--&lt;!&ndash;              <input type="hidden" v-model="form[key].customer_id" />&ndash;&gt;-->
<!--&lt;!&ndash;            </td>&ndash;&gt;-->
<!--&lt;!&ndash;            <td class="px-4 py-3 text-sm">&ndash;&gt;-->
<!--&lt;!&ndash;              <select v-model="form[key].contract_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">&ndash;&gt;-->
<!--&lt;!&ndash;                <option value="">Choose Option</option>&ndash;&gt;-->
<!--&lt;!&ndash;                <option v-for="(active_contract,index) in mlo.customer_active_contracts" :value="active_contract.id" :key="index">{{ mlo.mlo }} - ({{ active_contract.id }})</option>&ndash;&gt;-->
<!--&lt;!&ndash;              </select>&ndash;&gt;-->
<!--&lt;!&ndash;            </td>&ndash;&gt;-->
<!--&lt;!&ndash;            <td class="px-4 py-3 text-sm">&ndash;&gt;-->
<!--&lt;!&ndash;              <input type="text" :value="form[key].customer_active_contracts.find((el) => el.id === form[key].contract_id)?.effective_date" disabled class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:bg-gray-200 disabled:cursor-not-allowed form-input" />&ndash;&gt;-->
<!--&lt;!&ndash;            </td>&ndash;&gt;-->
<!--&lt;!&ndash;          </tr>&ndash;&gt;-->
<!--&lt;!&ndash;          <tr v-if="form.length === 0" class="text-center text-gray-700 dark:text-gray-400">&ndash;&gt;-->
<!--&lt;!&ndash;            <td colspan="4" class="px-4 py-3 text-sm">&ndash;&gt;-->
<!--&lt;!&ndash;              <span>No Record Found</span>&ndash;&gt;-->
<!--&lt;!&ndash;            </td>&ndash;&gt;-->
<!--&lt;!&ndash;          </tr>&ndash;&gt;-->
<!--          </tbody>-->
<!--        </table>-->
<!--      </fieldset>-->
<!--      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Assign Contract</button>-->
<!--    </form>-->
<!--  </div>-->
</template>
<script setup>
import { ref, watch, onMounted } from '@vue/runtime-core';
import AssignForm from '../../../components/commercial/AssignForm.vue';
import {useRoute} from "vue-router";
import useContractAssign from "../../../composables/commercial/useContractAssign";

const route = useRoute();
const voyageId = route.params.voyageId;
const voyageCustomerId = route.params.voyageId;

const { contractAssign, showContractAssign, isLoading } = useContractAssign();

onMounted(() => {
  showContractAssign(voyageCustomerId);
});
</script>