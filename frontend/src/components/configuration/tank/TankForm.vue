<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useMaterial from "../../../composables/scm/useMaterial.js";
    import useDispenser from '../../../composables/configuration/useDispenser.js';
    const props = defineProps({
        tank: { type: Object, required: true }
    });

    const { materials, searchFuel } = useMaterial();
    const { dispensers, searchDispenser } = useDispenser();

    function fetchFuel(query,loading) {
        searchFuel(query, loading);
        loading(true)
    }

    function fetchDispenser() {
        searchDispenser()
    }

    watch(() => props.tank.material, (value) => {
        props.tank.material_id = value?.id;
    });

    watch(() => props.tank.dispenser, (value) => {
        props.tank.dispenser_id = value?.id;
    });

</script>
<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="input-group">
           
            <label class="label-group">
                <span class="label-item-title">Tank Name <span class="required-style">*</span></span>
                <input type="text" required v-model="tank.tank_name" class="label-item-input" name="tank_name" :id="'tank_name'" />
            </label>
            <!-- <label class="label-group">
                <span class="label-item-title">Tank Size<span class="required-style">*</span></span>
                <input type="text" v-model="tank.tank_size" class="label-item-input" name="tank_size" :id="'tank_size'" />
            </label> -->
             <label class="label-group">
                <span class="label-item-title">Fuel Name <span class="required-style">*</span></span>
                <v-select :options="materials" placeholder="--Choose an option--" @search="fetchFuel"  v-model="tank.material" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                  <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!tank.material"
                        v-bind="attributes"
                        v-on="events"
                    />
                  </template>
                </v-select>
                <input type="hidden" required v-model="tank.material_id" class="label-item-input" name="material_name" :id="'material_name'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Fuel Color</span>
                <input type="color" v-model="tank.fuel_color" class="label-item-input" name="fuel_color" :id="'fuel_color'" />
            </label>
        </div>
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Opening Stock (Ltrs) <span class="required-style">*</span></span>
                <input type="number" required step=".001" v-model="tank.opening_stock" class="label-item-input" name="opening_stock" :id="'opening_stock'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Opening Meter Reading (DIP MM)</span>
                <input type="text" v-model="tank.opening_reading" class="label-item-input" name="opening_reading" :id="'opening_reading'" />
            </label>
<!--            <label class="label-group">-->
<!--                <span class="label-item-title">Opening Stock Purchase Price <span class="required-style">*</span></span>-->
<!--                <input type="text" v-model="tank.opening_stock_price" class="label-item-input" name="opening_stock_price" :id="'opening_stock_price'" />-->
<!--            </label>-->
            <!-- <label class="label-group">
                <span class="label-item-title">Connected Dispenser <span class="required-style">*</span></span>
                <v-select :options="dispensers" placeholder="--Choose an option--" @search="fetchDispenser"  v-model="tank.dispenser" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                <input type="hidden" v-model="tank.dispenser_id" class="label-item-input" name="dispenser" :id="'dispenser'" />
            </label> -->
          <label class="label-group">
            <span class="label-item-title">Setup Date</span>
            <input type="date" v-model="tank.setup_date" class="label-item-input" name="setup_date" :id="'setup_date'" />
          </label>
        </div>
<!--        <div class="input-group">-->
<!--            <label class="label-group">-->
<!--                <span class="label-item-title">Status <span class="required-style">*</span></span>-->
<!--                <div class="my-3 flex">-->
<!--                    <div class="flex items-center">-->
<!--                        <input type="radio" value="1" v-model="tank.status" class="" name="is_active" :id="'not_active'" />-->
<!--                        <label class="ml-2" for="not_active">Active</label>-->
<!--                    </div>-->
<!--                    <div class="flex items-center ml-8">-->
<!--                        <input type="radio" value="0" v-model="tank.status" class="" name="is_active" :id="'active'" />-->
<!--                        <label class="ml-2" for="active">Inactive</label>-->
<!--                    </div>-->

<!--                </div>-->
<!--            </label>-->
<!--            <label class="label-group">-->
<!--                <span class="label-item-title">Is CNG? <span class="required-style">*</span></span>-->
<!--                <div class="my-3 flex">-->
<!--                    <div class="flex items-center">-->
<!--                        <input type="radio" value="1" v-model="tank.is_cng" class="" name="is_cng" :id="'not_cng'" />-->
<!--                        <label class="ml-2" for="not_cng">Yes</label>-->
<!--                    </div>-->
<!--                    <div class="flex items-center ml-8">-->
<!--                        <input type="radio" value="0" v-model="tank.is_cng" class="" name="is_cng" :id="'cng'" />-->
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