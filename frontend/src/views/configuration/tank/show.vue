<script setup>
import TankForm from '../../../components/configuration/tank/TankForm.vue';
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';
import Title from "../../../services/title";
import useTank from "../../../composables/configuration/useTank.js";
import { useRoute } from 'vue-router';
import moment from 'moment';

const { tank, showTank, updateTank  } = useTank();
const { setTitle } = Title();
const route = useRoute();
const tankId = route.params.tankId;

setTitle('Tank Details');

onMounted(() => {
    showTank(tankId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Tanks</h2>
        <router-link :to="{ name: 'configuration.tank.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61] border border-transparent rounded-lg active:bg-[#0F6B61] hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
        </router-link>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <table>
            <tbody>
                <tr>
                    <th>Tank Name</th>
                    <td>{{ tank.tank_name }}</td>
                </tr>
                <tr>
                    <th>Tank Size</th>
                    <td>{{ tank.tank_size }}</td>
                </tr>
                <tr>
                    <th>Fuel Name</th>
                    <td>{{ tank.fuel_name }}</td>
                </tr>
                <tr>
                    <th>Fuel Color</th>
                    <td>{{ tank.fuel_color }}</td>
                </tr>
                <tr>
                    <th>Opening Stock (Ltrs)</th>
                    <td>{{ tank.opening_stock }}</td>
                </tr>
                <tr>
                    <th>Opening Meter Reading (DIP MM)</th>
                    <td>{{ tank.opening_reading }}</td>
                </tr>
                <tr>
                    <th>Opening Stock Purchase Price</th>
                    <td>{{ tank.opening_stock_price }}</td>
                </tr>
                <tr>
                    <th>Connected Machine</th>
                    <td>{{ tank.connected_machine }}</td>
                </tr>
                <tr>
                    <th>Setup Date</th>
                    <td>
                        {{ (tank?.setup_date) ? moment(tank?.setup_date).format('DD-MM-YYYY') : null }}
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span v-if="tank.status == 1" class="px-2 py-1 font-bold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Active</span>
                        <span v-else class="px-2 py-1 font-bold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">Inactive</span>
                    </td>
                </tr>
                <tr>
                    <th>Is CNG?</th>
                    <td>
                        <span v-if="tank.is_cng == 1" class="px-2 py-1 font-bold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Yes</span>
                        <span v-else class="px-2 py-1 font-bold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">No</span>
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