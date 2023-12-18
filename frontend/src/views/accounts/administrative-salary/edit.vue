<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import AdministrativeSalaryForm from '../../../components/accounts/AdministrativeSalaryForm.vue';
import useAdministrativeSalary from '../../../composables/accounts/useAdministrativeSalary';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const administrativeSalaryId = route.params.administrativeSalaryId;
const { administrativeSalary, showAdministrativeSalary, updateAdministrativeSalary, errors } = useAdministrativeSalary();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Administrative Adjustment');

const page = 'edit';

// watch(advanceAdjustment, (value) => {
//   if(value) {
//     advanceAdjustment.value.acc_cost_center_name = advanceAdjustment.value.costCenter;
//   }
// });

onMounted(() => {
  showAdministrativeSalary(administrativeSalaryId);
});

</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200"> Update Administrative Salary</h2>
    <default-button :title="'Administrative Salary'" :to="{ name: 'acc.administrative-salaries.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateAdministrativeSalary(administrativeSalary, administrativeSalaryId)">
            <!-- Booking Form -->
          <administrative-salary-form :page="page" v-model:form="administrativeSalary" :errors="errors"></administrative-salary-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>