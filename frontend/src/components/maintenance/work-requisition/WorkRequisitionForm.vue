<template>
    <business-unit-input v-model="form.business_unit"></business-unit-input>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Vessel" :options="vessels" @search="" v-model="form.ops_vessel_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.ops_vessel_name"
                      v-bind="attributes"
                      v-on="events"
                  />
                </template>
              </v-select>
              <input type="hidden" v-model="form.ops_vessel_id">
          <Error v-if="errors?.ops_vessel_id" :errors="errors.ops_vessel_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Department <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Department" :options="shipDepartments" @search="" v-model="form.mnt_ship_department_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.mnt_ship_department_name"
                  v-bind="attributes"
                  v-on="events"
              />
            </template>
          </v-select>
          <input type="hidden" v-model="form.mnt_ship_department_id">
          <Error v-if="errors?.mnt_ship_department_id" :errors="errors.mnt_ship_department_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Item Group <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Item Group" :options="form.mnt_item_groups" @search="" v-model="form.mnt_item_group_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.mnt_item_group_name"
                v-bind="attributes"
                v-on="events"
            />
          </template>
          </v-select>
          <input type="hidden" v-model="form.mnt_item_group_id">
          <Error v-if="errors?.mnt_item_group_id" :errors="errors.mnt_item_group_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Item <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Item" :options="form.mnt_items" @search="" v-model="form.mnt_item_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.mnt_item_name"
                    v-bind="attributes"
                    v-on="events"
                />
              </template>
            </v-select>
            <input type="hidden" v-model="form.mnt_item_id">
          <Error v-if="errors?.mnt_item_id" :errors="errors.mnt_item_id" />
        </label>
        
    </div>
    
</template>
<script setup>
import Error from "../../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useShipDepartment from "../../../composables/maintenance/useShipDepartment";
import {onMounted, ref, watch, watchEffect} from "vue";
// import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
import useVessel from "../../../composables/operations/useVessel";
import useItem from "../../../composables/maintenance/useItem";
import useItemGroup from "../../../composables/maintenance/useItemGroup";

const { vessels, getVesselsWithoutPaginate } = useVessel();
const { shipDepartments, getShipDepartmentsWithoutPagination } = useShipDepartment();
const { shipDepartmentWiseItemGroups, getShipDepartmentWiseItemGroups } = useItemGroup();
const { itemGroupWiseItems, getItemGroupWiseItems } = useItem();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});


watch(() => props.form.ops_vessel_name, (value) => {
  props.form.ops_vessel_id = value?.id;
});

watch(() => props.form.mnt_ship_department_name, (newValue, oldValue) => {
  props.form.mnt_ship_department_id = props.form.mnt_ship_department_name?.id;
  if(oldValue !== ''){
    props.form.mnt_item_group_name = null;
    props.form.mnt_item_group_id = null;
  }
  if(props.form.mnt_ship_department_id){
    getShipDepartmentWiseItemGroups(props.form.mnt_ship_department_id);
  }
});

watch(() => shipDepartmentWiseItemGroups.value, (val) => {
  props.form.mnt_item_groups = val;
});

watch(() => props.form.mnt_item_group_name, (newValue, oldValue) => {
  props.form.mnt_item_group_id = props.form.mnt_item_group_name?.id;
  
  if(oldValue !== ''){
    props.form.mnt_item_name = null;
    props.form.mnt_item_id = null;
  }
  if(props.form.mnt_item_group_id){
    getItemGroupWiseItems(props.form.mnt_item_group_id);
  }
});

watch(() => itemGroupWiseItems.value, (val) => {
  props.form.mnt_items = val;
});

watch(() => props.form.mnt_item_name, (value) => {
  props.form.mnt_item_id = value?.id;
});

watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;
  if(newValue !== oldValue && oldValue != ''){
    props.form.ops_vessel_name = null;
    props.form.mnt_ship_department_name = null;
  }
});


// const { shipDepartments, getShipDepartments } = useShipDepartment();


onMounted(() => {
  watchEffect(() => {
      if(businessUnit.value){
        getShipDepartmentsWithoutPagination(businessUnit.value);
        getVesselsWithoutPaginate(businessUnit.value);
      }
    });
});

</script>

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