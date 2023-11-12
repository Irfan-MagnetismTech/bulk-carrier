<script setup>
import TransactionForm from '../../../components/accounts/TransactionForm.vue';
import useTransaction from '../../../composables/accounts/useTransaction';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { transaction, storeTransaction, isLoading, bgColor, errors } = useTransaction();
const { setTitle } = Title();

setTitle('Create Voucher');

const page = 'create';

function handleColorSelected(color) {
  bgColor.value = color;
}
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Create Transaction</h2>
      <default-button :title="'Voucher List'" :to="{ name: 'acc.transactions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800 overflow-hidden" :style="{ 'background-color': bgColor }">
        <form @submit.prevent="storeTransaction(transaction)">
            <!-- Booking Form -->
            <transaction-form @bgColor="handleColorSelected" :page="page" v-model:form="transaction" :errors="errors"></transaction-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>