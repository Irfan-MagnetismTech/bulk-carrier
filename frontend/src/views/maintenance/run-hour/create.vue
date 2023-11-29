<script setup>
// import UserForm from '../../../components/authorization/UserForm.vue';
import RunHourForm from '../../../components/maintenance/run-hour/RunHourForm.vue';
import useRunHour from '../../../composables/maintenance/useRunHour';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import {onMounted, ref, watch} from 'vue';
const icons = useHeroIcon();
const { runHour, storeRunHour, isLoading, errors } = useRunHour();
const { setTitle } = Title();
const page = 'create';

setTitle('Create Runnig Hour');

onMounted(() => {
    const today = new Date().toISOString().substr(0, 10);
    runHour.value.updated_on = today;
  });
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-3 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Runnig Hour</h2>
        <!-- <router-link :to="{ name: 'mnt.run-hours.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Run Hour List
        </router-link> -->
        <default-button :title="'Run Hour List'" :to="{ name: 'mnt.run-hours.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="storeRunHour(runHour)">
            <!-- Booking Form -->
            <run-hour-form :page="page"  v-model:form="runHour" :errors="errors"></run-hour-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>