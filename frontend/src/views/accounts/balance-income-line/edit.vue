<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import BalanceIncomeLineForm from '../../../components/accounts/BalanceIncomeLineForm.vue';
import useBalanceIncomeLine from '../../../composables/accounts/useBalanceIncomeLine';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const balanceIncomeLineId = route.params.balanceIncomeLineId;
const { balanceIncomeLine, showBalanceIncomeLine, updateBalanceIncomeLine, errors } = useBalanceIncomeLine();
const icons = useHeroIcon();

const page = 'edit';

const { setTitle } = Title();

setTitle('Edit Balance Income Line');

watch(balanceIncomeLine, (value) => {
  if(value) {
    balanceIncomeLine.value.parent_id_name = balanceIncomeLine.value.parentLine;
  }
});

onMounted(() => {
  showBalanceIncomeLine(balanceIncomeLineId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 ">Update Balance Income Line</h2>
    <default-button :title="'Balance Income Line List'" :to="{ name: 'acc.balance-income-lines.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
        <form @submit.prevent="updateBalanceIncomeLine(balanceIncomeLine, balanceIncomeLineId)">
            <!-- Booking Form -->
          <balance-income-line-form :page="page" v-model:form="balanceIncomeLine" :errors="errors"></balance-income-line-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>