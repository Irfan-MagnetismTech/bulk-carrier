<script setup>
import {ref,onMounted, onBeforeMount} from "vue";

import Title from "../../../services/title";
import useWorkOrder from "../../../composables/supply-chain/useWorkOrder";
import useHelper from "../../../composables/useHelper.js";
// import PurchaseOrderForm from "../../../components/supply-chain/purchase-orders/PurchaseOrderForm.vue";
import WorkOrderForm from "../../../components/supply-chain/work-orders/WorkOrderForm.vue";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import { useRoute } from 'vue-router';

const icons = useHeroIcon();
const route = useRoute();

const { getWorkOrders, workOrder, storeWorkOrder, getWrAndWcsWiseWorkOrder, serviceObject, termsObject, woLineObject, errors, isLoading, serviceList } = useWorkOrder();
const page = ref('create');
const { setTitle } = Title();

// const props = defineProps({
//   cs_id: {
//     type: Number,
//     default: 1,
//     },
//     pr_id: {
//     type: Number,
//     default: 1,
//   },
// });


// onMounted(() => {
//     getPrAndCsWisePurchaseOrder(props.pr_id, props.cs_id);
// }); 



setTitle('Create Work Order');
</script>
<template>
    <!-- Heading -->
    
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Work Order</h2>
        <default-button :title="'WO List'" :to="{ name: 'scm.work-orders.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeWorkOrder(workOrder)">
          <work-order-form v-model:form="workOrder" :errors="errors" :serviceObject="serviceObject" :termsObject="termsObject" :page="page" :woLineObject="woLineObject" v-model:serviceList="serviceList"></work-order-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
        
    </div>
</template>
