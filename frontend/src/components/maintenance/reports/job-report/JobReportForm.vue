<script setup>
    import Error from "../../../Error.vue";
    import Editor from '@tinymce/tinymce-vue';
    
    import useShipDepartment from "../../../../composables/maintenance/useShipDepartment";
    import {onMounted, watch, watchEffect, ref} from "vue";
    import BusinessUnitInput from "../../../input/BusinessUnitInput.vue";
    import useVessel from "../../../../composables/operations/useVessel";
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
    
    const { vessels, getVesselsWithoutPaginate, isLoading:isVesselLoading  } = useVessel();
    const { shipDepartments, getShipDepartmentsWithoutPagination, isLoading:isShipDepartmentLoading } = useShipDepartment();
    
    function vesselChange() {
        props.form.ops_vessel_id = props.form.ops_vessel?.id;

        props.form.mnt_ship_department = "";
        props.form.mnt_ship_department_id = null;
        if (props.form.ops_vessel_id && businessUnit.value && businessUnit.value != 'ALL')
            getShipDepartmentsWithoutPagination(businessUnit.value);
    } 

    function shipDepartmentChange() {
        props.form.mnt_ship_department_id = props.form.mnt_ship_department?.id;
    } 

    watch(() => props.form.business_unit, (newValue, oldValue) => {
        businessUnit.value = newValue;

        props.form.ops_vessel = "";
        props.form.ops_vessel_id = null;
    });

    onMounted(() => {
        watchEffect(() => {
            if(businessUnit.value && businessUnit.value != 'ALL'){
                getVesselsWithoutPaginate(businessUnit.value);
            }
        });
    });
  
  </script>

<template>
    <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2">
        <business-unit-input v-model="form.business_unit"></business-unit-input>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Vessel <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Vessel" :loading="isVesselLoading"  :options="vessels" @search="" 
                            v-model="form.ops_vessel" label="name" @update:modelValue="vesselChange"  class="block form-input">
                <template #search="{attributes, events}">
                    <input
                                class="vs__search"
                                :required="!form.ops_vessel"
                                v-bind="attributes"
                                v-on="events"
                            />
                </template>
            </v-select>
            <input type="hidden" v-model="form.ops_vessel_id">
            <!-- <Error v-if="errors?.ops_vessel_id" :errors="errors.ops_vessel_id" /> -->
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Ship Department <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Ship Department" :loading="isShipDepartmentLoading"  
                        :options="shipDepartments" @search="" v-model="form.mnt_ship_department" label="name" @update:modelValue="shipDepartmentChange"  class="block form-input">
                <template #search="{attributes, events}">
                    <input
                                class="vs__search"
                                :required="!form.mnt_ship_department"
                                v-bind="attributes"
                                v-on="events"
                            />
                </template>
            </v-select>
            <input type="hidden" v-model="form.mnt_ship_department_id">
            <!-- <Error v-if="errors?.mnt_ship_department_id" :errors="errors.mnt_ship_department_id" /> -->
        </label>
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