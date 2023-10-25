<script setup>
    import { ref, watch, onMounted } from 'vue';
    import Error from "../../Error.vue";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    
    const props = defineProps({
        warehouse: { type: Object, required: true },
        errors: { type: [Object, Array], required: false },
    });
    
    const { warehouses, searchWarehouse } = useWarehouse();

    function fetchWarehouse(query, loading) {
        searchWarehouse(query, loading);
        loading(true)
    }

    watch(() => props.warehouse.cost_center_no, (value) => {
        props.warehouse.cost_center_id = value?.id;
    });
    
    const store_category = ['Warehouse', 'Tank']
</script>
<template>
    <business-unit-input v-model="warehouse.business_unit"></business-unit-input>
    <div class="border-b border-gray-200 dark:border-gray-700 pb-5">
        <legend>
            <div class="input-group">
                <label class="label-group">
                    <span class="label-item-title">Warehouse Name <span class="required-style">*</span></span>
                    <input type="text" required v-model="warehouse.name" class="form-input" name="name" :id="'name'" />
                    <Error v-if="errors?.name" :errors="errors.name" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Cost Center</span>
                    <v-select :options="warehouses" placeholder="--Choose an option--" @search="fetchWarehouse" v-model="warehouse.cost_center_no" label="name" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
                    <input type="hidden" v-model="warehouse.cost_center_id" class="label-item-input" name="parent_category" :id="'parent_category'" />
                    <Error v-if="errors?.cost_center_no" :errors="errors.cost_center_no" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Address <span class="text-red-500">*</span></span>
                    <input type="text" v-model="warehouse.address" class="form-input" name="address" :id="'address'" />
                    <Error v-if="errors?.address" :errors="errors.address" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Warehouse Code <span class="text-red-500">*</span></span>
                    <input type="text" v-model="warehouse.short_code" class="form-input" name="short_code" :id="'short_code'" />
                    <Error v-if="errors?.short_code" :errors="errors.short_code" />
                </label>
            </div>
            <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
            <legend class="px-2 text-gray-700 dark:text-gray-300">Contact Person <span class="text-red-500">*</span></legend>
            <div class="input-group">
                <label class="label-group">
                    <span class="label-item-title">Name<span class="required-style">*</span></span>
                    <input type="text" required v-model="warehouse.warehouse_contact_person.name" class="form-input" name="warehouse_contact_person_name" :id="'warehouse_contact_person_name'" />
                    <Error v-if="errors?.warehouse_contact_person_name" :errors="errors.warehouse_contact_person_name" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Designation/Rank<span class="text-red-500">*</span></span>
                    <input type="text" v-model="warehouse.warehouse_contact_person.designation" class="form-input" name="warehouse_contact_person_designation" :id="'warehouse_contact_person_designation'" />
                    <Error v-if="errors?.warehouse_contact_person_designation" :errors="errors.warehouse_contact_person_designation" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Contact<span class="text-red-500">*</span></span>
                    <input type="text" v-model="warehouse.warehouse_contact_person.contact_no" class="form-input" name="warehouse_contact_person_contact" :id="'warehouse_contact_person_contact'" />
                    <Error v-if="errors?.warehouse_contact_person_contact" :errors="errors.warehouse_contact_person_contact" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Email <span class="required-style">*</span></span>
                    <input type="text" required v-model="warehouse.warehouse_contact_person.email" class="form-input" name="warehouse_contact_person_email" :id="'warehouse_contact_person_email'" />
                    <Error v-if="errors?.warehouse_contact_person_email" :errors="errors.warehouse_contact_person_email" />
                </label>
            </div>
            </fieldset>
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