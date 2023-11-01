<script setup>
import CrewProfileForm from '../../../components/crew/CrewProfileForm.vue';
import useCrewProfile from '../../../composables/crew/useCrewProfile';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import {ref} from "vue";
const icons = useHeroIcon();
const { crewProfile, storeCrewProfile, isLoading, errors } = useCrewProfile();
const { setTitle } = Title();
const page = ref('create');

setTitle('Create Crew Profile');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Create Crew Profile</h2>
      <default-button :title="'Crew Profile List'" :to="{ name: 'crw.profiles.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeCrewProfile(crewProfile)">
            <!-- Booking Form -->
            <crew-profile-form v-model:form="crewProfile" :page="page" :errors="errors" :isLoading="isLoading"></crew-profile-form>
            <!-- Submit button -->
<!--            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>-->
        </form>
    </div>
</template>