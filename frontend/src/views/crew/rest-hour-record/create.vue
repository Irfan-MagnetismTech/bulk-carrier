<script setup>
import useRestHourRecord from '../../../composables/crew/useRestHourRecord';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import RestHourRecordForm from "../../../components/crew/RestHourRecordForm.vue";
const icons = useHeroIcon();
const { restHourRecord, storeRestHourRecord, isLoading, errors } = useRestHourRecord();
const { setTitle } = Title();
const page = 'create';

setTitle('Create Rest Hour Record');
</script>
<template>
    <div class="flex flex-col items-center justify-between w-full my-3 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create New Rest Hour Record</h2>
        <default-button :title="'Rest Hour Records'" :to="{ name: 'crw.rest-hour-records.report' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="storeRestHourRecord(restHourRecord)">
          <rest-hour-record-form :page="page" v-model:form="restHourRecord"  :errors="errors"></rest-hour-record-form>
        </form>
    </div>
</template>