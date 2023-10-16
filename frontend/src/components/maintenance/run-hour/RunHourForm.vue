<template>
    <!-- Basic information -->
    <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2 ">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel Name </span>
            <div v-if="form.form_type === 'create'">
              <v-select placeholder="Select Vessel" :options="vessels" @search="" v-model="form.vessel_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
              <input type="hidden" v-model="form.ops_vessel_id">
            </div>
            <input v-else type="text" v-model="form.name" placeholder="Vessel Name" class="form-input" readonly />
            
          <Error v-if="errors?.ops_vessel_id" :errors="errors.ops_vessel_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Department </span>
            <div v-if="form.form_type === 'create'">
              <v-select placeholder="Select Department" :options="shipDepartments" @search=""     v-model="form.department_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
              <input type="hidden" v-model="form.mnt_ship_department_id">
            </div>
            <input v-else type="text" v-model="form.name" placeholder="Ship Department Name" class="form-input" readonly />
          <Error v-if="errors?.mnt_ship_department_id" :errors="errors.mnt_ship_department_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Item Group </span>
            <div v-if="form.form_type === 'create'">
              <v-select placeholder="Select Item Group" :options="shipDepartmentWiseItemGroups" @search="" v-model="form.item_group_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
              <input type="hidden" v-model="form.mnt_item_group_id">
            </div>
            <input v-else type="text" v-model="form.name" placeholder="Item Group Name" class="form-input" readonly />
          <Error v-if="errors?.mnt_item_group_id" :errors="errors.mnt_item_group_id" />
        </label>
        <div class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Item Name </span>
            <div v-if="form.form_type === 'create'">
              <v-select placeholder="Select Item" :options="form.itemGroupWiseHourlyItems" multiple @search="" v-model="form.item_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
              <input type="hidden" v-model="form.mnt_item_id">
            </div>
            <input v-else type="text" v-model="form.name" placeholder="Item Name" class="form-input" readonly />
          <Error v-if="errors?.mnt_item_id" :errors="errors.mnt_item_id" />
          </div>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Previous Run Hour </span>
            <input type="text" v-model="form.previous_run_hour" placeholder="Previous Run Hour" class="form-input" :readonly="1" />
          <Error v-if="errors?.previous_run_hour" :errors="errors.previous_run_hour" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Running Hour (Since Last Update) </span>
            <input type="text" v-model="form.present_run_hour" placeholder="Present Run Hour" class="form-input"  />
          <Error v-if="errors?.present_run_hour" :errors="errors.present_run_hour" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Updated On </span>
            <input type="date" v-model="form.updated_on" placeholder="Updated on" class="form-input"  />
          <Error v-if="errors?.updated_on" :errors="errors.updated_on" />
        </label>
        
    </div>
    
</template>
<script setup>
import Error from "../../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useRunHour from "../../../composables/maintenance/useRunHour";
import {onMounted, watch} from "vue";
import useShipDepartment from "../../../composables/maintenance/useShipDepartment";
import useItemGroup from "../../../composables/maintenance/useItemGroup";
import useItem from "../../../composables/maintenance/useItem";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

const vessels = [{id:1, name: 'vessel'}];
const { shipDepartments, getShipDepartmentsWithoutPagination } = useShipDepartment();
const { shipDepartmentWiseItemGroups, getShipDepartmentWiseItemGroups } = useItemGroup();
const { itemGroupWiseHourlyItems, getItemGroupWiseHourlyItems } = useItem();

watch(() => props.form.vessel_name, (value) => {
  props.form.ops_vessel_id = value?.id;
});

function fetchShipDepartmentWiseItemGroups(){
  getShipDepartmentWiseItemGroups(props.form.mnt_ship_department_id);
}

watch(() => props.form.department_name, (value) => {
  props.form.mnt_ship_department_id = value?.id;
  fetchShipDepartmentWiseItemGroups();
});

function fetchItemGroupWiseHourlyItems(){
  getItemGroupWiseHourlyItems(props.form.mnt_item_group_id);
}

watch(() => props.form.item_group_name, (value) => {
  props.form.mnt_item_group_id = value?.id;
  fetchItemGroupWiseHourlyItems();
});

watch(() => props.form.item_name, (value) => {
  value = value ? value.find(val => val.id === 'all') ? [value.find(val => val.id === 'all')] : value : null;
  props.form.item_name = value;
  props.form.previous_run_hour = value.length == 1 ? value[0].present_run_hour : '';
  props.form.mnt_item_id = value ? value.map(val=>val.id) : null;
});

watch(() => itemGroupWiseHourlyItems.value, (value) => {
  props.form.itemGroupWiseHourlyItems  = [{id: 'all', item_code: '', name: 'All', present_run_hour: ''}, ...value];
});


onMounted(() => {
  getShipDepartmentsWithoutPagination();
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

.vs--searchable .vs__dropdown-toggle{
  height: auto !important;
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