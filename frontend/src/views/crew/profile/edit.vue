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
const originProfileData = ref(null);

const { setTitle } = Title();

setTitle('Edit Crew Profile');

watch(crewProfile, (value) => {
  if(value) {
    crewProfile.value.crw_recruitment_approval_name = value?.crwRecruitmentApproval;
    crewProfile.value.agency_name = value?.crwAgency;

    // training data
    if(crewProfile.value.trainings.length){
      crewProfile.value.is_training_data_required = true;
    } else {
      crewProfile.value.is_training_data_required = false;
      //crewProfile.value.trainings.push(originProfileData.value.trainings[0]);
    }
    //training data

    //experience data
    if(crewProfile.value.experiences.length){
      crewProfile.value.is_experience_data_required = true;
    } else {
      crewProfile.value.is_experience_data_required = false;
      //crewProfile.value.experiences.push(originProfileData.value.experiences[0]);
    }
    //experience data

    // languages data
    if(crewProfile.value.languages.length){
      crewProfile.value.is_other_data_required = true;
    } else {
      crewProfile.value.is_other_data_required = false;
      //crewProfile.value.languages.push(originProfileData.value.languages[0]);
    }
    // languages data

    // languages data
    if(crewProfile.value.references.length){
      crewProfile.value.is_reference_data_required = true;
    } else {
      crewProfile.value.is_reference_data_required = false;
      //crewProfile.value.references.push(originProfileData.value.references[0]);
    }
    // languages data

    // nominee data
    if(crewProfile.value.nominees.length){
      crewProfile.value.is_nominee_data_required = true;
    } else {
      crewProfile.value.is_nominee_data_required = false;
      //crewProfile.value.nominees.push(originProfileData.value.nominees[0]);
    }
    // nominee data
  }
});

onMounted(() => {
  originProfileData.value = crewProfile.value;
  showCrewProfile(profileId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Crew Profile</h2>
    <default-button :title="'Crew Profile List'" :to="{ name: 'crw.profiles.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateCrewProfile(crewProfile, profileId)">
            <!-- Booking Form -->
          <crew-profile-form v-model:form="crewProfile" v-model:page="page" :errors="errors"></crew-profile-form>
            <!-- Submit button -->
        </form>
    </div>
</template>