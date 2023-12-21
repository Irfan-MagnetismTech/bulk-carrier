<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useCrewSalaryStructure from '../../../composables/crew/useCrewSalaryStructure.js';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';

const icons = useHeroIcon();

const route = useRoute();
const crewSalaryStructureId = route.params.crewSalaryStructureId;
const { crewSalaryStructure, showCrewSalaryStructure, errors } = useCrewSalaryStructure();

const { setTitle } = Title();

setTitle(' Crew Salary Structure Details');

onMounted(() => {
  showCrewSalaryStructure(crewSalaryStructureId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">  Crew Salary Structure Details  # {{crewSalaryStructureId}} </h2>
    <default-button :title="' Crew Salary Structure List'" :to="{ name: 'crw.crewBankAccounts.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <div class="flex md:gap-4">
      <div class="w-full">
        <table class="w-full">
          <thead>
            <tr>
              <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Basic Info</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th class="w-40"> Business Unit </th>
              <td><span :class="crewBankAccount?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ crewBankAccount?.business_unit }}</span></td>
            </tr>
            <tr>
              <th class="w-40"> Crew Name </th>
              <td>{{ crewBankAccount?.crwCrew?.full_name }}</td>
            </tr>
            <tr>
              <th class="w-40"> Crew Contact </th>
              <td>{{ crewBankAccount?.crwCrew?.pre_mobile_no }}</td>
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

tfoot td{
  @apply text-center
}

#profileDetailTable th{
  text-align: center;
}
#profileDetailTable thead tr{
  @apply bg-gray-200
}

</style>