<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import CrewRequisitionForm from '../../../components/crew/CrewRequisitionForm.vue';
import useCrewRequisition from '../../../composables/crew/useCrewRequisition';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const crewRequisitionId = route.params.crewRequisitionId;
const { crewRequisition, showCrewRequisition, updateCrewRequisition, errors } = useCrewRequisition();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Edit Crew Requisition');

onMounted(() => {
  showCrewRequisition(crewRequisitionId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Crew Requisition</h2>
    <default-button :title="'Crew Requisition List'" :to="{ name: 'crw.crewRequisitions.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateCrewRequisition(crewRequisition, crewRequisitionId)">
            <!-- Booking Form -->
          <crew-requisition-form v-model:form="crewRequisition" :errors="errors"></crew-requisition-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>