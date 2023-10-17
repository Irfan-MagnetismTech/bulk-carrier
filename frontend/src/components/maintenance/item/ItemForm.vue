<template>
    <business-unit-input v-model="form.business_unit"></business-unit-input>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Ship Department <span class="text-red-500">*</span></span>
            <!-- <select v-model="form.mnt_ship_department_id" required class="form-input">
              <option value="" disabled selected>Select Ship Department</option>
              <option v-for="shipDepartment in shipDepartments" :value="shipDepartment.id">{{ shipDepartment.name }}</option>
            </select> -->
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
          <!-- <select v-model="form.mnt_item_group_id" required class="form-input" @change="fetchItemCode" >
            <option value="" disabled selected>Select Item Group</option>
            <option v-for="itemGroup in itemGroups" :value="itemGroup.id">{{ itemGroup.name }}</option>
              
            </select> -->
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
            <span class="text-gray-700 dark:text-gray-300">Item Code <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.item_code" placeholder="Item Code" class="form-input" required />
          <Error v-if="errors?.item_code" :errors="errors.item_code" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Item Name <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.name" placeholder="Item Name" class="form-input" required />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
    </div>
    <!-- <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Description <span class="text-red-500">*</span></span>
            <textarea v-model="form.description" placeholder="Description" class="form-input"></textarea>
          <Error v-if="errors?.description" :errors="errors.description" />
        </label>
    </div> -->

    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Description</legend>
    <table class="w-full whitespace-no-wrap" id="table">
              <thead>
                <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                  <th class="px-4 py-3 align-bottom">Key</th>
                  <th class="px-4 py-3 align-bottom">Value</th>
                  <th class="px-4 py-3 align-bottom text-center">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <tr class="text-gray-700 dark:text-gray-400" v-for="(des, index) in form.description" :key="index">
                  <td class="px-1 py-1"><input type="text" class="form-input" required v-model="des.key" placeholder="Key" /></td>
                  <td class="px-1 py-1"><input type="text" class="form-input" required v-model="des.value" placeholder="Value" /></td>
                  <td class="px-1 py-1"><button type="button" class="bg-green-600 text-white px-3 py-2 rounded-md" v-show="index==0" @click="addRow"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg></button> <button type="button" class="bg-red-600 text-white px-3 py-2 rounded-md" v-show="index!=0" @click="removeRow(index)" ><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg></button></td>
                </tr>
              </tbody>
            </table>
  </fieldset>

    <!-- <div class="flex flex-col justify-center w-full md:flex-row md:gap-2" >
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Description </span>
            <table class="w-full whitespace-no-wrap" id="table">
              <thead>
                <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                  <th class="px-4 py-3 align-bottom">Key <span class="text-red-500">*</span></th>
                  <th class="px-4 py-3 align-bottom">Value <span class="text-red-500">*</span></th>
                  <th class="px-4 py-3 align-bottom text-center">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <tr class="text-gray-700 dark:text-gray-400" v-for="(des, index) in form.description" :key="index">
                  <td><input class="form-input" required v-model="des.key" placeholder="Key" /></td>
                  <td><input class="form-input" required v-model="des.value" placeholder="Value" /></td>
                  <td><button type="button" class="bg-green-600 text-white px-3 py-2 rounded-md" v-show="index==0" @click="addRow"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg></button> <button type="button" class="bg-red-600 text-white px-3 py-2 rounded-md" v-show="index!=0" @click="removeRow(index)" ><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg></button></td>
                </tr>
              </tbody>
            </table>
          <Error v-if="errors?.description" :errors="errors.description" />
        </label>
    </div> -->

    <div class="flex flex-col justify-center  w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300"> </span>
            <input type="checkbox" v-model="form.has_run_hour" /> Enable Regular Run Hour Entry
          <Error v-if="errors?.has_run_hour" :errors="errors.has_run_hour" />
        </label>        
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2" v-show="form.has_run_hour">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Present Run Hour</span>
            <input type="text" v-model="form.present_run_hour" placeholder="Present Run Hour" class="form-input" />
          <Error v-if="errors?.present_run_hour" :errors="errors.present_run_hour" />
        </label>        
    </div>
</template>
<script setup>
import Error from "../../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useShipDepartment from "../../../composables/maintenance/useShipDepartment";
import {onMounted, watch} from "vue";
import useItemGroup from "../../../composables/maintenance/useItemGroup";
import useItem from "../../../composables/maintenance/useItem";
import BusinessUnitInput from "../../input/BusinessUnitInput.vue";

const { getItemCodeByGroupId, errors } = useItem();
const { shipDepartmentWiseItemGroups, getShipDepartmentWiseItemGroups } = useItemGroup();

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

watch(() => props.form.mnt_ship_department_name, (newValue, oldValue) => {
  props.form.mnt_ship_department_id = props.form.mnt_ship_department_name?.id;
  console.log("New:" + newValue + "Old:" + oldValue)
  if(oldValue !== ''){
    props.form.mnt_item_group_name = null;
  }
  getShipDepartmentWiseItemGroups(props.form.mnt_ship_department_id);
});


// watch(props.form.mnt_ship_department_name, (newValue, oldValue) => {
//   alert("s")
//   if(newValue !== oldValue){
//     props.form.mnt_item_group_name = null;
//   }
//   getShipDepartmentWiseItemGroups(props.form.mnt_ship_department_id);
// });

watch(() => props.form.mnt_item_group_name, (value) => {
  props.form.mnt_item_group_id = value?.id;
  fetchItemCode();
});

watch(() => shipDepartmentWiseItemGroups.value, (val) => {
  props.form.mnt_item_groups = val;
});

function fetchShipDepartmentWiseItemGroups(){
  props.form.mnt_item_group_name = '';
  props.form.mnt_item_group_id = '';
  getShipDepartmentWiseItemGroups(props.form.mnt_ship_department_id);
}

function addRow() {
  props.form.description.push({ key: '', value: '' });
}
function removeRow(index) {
  props.form.description.splice(index, 1);
}

function fetchItemCode(){
  getItemCodeByGroupId(props.form, props.form.mnt_item_group_id);
}

const { shipDepartments, getShipDepartmentsWithoutPagination } = useShipDepartment();



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