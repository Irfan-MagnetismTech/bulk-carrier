<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useMaterialReceiptReport from "../../../composables/supply-chain/useMaterialReceiptReport";
import MaterialReceiptReportForm from "../../../components/supply-chain/material-receipt-reports/MaterialReceiptReportForm.vue";
import { useRoute } from 'vue-router';

const { getMaterialReceiptReports, showMaterialReceiptReport, materialReceiptReport, updateMaterialReceiptReport,materialObject, errors, isLoading,poMaterialList} = useMaterialReceiptReport();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const materialReceiptReportId = route.params.materialReceiptReportId;
const formType = 'edit';

setTitle('Update Material Receipt Report');

onMounted(() => {
    showMaterialReceiptReport(materialReceiptReportId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Material Receipt Report</h2>
        <default-button :title="'MRR List'" :to="{ name: 'scm.material-receipt-reports.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateMaterialReceiptReport(materialReceiptReport, materialReceiptReportId)">
            <material-receipt-report-form v-model:form="materialReceiptReport" :page="formType" :errors="errors" :formType="formType" :materialObject="materialObject" :poMaterialList="poMaterialList" ></material-receipt-report-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
