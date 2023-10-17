<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useMaterialCategory from "../../../composables/supply-chain/useMaterialCategory.js";
    import useUnit from "../../../composables/supply-chain/useUnit.js";

    
    const { materialCategories, searchMaterialCategory } = useMaterialCategory();
    const { materialUnits, searchUnit } = useUnit();
    const store_category = ['Deck Store', 'Saloon Store'];

    const props = defineProps({
        material: { type: Object, required: true },
        errors: { type: [Object, Array], required: false },
    });
       

    function fetchCategory(query, loading) {
        searchMaterialCategory(query, loading);
        loading(true)
    }

    watch(() => props.material.parent_category_name, (value) => {
        props.material.parent_category_id = value?.id;
    });

    function fetchUnit(query, loading) {
        searchUnit(query, loading);
        loading(true)
    }


</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        <legend>
                        
            <div class="input-group">
                
                <label class="label-group">
                    <span class="label-item-title">Material Name<span class="required-style">*</span></span>
                    <input type="text" required v-model="material.name" class="form-input" name="name" :id="'name'" />
                    <Error v-if="errors?.name" :errors="errors.name" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Material Code <span class="text-red-500">*</span></span>
                    <input type="text" v-model="material.material_code" class="form-input" name="material_code" :id="'material_code'" />
                    <Error v-if="errors?.material_code" :errors="errors.material_code" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Category Name</span>
                    <v-select :options="materialCategories" placeholder="--Choose an option--" @search="fetchCategory" v-model="material.scm_material_category_name" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                    <input type="hidden" v-model="material.scm_material_category_id" class="label-item-input" name="parent_category" :id="'parent_category'" />
                    <Error v-if="errors?.scm_material_category_id" :errors="errors.scm_material_category_id" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Unit <span class="text-red-500">*</span></span>
                    <v-select :options="materialUnits" placeholder="--Choose an option--" @search="fetchUnit" v-model="material.unit" label="unit" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                    <Error v-if="errors?.unit" :errors="errors.unit" />
                </label>
            </div>
            <div class="input-group !w-3/4">
                <label class="label-group">
                    <span class="label-item-title">HS Code <span class="required-style">*</span></span>
                    <input type="text" required v-model="material.hs_code" class="form-input" name="hs_code" :id="'hs_code'" />
                    <Error v-if="errors?.hs_code" :errors="errors.hs_code" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Minimum Stock<span class="text-red-500">*</span></span>
                    <input type="text" v-model="material.minimum_stock" class="form-input" name="minimum_stock" :id="'minimum_stock'" />
                    <Error v-if="errors?.minimum_stock" :errors="errors.minimum_stock" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Store Category<span class="text-red-500">*</span></span>
                    <v-select name="user" v-model="material.store_category" placeholder="--Choose an option--" label="Store Category" :options="store_category" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    </v-select>
                    <Error v-if="errors?.store_category" :errors="errors.store_category" />
                </label>
            </div>          
            <div class="input-group !w-1/2">
                <label class="label-group">
                    <span class="label-item-title">Description <span class="required-style">*</span></span>
                    <input type="text" required v-model="material.description" class="form-input" name="description" :id="'description'" />
                    <Error v-if="errors?.description" :errors="errors.description" />
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