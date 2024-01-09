<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useAppraisalRecord from '../../../composables/crew/useAppraisalRecord';
import { formatDate } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const appraisalRecordId = route.params.appraisalRecordId;
const { appraisalRecord, showAppraisalRecord, errors } = useAppraisalRecord();

const { setTitle } = Title();

setTitle('Appraisal Record Details');

onMounted(() => {
  showAppraisalRecord(appraisalRecordId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Appraisal Record Details</h2>
    <default-button :title="'Appraisal Record List'" :to="{ name: 'crw.appraisal-records.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Appraisal Record Information</h2>
          <table class="w-full">
            <!-- <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="2">Personal Info</td>
            </tr>
            </thead> -->
            <tbody>
              <tr>
                <th class="w-40">Business Unit</th>
                <td><span :class="appraisalRecord?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ appraisalRecord?.business_unit }}</span></td>
              </tr>
             <tr>
                <th class="w-40">Crew</th>
                <td>{{ appraisalRecord?.crwCrew?.full_name }}</td>
              </tr>
              
             <tr>
                <th class="w-40">Contact No</th>
                <td>{{ appraisalRecord?.crwCrew?.pre_mobile_no }}</td>
              </tr>

             <tr>
                <th class="w-40">Age</th>
                <td>{{ appraisalRecord?.age }}</td>
              </tr>
              <tr>
                <th class="w-40">Passport No</th>
                <td>{{ appraisalRecord?.crwCrew?.passport_no }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Seaman's Book No</th>
                <td></td>
              </tr>

              
              <tr>
                <th class="w-40">Nationality</th>
                <td>{{ appraisalRecord?.crwCrew?.nationality }}</td>
              </tr>

              <tr>
                <th class="w-40">Completed Assignment Code</th>
                <td>{{ appraisalRecord?.crwCrewAssignment?.assignment_code }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Vessel</th>
                <td>{{ appraisalRecord?.crwCrewAssignment?.opsVessel?.name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Rank</th>
                <td>{{ appraisalRecord?.crwCrewAssignment?.position_onboard }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Service From</th>
                <td>{{ formatDate(appraisalRecord?.crwCrewAssignment?.joining_date) }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Service To</th>
                <td>{{ formatDate(appraisalRecord?.crwCrewAssignment?.completion_date) }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Appraisal Form</th>
                <td></td>
              </tr>

              
              <tr>
                <th class="w-40">Total Marks</th>
                <td></td>
              </tr>

              
              <tr>
                <th class="w-40">Appraisal Date</th>
                <td>{{ formatDate(appraisalRecord?.appraisal_date) }}</td>
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

  #profileDetailTable th{
    text-align: center;
  }
  #profileDetailTable thead tr{
    @apply bg-gray-200
  }

  th.text-center, td.text-center, tr.text-center {
    @apply text-center border-gray-500
  }

</style>