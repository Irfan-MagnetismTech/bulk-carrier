<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import IncidentRecordForm from '../../../components/crew/IncidentRecordForm.vue';
import useIncidentRecord from '../../../composables/crew/useIncidentRecord';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const incidentRecordId = route.params.incidentRecordId;
const { incidentRecord, showIncidentRecord, updateIncidentRecord, errors } = useIncidentRecord();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Edit Incident Record');

watch(incidentRecord, (value) => {
  if(value) {
    incidentRecord.value.ops_vessel_name = value?.opsVessel;
    value?.crwIncidentParticipants?.forEach((line, index) => {
      incidentRecord.value.crwIncidentParticipants[index].crw_crew_name = value?.crwIncidentParticipants[index]?.crwCrew ?? '';
      incidentRecord.value.crwIncidentParticipants[index].crw_crew_rank = value?.crwIncidentParticipants[index]?.crwCrew?.crwRank?.name ?? '';
    });
  }
});

onMounted(() => {
  showIncidentRecord(incidentRecordId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Incident Record</h2>
    <default-button :title="'Incident Record List'" :to="{ name: 'crw.incidentRecords.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateIncidentRecord(incidentRecord, incidentRecordId)">
            <!-- Booking Form -->
          <incident-record-form v-model:form="incidentRecord" :errors="errors"></incident-record-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>