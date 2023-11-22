<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import DocumentForm from '../../../components/crew/DocumentForm.vue';
import useCrewDocument from '../../../composables/crew/useCrewDocument';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const documentId = route.params.documentId;
const { crewDocument, showCrewDocument, updateCrewDocument, errors } = useCrewDocument();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Edit Crew Document');

watch(crewDocument, (value) => {
  if(value) {
    // incidentRecord.value.ops_vessel_name = value?.opsVessel;
    // value?.crwIncidentParticipants?.forEach((line, index) => {
    //   incidentRecord.value.crwIncidentParticipants[index].crw_crew_name = value?.crwIncidentParticipants[index]?.crwCrew ?? '';
    // });
  }
});

onMounted(() => {
  showCrewDocument(documentId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 ">Update Crew Document</h2>
    <default-button :title="'Crew Document List'" :to="{ name: 'crw.documents.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
        <form @submit.prevent="updateCrewDocument(crewDocument, documentId)">
            <!-- Booking Form -->
          <document-form v-model:form="crewDocument" :errors="errors"></document-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>