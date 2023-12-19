<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import CrewAttendanceForm from '../../../components/crew/CrewAttendanceForm.vue';
import useCrwAttendance from '../../../composables/crew/useCrwAttendance';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const crwAttendanceId = route.params.crwAttendanceId;
const { crwAttendance, showCrwAttendance, updateCrwAttendance, errors } = useCrwAttendance();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Edit Crew Attendance');

onMounted(() => {
  showCrwAttendance(crwAttendanceId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Crew Attendance</h2>
    <default-button :title="'Crew Attendance List'" :to="{ name: 'crw.crwAttendances.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateCrwAttendance(crwAttendance, crwAttendanceId)">
          <crew-attendance-form v-model:form="crwAttendance" :errors="errors"></crew-attendance-form>

            <!-- Submit button -->
            <button type="submit" 
            :class="{'cursor-not-allowed': crwAttendance.crwAttendanceLines.length < 1}" 
            :disabled="crwAttendance?.crwAttendanceLines?.length < 1"
            class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              Update
            </button>
        </form>
    </div>
</template>