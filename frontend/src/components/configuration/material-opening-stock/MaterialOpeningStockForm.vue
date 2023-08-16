<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useWarehouse from "../../../composables/configuration/useWarehouse.js";
    import useMaterial from "../../../composables/scm/useMaterial.js";
    const { warehouses, searchWarehouse } = useWarehouse();

    const { materials, searchMaterial } = useMaterial();

    const props = defineProps({
        materialOpeningStock: { type: Object, required: true },
    });

    function fetchMaterial(query, loading) {
        
        searchMaterial(query, loading);
        loading(true);
    }
    function fetchWarehouse(query, loading) {
        searchWarehouse(query, loading);
    }

    watch(() => props.materialOpeningStock.material, (value) => {
        props.materialOpeningStock.material_id = value?.id;
        props.materialOpeningStock.unit = value?.unit;

    });

    watch(() => props.materialOpeningStock.warehouse, (value) => {
        props.materialOpeningStock.warehouse_id = value?.id;
    });

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        <legend>
            <div class="input-group">
                <label class="label-group">
                    <span class="label-item-title">Warehouse <span class="required-style">*</span></span>
                    <v-select :options="warehouses" required placeholder="--Choose an option--" @search="fetchWarehouse"  v-model="materialOpeningStock.warehouse" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                      <template #search="{attributes, events}">
                        <input
                            class="vs__search"
                            :required="!materialOpeningStock.warehouse"
                            v-bind="attributes"
                            v-on="events"
                        />
                      </template>
                    </v-select>
                    <input type="hidden" required v-model="materialOpeningStock.warehouse_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Material <span class="required-style">*</span></span>
                    <v-select :options="materials" required placeholder="--Choose an option--" @search="fetchMaterial"  v-model="materialOpeningStock.material" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                      <template #search="{attributes, events}">
                        <input
                            class="vs__search"
                            :required="!materialOpeningStock.material"
                            v-bind="attributes"
                            v-on="events"
                        />
                      </template>
                    </v-select>
                    <input type="hidden" required v-model="materialOpeningStock.material_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                </label>
                
            </div>
            <div class="input-group">
<!--                <label class="label-group">-->
<!--                    <span class="label-item-title">Cost Center </span>-->
<!--                    <input type="text"  v-model="materialOpeningStock.cost_center" class="label-item-input" name="mobile" :id="'mobile'" />-->
<!--                </label>-->
                <label class="label-group">
                    <span class="label-item-title">Unit </span>
                    <input type="text" readonly v-model="materialOpeningStock.unit" class="bg-gray-100 label-item-input" name="mobile" :id="'mobile'" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Quantity <span class="required-style">*</span></span>
                    <input type="number" required step="0.01" v-model="materialOpeningStock.quantity" class="label-item-input" name="mobile" :id="'mobile'" />
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