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
const { transaction, showTransaction, updateTransaction, bgColor, errors } = useTransaction();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Edit Voucher');

const page = 'edit';

watch(transaction, (value) => {
  if(value) {
    transaction.value.acc_cost_center_name = transaction.value.costCenter;
    value?.ledgerEntries?.forEach((entry, index) => {
      if(entry.account){
        transaction.value.ledgerEntries[index].acc_account_name = entry.account;
      }
    });
  }
});

function handleColorSelected(color) {
  bgColor.value = color;
}


onMounted(() => {
  showTransaction(transactionId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 ">Update Voucher</h2>
    <default-button :title="'Transaction List'" :to="{ name: 'acc.transactions.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md " :style="{ 'background-color': bgColor }">
        <form @submit.prevent="updateTransaction(transaction, transactionId)">
            <!-- Booking Form -->
          <transaction-form @bgColor="handleColorSelected" :page="page" v-model:form="transaction" :errors="errors"></transaction-form>
            <!-- Submit button -->
            <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>