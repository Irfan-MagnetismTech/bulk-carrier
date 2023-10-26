<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import TransactionForm from '../../../components/accounts/TransactionForm.vue';
import useTransaction from '../../../composables/accounts/useTransaction';
import Title from "../../../services/title";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const transactionId = route.params.transactionId;
const { transaction, showTransaction, updateTransaction, errors } = useTransaction();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Edit Transaction');

watch(transaction, (value) => {
  if(value) {
    transaction.value.acc_cost_center_name = transaction.value.costCenter;
    value?.ledgerEntries?.forEach((entry, index) => {
      if(entry.account){
        props.form.ledgerEntries[index].acc_account_id = props.form.ledgerEntries[index].acc_account_name.id ?? '';
        props.form.ledgerEntries[index].acc_balance_and_income_line_id = props.form.ledgerEntries[index].acc_account_name.acc_balance_and_income_line_id ?? '';
        props.form.ledgerEntries[index].acc_cost_center_id = props.form.acc_cost_center_id ?? '';
      }
    });
  }
});

onMounted(() => {
  showTransaction(transactionId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Transaction</h2>
    <default-button :title="'Transaction List'" :to="{ name: 'acc.transactions.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateTransaction(transaction, transactionId)">
            <!-- Booking Form -->
          <transaction-form v-model:form="transaction" :errors="errors"></transaction-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>