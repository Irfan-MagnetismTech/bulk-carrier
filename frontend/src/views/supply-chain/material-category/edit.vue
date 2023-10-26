<script setup>
import {onMounted, ref, watch} from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from "vuex";

import Title from "../../../services/title";
import MaterialCategoryForm from '../../../components/supply-chain/material-category/MaterialCategoryForm.vue';
import useMaterialCategory from "../../../composables/supply-chain/useMaterialCategory.js";

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const store = useStore();
const route = useRoute();
const icons = useHeroIcon();
const { setTitle } = Title();

const { materialCategory, showMaterialCategory, updateMaterialCategory,isLoading } = useMaterialCategory();

const materialCategoryId = route.params.materialCategoryId;

setTitle('Edit Material Category');

watch(materialCategory, (value) => {
  if(value) {
    materialCategory.value.parent_category_name = materialCategory.value?.parent;
  }
});

onMounted(() => {
    showMaterialCategory(materialCategoryId);
    
});

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Edit Material Category</h2>
        <default-button :title="'Material Category List'" :to="{ name: 'scm.material-category.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateMaterialCategory(materialCategory, materialCategoryId)">
            <material-category-form v-model:form="materialCategory" :errors="errors"></material-category-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600  hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update Material Category</button>
        </form>
    </div>
</template>
