<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useIncidentRecord from '../../../composables/crew/useIncidentRecord.js';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';

const icons = useHeroIcon();

const route = useRoute();
const incidentRecordId = route.params.incidentRecordId;
const { incidentRecord, showIncidentRecord, errors } = useIncidentRecord();

const { setTitle } = Title();

setTitle('Incident Record Details');

onMounted(() => {
  showIncidentRecord(incidentRecordId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">  Incident Record Details  # {{incidentRecordId}} </h2>
    <default-button :title="' Incident Record List'" :to="{ name: 'crw.incidentRecords.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <div class="flex md:gap-4">
      <div class="w-full">
        <table class="w-full">
          <thead>
            <tr>
              <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Basic Info</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th class="w-40"> Business Unit </th>
              <td><span :class="incidentRecord?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ incidentRecord?.business_unit }}</span></td>
            </tr>
            <tr>
              <th class="w-40"> Vessel Name </th>
              <td>{{ incidentRecord?.opsVessel?.name }}</td>
            </tr>
            <tr>
              <th class="w-40"> Incident Date & Time </th>
              <td>{{ incidentRecord?.date_time }}</td>
            </tr>
            <tr>
              <th class="w-40"> Incident Type </th>
              <td>{{ incidentRecord?.type }}</td>
            </tr>
            <tr>
              <th class="w-40"> Incident Location </th>
              <td>{{ incidentRecord?.location }}</td>
            </tr>
            <tr>
              <th class="w-40"> Reported Person </th>
              <td>{{ incidentRecord?.reported_by }}</td>
            </tr>
            <tr>
              <th class="w-40"> Attachment </th>
              <td>
                <a type="button" v-if="typeof incidentRecord?.attachment === 'string'" class="text-green-800" target="_blank" :href="env.BASE_API_URL+'/'+incidentRecord?.attachment">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                  </svg>
                </a>
                <a v-else>---</a>              
              </td>
            </tr> 
          </tbody>
        </table>
 
        <table class="w-full mt-2">
          <thead>
            <tr>
              <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="5"> Participant List </td>
            </tr>
            <tr>
              <th class="w-5 !text-center">#</th>
              <th class="w-40 !text-center"> Crew Name </th>
              <th class="w-40 !text-center"> Contact No </th>
              <th class="w-40 !text-center"> Injury Status </th>
              <th class="w-40 !text-center"> Notes </th>
            </tr>            
          </thead>
          <tbody>
            <tr v-for="(crwIncidentParticipant, index) in incidentRecord?.crwIncidentParticipants" :key="index">
              <td class="!text-center">{{ index + 1 }}</td>
              <td class="text-left"> {{ crwIncidentParticipant?.crwCrew?.full_name }} </td>
              <td class="!text-center"> {{ crwIncidentParticipant?.crwCrew?.pre_mobile_no }} </td>
              <td class="text-left"> {{ crwIncidentParticipant?.injury_status }} </td>
              <td class="text-left"> {{ crwIncidentParticipant?.notes }} </td>
            </tr>
          </tbody>
        </table>


      </div>
    </div>
  </div>
</template>
<style lang="postcss" scoped>
th, td, tr {
  @apply text-left border-gray-500
}

tfoot td{
  @apply text-center
}

#profileDetailTable th{
  text-align: center;
}
#profileDetailTable thead tr{
  @apply bg-gray-200
}

</style>