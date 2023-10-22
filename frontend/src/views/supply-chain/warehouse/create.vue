<script setup>
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';

import Title from "../../../services/title";
import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
import WarehouseForm from '../../../components/supply-chain/warehouse/WarehouseForm.vue';
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { warehouse, storeWarehouse, isLoading, errors } = useWarehouse();
const { setTitle } = Title();

setTitle('Create Warehouse');
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Create Warehouse</h2>
        <default-button :title="'Warehouse List'" :to="{ name: 'scm.warehouse.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeWarehouse(warehouse)">
            <warehouse-form v-model:form="warehouse" :errors="errors"></warehouse-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
    
</template>
