<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useAdministrativeSalary from '../../../composables/accounts/useAdministrativeSalary.js';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';
import { formatMonthYear, formatDate } from "../../../utils/helper.js";
const icons = useHeroIcon();

const route = useRoute();
const administrativeSalaryId = route.params.administrativeSalaryId;
const { administrativeSalary, showAdministrativeSalary, errors } = useAdministrativeSalary();

const { setTitle } = Title();

setTitle('Administrative Salary Details');

onMounted(() => {
  showAdministrativeSalary(administrativeSalaryId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Administrative Salary Details</h2>
    <default-button :title="'Administrative Salary List'" :to="{ name: 'acc.administrative-salaries.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <div class="flex md:gap-4">
      <div class="w-full">
        <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Administrative Salaries Information # {{administrativeSalaryId}}</h2>
        <table class="w-full">
          <thead>
          <tr>
            <td class="!text-center bg-gray-200 font-bold" colspan="2">Basic Info</td>
          </tr>
          </thead>
          <tbody>
          <tr>
            <th class="w-40">Business Unit</th>
            <td><span :class="administrativeSalary?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ administrativeSalary?.business_unit }}</span></td>
          </tr>
          <tr>
            <th class="w-40">Month - Year</th>
            <td>{{ formatMonthYear(administrativeSalary?.year_month) }}</td>
          </tr>
          <tr>
            <th class="w-40">Remarks</th>
            <td>{{ administrativeSalary?.remarks }}</td>
          </tr>
          </tbody>
        </table>
        <table class="w-full mt-1" id="profileDetailTable">
          <thead>
          <tr>
            <td class="!text-center bg-gray-200 font-bold" colspan="8">Particular List</td>
          </tr>
          <tr>
            <th>Sl.</th>
            <th>Particular</th>
            <th class="w-52">Amount</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(adjustmentData,index) in administrativeSalary?.accSalaryLines" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ adjustmentData?.particular }}</td>
            <td class="!text-right">{{ adjustmentData?.amount }}</td>
          </tr>
          <tr class="text-gray-700 dark-disabled:text-gray-400">
            <td class="font-bold !text-right" colspan="2">Total Amount</td>
            <td class="px-1 py-1 font-bold !text-right">
              {{administrativeSalary?.total_salary}}
            </td>
          </tr>
          </tbody>
          <tfoot v-if="!administrativeSalary?.accSalaryLines?.length">
          <tr>
            <td colspan="3">No data found.</td>
          </tr>
          </tfoot>
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