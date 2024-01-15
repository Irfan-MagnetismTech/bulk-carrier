<script setup>
import useAppraisalRecord from '../../../composables/crew/useAppraisalRecord';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import RestHourRecordForm from "../../../components/crew/RestHourRecordForm.vue";
const icons = useHeroIcon();
const { appraisalRecord, storeAppraisalRecord, isLoading, errors } = useAppraisalRecord();
const { setTitle } = Title();
const page = 'create';

setTitle('Create Rest Hour Record');
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-3 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create New Rest Hour Record</h2>
        <!-- <router-link :to="{ name: 'mnt.items.index' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Item List
        </router-link> -->
        <default-button :title="'Rest Hour Record List'" :to="{ name: 'crw.rest-hour-records.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="storeAppraisalRecord(appraisalRecord)">
            <!-- Booking Form -->
          <rest-hour-record-form :page="page" v-model:form="appraisalRecord"  :errors="errors"></rest-hour-record-form>
            <!-- Submit button -->
<!--            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>-->
        </form>
    </div>
</template>