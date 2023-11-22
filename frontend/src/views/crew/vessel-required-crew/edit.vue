<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import VesselRequiredCrewForm from '../../../components/crew/VesselRequiredCrewForm.vue';
import useVesselRequiredCrew from '../../../composables/crew/useVesselRequiredCrew';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const vesselRequiredCrewId = route.params.vesselRequiredCrewId;
const { vesselRequiredCrew, showVesselRequiredCrew, updateVesselRequiredCrew, errors } = useVesselRequiredCrew();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Edit Vessel Required Crew');

watch(vesselRequiredCrew, (value) => {
  if(value) {
    vesselRequiredCrew.value.ops_vessel_name = vesselRequiredCrew.value.opsVessel;
  }
});

onMounted(() => {
  showVesselRequiredCrew(vesselRequiredCrewId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 ">Update Vessel Crew Manning</h2>
    <default-button :title="'Vessel Required Crew List'" :to="{ name: 'crw.vesselRequiredCrews.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
        <form @submit.prevent="updateVesselRequiredCrew(vesselRequiredCrew, vesselRequiredCrewId)">
            <!-- Booking Form -->
          <vessel-required-crew-form v-model:form="vesselRequiredCrew" :errors="errors"></vessel-required-crew-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>