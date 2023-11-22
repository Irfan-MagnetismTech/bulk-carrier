<script setup>
import WorkRequisitionForm from '../../../components/maintenance/work-requisition/WorkRequisitionForm.vue';
import useWorkRequisition from '../../../composables/maintenance/useWorkRequisition';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
const icons = useHeroIcon();
const { workRequisition, storeWorkRequisition, isLoading, errors } = useWorkRequisition();
const { setTitle } = Title();
const page = 'create';

setTitle('Create Work Requisition');
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-3 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Work Requisition</h2>
        
        <default-button :title="'Work Requisition List'" :to="{ name: 'mnt.work-requisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="storeWorkRequisition(workRequisition)">
            <!-- Booking Form -->
            <work-requisition-form :page="page" v-model:form="workRequisition" :errors="errors"></work-requisition-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>