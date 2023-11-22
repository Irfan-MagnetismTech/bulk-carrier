<script setup>
import AgencyContractForm from '../../../components/crew/AgencyContractForm.vue';
import useAgencyContract from '../../../composables/crew/useAgencyContract';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { agencyContract, storeAgencyContract, isLoading, errors } = useAgencyContract();
const { setTitle } = Title();

setTitle('Create Agency Contract');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Agency Contract</h2>
      <default-button :title="'Agency Contract List'" :to="{ name: 'crw.agencyContracts.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeAgencyContract(agencyContract)">
            <!-- Booking Form -->
            <agency-contract-form v-model:form="agencyContract" :errors="errors"></agency-contract-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>