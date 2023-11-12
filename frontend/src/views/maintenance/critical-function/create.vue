<script setup>
// import UserForm from '../../../components/authorization/UserForm.vue';
import CriticalFunctionForm from '../../../components/maintenance/critical-function/CriticalFunctionForm.vue';
import useCriticalFunction from '../../../composables/maintenance/useCriticalFunction';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { criticalFunction, storeCriticalFunction, isLoading, errors } = useCriticalFunction();
const { setTitle } = Title();
const page = 'create';

setTitle('Create Critical Function');
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-3 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Create New Critical Function</h2>
        
        <default-button :title="'Critical Function List'" :to="{ name: 'mnt.critical-functions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeCriticalFunction(criticalFunction)">
            <!-- Booking Form -->
            <critical-function-form :page="page"  v-model:form="criticalFunction" :errors="errors"></critical-function-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create Critical Function</button>
        </form>
    </div>
</template>