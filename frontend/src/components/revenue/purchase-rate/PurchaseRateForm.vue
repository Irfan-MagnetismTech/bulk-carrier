<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useMaterial from "../../../composables/scm/useMaterial.js";
    import useVendor from "../../../composables/configuration/useVendor.js";

    const props = defineProps({
        purchaseRate: { type: Object, required: true },
        defaultVendor: { type: Object, required: true },
    });
    const { material, materials, searchMaterial } = useMaterial();
    const { vendor, vendors, searchVendor } = useVendor();
    function fetchMaterial(query, loading) {
        searchMaterial(query, loading);
        loading(true);
    }

    function fetchVendor(query, loading) {
        searchVendor(query, loading);
        loading(true);

    }

    watch(() => props.purchaseRate.material, (value) => {
        props.purchaseRate.material_id = value?.id;
    });

    watch(() => props.purchaseRate.vendor, (value) => {
        props.purchaseRate.vendor_id = value?.id;
    });

    watch(() => props.defaultVendor, (value) => {
        props.purchaseRate.vendor = value
    }, { deep: true })

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="input-group mb-4">
            <label class="label-group">
                <span class="label-item-title">Vendor <span class="required-style">*</span></span>
                <v-select :options="vendors" placeholder="--Choose an option--" @search="fetchVendor"  v-model="purchaseRate.vendor" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                  <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!purchaseRate.vendor"
                        v-bind="attributes"
                        v-on="events"
                    />
                  </template>
                </v-select>
                <input type="hidden" required v-model="purchaseRate.vendor_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Material <span class="required-style">*</span></span>
                <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterial"  v-model="purchaseRate.material" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                  <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!purchaseRate.material"
                        v-bind="attributes"
                        v-on="events"
                    />
                  </template>
                </v-select>
                <input type="hidden" required v-model="purchaseRate.material_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Rate <small>(Per Liter)</small> <span class="required-style">*</span></span>
                <input type="number" required step="0.01" v-model="purchaseRate.rate" class="label-item-input" name="mobile" :id="'mobile'" />
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