<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useMaterialReceiptReport from "../../../composables/supply-chain/useMaterialReceiptReport";
import useHelper from "../../../composables/useHelper.js";
import MaterialReceiptReportForm from "../../../components/supply-chain/material-receipt-reports/MaterialReceiptReportForm.vue";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { getMaterialReceiptReports, materialReceiptReport, storeMaterialReceiptReport,materialObject,getPrAndPoWiseMrrData, errors, isLoading,poMaterialList } = useMaterialReceiptReport();
const page = ref('create');
const { setTitle } = Title();

setTitle('Create Material Receipt Report');

const props = defineProps({
    pr_id: {
        type: Number,
        default: 1,
        },
    po_id: {
        type: Number,
        default: 1,
    },
});

onMounted(() => {
    // getPrAndPoWiseMrrData(props.pr_id, props.po_id);
}); 

</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Material Receipt Report</h2>
        <default-button :title="'MRR List'" :to="{ name: 'scm.material-receipt-reports.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="storeMaterialReceiptReport(materialReceiptReport)">
          <material-receipt-report-form v-model:form="materialReceiptReport" :errors="errors" :materialObject="materialObject" v-model:poMaterialList="poMaterialList" :page="page"></material-receipt-report-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
        
    </div>
</template>
