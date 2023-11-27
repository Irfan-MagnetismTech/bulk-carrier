<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useMaterialAdjustment from "../../../composables/supply-chain/useMaterialAdjustment";
import MaterialAdjustmentForm from "../../../components/supply-chain/material-adjustments/MaterialAdjustmentForm.vue";
import { useRoute } from 'vue-router';

const { getMaterialAdjustment, showMaterialAdjustment, materialAdjustment, updateMaterialAdjustment,materialObject, errors, isLoading } = useMaterialAdjustment();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const materialAdjustmentId = route.params.materialAdjustmentId;
const formType = 'edit';

setTitle('Update Material Adjustment');

onMounted(() => {
    showMaterialAdjustment(materialAdjustmentId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Edit Material Adjustment</h2>
        <default-button :title="'Opening Stock List'" :to="{ name: 'scm.material-adjustments.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="updateMaterialAdjustment(materialAdjustment, materialAdjustmentId)">
            <material-adjustment-form :form="materialAdjustment" :page="formType" :errors="errors" :formType="formType" :materialObject="materialObject"></material-adjustment-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
