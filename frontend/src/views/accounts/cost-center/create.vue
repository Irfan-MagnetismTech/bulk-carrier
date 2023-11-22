<script setup>
import CostCenterForm from '../../../components/accounts/CostCenterForm.vue';
import useCostCenter from '../../../composables/accounts/useCostCenter';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { costCenter, storeCostCenter, isLoading, errors } = useCostCenter();
const { setTitle } = Title();

const page = 'create';

setTitle('Create Cost Center');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 ">Create Cost Center</h2>
      <default-button :title="'Cost center List'" :to="{ name: 'acc.cost-centers.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md  overflow-hidden">
        <form @submit.prevent="storeCostCenter(costCenter)">
            <!-- Booking Form -->
            <cost-center-form :page="page" v-model:form="costCenter" :errors="errors"></cost-center-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>