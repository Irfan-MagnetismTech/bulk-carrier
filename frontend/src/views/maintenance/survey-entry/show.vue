<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import moment from 'moment';
import useSurvey from '../../../composables/maintenance/useSurvey';
import useSurveyEntry from '../../../composables/maintenance/useSurveyEntry';
// import { formatDate } from '../../../utils/helper';
import { formatDate, showPdfExport } from "../../../utils/helper.js";


const icons = useHeroIcon();

const route = useRoute();
const surveyEntryId = route.params.surveyEntryId;
const { surveyEntry, showSurveyEntry, errors } = useSurveyEntry();

const { setTitle } = Title();

setTitle('Survey Entry Details');

onMounted(() => {
  showSurveyEntry(surveyEntryId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Survey Entry Details</h2>
    <div class="flex gap-2">
      <default-button :title="'Survey Entry List'" :to="{ name: 'mnt.survey-entries.index' }" :icon="icons.DataBase"></default-button>
      <button title="Export PDF" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red" @click="showPdfExport(businessUnit,'l', 'Survey Entry Details',['survey-entry-details'], false, false)">
        <span v-html="icons.PdfExportIcon"></span>
      </button>
    </div>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Survey Entry Information</h2>
          <table class="w-full" id="survey-entry-details">
            <!-- <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="2">Personal Info</td>
            </tr>
            </thead> -->
            <tbody>
              <tr>
                <th class="w-40">Business Unit</th>
                <td>{{ surveyEntry?.business_unit }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Vessel</th>
                <td>{{ surveyEntry?.opsVessel?.name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Survey Item</th>
                <td>{{ surveyEntry?.mntSurvey?.mntSurveyItem?.item_name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Survey Type</th>
                <td>{{ surveyEntry?.mntSurvey?.mntSurveyType?.survey_type_name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Short Code</th>
                <td>{{ surveyEntry?.mntSurvey?.short_code }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Survey Name</th>
                <td>{{ surveyEntry?.mntSurvey?.survey_name }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Range Date</th>
                <td>{{ formatDate(surveyEntry?.range_date_from) }} - {{ formatDate(surveyEntry?.range_date_to) }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Assigned Date</th>
                <td>{{ formatDate(surveyEntry?.assigned_date) }}</td>
              </tr>

              
              <tr>
                <th class="w-40">Due Date</th>
                <td>{{ formatDate(surveyEntry?.due_date) }}</td>
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