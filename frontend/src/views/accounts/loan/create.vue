<script setup>
import LoanForm from '../../../components/accounts/LoanForm.vue';
import useLoan from '../../../composables/accounts/useLoan';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { loan, storeLoan, isLoading, errors } = useLoan();
const { setTitle } = Title();

setTitle('Loan');

const page = 'create';

</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Loan</h2>
      <default-button :title="'Loan List'" :to="{ name: 'acc.loans.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="storeLoan(loan)">
            <!-- Booking Form -->
            <loan-form :page="page" v-model:form="loan" :errors="errors"></loan-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>