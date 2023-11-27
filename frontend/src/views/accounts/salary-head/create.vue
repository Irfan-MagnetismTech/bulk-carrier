<script setup>
import SalaryHeadForm from '../../../components/accounts/SalaryHeadForm.vue';
import useSalaryHead from '../../../composables/accounts/useSalaryHead';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { salaryHead, storeSalaryHead, isLoading, errors } = useSalaryHead();
const { setTitle } = Title();

const page = 'create';

setTitle('Create Salary Head');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Salary Head</h2>
      <default-button :title="'Salary head List'" :to="{ name: 'acc.salary-heads.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeSalaryHead(salaryHead)">
            <!-- Booking Form -->
            <salary-head-form :page="page" v-model:form="salaryHead" :errors="errors"></salary-head-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>