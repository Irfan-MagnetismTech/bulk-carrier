<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useStoreRequisition from "../../../composables/supply-chain/useStoreRequisition";
import useMaterialCosting from "../../../composables/supply-chain/useMaterialCosting";
import StoreRequisitionForm from "../../../components/supply-chain/store-requisitions/StoreRequisitionForm.vue";
import MaterialCostingForm from "../../../components/supply-chain/material-costings/MaterialCostingForm.vue";
import { useRoute } from 'vue-router';

// const { getStoreRequisition, showStoreRequisition, storeRequisition, updateStoreRequisition,materialObject, errors, isLoading } = useStoreRequisition();

const { getMaterialCosting, materialCosting,showMaterialCosting, updateMaterialCosting,storeMaterialCosting,materialObject, errors, isLoading } = useMaterialCosting();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const materialCostingId = route.params.materialCostingId;
const formType = 'edit';

setTitle('Update Store Requisition');

onMounted(() => {
    showMaterialCosting(materialCostingId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Store Requisition</h2>
        <default-button :title="'Store Requisition List'" :to="{ name: 'scm.store-requisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updateMaterialCosting(materialCosting, materialCostingId)">
            <material-costing-form v-model:form="materialCosting" :errors="errors" :materialObject="materialObject" :page="page" :formType="formType"></material-costing-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
