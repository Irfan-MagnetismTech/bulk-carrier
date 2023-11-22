<template>
  <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2">
      <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Ship Department <span class="text-red-500">*</span></span>
            <v-select :loading="isShipDepartmentLoading"  placeholder="Select Ship Department" :options="shipDepartments" @search="" v-model="form.mnt_ship_department_name" label="name" class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
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
          <span class="text-gray-700 dark-disabled:text-gray-300">Item Group <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Item Group" :loading="isItemGroupLoading"  :options="form.mnt_item_groups" @search="" v-model="form.mnt_item_group_name" label="name" class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
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
            <span class="text-gray-700 dark-disabled:text-gray-300">Item Code <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.item_code" placeholder="Item Code" class="form-input" required />
          <Error v-if="errors?.item_code" :errors="errors.item_code" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Item Name <span class="text-red-500">*</span></span>
          <input type="text" v-model.trim="form.name" placeholder="Item Name" class="form-input" required />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
    </div>

    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
    <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Description</legend>
    <table class="w-full whitespace-no-wrap" id="table">
              <thead>
                <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                  <th class="px-4 py-3 align-bottom">Key</th>
                  <th class="px-4 py-3 align-bottom">Value</th>
                  <th class="px-4 py-3 align-bottom text-center">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(des, index) in form.description" :key="index">
                  <td class="px-1 py-1"><input type="text" class="form-input"  v-model.trim="des.key" placeholder="Key" /></td>
                  <td class="px-1 py-1"><input type="text" class="form-input"  v-model.trim="des.value" placeholder="Value" /></td>
                  <td class="px-1 py-1"><button type="button" class="bg-green-600 text-white px-3 py-2 rounded-md" v-show="index==0" @click="addRow"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg></button> <button type="button" class="bg-red-600 text-white px-3 py-2 rounded-md" v-show="index!=0" @click="removeRow(index)" ><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg></button></td>
                </tr>
              </tbody>
            </table>
  </fieldset>


    <div class="flex flex-col justify-center  w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300"> </span>
            <input type="checkbox" v-model="form.has_run_hour" :disabled="page === 'edit' && form.mntJobs?.length" /> Enable Regular Run Hour Entry
          <Error v-if="errors?.has_run_hour" :errors="errors.has_run_hour" />
        </label>        
    </div>

    <ErrorComponent :errors="errors"></ErrorComponent>   
</template>
<script setup>
import Error from "../../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useShipDepartment from "../../../composables/maintenance/useShipDepartment";
import {onMounted, watch, watchEffect, ref} from "vue";
import useItemGroup from "../../../composables/maintenance/useItemGroup";
import useItem from "../../../composables/maintenance/useItem";
import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
import ErrorComponent from "../../utils/ErrorComponent.vue";

const { getItemCodeByGroupId } = useItem();
const { shipDepartmentWiseItemGroups, getShipDepartmentWiseItemGroups, isItemGroupLoading } = useItemGroup();
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

watch(() => props.form.mnt_ship_department_name, (newValue, oldValue) => {
  props.form.mnt_ship_department_id = props.form.mnt_ship_department_name?.id;
  if(oldValue !== ''){
    props.form.mnt_item_group_name = null;
    props.form.mnt_item_group_id = null;
    shipDepartmentWiseItemGroups.value = [];
  }
  if(props.form.mnt_ship_department_id)
    getShipDepartmentWiseItemGroups(props.form.mnt_ship_department_id);
});

watch(() => props.form.mnt_item_group_name, (newValue, oldValue) => {
  props.form.mnt_item_group_id = props.form.mnt_item_group_name?.id;
  
  if((oldValue !== '' || props.page !== 'edit') && props.form.mnt_item_group_id){
    // fetchItemCode();
    props.form.item_code = '';
    getItemCodeByGroupId(props.form, props.form.mnt_item_group_id);
  }
  else if (!props.form.mnt_item_group_id) {
    props.form.item_code = '';
  }
});

watch(() => shipDepartmentWiseItemGroups.value, (val) => {
  props.form.mnt_item_groups = val;
});

// function fetchShipDepartmentWiseItemGroups(){
//   props.form.mnt_item_group_name = '';
//   props.form.mnt_item_group_id = '';
//   getShipDepartmentWiseItemGroups(props.form.mnt_ship_department_id);
// }

function addRow() {
  props.form.description.push({ key: '', value: '' });
}
function removeRow(index) {
  props.form.description.splice(index, 1);
}



const { shipDepartments, getShipDepartmentsWithoutPagination, isShipDepartmentLoading } = useShipDepartment();

watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;
  if(newValue !== oldValue && oldValue != ''){
    props.form.mnt_ship_department_name = null;
  }
  shipDepartments.value = [];
});


onMounted(() => {
  watchEffect(() => {
    if(businessUnit.value && businessUnit.value != 'ALL'){
      getShipDepartmentsWithoutPagination(businessUnit.value);
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