<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import CrewAssignForm from '../../../components/crew/CrewAssignForm.vue';
import useCrewAssign from '../../../composables/crew/useCrewAssign';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const crewAssignId = route.params.crewAssignId;
const { crewAssign, showCrewAssign, updateCrewAssign, errors } = useCrewAssign();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Edit Assign Crew');

watch(crewAssign, (value) => {
  if(value) {
    crewAssign.value.ops_vessel_name = crewAssign.value.opsVessel;
    crewAssign.value.crw_crew_name = crewAssign.value.crwCrewProfile;
    crewAssign.value.port_of_joining_name = crewAssign.value.port;
  }
});

onMounted(() => {
  showCrewAssign(crewAssignId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Assign Crew</h2>
    <default-button :title="'Assign Crew List'" :to="{ name: 'crw.crewAssigns.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateCrewAssign(crewAssign, crewAssignId)">
            <!-- Booking Form -->
          <crew-assign-form v-model:form="crewAssign" :errors="errors"></crew-assign-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>