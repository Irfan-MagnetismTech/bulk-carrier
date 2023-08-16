<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2">
        <span>Update Fuel:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ fuelId }}</span>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateFuel(fuel, fuelId)">
            <!-- Booking Form -->
          <fuel-form v-model:form="fuel" :errors="errors"></fuel-form>
            <!-- Submit button -->
            <button type="submit" class="flex button_pm_color items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 border border-transparent rounded-lg fon2t-medium hover:bg-teal-700 focus:outline-none focus:shadow-outline-purple">Update Fuel</button>
        </form>
    </div>
</template>
<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import FuelForm from '../../../components/configuration/fuel/FuelForm.vue';
import useFuel from "../../../composables/configuration/useFuel";
import Title from "../../../services/title";

const route = useRoute();
const fuelId = route.params.fuelId;
const { fuel, showFuel, updateFuel, errors } = useFuel();

const { setTitle } = Title();

setTitle('Edit Fuel');

onMounted(() => {
    showFuel(fuelId);
});
</script>