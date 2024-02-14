<template>
  <!-- Heading -->
  
  <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Lighter Noon Report</h2>
      <default-button :title="'Lighter Noon Reports List'" :to="{ name: 'ops.lighter-noon-reports.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <form @submit.prevent="storeLighterNoonReport(lighterNoonReport)">
          <!-- Port Form -->
          <lighter-noon-report-form v-model:form="lighterNoonReport" :errors="errors" :formType="formType"></lighter-noon-report-form>
          <!-- Submit button -->
          <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import LighterNoonReportForm from '../../../components/operations/LighterNoonReportForm.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useLighterNoonReport from '../../../composables/operations/useLighterNoonReport';
const icons = useHeroIcon();

const { lighterNoonReport, storeLighterNoonReport, isLoading, errors } = useLighterNoonReport();
const { setTitle } = Title();

setTitle('Create Lighter Noon Reports');

const formType = 'create';

</script>