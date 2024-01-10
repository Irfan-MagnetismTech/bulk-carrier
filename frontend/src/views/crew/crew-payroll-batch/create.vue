<script setup>
import PayrollBatchForm from '../../../components/crew/PayrollBatchForm.vue';
import usePayrollBatch from '../../../composables/crew/usePayrollBatch';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import {ref} from "vue";
const icons = useHeroIcon();
const { payrollBatch, storePayrollBatch, isLoading, errors } = usePayrollBatch();
const { setTitle } = Title();

const page = ref('create');

setTitle('Create Payroll Batch');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Payroll Batch</h2>
      <default-button :title="'Payroll Batch List'" :to="{ name: 'crw.crewPayrollBatches.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="storePayrollBatch(payrollBatch)">
            <!-- Booking Form -->
            <payroll-batch-form v-model:form="payrollBatch" :page="page" :errors="errors"></payroll-batch-form>
            <!-- Submit button -->
<!--            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>-->
        </form>
    </div>
</template>