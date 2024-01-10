<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';

import PayrollBatchForm from '../../../components/crew/PayrollBatchForm.vue';
import usePayrollBatch from '../../../composables/crew/usePayrollBatch';
import Title from "../../../services/title";
const page = ref('update');

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const route = useRoute();
const crewPayrollBatchId = route.params.crewPayrollBatchId;
const { payrollBatch, showPayrollBatch, updatePayrollBatch, errors } = usePayrollBatch();
const icons = useHeroIcon();

const { setTitle } = Title();

setTitle('Edit Payroll Batch');

watch(payrollBatch, (value) => {
  if(value) {
    payrollBatch.value.ops_vessel_name = value?.opsVessel;
    payrollBatch.value.ops_vessel_id = value?.opsVessel?.id;
    value?.crwIncidentParticipants?.forEach((line, index) => {
      payrollBatch.value.crwIncidentParticipants[index].crw_crew_name = value?.crwIncidentParticipants[index]?.crwCrew ?? '';
      payrollBatch.value.crwIncidentParticipants[index].crw_crew_contact = value?.crwIncidentParticipants[index]?.crwCrew?.pre_mobile_no ?? '';
    });
  }
});

onMounted(() => {
  showPayrollBatch(crewPayrollBatchId);
});
</script>
<template>
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Payroll Batch</h2>
    <default-button :title="'Payroll Batch List'" :to="{ name: 'crw.crewPayrollBatches.index' }" :icon="icons.DataBase"></default-button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updatePayrollBatch(payrollBatch, crewPayrollBatchId)">
            <!-- Booking Form -->
          <payroll-batch-form v-model:form="payrollBatch" :page="page" :errors="errors"></payroll-batch-form>
            <!-- Submit button -->
        </form>
    </div>
</template>