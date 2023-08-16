<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useTank from "../../../composables/configuration/useTank.js";
    import useMaterial from '../../../composables/scm/useMaterial';

    const props = defineProps({
        dispenser: { type: Object, required: true }
    });

    const { tanks, searchTank, tank } = useTank();
    const { material, materials, searchMaterial }  = useMaterial();

    function fetchTanks(query, loading) {
        searchTank(query, loading);
        loading(true)
    }

    function fetchMaterial(query, loading) {
        searchMaterial(query, loading);
        loading(true)
    }

    watch(() => props.dispenser.tank_name, (value) => {
        props.dispenser.warehouse_id = value?.id;
    });

    watch(() => props.dispenser.material, (value) => {
        props.dispenser.material_id = value?.id;
    });

</script>
<template>
    <div class="">
        <div class="input-group">
            <label class="label-group !w-1/3">
                <span class="label-item-title">Dispenser Name <span class="required-style">*</span></span>
                <input type="text" required v-model="dispenser.name" class="label-item-input" name="name" :id="'name'" />
            </label>
            <label class="label-group !w-1/3">
                <span class="label-item-title">Tank Name</span>
                <v-select :options="tanks" placeholder="--Choose an option--" @search="fetchTanks"  v-model="dispenser.tank_name" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                <input type="hidden" v-model="dispenser.warehouse_id" class="label-item-input" name="warehouse_id" :id="'warehouse_id'" />
            </label>
            <label for="" class="label-group !w-1/3">
                <span class="label-item-title">Material Name</span>
                <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterial"  v-model="dispenser.material" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                <input type="hidden" v-model="dispenser.material_id" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Opening Dispenser Meter Reading / Opening CNG Balance Reading</span>
                <input type="number" step="0.01" v-model="dispenser.opening_reading" class="label-item-input" name="opening_reading" :id="'opening_reading'" />
            </label>
            
        </div>
      
<!--        <div class="input-group">-->
<!--            <label class="label-group !w-1/3">-->
<!--                <span class="label-item-title">Dispenser Max Meter Reading</span>-->
<!--                <input type="number" step="0.01" v-model="dispenser.max_reading" class="label-item-input" name="max_reading" :id="'max_reading'" />-->
<!--            </label>-->
<!--            <label class="label-group !w-1/3">-->
<!--                <span class="label-item-title">Active / Inactive?</span>-->
<!--                <div class="my-3 flex">-->
<!--                    <div class="flex items-center">-->
<!--                        <input type="radio" value="1" v-model="dispenser.status" class="" name="status" :id="'not_active'" />-->
<!--                        <label class="ml-2" for="not_active">Active</label>-->
<!--                    </div>-->
<!--                    <div class="flex items-center ml-8">-->
<!--                        <input type="radio" value="0" v-model="dispenser.status" class="" name="status" :id="'active'" />-->
<!--                        <label class="ml-2" for="active">Inactive</label>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </label>-->
<!--            -->
<!--            <label class="label-group">-->
<!--                <span class="label-item-title">Is CNG?</span>-->
<!--                <div class="my-3 flex">-->
<!--                    <div class="flex items-center">-->
<!--                        <input type="radio" value="1" v-model="dispenser.is_cng" class="" name="is_cng" :id="'not_cng'" />-->
<!--                        <label class="ml-2" for="not_cng">Yes</label>-->
<!--                    </div>-->
<!--                    <div class="flex items-center ml-8">-->
<!--                        <input type="radio" value="0" v-model="dispenser.is_cng" class="" name="is_cng" :id="'cng'" />-->
<!--                        <label class="ml-2" for="cng">No</label>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </label>-->
<!--        </div>-->
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