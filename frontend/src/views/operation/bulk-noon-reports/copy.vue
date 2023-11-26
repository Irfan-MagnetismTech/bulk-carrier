<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-dsiabled:text-gray-200">Add Bulk Noon Report</h2>
    <default-button :title="'Bulk Noon Report List'" :to="{ name: 'ops.bulk-noon-reports.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-dsiabled:bg-gray-800">
      <form @submit.prevent="storeBulkNoonReport(bulkNoonReport)">
          <!-- Port Form -->
          <bulk-noon-report-form v-model:form="bulkNoonReport" :errors="errors" :formType="formType" :bulkNoonReportLineObject="bulkNoonReportLineObject"></bulk-noon-report-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
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
const { bulkNoonReport, bulkNoonReportLineObject, showBulkNoonReport, storeBulkNoonReport, errors } = useBulkNoonReport();

const { setTitle } = Title();

setTitle('Edit Bulk Noon Report');

const formType = 'edit';

onMounted(() => {
  showBulkNoonReport(bulkNoonReportId);
});
</script>