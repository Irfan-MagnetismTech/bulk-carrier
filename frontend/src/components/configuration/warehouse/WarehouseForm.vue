<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useMaterial from "../../../composables/scm/useMaterial.js";

    const props = defineProps({
        warehouse: { type: Object, required: true }
    });
    const { materials, searchFuel } = useMaterial();

    function fetchFuel(query,loading) {
        searchFuel(query, loading);
        loading(true)
    }
    watch(() => props.warehouse.material, (value) => {
        props.warehouse.material_id = value?.id;
    });
</script>
<template>
    <div class=" border-gray-200 dark:border-gray-700">
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Type <span class="required-style">*</span></span>
                <select name="" id="" class="label-item-input" v-model="warehouse.warehouse_type">
                    <option value="Warehouse">Warehouse</option>
                    <option value="Tank">Tank</option>
                </select>
            </label>
            <label class="label-group">
                <span class="label-item-title">Name <span class="required-style">*</span></span>
                <input type="text" required v-model="warehouse.name" class="label-item-input" name="warehouse_name" :id="'warehouse_name'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Code <span class="required-style">*</span></span>
                <input type="text" required v-model="warehouse.code" class="label-item-input" name="warehouse_code" :id="'warehouse_code'" />
            </label>
            <div class="label-group">
                <span class="label-item-title">Status <span class="required-style">*</span></span>
                <div class="my-3 flex">
                    <div class="flex items-center">
                        <input type="radio" value="1" v-model="warehouse.status" class="" name="status" :id="'not_active'" />
                        <label class="ml-2" for="not_active">Active</label>
                    </div>
                    <div class="flex items-center ml-8">
                        <input type="radio" value="0" v-model="warehouse.status" class="" name="status" :id="'active'" />
                        <label class="ml-2" for="active">Inactive</label>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="input-group" v-if="warehouse.warehouse_type=='Warehouse'">
            
            <label class="label-group">
                <span class="label-item-title">Responsible Person</span>
                <input type="text" v-model="warehouse.responsible_person" class="label-item-input" name="responsible_person" :id="'responsible_person'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Phone</span>
                <input type="text" v-model="warehouse.phone" class="label-item-input" name="phone" :id="'phone'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Address</span>
                <input type="text" v-model="warehouse.address" class="label-item-input" name="address" :id="'address'" />
            </label>
            
        </div>
        <div class="input-group" v-if="warehouse.warehouse_type=='Tank'">
            <label class="label-group">
                <span class="label-item-title">Fuel Name <span class="required-style">*</span></span>
                <v-select :options="materials" placeholder="--Choose an option--" @search="fetchFuel"  v-model="warehouse.material" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                  <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        v-bind="attributes"
                        v-on="events"
                    />
                  </template>
                </v-select>
                <input type="hidden" required v-model="warehouse.material_id" class="label-item-input" name="material_name" :id="'material_name'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Fuel Color</span>
                <input type="color" v-model="warehouse.fuel_color" class="label-item-input" name="fuel_color" :id="'fuel_color'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Setup Date</span>
                <input type="date" v-model="warehouse.setup_date" class="label-item-input" name="setup_date" :id="'setup_date'" />
            </label>
        </div>
        <div class="input-group" v-if="warehouse.warehouse_type=='Tank'">
            <label class="label-group">
                <span class="label-item-title">Tank Size (Ltrs)</span>
                <input type="number" v-model="warehouse.tank_size" class="label-item-input" name="tank_size" :id="'tank_size'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Opening Stock (Ltrs)</span>
                <input type="number" step=".001" v-model="warehouse.opening_stock" class="label-item-input" name="opening_stock" :id="'opening_stock'" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Opening Meter Reading (DIP MM)</span>
                <input type="text" v-model="warehouse.opening_reading" class="label-item-input" name="opening_reading" :id="'opening_reading'" />
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