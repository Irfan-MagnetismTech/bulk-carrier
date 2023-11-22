<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import BankAccountForm from '../../../components/accounts/BankAccountForm.vue';
import useBankAccount from '../../../composables/accounts/useBankAccount';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const bankAccountId = route.params.bankAccountId;
const { bankAccount, showBankAccount, updateBankAccount, errors } = useBankAccount();
const icons = useHeroIcon();

const { setTitle } = Title();

const page = 'edit';

setTitle('Edit Bank Account');

onMounted(() => {
  showBankAccount(bankAccountId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Bank Account</h2>
    <default-button :title="'Bank Account List'" :to="{ name: 'acc.bank-accounts.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateBankAccount(bankAccount, bankAccountId)">
            <!-- Booking Form -->
          <bank-account-form :page="page" v-model:form="bankAccount" :errors="errors"></bank-account-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>