<template>
  <div class="justify-center w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 md:gap-2">
      <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Vessel <span class="text-red-500">*</span></span>
            <div v-if="!form.has_work_requisition">
              <v-select placeholder="--Choose an option--" :loading="isVesselLoading"  :options="vessels" @search="" v-model="form.ops_vessel_name" label="name" class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
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
            </div>
            <input v-else type="text" :value="form.ops_vessel_name?.name" placeholder="Vessel" class="form-input vms-readonly-input" readonly />
          <!-- <Error v-if="errors?.ops_vessel_id" :errors="errors.ops_vessel_id" /> -->
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Ship Department <span class="text-red-500">*</span></span>
            <div v-if="!form.has_work_requisition">
                <v-select placeholder="--Choose an option--" :loading="isShipDepartmentLoading"  :options="shipDepartments" @search="" v-model="form.mnt_ship_department_name" label="name" class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
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
            </div>

          <input v-else type="text" :value="form.mnt_ship_department_name?.name" placeholder="Ship Department" class="form-input vms-readonly-input" readonly />
          <!-- <Error v-if="errors?.mnt_ship_department_id" :errors="errors.mnt_ship_department_id" /> -->
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Item Group <span class="text-red-500">*</span></span>
          <div v-if="!form.has_work_requisition">
            <v-select placeholder="--Choose an option--" :loading="isItemGroupLoading"  :options="form.mnt_item_groups" @search="" v-model="form.mnt_item_group_name" label="name" class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
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
          </div>

          <input v-else type="text" :value="form.mnt_item_group_name?.name" placeholder="Item Group" class="form-input vms-readonly-input" readonly />
          <!-- <Error v-if="errors?.mnt_item_group_id" :errors="errors.mnt_item_group_id" /> -->
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Item <span class="text-red-500">*</span></span>
            <div v-if="!form.has_work_requisition">
              <v-select placeholder="--Choose an option--" :loading="isItemLoading"  :options="form.mnt_items" @search="" v-model="form.mnt_item_name" label="name" class="block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray form-input">
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
            </div>

            <input v-else type="text" :value="form.mnt_item_name?.name" placeholder="Item" class="form-input vms-readonly-input" readonly />
          <!-- <Error v-if="errors?.mnt_item_id" :errors="errors.mnt_item_id" /> -->
        </label>
        <label class="block w-full mt-2 text-sm" v-show="form.mnt_item_name?.has_run_hour">
            <span class="text-gray-700 dark-disabled:text-gray-300">Present Running Hour</span>
            <input type="number" min="0" v-model="form.present_run_hour" placeholder="Present Running Hour" class="form-input" :class="{ 'vms-readonly-input': page === 'edit' }" :disabled="page === 'edit'" />
          <!-- <Error v-if="errors?.present_run_hour" :errors="errors.present_run_hour" /> -->
        </label>
        
    </div>
    
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
      <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Job List <span class="text-red-500">*</span></legend>
      <table class="w-full whitespace-no-wrap" id="table">
        <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500  bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="w-2/12 px-4 py-3 align-bottom">Job Description <span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom">Cycle Unit <span class="text-red-500">*</span></th>
            <th class="w-1/12 px-4 py-3 align-bottom">Cycle <span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom">Add To Upcoming List <span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom">Last Done <span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom">Remarks</th>
            <th class="w-1/12 px-4 py-3 align-bottom text-center">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(job_line, index) in form.mntJobLines" :key="index">
            <td class="px-1 py-1">
              <div class="relative">
                <input type="text" class="form-input" :class="{'pr-7' : job_line.isJobDescriptionDuplicate}"  required  v-model.trim="job_line.job_description" placeholder="Job Description" />
                <span v-show="job_line.isJobDescriptionDuplicate" class="text-yellow-600 pl-1 absolute top-2 right-1" title="Duplicate Job Description" v-html="icons.ExclamationTriangle"></span>
              </div>
            
            </td>
            <td class="px-1 py-1">
              <select v-model="job_line.cycle_unit" required class="form-input bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark-disabled:bg-gray-700 dark-disabled:border-gray-600 dark-disabled:placeholder-gray-400 dark-disabled:text-white dark-disabled:focus:ring-blue-500 dark-disabled:focus:border-blue-500">
                        <option value="" disabled selected>Select Cycle Unit</option>
                        <option value="Hours" v-show="form.mnt_item_name?.has_run_hour">Hours</option>
                        <option value="Days">Days</option>
                        <option value="Weeks">Weeks</option>
                        <option value="Months">Months</option>
                    </select>
            </td>
            <td class="px-1 py-1"><input type="number" min="1"  required class="form-input"  v-model="job_line.cycle" placeholder="Cycle" /></td>
            <td class="px-1 py-1"><input type="number" :max="job_line.cycle - 1" required class="form-input"  v-model="job_line.min_limit" placeholder="Add To Upcoming List" /></td>
            <td class="px-1 py-1">
              <!-- <input type="date" required class="form-input"  v-model="job_line.last_done"/> -->
              <VueDatePicker v-model="job_line.last_done" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
            </td>
            
            <td class="px-1 py-1"><input type="text" class="form-input"  v-model.trim="job_line.remarks" placeholder="Remarks" /></td>
            <td class="px-1 py-1"><button type="button" class="bg-green-600 text-white px-3 py-2 rounded-md" v-show="index == 0" @click="addJob"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg></button> <button type="button" class="bg-red-600 text-white px-3 py-2 rounded-md" v-show="index != 0 && job_line.mnt_work_requisition_status == null" @click="removeJob(index)" ><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg></button></td>
          </tr>
        </tbody>
      </table>
    </fieldset>
    <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<script setup>
