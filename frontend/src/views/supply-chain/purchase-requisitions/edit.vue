<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import usePurchaseRequisition from "../../../composables/supply-chain/usePurchaseRequisition";
import PurchaseRequisitionForm from "../../../components/supply-chain/purchase-requisitions/PurchaseRequisitionForm.vue";
import { useRoute } from 'vue-router';

const { getPurchaseRequisition, showPurchaseRequisition, purchaseRequisition, updatePurchaseRequisition,materialObject, errors, isLoading } = usePurchaseRequisition();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const purchaseRequisitionId = route.params.purchaseRequisitionId;
const formType = 'edit';

setTitle('Update Purchase Requisition');

onMounted(() => {
    showPurchaseRequisition(purchaseRequisitionId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Purchase Requisition</h2>
        <default-button :title="'Opening Stock List'" :to="{ name: 'scm.purchase-requisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <form @submit.prevent="updatePurchaseRequisition(purchaseRequisition, purchaseRequisitionId)">
            <purchase-requisition-form :form="purchaseRequisition" :page="formType" :errors="errors" :formType="formType" :materialObject="materialObject"></purchase-requisition-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
