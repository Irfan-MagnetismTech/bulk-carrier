<template>
  <!-- Heading -->
  
  <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Add Charterer Contracts</h2>
      <default-button :title="'Charterer Contracts List'" :to="{ name: 'ops.charterer-contracts.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <form @submit.prevent="storeChartererContract(chartererContract)">
          <!-- Port Form -->
          <charterer-contract-form v-model:form="chartererContract" :errors="errors" :formType="formType"></charterer-contract-form>
          <!-- Submit button -->
          <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import ChartererContractForm from '../../../components/operations/ChartererContractForm.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useChartererContract from '../../../composables/operations/useChartererContract';
const icons = useHeroIcon();

const { chartererContract, storeChartererContract, isLoading, errors } = useChartererContract();
const { setTitle } = Title();

setTitle('Create Charterer Contracts');

const formType = 'create';

</script>