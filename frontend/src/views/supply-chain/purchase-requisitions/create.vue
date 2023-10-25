<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import usePurchaseRequisition from "../../../composables/supply-chain/usePurchaseRequisition";
import useHelper from "../../../composables/useHelper.js";
import PurchaseRequisitionForm from "../../../components/supply-chain/purchase-requisitions/PurchaseRequisitionForm.vue";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { getPurchaseRequisition, purchaseRequisition, excelExportData, getStoreCategoryWiseExcel, storePurchaseRequisition,materialObject, errors, isLoading } = usePurchaseRequisition();
const page = ref('create');
const { setTitle } = Title();

setTitle('Create Purchase Requisition');
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Create Purchase Requisition</h2>
        <default-button :title="'PR List'" :to="{ name: 'scm.purchase-requisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storePurchaseRequisition(purchaseRequisition)">
          <purchase-requisition-form v-model:form="purchaseRequisition" v-model:excelExportData="excelExportData" :downloadExcel="getStoreCategoryWiseExcel" :errors="errors" :materialObject="materialObject" :page="page"></purchase-requisition-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
        
    </div>
</template>