import Error from "../../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useShipDepartment from "../../../composables/maintenance/useShipDepartment";
import {ref, onMounted, watch, watchEffect, computed} from "vue";
import useItem from "../../../composables/maintenance/useItem";
import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
import useItemGroup from "../../../composables/maintenance/useItemGroup";
import useVessel from "../../../composables/operations/useVessel";
import ErrorComponent from "../../utils/ErrorComponent.vue";
import useHeroIcon from "../../../assets/heroIcon";

const { shipDepartments, getShipDepartmentsWithoutPagination, isShipDepartmentLoading } = useShipDepartment();
const { vessels, getVesselsWithoutPaginate, isVesselLoading } = useVessel();
const { shipDepartmentWiseItems, itemGroupWiseItems, getShipDepartmentWiseItems, getItemGroupWiseItems, isItemLoading } = useItem();
const { shipDepartmentWiseItemGroups, getShipDepartmentWiseItemGroups, isItemGroupLoading } = useItemGroup();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);

const icons = useHeroIcon();

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

function addJob() {
  props.form.mntJobLines.push({ job_description: '', cycle_unit: '', cycle: '', min_limit: '', last_done: '', remarks: '' });
}
function removeJob(index) {
  props.form.mntJobLines.splice(index, 1);
}

// function fetchShipDepartmentWiseItems()
// {
//   props.form.item_name = '';
//   props.form.mnt_item_id = '';
//   getShipDepartmentWiseItems(props.form.mnt_ship_department_id);
// }

watch(() => props.form.ops_vessel_name, (value) => {
  props.form.ops_vessel_id = value?.id;
});

watch(() => props.form.mnt_ship_department_name, (newValue, oldValue) => {
  props.form.mnt_ship_department_id = props.form.mnt_ship_department_name?.id;
  if(oldValue !== ''){
    props.form.mnt_item_group_name = null;
    props.form.mnt_item_group_id = null;
    shipDepartmentWiseItemGroups.value = [];
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
    itemGroupWiseItems.value = [];
  }
  if(props.form.mnt_item_group_id){
    getItemGroupWiseItems(props.form.mnt_item_group_id);
  }
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
  if(!props.form.mnt_item_name?.has_run_hour)
    props.form.present_run_hour = 0;
});

watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;
  if(newValue !== oldValue && oldValue != ''){
    props.form.ops_vessel_name = null;
    vessels.value = [];
    props.form.mnt_ship_department_name = null;
    shipDepartments.value = [];
  }
});


// const { shipDepartments, getShipDepartments } = useShipDepartment();


onMounted(() => {
    watchEffect(() => {
      if(businessUnit.value && businessUnit.value != 'ALL'){
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

  --dp-border-color: #4b5563;
  --dp-border-color-hover: #4b5563;
  --dp-icon-color: #4b5563;
}
</style>