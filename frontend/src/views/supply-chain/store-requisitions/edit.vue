<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useStoreRequisition from "../../../composables/supply-chain/useStoreRequisition";
import StoreRequisitionForm from "../../../components/supply-chain/store-requisitions/StoreRequisitionForm.vue";
import { useRoute } from 'vue-router';

const { getStoreRequisition, showStoreRequisition, storeRequisition, updateStoreRequisition,materialObject, errors, isLoading } = useStoreRequisition();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const storeRequisitionId = route.params.storeRequisitionId;
const formType = 'edit';

setTitle('Edit Store Requisition');

onMounted(() => {
    showStoreRequisition(storeRequisitionId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Edit Store Requisition</h2>
        <default-button :title="'Opening Stock List'" :to="{ name: 'scm.store-requisitions.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateStoreRequisition(storeRequisition, storeRequisitionId)">
            <store-requisition-form :form="storeRequisition" :page="formType" :errors="errors" :formType="formType" :materialObject="materialObject"></store-requisition-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>
