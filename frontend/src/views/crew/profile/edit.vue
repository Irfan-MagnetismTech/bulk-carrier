<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import CrewProfileForm from '../../../components/crew/CrewProfileForm.vue';
import useCrewProfile from '../../../composables/crew/useCrewProfile';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const profileId = route.params.profileId;
const { crewProfile, showCrewProfile, updateCrewProfile, errors } = useCrewProfile();
const icons = useHeroIcon();
const page = ref('update');

const { setTitle } = Title();

setTitle('Edit Crew Profile');

watch(crewProfile, (value) => {
  if(value) {
    crewProfile.value.crw_recruitment_approval_name = value?.crewRecruitmentApproval;
    crewProfile.value.agency_name = value?.crewAgency;

  }
});

onMounted(() => {
  showCrewProfile(profileId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 ">Update Crew Profile</h2>
    <default-button :title="'Crew Profile List'" :to="{ name: 'crw.profiles.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
        <form @submit.prevent="updateCrewProfile(crewProfile, profileId)">
            <!-- Booking Form -->
          <crew-profile-form v-model:form="crewProfile" v-model:page="page" :errors="errors"></crew-profile-form>
            <!-- Submit button -->
        </form>
    </div>
</template>