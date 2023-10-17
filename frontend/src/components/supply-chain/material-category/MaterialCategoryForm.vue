<script setup>
    import { ref, watch, onMounted, watchEffect } from 'vue';
    import Error from "../../Error.vue";
    import useMaterialCategory from "../../../composables/supply-chain/useMaterialCategory.js";


    const { materialCategories, searchMaterialCategory } = useMaterialCategory();
    const props = defineProps({
        materialCategory: { type: Object, required: true },
        errors: { type: [Object, Array], required: false },
    });

    function fetchCategory(query, loading) {
        searchMaterialCategory(query, loading);
        loading(true)
    }

    watch(() => props.materialCategory.parent_category_name, (value) => {
        props.materialCategory.parent_id = value?.id;
    });

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        <legend>
                        
            <div class="input-group">
                <label class="label-group">
                    <span class="label-item-title">Parent Category </span>
                    <v-select :options="materialCategories" placeholder="--Choose an option--" @search="fetchCategory" v-model="materialCategory.parent_category_name" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                    <input type="hidden" v-model="materialCategory.parent_id" class="label-item-input" name="parent_category" :id="'parent_category'" />
                    <Error v-if="errors?.parent_id" :errors="errors.parent_id" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Name <span class="required-style">*</span></span>
                    <input type="text" required v-model="materialCategory.name" class="form-input" name="name" :id="'name'" />
                    <Error v-if="errors?.name" :errors="errors.name" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Short Code <span class="text-red-500">*</span></span>
                    <input type="text" v-model="materialCategory.short_code" class="form-input" name="short_code" :id="'short_code'" />
                    <Error v-if="errors?.short_code" :errors="errors.short_code" />
                </label>
            </div>
        </legend>
    </div>
</template>
<style lang="postcss" scoped>
     #table, #table th, #table td{
        @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
      }
      
      .input-group {
        @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
      }
      
      .label-group {
        @apply block w-full mt-2 text-sm;
      }

      .label-item-title {
        @apply text-gray-700 dark:text-gray-300;
      }
      .label-item-input {
        @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
      }
      
      >>> {
        --vs-controls-color: #374151;
        --vs-border-color: #4b5563;
      
        --vs-dropdown-bg: #282c34;
        --vs-dropdown-color: #eeeeee;
        --vs-dropdown-option-color: #eeeeee;
      
        --vs-selected-bg: #664cc3;
        --vs-selected-color: #374151;
      
        --vs-search-input-color: #4b5563;
      
        --vs-dropdown-option--active-bg: #664cc3;
        --vs-dropdown-option--active-color: #eeeeee;
      }
</style>