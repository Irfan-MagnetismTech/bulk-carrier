<script setup>
    import Error from "../../../Error.vue";
    import Editor from '@tinymce/tinymce-vue';
    
    import useShipDepartment from "../../../../composables/maintenance/useShipDepartment";
    import {onMounted, watch, watchEffect, ref} from "vue";
    import BusinessUnitInput from "../../../input/BusinessUnitInput.vue";
    import useVessel from "../../../../composables/operations/useVessel";
    import useWarehouse from "../../../../composables/supply-chain/useWarehouse";
    const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
        
    const props = defineProps({
        form: {
            required: false,
            default: {}
        },
        page: {
            required: false,
            default: {}
        },
        errors: { type: [Object, Array], required: false },
    });
    
    const { warehouses, searchWarehouse , isLoading:isWarehouseLoading} = useWarehouse();
    


    const warehouseChange = () => {
        props.form.scm_warehouse_id = props.form.scmWarehouse?.id;
    }

    const fromDateChange = () => {
        if(props.form.to_date < props.form.from_date) props.form.to_date = null;
    }



    watch(() => props.form.business_unit, (newValue, oldValue) => {
        businessUnit.value = newValue;

        props.form.scmWarehouse = "";
        props.form.scm_warehouse_id = null;
    });

    onMounted(() => {
        watchEffect(() => {
            if(businessUnit.value && businessUnit.value != 'ALL'){
                searchWarehouse('', businessUnit.value);
            }
        });
    });
  
  </script>

<template>
    <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2">
        <business-unit-input v-model="form.business_unit"></business-unit-input>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Warehouse <span class="text-red-500">*</span></span>
            <v-select placeholder="--Choose an option--" :loading="isWarehouseLoading"  :options="warehouses" @search="" 
                            v-model="form.scmWarehouse" label="name" @update:modelValue="warehouseChange"  class="block form-input">
                <template #search="{attributes, events}">
                    <input
                                class="vs__search"
                                :required="!form.scmWarehouse"
                                v-bind="attributes"
                                v-on="events"
                            />
                </template>
            </v-select>
            <input type="hidden" v-model="form.scm_warehouse_id">
        </label>
        <div class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">From Date <span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.from_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" @update:model-value="fromDateChange"></VueDatePicker>
          <Error v-if="errors?.from_date" :errors="errors.from_date" />
        </div>

        
        <div class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">To Date <span class="text-red-500">*</span></span>
          <VueDatePicker v-model="form.to_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :min-date="form.from_date"></VueDatePicker>
          <Error v-if="errors?.to_date" :errors="errors.to_date" />
        </div>
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
    @apply text-gray-700 dark-disabled:text-gray-300;
  }
  .label-item-input {
    @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
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