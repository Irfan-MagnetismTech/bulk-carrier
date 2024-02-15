<script setup>
import {ref,onMounted, onBeforeMount} from "vue";

import Title from "../../../services/title";
import usePurchaseOrder from "../../../composables/supply-chain/usePurchaseOrder";
import useHelper from "../../../composables/useHelper.js";
import PurchaseOrderForm from "../../../components/supply-chain/purchase-orders/PurchaseOrderForm.vue";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import { useRoute,useRouter } from 'vue-router';
import Store from "../../../store";
const icons = useHeroIcon();
const route = useRoute();
const router = useRouter();

const { getPurchaseOrder, purchaseOrder, storePurchaseOrder,getPrAndCsWisePurchaseOrder,materialObject,termsObject,poLineObject, errors, isLoading,materialList } = usePurchaseOrder();
const page = ref('create');
const { setTitle } = Title();
// const permissionName = ref('create-purchase-order');

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

onMounted(() => {
    // console.log(Store.getters.getCurrentUser.permissions);
    // check permission if fail redirect to previous route
    // if (!Store.getters.getCurrentUser.permissions.includes(permissionName.value)) {
    //     router.go(-1);
    // }
});

setTitle('Create Purchase Order');
</script>
<template>
    <!-- Heading -->
    
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Purchase Order</h2>
        <default-button :title="'PO List'" :to="{ name: 'scm.purchase-orders.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storePurchaseOrder(purchaseOrder)">
          <purchase-order-form v-model:form="purchaseOrder" :errors="errors" :materialObject="materialObject" :termsObject="termsObject" :page="page" :poLineObject="poLineObject" v-model:materialList="materialList"></purchase-order-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
        
    </div>
</template>
