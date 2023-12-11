<script setup>
// import UserForm from '../../../components/authorization/UserForm.vue';
import SurveyTypeForm from '../../../components/maintenance/survey-type/SurveyTypeForm.vue';
import useSurveyType from '../../../composables/maintenance/useSurveyType';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { surveyType, storeSurveyType, isLoading, errors } = useSurveyType();
const { setTitle } = Title();
const page = 'create';

setTitle('Create Survey Types');
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-3 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create New Survey Type </h2>
        
        <default-button :title="'Survey Type List'" :to="{ name: 'mnt.survey-types.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="storeSurveyType(surveyType)">
            <!-- Booking Form -->
            <survey-type-form :page="page"  v-model:form="surveyType" :errors="errors"></survey-type-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create </button>
        </form>
    </div>
</template>