<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import usePurchaseOrder from "../../../composables/supply-chain/usePurchaseOrder";
import useWorkOrder from "../../../composables/supply-chain/useWorkOrder";
import PurchaseOrderForm from "../../../components/supply-chain/purchase-orders/PurchaseOrderForm.vue";
import WorkOrderForm from "../../../components/supply-chain/work-orders/WorkOrderForm.vue";
import { useRoute } from 'vue-router';

const { getWorkOrders, showWorkOrder, workOrder, updateWorkOrder,serviceObject, errors, isLoading,termsObject, serviceList, woLineObject} = useWorkOrder();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const workOrderId = route.params.workOrderId;
const formType = 'edit';

setTitle('Update Work Order');

onMounted(() => {
    showWorkOrder(workOrderId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Work Order</h2>
        <default-button :title="'Work Order List'" :to="{ name: 'scm.work-orders.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="updateWorkOrder(workOrder, workOrderId)">
            <work-order-form v-model:form="workOrder" :errors="errors" :formType="formType" :serviceObject="serviceObject" :page="formType" :termsObject="termsObject" :woLineObject="woLineObject" v-model:serviceList="serviceList"></work-order-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
