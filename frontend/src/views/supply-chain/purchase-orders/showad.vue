<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import usePurchaseOrder from "../../../composables/supply-chain/usePurchaseOrder";
import PurchaseOrderShow from "../../../components/supply-chain/purchase-orders/PurchaseOrderShow.vue";
import { useRoute } from 'vue-router';

const { getPurchaseOrder, showPurchaseOrder, purchaseOrder, updatePurchaseOrder,materialObject, errors, isLoading } = usePurchaseOrder();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const purchaseOrderId = route.params.purchaseOrderId;
const formType = 'edit';

setTitle('Purchase Order Details');

onMounted(() => {
    showPurchaseOrder(purchaseOrderId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Purchase Order Details</h2>
        <default-button :title="'Opening Stock List'" :to="{ name: 'scm.purchase-orders.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
            <purchase-order-show v-model:form="purchaseOrder"></purchase-order-show>
    </div>
</template>
