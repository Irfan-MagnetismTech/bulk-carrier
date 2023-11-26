<script setup>
import CashAccountForm from '../../../components/accounts/CashAccountForm.vue';
import useCashAccount from '../../../composables/accounts/useCashAccount';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { cashAccount, storeCashAccount, isLoading, errors } = useCashAccount();
const { setTitle } = Title();

setTitle('Create Cash Account');
const page = 'create';
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Cash Account</h2>
      <default-button :title="'Cash Account List'" :to="{ name: 'acc.cash-accounts.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeCashAccount(cashAccount)">
            <!-- Booking Form -->
            <cash-account-form :page="page" v-model:form="cashAccount" :errors="errors"></cash-account-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>