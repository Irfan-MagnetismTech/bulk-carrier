<script setup>
import {ref} from "vue";
import { onMounted } from '@vue/runtime-core';

import Title from "../../../services/title";
import useOpeningStock from "../../../composables/supply-chain/useOpeningStock";
import OpeningStockForm from "../../../components/supply-chain/opening-stock/OpeningStockForm.vue";
import { useRoute } from 'vue-router';

const { getOpeningStock, showOpeningStock, openingStock, updateOpeningStock, errors, isLoading } = useOpeningStock();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const openingStockId = route.params.openingStockId;

setTitle('Edit Opening Stock');

onMounted(() => {
    showOpeningStock(openingStockId);
});
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Create Opening Stock</h2>
        <default-button :title="'Opening Stock List'" :to="{ name: 'scm.opening-stock.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateOpeningStock(openingStock, openingStockId)">
            <opening-stock-form :form="openingStock" :errors="errors"></opening-stock-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-[#0F6B61]  border border-transparent rounded-lg fon2t-medium mt- active:bg-[#0F6B61]  hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
