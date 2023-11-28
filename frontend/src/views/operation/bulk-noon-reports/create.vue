<template>
  <!-- Heading -->
  
  <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-dsiabled:text-gray-200">Add Bulk Noon Reports</h2>
      <default-button :title="'Bulk Noon Reports List'" :to="{ name: 'ops.bulk-noon-reports.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-dsiabled:bg-gray-800">
      <form @submit.prevent="storeBulkNoonReport(bulkNoonReport)">
          <!-- Port Form -->
          <bulk-noon-report-form v-model:form="bulkNoonReport" :errors="errors" :formType="formType" :engineObject="engineObject" :cargoTankObject="cargoTankObject" :portObject="portObject"></bulk-noon-report-form>
          <!-- Submit button -->
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import BulkNoonReportForm from '../../../components/operations/BulkNoonReportForm.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useBulkNoonReport from '../../../composables/operations/useBulkNoonReport';
const icons = useHeroIcon();

const { bulkNoonReport, portObject, cargoTankObject, engineObject, storeBulkNoonReport, isLoading, errors } = useBulkNoonReport();
const { setTitle } = Title();

setTitle('Create Bulk Noon Reports');

const formType = 'create';

</script>