<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useWorkRequisition from "../../../composables/supply-chain/useWorkRequisition";
import WorkRequisitionForm from "../../../components/supply-chain/work-requisitions/WorkRequisitionForm.vue";
import { useRoute } from 'vue-router';

const { getWorkRequisitions, showWorkRequisition, workRequisition, updateWorkRequisition,workObject, errors, isLoading } = useWorkRequisition();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const workRequisitionId = route.params.workRequisitionId;
const formType = 'edit';

setTitle('Update Work Requisition');

onMounted(() => {
    showWorkRequisition(workRequisitionId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Work Requisition</h2>
        <default-button :title="'Work Requisition List'" :to="{ name: 'scm.work-requisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateWorkRequisition(workRequisition, workRequisitionId)">
            <work-requisition-form :form="workRequisition" :page="formType" :errors="errors" :formType="formType" :workObject="workObject"></work-requisition-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
