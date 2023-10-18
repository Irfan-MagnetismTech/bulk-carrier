<template>
    <business-unit-input v-model="form.business_unit"></business-unit-input>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel <span class="text-red-500">*</span></span>
            <select v-model="form.ops_vessel_id" class="form-input">
              <option value="" disabled selected>Select Vessel</option>
              <option value="1" > Vessel</option>
              <!-- <option v-for="shipDepartment in shipDepartments" :value="shipDepartment.id">{{ shipDepartment.name }}</option> -->
            </select>
          <Error v-if="errors?.ops_vessel_id" :errors="errors.ops_vessel_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Department <span class="text-red-500">*</span></span>
          <!-- <select v-model="form.mnt_ship_department_id" required class="form-input" @change="fetchShipDepartmentWiseItems" >
            <option value="" disabled selected>Select Department</option>
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
          <span class="text-gray-700 dark:text-gray-300">Item <span class="text-red-500">*</span></span>
          <!-- <select v-model="form.mnt_item_id" required class="form-input" >
            <option value="" disabled selected>Select Item</option>
            <option v-for="item in shipDepartmentWiseItems" :value="item.id">{{ item.item_code + ' ' + item.name }}</option>
              
            </select> -->
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
    <!-- <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <table class="w-full border border-gray-500">
              <thead class="w-full">
                <tr class="w-full">
                  <th class="w-3/12">Job Description</th>
                  <th class="w-2/12">Cycle Unit</th>
                  <th class="w-2/12">Cycle</th>
                  <th class="w-2/12">Add To Upcoming List</th>
                  <th class="w-2/12">Remarks</th>
                  <th class="w-1/12">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(job_line, index) in form.mntJobLines" :key="index">
                  <td><input class="form-input bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="job_line.job_description" placeholder="Job Description" /></td>

                  <td>
                    <select v-model="job_line.cycle_unit" class="form-input bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="" disabled selected>Select Cycle Unit</option>
                        <option v-for="jobCycleUnit in jobCycleUnits" :value="jobCycleUnit">{{ jobCycleUnit }}</option>
                    </select>
                </td>

                  <td><input class="form-input bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="job_line.cycle" placeholder="Cycle" /></td>
                  
                  <td><input class="form-input bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="job_line.min_limit" placeholder="Add To Upcoming List" /></td>
                  
                  <td><input class="form-input bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="job_line.remarks" placeholder="Remarks" /></td>
                  
                  

                  <td><button type="button" class="bg-green-600 text-white px-3 py-2 rounded-md" v-show="index==0" @click="addJob">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                
                </button> <button type="button" class="bg-red-600 text-white px-3 py-2 rounded-md" v-show="index!=0" @click="removeJob(index)" >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
                </button></td>
                </tr>
              </tbody>
            </table>
        </label>
    </div> -->

    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 text-gray-700 dark:text-gray-300">Job List <span class="text-red-500">*</span></legend>
      <table class="w-full whitespace-no-wrap" id="table">
        <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="w-3/12 px-4 py-3 align-bottom">Job Description <span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom">Cycle Unit <span class="text-red-500">*</span></th>
            <th class="w-1/12 px-4 py-3 align-bottom">Cycle <span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom">Add To Upcoming List <span class="text-red-500">*</span></th>
            <th class="w-1/12 px-4 py-3 align-bottom">Last Done</th>
            <th class="w-2/12 px-4 py-3 align-bottom">Remarks</th>
            <th class="w-1/12 px-4 py-3 align-bottom text-center">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400" v-for="(job_line, index) in form.mntJobLines" :key="index">
            <td class="px-1 py-1"><input type="text" class="form-input" required  v-model="job_line.job_description" placeholder="Job Description" /></td>
            <td class="px-1 py-1">
              <select v-model="job_line.cycle_unit" required class="form-input bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="" disabled selected>Select Cycle Unit</option>
                        <option v-for="jobCycleUnit in jobCycleUnits" :value="jobCycleUnit">{{ jobCycleUnit }}</option>
                    </select>
            </td>
            <td class="px-1 py-1"><input type="text" required class="form-input"  v-model="job_line.cycle" placeholder="Cycle" /></td>
            <td class="px-1 py-1"><input type="text" required class="form-input"  v-model="job_line.min_limit" placeholder="Add To Upcoming List" /></td>
            <td class="px-1 py-1"><input type="date" class="form-input"  v-model="job_line.last_done"/></td>
            <td class="px-1 py-1"><input type="text" class="form-input"  v-model="job_line.remarks" placeholder="Remarks" /></td>
            <td class="px-1 py-1"><button type="button" class="bg-green-600 text-white px-3 py-2 rounded-md" v-show="index == 0" @click="addJob"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg></button> <button type="button" class="bg-red-600 text-white px-3 py-2 rounded-md" v-show="index != 0" @click="removeJob(index)" ><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg></button></td>
          </tr>
        </tbody>
      </table>
    </fieldset>
    
</template>
<script setup>
import Error from "../../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useShipDepartment from "../../../composables/maintenance/useShipDepartment";
import {ref, onMounted, watch, watchEffect, computed} from "vue";
import useItem from "../../../composables/maintenance/useItem";
import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
import useItemGroup from "../../../composables/maintenance/useItemGroup";

const { shipDepartments, getShipDepartmentsWithoutPagination } = useShipDepartment();
const { shipDepartmentWiseItems, itemGroupWiseItems, getShipDepartmentWiseItems, getItemGroupWiseItems } = useItem();
const { shipDepartmentWiseItemGroups, getShipDepartmentWiseItemGroups } = useItemGroup();

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

const jobCycleUnits = ref(['Hours', 'Weeks', 'Months', 'Years']);

function addJob() {
  props.form.mntJobLines.push({ job_description: '', cycle_unit: '', cycle: '', min_limit: '', last_done: '', remarks: '' });
}
function removeJob(index) {
  props.form.mntJobLines.splice(index, 1);
}

function fetchShipDepartmentWiseItems()
{
  props.form.item_name = '';
  props.form.mnt_item_id = '';
  getShipDepartmentWiseItems(props.form.mnt_ship_department_id);
}

watch(() => props.form.mnt_ship_department_name, (newValue, oldValue) => {
  props.form.mnt_ship_department_id = props.form.mnt_ship_department_name?.id;
  if(oldValue !== ''){
    props.form.mnt_item_group_name = null;
    props.form.mnt_item_group_id = null;
  }
  getShipDepartmentWiseItemGroups(props.form.mnt_ship_department_id);
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
  getItemGroupWiseItems(props.form.mnt_item_group_id);
});

watch(() => itemGroupWiseItems.value, (val) => {
  props.form.mnt_items = val;
});

// watch(() => shipDepartmentWiseItems.value, (val) => {
//   props.form.dept_wise_items = val;
// });

// function fetchItem(query, loading) {
//   searchMaterialCategory(query, loading);
//   loading(true)
// }
watch(() => props.form.mnt_item_name, (value) => {
  props.form.mnt_item_id = value?.id;
});

watch(() => props.form.business_unit, (newValue, oldValue) => {
  if(newValue !== oldValue && oldValue != ''){
    props.form.mnt_ship_department_name = null;
  }
});


// const { shipDepartments, getShipDepartments } = useShipDepartment();


onMounted(() => {
    watchEffect(() => {
      getShipDepartmentsWithoutPagination(props.form.business_unit ? props.form.business_unit : 'ALL');
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