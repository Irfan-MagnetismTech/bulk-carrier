<script setup>
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';

import Title from "../../../services/title";
import useOpeningStock from "../../../composables/supply-chain/useOpeningStock";
import useHelper from "../../../composables/useHelper.js";
import OpeningStockForm from "../../../components/supply-chain/opening-stock/OpeningStockForm.vue";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { getOpeningStocks, materialObject, openingStock, storeOpeningStock, errors, isLoading } = useOpeningStock();
const { setTitle } = Title();

setTitle('Create Opening Stock');
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Create Opening Stock</h2>
        <default-button :title="'Opening Stock List'" :to="{ name: 'scm.opening-stock.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="storeOpeningStock(openingStock)">
          <opening-stock-form v-model:form="openingStock" v-model:materialObject="materialObject" :errors="errors"></opening-stock-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>
