<script setup>
import AdvanceAdjustmentForm from '../../../components/accounts/AdvanceAdjustmentForm.vue';
import useAdvanceAdjustment from '../../../composables/accounts/useAdvanceAdjustment';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { advanceAdjustment, storeAdvanceAdjustment, isLoading, errors } = useAdvanceAdjustment();
const { setTitle } = Title();

setTitle('Advance Adjustment');

const page = 'create';

</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Advance Adjustment</h2>
      <default-button :title="'Advance Adjustment List'" :to="{ name: 'acc.advance-adjustments.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="storeAdvanceAdjustment(advanceAdjustment)">
            <!-- Booking Form -->
            <advance-adjustment-form :page="page" v-model:form="advanceAdjustment" :errors="errors"></advance-adjustment-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>