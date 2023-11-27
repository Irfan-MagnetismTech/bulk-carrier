<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import SalaryHeadForm from '../../../components/accounts/SalaryHeadForm.vue';
import useSalaryHead from '../../../composables/accounts/useSalaryHead';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const salaryHeadId = route.params.salaryHeadId;
const { salaryHead, showSalaryHead, updateSalaryHead, errors } = useSalaryHead();
const icons = useHeroIcon();

const { setTitle } = Title();

const page = 'edit';

setTitle('Edit Salary Head');

onMounted(() => {
  showSalaryHead(salaryHeadId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Salary Head</h2>
    <default-button :title="'Salary Head List'" :to="{ name: 'acc.salary-heads.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateSalaryHead(salaryHead, salaryHeadId)">
            <!-- Booking Form -->
          <salary-head-form :page="page" v-model:form="salaryHead" :errors="errors"></salary-head-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>