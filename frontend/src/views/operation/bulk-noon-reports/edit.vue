<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-dsiabled:text-gray-200">Update Bulk Noon Report</h2>
    <default-button :title="'Bulk Noon Report List'" :to="{ name: 'ops.bulk-noon-reports.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-dsiabled:bg-gray-800">
      <form @submit.prevent="updateBulkNoonReport(bulkNoonReport, bulkNoonReportId)">
          <!-- Port Form -->
          <bulk-noon-report-form v-model:form="bulkNoonReport" :errors="errors" :formType="formType" :engineObject="engineObject" :cargoTankObject="cargoTankObject" :portObject="portObject"></bulk-noon-report-form>
          <!-- Submit button -->
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import BulkNoonReportForm from '../../../components/operations/BulkNoonReportForm.vue';
import useBulkNoonReport from '../../../composables/operations/useBulkNoonReport';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const bulkNoonReportId = route.params.bulkNoonReportId;
const { bulkNoonReport, portObject, cargoTankObject, engineObject , showBulkNoonReport, updateBulkNoonReport, errors } = useBulkNoonReport();

const { setTitle } = Title();

setTitle('Edit Bulk Noon Report');

const formType = 'edit';

onMounted(() => {
  showBulkNoonReport(bulkNoonReportId);
});
</script>