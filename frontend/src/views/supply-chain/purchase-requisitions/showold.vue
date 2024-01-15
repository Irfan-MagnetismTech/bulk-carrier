<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import usePurchaseRequisition from "../../../composables/supply-chain/usePurchaseRequisition";
import useHelper from "../../../composables/useHelper.js";
import PurchaseRequisitionShow from "../../../components/supply-chain/purchase-requisitions/PurchaseRequisitionShow.vue";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import { useRoute } from 'vue-router';

const icons = useHeroIcon();

const { getPurchaseRequisition, showPurchaseRequisition, purchaseRequisition, updatePurchaseRequisition,materialObject, errors, isLoading } = usePurchaseRequisition();
const page = ref('create');
const { setTitle } = Title();
const route = useRoute();
const purchaseRequisitionId = route.params.purchaseRequisitionId;

setTitle('Purchase Requisition Details');
onMounted(() => {
    showPurchaseRequisition(purchaseRequisitionId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Purchase Requisition Details</h2>
        <default-button :title="'PR List'" :to="{ name: 'scm.purchase-requisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
          <purchase-requisition-show v-model:form="purchaseRequisition"></purchase-requisition-show>
    </div>
</template>
