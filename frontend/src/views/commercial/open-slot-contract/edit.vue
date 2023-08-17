<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2">
        <span>Update Open Contract:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ contractId }}</span>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateContract(contract, contractId)">
            <!-- Booking Form -->
          <open-slot-contract-form v-model:form="contract" :errors="errors" v-model:isLoading="isLoading" v-model:page="page" v-model:customers="customers" v-model:services="services"></open-slot-contract-form>
            <!-- Submit button -->
<!--            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Contract</button>-->
        </form>
    </div>
</template>
<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import useOpenSlotContract from '../../../composables/commercial/useOpenSlotContract';
import useCustomer from "../../../composables/commercial/useCustomer";
import useService from "../../../composables/commercial/useService";
import OpenSlotContractForm from "../../../components/commercial/OpenSlotContractForm.vue";

const route = useRoute();
const contractId = route.params.contractId;
const { contract, showContract, updateContract, globalOsContractSurcharges, isLoading, errors } = useOpenSlotContract();
const { customers, getCustomerWithoutPaginate } = useCustomer();
const { services, getServices } = useService();
const page = ref('update');

watch(contract, (value) => {
  if(value) {
    contract.value.customer_code = value?.customer ?? '';

    contract.value.contract_os_ports.forEach((contractPort,contractPortIndex) => {
      const output = globalOsContractSurcharges.value.filter(val => !contract.value.contract_os_ports[contractPortIndex].contract_os_port_surcharges.find( arr1Obj => arr1Obj.charge_type === val.charge_type));
      contract.value.contract_os_ports[contractPortIndex].contract_os_port_surcharges.push(...output);
    });
  }
});

onMounted(() => {
  getCustomerWithoutPaginate();
  getServices();
  showContract(contractId);
});
</script>