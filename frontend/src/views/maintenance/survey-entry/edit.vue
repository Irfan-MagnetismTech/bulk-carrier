<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Survey Entry</h2>
     
      <default-button :title="'Survey Entry List'" :to="{ name: 'mnt.survey-entries.index' }" :icon="icons.DataBase"></default-button>
    </div>
    
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
          <form @submit.prevent="updateSurveyEntry(surveyEntry, surveyEntryId)"> 
              <!-- Booking Form -->
            <survey-entry-form :page="page" v-model:form="surveyEntry" :errors="errors"></survey-entry-form>
              <!-- Submit button -->
              <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
          </form>
      </div>
  </template>
  <script setup>
  import {onMounted, ref, watch} from 'vue';
  import { useRoute } from 'vue-router';
  
  import SurveyEntryForm from '../../../components/maintenance/survey-entry/SurveyEntryForm.vue';
  import Title from "../../../services/title";
  // import useSurvey from '../../../composables/maintenance/useSurvey';
  import useSurveyEntry from '../../../composables/maintenance/useSurveyEntry';
  import useHeroIcon from "../../../assets/heroIcon";
  import DefaultButton from '../../../components/buttons/DefaultButton.vue';
  
  const route = useRoute();
  const surveyEntryId = route.params.surveyEntryId;
  const { surveyEntry, showSurveyEntry, updateSurveyEntry, errors } = useSurveyEntry();
  const icons = useHeroIcon();
  
  const { setTitle } = Title();
  const page = 'edit';
  
  setTitle('Edit Survey Entry');

  watch(surveyEntry, (value) => {
    // criticalVesselItem.value.ops_vessel = value?.opsVessel;
    // criticalVesselItem.value.mnt_critical_function = value?.mntCriticalItem?.mntCriticalItemCat?.mntCriticalFunction;
    // criticalVesselItem.value.mnt_critical_item_cat = value?.mntCriticalItem?.mntCriticalItemCat;
    // criticalVesselItem.value.mnt_critical_item = value?.mntCriticalItem;

    // criticalVesselItem.value.mntCriticalItemCategories = value?.mntCriticalItem?.mntCriticalItemCat?.mntCriticalFunction?.mntCriticalItemCats;
    // criticalVesselItem.value.mntCriticalItems = value?.mntCriticalItem?.mntCriticalItemCat?.mntCriticalItems;

    surveyEntry.value.ops_vessel = value?.opsVessel;
    surveyEntry.value.mnt_survey_item = value?.mntSurvey?.mntSurveyItem;
    surveyEntry.value.mnt_survey_type = value?.mntSurvey?.mntSurveyType;
    surveyEntry.value.mnt_survey = value?.mntSurvey;
    surveyEntry.value.mnt_surveys = value?.mntSurvey?.mntSurveyType?.mntSurveys;
  });
  
  onMounted(() => {
      showSurveyEntry(surveyEntryId);
  });
  </script>