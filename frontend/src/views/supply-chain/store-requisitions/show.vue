<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useStoreRequisition from "../../../composables/supply-chain/useStoreRequisition";
import StoreRequisitionShow from "../../../components/supply-chain/store-requisitions/StoreRequisitionShow.vue";
import { useRoute } from 'vue-router';

const { getStoreRequisition, showStoreRequisition, storeRequisition, updateStoreRequisition,materialObject, errors, isLoading } = useStoreRequisition();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const storeRequisitionId = route.params.storeRequisitionId;
const formType = 'edit';

setTitle('Store Requisition Details');

onMounted(() => {
    showStoreRequisition(storeRequisitionId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Store Requisition Details</h2>
        <default-button :title="'Opening Stock List'" :to="{ name: 'scm.store-requisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
            <store-requisition-show :form="storeRequisition"></store-requisition-show>
    </div>
</template>
