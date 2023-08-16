<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useMaterialCategory from "../../../composables/scm/useMaterialCategory.js";

    const { materialCategories, searchMaterialCategory } = useMaterialCategory();

    const props = defineProps({
        material: { type: Object, required: true },
        units: { type: Object, required: true },
    });

    function fetchCategory(query, loading) {
        searchMaterialCategory(query, loading);
        loading(true)
    }

    watch(() => props.material.category_name, (value) => {
        props.material.category_id = value?.id;
    });

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="input-group mb-4">
            <label class="label-group">
                    <span class="label-item-title">Category <span class="required-style">*</span></span>
                    <v-select :options="materialCategories" placeholder="--Choose an option--" @search="fetchCategory"  v-model="material.category_name" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                        <template #search="{attributes, events}">
                            <input
                            class="vs__search"
                            :required="!material.category_name"
                            v-bind="attributes"
                            v-on="events"
                            />
                        </template>
                    </v-select>
                    <input type="hidden" required v-model="material.category_id" class="label-item-input" name="category_id" :id="'category_id'" />
                </label>
            <label class="label-group">
                <span class="label-item-title">Name <span class="required-style">*</span></span>
                <input type="text" required v-model="material.name" class="label-item-input" name="material_name" :id="'branmaterial_namech_name'" />
            </label>
            
            <label class="label-group">
                <span class="label-item-title" for="feedingUnit_name">Select Unit Name  <span class="required-style">*</span></span>
                <select v-model="material.unit" required name="feedingUnit_name" id="feedingUnit_name" class="label-item-input">
                    <option v-for="unit, index in units">{{ unit }}</option>
                </select>
            </label>
            <label class="label-group">
                <span class="label-item-title">Minimum Stock</span>
                <input type="text" v-model="material.min_stock" class="label-item-input" name="material_name" :id="'branmaterial_namech_name'" />
            </label>
        </div>
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