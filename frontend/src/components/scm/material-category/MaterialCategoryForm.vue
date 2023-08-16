<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useMaterialCategory from "../../../composables/scm/useMaterialCategory.js";


    const { materialCategories, searchMaterialCategory } = useMaterialCategory();
    const props = defineProps({
        materialCategory: { type: Object, required: true },
    });

    function fetchCategory(query, loading) {
        searchMaterialCategory(query, loading);
        loading(true)
    }

    watch(() => props.materialCategory.parent_category_name, (value) => {
        console.log('category', value)
        props.materialCategory.parent_category = value?.id;
    });

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        <legend>
            <div class="input-group">
                <label class="label-group">
                    <span class="label-item-title">Parent Category </span>
                    <v-select :options="materialCategories" placeholder="--Choose an option--" @search="fetchCategory"  v-model="materialCategory.parent_category_name" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                    <input type="hidden" v-model="materialCategory.parent_category" class="label-item-input" name="parent_category" :id="'parent_category'" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Name <span class="required-style">*</span></span>
                    <input type="text" required v-model="materialCategory.name" class="label-item-input" name="name" :id="'name'" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Description</span>
                    <input type="text" v-model="materialCategory.description" class="label-item-input" name="description" :id="'description'" />
                </label>
            </div>
        </legend>
    </div>
</template>
<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm font-semibold;
    }
    .label-item-title {
        @apply text-gray-700 dark:text-gray-300;
    }
    .label-item-input {
        @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

</style>