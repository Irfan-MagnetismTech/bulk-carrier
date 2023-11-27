<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import OpeningBalanceForm from '../../../components/accounts/OpeningBalanceForm.vue';
import useChartOfAccount from '../../../composables/accounts/useChartOfAccount';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import useOpeningBalance from "../../../composables/accounts/useOpeningBalance";

const route = useRoute();
const openingBalanceId = route.params.openingBalanceId;
const { openingBalance, showOpeningBalance, updateOpeningBalance, errors } = useOpeningBalance();
const icons = useHeroIcon();

const { setTitle } = Title();

const page = 'edit';

setTitle('Edit Opening Balance');

watch(openingBalance, (value) => {
  if(value) {
    openingBalance.value.acc_account_name = openingBalance.value.account;
    openingBalance.value.acc_cost_center_name = openingBalance.value.costCenter;
  }
});

onMounted(() => {
  showOpeningBalance(openingBalanceId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Opening Balance</h2>
    <default-button :title="'Openign Balance List'" :to="{ name: 'acc.opening-balances.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateOpeningBalance(openingBalance, openingBalanceId)">
            <!-- Booking Form -->
          <opening-balance-form :page="page" v-model:form="openingBalance" :errors="errors"></opening-balance-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>