<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import LoanForm from '../../../components/accounts/LoanForm.vue';
import useLoan from '../../../composables/accounts/useLoan';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const loanId = route.params.loanId;
const { loan, showLoan, updateLoan, bgColor, errors } = useLoan();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Loan');

const page = 'edit';

watch(loan, (value) => {
  if(value) {
    loan.value.acc_cost_center_name = loan.value.costCenter;
    value?.ledgerEntries?.forEach((entry, index) => {
      if(entry.account){
        loan.value.ledgerEntries[index].acc_account_name = entry.account;
      }
    });
  }
});

function handleColorSelected(color) {
  bgColor.value = color;
}


onMounted(() => {
  showLoan(loanId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Loan</h2>
    <default-button :title="'Loan List'" :to="{ name: 'acc.loans.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800" :style="{ 'background-color': bgColor }">
        <form @submit.prevent="updateLoan(loan, loanId)">
            <!-- Booking Form -->
          <loan-form @bgColor="handleColorSelected" :page="page" v-model:form="loan" :errors="errors"></loan-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>