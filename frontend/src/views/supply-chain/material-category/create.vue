<script setup>
import {ref,onMounted} from "vue";

import useMaterialCategory from "../../../composables/supply-chain/useMaterialCategory.js";
import MaterialCategoryForm from '../../../components/supply-chain/material-category/MaterialCategoryForm.vue';

import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();
const formType = ref('create');

const { materialCategory, storeMaterialCategory,isLoading,errors } = useMaterialCategory();
const { setTitle } = Title();

setTitle('Create Material Category');
</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 ">Create Material Category</h2>
        <default-button :title="'Material Cattegory List'" :to="{ name: 'scm.material-category.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
        <form @submit.prevent="storeMaterialCategory(materialCategory)">
            <material-category-form v-model:form="materialCategory" :errors="errors" :formType="formType"></material-category-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
</template>
