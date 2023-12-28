<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useMovementRequisition from "../../../composables/supply-chain/useMovementRequisition";
import MovementRequisitionShow from "../../../components/supply-chain/movement-requisitions/MovementRequisitionShow.vue";
import { useRoute } from 'vue-router';

const { getMovementRequisition, showMovementRequisition, movementRequisition, updateMovementRequisition,materialObject, errors, isLoading } = useMovementRequisition();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const movementRequisitionId = route.params.movementRequisitionId;
const formType = 'edit';

setTitle('Movement Requisition Details');

onMounted(() => {
    showMovementRequisition(movementRequisitionId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Movement Requisition Details</h2>
        <default-button :title="'Movement Requisition List'" :to="{ name: 'scm.movement-requisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
            <movement-requisition-show :form="movementRequisition"></movement-requisition-show>
    </div>
</template>
