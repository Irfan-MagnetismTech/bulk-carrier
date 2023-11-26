<script setup>
    import { ref, watch, onMounted, watchEffect, watchPostEffect } from 'vue';
    import Error from "../../Error.vue";
    import useWarehouse from "../../../composables/supply-chain/useWarehouse.js";
    import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
    import ErrorComponent from "../../utils/ErrorComponent.vue";
    const { costCenters, getCostCenters } = useWarehouse();
    
    const props = defineProps({
        form: { type: Object, required: true },
        errors: { type: [Object, Array], required: false },
        formType: { type: String, required : false },
    });

    // function fetchCostCenter(query, loading) {
    //     getCostCenters(props.form.business_unit,query, loading);
    //     loading(true)
    // }

    // function fetchCostCenter(query) {
    //     getCostCenters(props.form.business_unit,query);
    // }

    watch(() => props.form.accCostCenter, (value) => {
        props.form.cost_center_id = value?.id ?? null;
        props.form.cost_center_name = value?.name ?? null;
    });

    onMounted(() => {
        watchEffect(() => {
                getCostCenters(props.form.business_unit);
        });
    });

    watch(() => props.form.business_unit, (newValue, oldValue) => {
        if (newValue != oldValue && oldValue != '')
            props.form.accCostCenter = null;
        });

</script>
<template>
    <div class="flex flex-col justify-center w-1/4 md:flex-row md:gap-2">
        <business-unit-input v-model="form.business_unit"></business-unit-input>
    </div>
    <div class="border-b border-gray-200 dark-disabled:border-gray-700 pb-5">
        <legend>
            <div class="input-group">
                <label class="label-group">
                    <span class="label-item-title">Warehouse Name <span class="required-style">*</span></span>
                    <input type="text" required v-model="form.name" class="form-input" name="name" :id="'name'" />
                    <!-- <Error v-if="errors?.name" :errors="errors.name" /> -->
                </label>
              
                <label class="label-group">
                    <span class="label-item-title">Cost Center<span class="required-style">*</span></span>
                    <v-select :options="costCenters" placeholder="--Choose an option--" v-model="form.accCostCenter" label="name" class="block w-full mt-1 text-xs rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
                        <!-- <input
                            slot="no-options"
                            class="form-input"
                            :value="form.accCostCenter"
                            @input="form.accCostCenter = $event.target.value"
                        /> -->
                        <template #search="{attributes, events}">
                            <input
                                class="vs__search"
                                :required="!form.accCostCenter"
                                v-bind="attributes"
                                v-on="events"
                            />
                        </template>
                    </v-select>
                    <!-- <input type="hidden" v-model="form.cost_center_id" class="label-item-input" name="parent_category" :id="'parent_category'" /> -->
                    <Error v-if="errors?.cost_center_no" :errors="errors.cost_center_no" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Address</span>
                    <input type="text" v-model="form.address" class="form-input" name="address" :id="'address'" />
                    <!-- <Error v-if="errors?.address" :errors="errors.address" /> -->
                </label>
                <label class="label-group">
                    <span class="label-item-title">Warehouse Code <span class="required-style">*</span></span>
                    <input type="text" required v-model="form.short_code" class="form-input" name="short_code" :id="'short_code'" />
                    <!-- <Error v-if="errors?.short_code" :errors="errors.short_code" /> -->
                </label>
            </div>
            <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
            <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Contact Person <span class="required-style">*</span></legend>
            <div class="input-group">
                <label class="label-group">
                    <span class="label-item-title">Name<span class="required-style">*</span></span>
                    <input type="text" required v-model="form.scmWarehouseContactPersons[0].name" class="form-input" name="warehouse_contact_person_name" :id="'warehouse_contact_person_name'" />
                    <!-- <Error v-if="errors?.warehouse_contact_person_name" :errors="errors.warehouse_contact_person_name" /> -->
                </label>
                <label class="label-group">
                    <span class="label-item-title">Designation/Rank<span class="required-style">*</span></span>
                    <input type="text" required v-model="form.scmWarehouseContactPersons[0].designation" class="form-input" name="warehouse_contact_person_designation" :id="'warehouse_contact_person_designation'" />
                    <!-- <Error v-if="errors?.warehouse_contact_person_designation" :errors="errors.scmWarehouseContactPersons_designation" /> -->
                </label>
                <label class="label-group">
                    <span class="label-item-title">Contact<span class="required-style">*</span></span>
                    <input type="text" required v-model="form.scmWarehouseContactPersons[0].phone" class="form-input" name="warehouse_contact_person_contact" :id="'warehouse_contact_person_contact'" />
                    <!-- <Error v-if="errors?.warehouse_contact_person_contact" :errors="errors.warehouse_contact_person_contact" /> -->
                </label>
                <label class="label-group">
                    <span class="label-item-title">Email <span class="required-style">*</span></span>
                    <input type="email" required v-model="form.scmWarehouseContactPersons[0].email" class="form-input" name="warehouse_contact_person_email" :id="'warehouse_contact_person_email'" />
                    <!-- <Error v-if="errors?.warehouse_contact_person_email" :errors="errors.warehouse_contact_person_email" /> -->
                </label>
            </div>
            </fieldset>
        </legend>
    </div>
    <ErrorComponent :errors="errors"></ErrorComponent>  
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
        @apply text-gray-700 dark-disabled:text-gray-300;
      }
      .label-item-input {
        @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
      }
      
        .required-style {
            @apply text-red-500;
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