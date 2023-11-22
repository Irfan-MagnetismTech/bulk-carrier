<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 ">Update Charterer Contract</h2>
    <default-button :title="'Charterer Contract List'" :to="{ name: 'ops.charterer-contracts.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
      <form @submit.prevent="updateChartererContract(chartererContract, chartererContractId)">
          <!-- Port Form -->
          <charterer-contract-form v-model:form="chartererContract" :errors="errors" :formType="formType" :chartererContractLineObject="chartererContractLineObject"></charterer-contract-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import ChartererContractForm from '../../../components/operations/ChartererContractForm.vue';
import useChartererContract from '../../../composables/operations/useChartererContract';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const chartererContractId = route.params.chartererContractId;
const { chartererContract, chartererContractLineObject, showChartererContract, updateChartererContract, errors } = useChartererContract();

const { setTitle } = Title();

setTitle('Edit Charterer Contract');

const formType = 'edit';

onMounted(() => {
  showChartererContract(chartererContractId);
});
</script>