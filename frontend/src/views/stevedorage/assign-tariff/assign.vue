<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Assign Contract</h2>
        <router-link :to="{ name: 'commercial.contract-assigns.voyages' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeContractAssign(contractAssign)">
            <assign-form v-model:form="contractAssign" v-model:voyage="voyage"></assign-form>
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Assign Contract</button>
        </form>
    </div>
</template>
<script setup>
import { ref, watch, onMounted } from '@vue/runtime-core';
import AssignForm from '../../../components/commercial/AssignForm.vue';
import {useRoute} from "vue-router";
import useContractAssign from "../../../composables/commercial/useContractAssign";
import useVoyage from "../../../composables/operation/useVoyage";

const route = useRoute();
const voyageId = route.params.voyageId;

const { voyage, getVoyageDetails } = useVoyage();
const { contractAssign, storeContractAssign, isLoading } = useContractAssign();

watch(voyage, (newVal, oldVal) => {
  for (const key in newVal.voyage_customers) {
    if(newVal.voyage_customers[key].contract_id === 0 && newVal.voyage_customers[key].customer.contracts.length > 0) {
      const contract = {
        voy_cus_id: newVal.voyage_customers[key].id ?? '',
        customer_id: newVal.voyage_customers[key].customer_id ?? '',
        contract_id: '',
        customer_active_contracts: newVal.voyage_customers[key].customer.contracts,
        customer_name: newVal.voyage_customers[key].customer.customer_name ?? '',
        mlo: newVal.voyage_customers[key].customer.customer_code ?? '',
      };
      contractAssign.value.push(contract);
    }
  }
});


onMounted(() => {
  getVoyageDetails(voyageId);
});
</script>