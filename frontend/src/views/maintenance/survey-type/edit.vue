<template>
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Survey Type</h2>
     
      <default-button :title="'Survey Type List'" :to="{ name: 'mnt.survey-types.index' }" :icon="icons.DataBase"></default-button>
    </div>
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
          <form @submit.prevent="updateSurveyType(surveyType, surveyTypeId)"> 
              <!-- Booking Form -->
            <survey-type-form :page="page" v-model:form="surveyType" :errors="errors"></survey-type-form>
              <!-- Submit button -->
              <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
          </form>
      </div>
  </template>
  <script setup>
  import {onMounted, ref, watch} from 'vue';
  import { useRoute } from 'vue-router';
  
  import SurveyTypeForm from '../../../components/maintenance/survey-type/SurveyTypeForm.vue'; 
  import Title from "../../../services/title";
  import useSurveyType from '../../../composables/maintenance/useSurveyType';
  import useHeroIcon from "../../../assets/heroIcon";
  import DefaultButton from '../../../components/buttons/DefaultButton.vue';
  
  const route = useRoute();
  const surveyTypeId = route.params.surveyTypeId;
  const { surveyType, showSurveyType, updateSurveyType, errors } = useSurveyType();
  const icons = useHeroIcon();
  
  const { setTitle } = Title();
  const page = 'edit';
  
  setTitle('Edit Survey Item');

  watch(surveyType, (value) => {
    surveyType.value.mnt_survey_function_name = value?.mntSurveyTypeCat?.mntSurveyFunction;

    surveyType.value.mnt_survey_item_cat_name = value?.mntSurveyTypeCat;
    surveyType.value.mntItemCategories = value?.mntSurveyTypeCat?.mntSurveyFunction?.mntSurveyTypeCats;
  });
  
  onMounted(() => {
      showSurveyType(surveyTypeId);
  });
  </script>