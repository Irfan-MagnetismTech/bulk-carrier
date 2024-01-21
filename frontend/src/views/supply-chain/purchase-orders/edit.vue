<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import usePurchaseOrder from "../../../composables/supply-chain/usePurchaseOrder";
import PurchaseOrderForm from "../../../components/supply-chain/purchase-orders/PurchaseOrderForm.vue";
import { useRoute } from 'vue-router';

const { getPurchaseOrder, showPurchaseOrder, purchaseOrder, updatePurchaseOrder,materialObject,poLineObject,errors, isLoading,termsObject,materialList} = usePurchaseOrder();
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const purchaseOrderId = route.params.purchaseOrderId;
const formType = 'edit';

setTitle('Update Purchase Order');

onMounted(() => {
    showPurchaseOrder(purchaseOrderId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Purchase Order</h2>
        <default-button :title="'PO List'" :to="{ name: 'scm.purchase-orders.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="updatePurchaseOrder(purchaseOrder, purchaseOrderId)">
            <purchase-order-form v-model:form="purchaseOrder" :errors="errors" :formType="formType" :materialObject="materialObject" :page="formType" :termsObject="termsObject" :poLineObject="poLineObject" v-model:materialList="materialList"></purchase-order-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
