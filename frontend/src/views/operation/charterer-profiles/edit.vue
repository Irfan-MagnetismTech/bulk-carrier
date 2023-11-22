<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Charterer Profile</h2>
    <default-button :title="'Charterer Profile List'" :to="{ name: 'ops.charterer-profiles.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <form @submit.prevent="updateChartererProfile(chartererProfile, chartererProfileId)">
          <!-- Port Form -->
          <charterer-profile-form v-model:form="chartererProfile" :errors="errors" :formType="formType" :chartererProfileLineObject="chartererProfileLineObject"></charterer-profile-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import ChartererProfileForm from '../../../components/operations/ChartererProfileForm.vue';
import useChartererProfile from '../../../composables/operations/useChartererProfile';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const chartererProfileId = route.params.chartererProfileId;
const { chartererProfile, chartererProfileLineObject, showChartererProfile, updateChartererProfile, errors } = useChartererProfile();

const { setTitle } = Title();

setTitle('Edit Charterer Profile');

const formType = 'edit';

onMounted(() => {
  showChartererProfile(chartererProfileId);
});
</script>