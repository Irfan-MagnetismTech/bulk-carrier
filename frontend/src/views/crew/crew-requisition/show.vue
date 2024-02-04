<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useCrewRequisition from "../../../composables/crew/useCrewRequisition";
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';
import { formatDate } from "../../../utils/helper.js";

const icons = useHeroIcon();

const route = useRoute();
const crewRequisitionId = route.params.crewRequisitionId;
const { crewRequisition, showCrewRequisition, errors } = useCrewRequisition();

const { setTitle } = Title();

setTitle('Crew Requisition Details');

onMounted(() => {
  showCrewRequisition(crewRequisitionId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Crew Requisition Details</h2>
    <default-button :title="'Crew Requisition'" :to="{ name: 'crw.crewRequisitions.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <table class="w-full">
            <thead>
            <tr>
              <td class="!text-center text-white font-bold uppercase bg-green-600" colspan="2">Basic Info</td>
            </tr>
            </thead>
            <tbody>
              <tr>
                <th class="w-40 text-left">Business Unit</th>
                <td class="text-left"><span :class="crewRequisition?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ crewRequisition?.business_unit }}</span></td>
              </tr>
              <tr>
                <th class="w-40 text-left">Vessel Name</th>
                <td class="text-left">{{ crewRequisition?.opsVessel?.name }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Applied Date</th>
                <td class="text-left">{{ formatDate(crewRequisition?.applied_date) }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Total Crew</th>
                <td class="text-left">{{ crewRequisition?.total_required_manpower }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Remarks</th>
                <td class="text-left">{{ crewRequisition?.remarks ?? '----' }}</td>
              </tr>
            </tbody>
          </table>
          <table class="w-full mt-1" id="profileDetailTable">
            <thead>
            <tr>
              <td class="!text-center text-white uppercase bg-green-600 font-bold" colspan="8">Crew Requisition List</td>
            </tr>
            <tr>
              <th>#</th>
              <th>Rank</th>
              <th>Required Manpower</th>
              <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(crewRequisitionData,index) in crewRequisition?.crwCrewRequisitionLines" :key="index">
              <td>{{ index + 1 }}</td>
              <td class="text-left">{{ crewRequisitionData?.crwRank?.name }}</td>
              <td>{{ crewRequisitionData?.required_manpower }}</td>
              <td>{{ crewRequisitionData?.remarks }}</td>
            </tr>
            </tbody>
            <tfoot v-if="!crewRequisition?.crwCrewRequisitionLines?.length">
            <tr>
              <td colspan="4">No data found.</td>
            </tr>
            </tfoot>
          </table>
        </div>
      </div>
  </div>
</template>
<style lang="postcss" scoped>
  th, td, tr {
    @apply border-gray-500
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