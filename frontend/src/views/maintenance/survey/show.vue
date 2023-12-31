<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import moment from 'moment';
import useSurvey from '../../../composables/maintenance/useSurvey';
import { formatDate } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const surveyId = route.params.surveyId;
const { survey, showSurvey, errors } = useSurvey();

const { setTitle } = Title();

setTitle('Survey Details');

onMounted(() => {
  showSurvey(surveyId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Survey Details</h2>
    <default-button :title="'Survey List'" :to="{ name: 'mnt.surveys.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Survey Information</h2>
          <table class="w-full">
            <!-- <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="2">Personal Info</td>
            </tr>
            </thead> -->
            <tbody>
              <!-- <tr>
                <th class="w-40">Business Unit</th>
                <td>{{ survey?.business_unit }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Vessel</th>
                <td>{{ survey?.opsVessel?.name }}</td>
              </tr> -->

              
              <tr>
                <th class="w-40">Survey Item</th>
                <td>{{ survey?.mntSurveyItem?.item_name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Survey Type</th>
                <td>{{ survey?.mntSurveyType?.survey_type_name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Short Code</th>
                <td>{{ survey?.short_code }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Survey Name</th>
                <td>{{ survey?.survey_name }}</td>
              </tr>

              
              <!-- <tr>
                <th class="w-40">Range Date</th>
                <td>{{ formatDate(survey?.range_date_from) }} - {{ formatDate(survey?.range_date_to) }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Assigned Date</th>
                <td>{{ formatDate(survey?.assigned_date) }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Due Date</th>
                <td>{{ formatDate(survey?.due_date) }}</td>
              </tr> -->
              
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