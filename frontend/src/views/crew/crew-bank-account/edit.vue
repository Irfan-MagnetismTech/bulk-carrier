<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import CrewBankAccountForm from '../../../components/crew/CrewBankAccountForm.vue';
import useCrewBankAccount from '../../../composables/crew/useCrewBankAccount';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const crewBankAccountId = route.params.crewBankAccountId;
const { crewBankAccount, showCrewBankAccount, updateCrewBankAccount, errors } = useCrewBankAccount();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Edit Crew Bank Account');

watch(crewBankAccount, (value) => {
  if(value) {
    crewBankAccount.value.crw_crew_name = crewBankAccount.value.crwCrewProfile;
  }
});

onMounted(() => {
  showCrewBankAccount(crewBankAccountId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Crew Bank Account</h2>
    <default-button :title="'Crew Bank Account List'" :to="{ name: 'crw.crewBankAccounts.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateCrewBankAccount(crewBankAccount, crewBankAccountId)">
            <!-- Booking Form -->
          <crew-bank-account-form v-model:form="crewBankAccount" :errors="errors"></crew-bank-account-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>