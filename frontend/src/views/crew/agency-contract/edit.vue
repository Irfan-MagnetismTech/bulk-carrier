<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import AgencyContractForm from '../../../components/crew/AgencyContractForm.vue';
import useAgencyContract from '../../../composables/crew/useAgencyContract';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const agencyContractId = route.params.agencyContractId;
const { agencyContract, showAgencyContract, updateAgencyContract, errors } = useAgencyContract();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Edit Agency Contract');

onMounted(() => {
  showAgencyContract(agencyContractId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Agency Contract</h2>
    <default-button :title="'Agency Contract List'" :to="{ name: 'crw.agencyContracts.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateAgencyContract(agencyContract, agencyContractId)">
            <!-- Booking Form -->
          <agency-contract-form v-model:form="agencyContract" :errors="errors"></agency-contract-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>