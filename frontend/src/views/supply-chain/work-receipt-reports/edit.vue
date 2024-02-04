<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useWorkReceiptReport from "../../../composables/supply-chain/useWorkReceiptReport";
import WorkReceiptReportForm from "../../../components/supply-chain/work-receipt-reports/WorkReceiptReportForm.vue";
import { useRoute } from 'vue-router';

const { getWorkReceiptReports, showWorkReceiptReport, workReceiptReport, updateWorkReceiptReport,workObject, errors, isLoading } = useWorkReceiptReport();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const workReceiptReportId = route.params.workReceiptReportId;
const formType = 'edit';

setTitle('Update Work Receipt Report');

onMounted(() => {
    showWorkReceiptReport(workReceiptReportId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Work Receipt Report</h2>
        <default-button :title="'MRR List'" :to="{ name: 'scm.work-receipt-reports.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateWorkReceiptReport(workReceiptReport, workReceiptReportId)">
            <work-receipt-report-form v-model:form="workReceiptReport" :page="formType" :errors="errors" :formType="formType" :workObject="workObject"></work-receipt-report-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
