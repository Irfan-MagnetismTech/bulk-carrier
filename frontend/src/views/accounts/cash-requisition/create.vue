<script setup>
import CashRequisitionForm from '../../../components/accounts/CashRequisitionForm.vue';
import useCashRequisition from '../../../composables/accounts/useCashRequisition';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { cashRequisition, storeCashRequisition, isLoading, errors } = useCashRequisition();
const { setTitle } = Title();

setTitle('Cash Requisition');

const page = 'create';

</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Cash Requisition</h2>
      <default-button :title="'Cash Requisition List'" :to="{ name: 'acc.cash-requisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="storeCashRequisition(cashRequisition)">
            <!-- Booking Form -->
            <cash-requisition-form :page="page" v-model:form="cashRequisition" :errors="errors"></cash-requisition-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>