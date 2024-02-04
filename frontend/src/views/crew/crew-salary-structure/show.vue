<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useCrewSalaryStructure from '../../../composables/crew/useCrewSalaryStructure.js';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';
import { formatDate,formatMonthYear,formatMonthYearWithTime } from "../../../utils/helper.js";

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
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">  Crew Salary Structure Details</h2>
    <default-button :title="' Crew Salary Structure List'" :to="{ name: 'crw.crewSalaryStructures.index' }" :icon="icons.DataBase"></default-button>
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
              <td><span :class="crewSalaryStructure?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ crewSalaryStructure?.business_unit }}</span></td>
            </tr>
            <tr>
              <th class="w-40"> Effective Date </th>
              <td>{{ formatDate(crewSalaryStructure?.effective_date) }}</td>
            </tr>
            <tr>
              <th class="w-40"> Currency </th>
              <td>{{ crewSalaryStructure?.currency }}</td>
            </tr>
            <tr>
              <th class="w-40"> Crew Name </th>
              <td>{{ crewSalaryStructure?.crwCrew?.full_name }}</td>
            </tr>
            <tr>
              <th class="w-40"> Crew Contact </th>
              <td>{{ crewSalaryStructure?.crwCrew?.pre_mobile_no }}</td>
            </tr>
            <tr>
              <th class="w-40"> Gross Salary </th>
              <td>{{ crewSalaryStructure?.gross_salary }}</td>
            </tr>
            <tr>
              <th class="w-40"> Addition </th>
              <td>{{ crewSalaryStructure?.addition }}</td>
            </tr>
            <tr>
              <th class="w-40"> Deduction  </th>
              <td>{{ crewSalaryStructure?.deduction }}</td>
            </tr>
            <tr>
              <th class="w-40"> Net Amount  </th>
              <td>{{ crewSalaryStructure?.net_amount }}</td>
            </tr>
            <tr>
              <th class="w-40"> Remarks  </th>
              <td>{{ crewSalaryStructure?.remarks }}</td>
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