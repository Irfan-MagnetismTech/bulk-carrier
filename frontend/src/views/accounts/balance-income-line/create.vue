<script setup>
import BalanceIncomeLineForm from '../../../components/accounts/BalanceIncomeLineForm.vue';
import useBalanceIncomeLine from '../../../composables/accounts/useBalanceIncomeLine';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { balanceIncomeLine, storeBalanceIncomeLine, isLoading, errors } = useBalanceIncomeLine();
const { setTitle } = Title();

setTitle('Create Balance Income Line');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Create New Balance Income Line</h2>
      <default-button :title="'Balance Income Line List'" :to="{ name: 'acc.balance-income-lines.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeBalanceIncomeLine(balanceIncomeLine)">
            <!-- Booking Form -->
            <balance-income-line-form v-model:form="balanceIncomeLine" :errors="errors"></balance-income-line-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>