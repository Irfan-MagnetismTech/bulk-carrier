<script setup>
import CrewRequisitionForm from '../../../components/crew/CrewRequisitionForm.vue';
import useCrewRequisition from '../../../composables/crew/useCrewRequisition';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { crewRequisition, storeCrewRequisition, isLoading, errors } = useCrewRequisition();
const { setTitle } = Title();

setTitle('Create Crew Requisition');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Crew Requisition</h2>
      <default-button :title="'Crew Requisition List'" :to="{ name: 'crw.crewRequisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeCrewRequisition(crewRequisition)">
            <!-- Booking Form -->
            <crew-requisition-form v-model:form="crewRequisition" :errors="errors"></crew-requisition-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>