<script setup>
import AgencyBillForm from '../../../components/crew/AgencyBillForm.vue';
import useAgencyBill from '../../../composables/crew/useAgencyBill';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { agencyBill, storeAgencyBill, isLoading, errors } = useAgencyBill();
const { setTitle } = Title();

setTitle('Create Agency Bill');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 ">Create Agency Bill</h2>
      <default-button :title="'Agency Bill List'" :to="{ name: 'crw.agencyBills.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md  overflow-hidden">
        <form @submit.prevent="storeAgencyBill(agencyBill)">
            <!-- Booking Form -->
            <agency-bill-form v-model:form="agencyBill" :errors="errors"></agency-bill-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>