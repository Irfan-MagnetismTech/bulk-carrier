<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useMovementRequisition from "../../../composables/supply-chain/useMovementRequisition";
import MovementRequisitionForm from "../../../components/supply-chain/movement-requisitions/MovementRequisitionForm.vue";
import { useRoute } from 'vue-router';

const { getMovementRequisition, showMovementRequisition, movementRequisition, updateMovementRequisition,materialObject, errors, isLoading } = useMovementRequisition();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const movementRequisitionId = route.params.movementRequisitionId;
const formType = 'edit';

setTitle('Update Movement Requisition');

onMounted(() => {
    showMovementRequisition(movementRequisitionId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Edit Movement Requisition</h2>
        <default-button :title="'Opening Stock List'" :to="{ name: 'scm.movement-requisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="updateMovementRequisition(movementRequisition, movementRequisitionId)">
            <movement-requisition-form :form="movementRequisition" :page="formType" :errors="errors" :formType="formType" :materialObject="materialObject"></movement-requisition-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
