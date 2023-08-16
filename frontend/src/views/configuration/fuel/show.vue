<script setup>
import FuelForm from "../../../components/configuration/fuel/FuelForm.vue";
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useFuel from "../../../composables/configuration/useFuel";
import { useRoute } from 'vue-router';

const { fuel, showFuel, updateFuel  } = useFuel();
const { setTitle } = Title();
const route = useRoute();
const fuelId = route.params.fuelId;

setTitle('Fuel Details');

onMounted(() => {
    showFuel(fuelId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Fuel</h2>
        <router-link :to="{ name: 'configuration.fuel.index' }" class="flex button_pm_color items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 border border-transparent rounded-lg hover:bg-teal-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <table>
            <tbody>
                <tr>
                    <th>Fuel Name</th>
                    <td>{{ fuel.fuel_name }}</td>
                </tr>
                <tr>
                    <th>Current Price</th>
                    <td>{{ fuel.current_price }}</td>
                </tr>
                <tr>
                    <th>Average Price</th>
                    <td>{{ fuel.average_purchase }}</td>
                </tr>
                <tr>
                    <th>Purchase Price</th>
                    <td>{{ fuel.purchase_price }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span v-if="fuel.status === 1" class="text-green-600 font-bold">Available</span>
                        <span v-else class="text-red-600 font-bold">Unavailable</span>
                    </td>
                </tr>
                <tr>
                    <th>Is Cng?</th>
                    <td>
                        <span v-if="fuel.is_cng === 1" class="text-green-600 font-bold">Yes</span>
                        <span v-else class="text-red-600 font-bold">No</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<style scoped>
    th {
        @apply text-left
    }
    td {
        @apply pl-5
    }
    th, td {
        @apply p-2 border 
    }
</style>