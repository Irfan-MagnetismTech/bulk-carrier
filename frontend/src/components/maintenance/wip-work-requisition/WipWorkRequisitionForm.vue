<template>
    <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
    <div class="justify-center w-full grid grid-cols-1 md:grid-cols-4 md:gap-2 ">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Requisition Date </span>
            <input type="date" :value="form.requisition_date" placeholder="Requisition Date" class="form-input vms-readonly-input" readonly  />
          <Error v-if="errors?.requisition_date" :errors="errors.requisition_date" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Reference No </span>
            <input type="text" :value="form.reference_no" placeholder="Reference No" class="form-input vms-readonly-input"  />
          <Error v-if="errors?.reference_no" :errors="errors.reference_no" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Maintenance Type</span>
            <!-- <select v-model="form.maintenance_type" class="form-input">
              <option value="" disabled selected>Select Maintenance Type</option>
              <option value="Schedule" > Schedule</option>
              <option value="Breakdown" > Breakdown</option>
              <option value="Dry Dock" > Dry Dock</option>
            </select> -->
            <input type="text" :value="form.maintenance_type" placeholder="Maintenance Type" class="form-input vms-readonly-input"  />
          <Error v-if="errors?.maintenance_type" :errors="errors.maintenance_type" />
        </label>

        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel</span>
            <!-- <v-select placeholder="Select Vessel" :options="vessels" @search="" v-model="form.ops_vessel_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.ops_vessel_name"
                      v-bind="attributes"
                      v-on="events"
                  />
                </template>
              </v-select> -->
              <input type="text" :value="form.opsVessel?.name" placeholder="Vessel Name" class="form-input vms-readonly-input"  readonly/>
              <!-- <input type="hidden" v-model="form.ops_vessel_id"> -->
          <Error v-if="errors?.ops_vessel_id" :errors="errors.ops_vessel_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Department </span>
            <!-- <v-select placeholder="Select Department" :options="shipDepartments" @search="" v-model="form.mnt_ship_department_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.mnt_ship_department_name"
                  v-bind="attributes"
                  v-on="events"
              />
            </template>
          </v-select>
          <input type="hidden" v-model="form.mnt_ship_department_id"> -->
          <input type="text" :value="form.mntWorkRequisitionItem?.MntItem?.MntItemGroup?.MntShipDepartment?.name" placeholder="Ship Department" class="form-input vms-readonly-input"  readonly/>
          <Error v-if="errors?.mnt_ship_department_id" :errors="errors.mnt_ship_department_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Item Group </span>
            <!-- <v-select placeholder="Select Item Group" :options="form.mnt_item_groups" @search="" v-model="form.mnt_item_group_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
            <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.mnt_item_group_name"
                v-bind="attributes"
                v-on="events"
            />
          </template>
          </v-select>
          <input type="hidden" v-model="form.mnt_item_group_id"> -->
          <input type="text" :value="form.mntWorkRequisitionItem?.MntItem?.MntItemGroup?.name" placeholder="Item Group Name" class="form-input vms-readonly-input"  readonly/>
          <Error v-if="errors?.mnt_item_group_id" :errors="errors.mnt_item_group_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Item </span>
            <!-- <v-select placeholder="Select Item" :options="form.mnt_items" @search="" v-model="form.mnt_item_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.mnt_item_name"
                    v-bind="attributes"
                    v-on="events"
                />
              </template>
            </v-select>
            <input type="hidden" v-model="form.mnt_item_id"> -->
            <input type="text" :value="form.mntWorkRequisitionItem?.MntItem?.name" placeholder="Item Name" class="form-input vms-readonly-input"  readonly/>
          <Error v-if="errors?.mnt_item_id" :errors="errors.mnt_item_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Present Run Hour </span>
            <input type="text" v-model="form.present_run_hour" placeholder="Present Run Hour" class="form-input vms-readonly-input" readonly />
          <Error v-if="errors?.present_run_hour" :errors="errors.present_run_hour" />
        </label>


        
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Act. Start Date <span class="text-red-500">*</span></span>
            <input type="date" v-model="form.act_start_date" placeholder="Act. Start Date" class="form-input" required  />
          <Error v-if="errors?.act_start_date" :errors="errors.act_start_date" />
        </label>

        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Act. Completion Date <span class="text-red-500">*</span></span>
            <input type="date" v-model="form.act_completion_date" placeholder="Act. completion Date" class="form-input" required  />
          <Error v-if="errors?.act_completion_date" :errors="errors.act_completion_date" />
        </label>

        
        

        
        <!-- <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Assign To <span class="text-red-500">*</span></span>
            <select v-model="form.assigned_to" class="form-input">
              <option value="" disabled selected>Select</option>
              <option value="Team" > Team</option>
              <option value="Vendor" > Vendor</option>
            </select>
          <Error v-if="errors?.assigned_to" :errors="errors.assigned_to" />
        </label> -->

        
        <!-- <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Responsible Person <span class="text-red-500">*</span></span>
            <v-select placeholder="Select Responsible Person" :options="crews" @search="" v-model="form.responsible_person_name" label="name" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.responsible_person_name"
                    v-bind="attributes"
                    v-on="events"
                />
              </template>
            </v-select>
            <input type="hidden" v-model="form.responsible_person">
          <Error v-if="errors?.responsible_person" :errors="errors.responsible_person" />
        </label> -->

        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Responsible Person</span>
            <!-- <select v-model="form.responsible_person" class="form-input">
              <option value="" disabled selected>Select</option>
              <option value="Rahim" > Rahim</option>
              <option value="Karim" > Karim</option>
            </select>
             -->
            <input type="text" :value="form.responsible_person" placeholder="Responsible Person Name" class="form-input vms-readonly-input"  readonly/>
          <Error v-if="errors?.responsible_person" :errors="errors.responsible_person" />
        </label>

        
        <!-- <label class="block w-full mt-2 text-sm" v-show="page == 'edit'">
            <span class="text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></span>
            <select v-model="form.status" class="form-input" required :disabled="page != 'edit'">
              <option value="" disabled selected>Select</option>
              <option value="0" > Pending</option>
              <option value="1" > WIP</option>
            </select>
          <Error v-if="errors?.status" :errors="errors.status" />
        </label> -->




        
    </div>

    <div>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 dark:text-gray-300">Item Description </legend>
        <!-- <ul>
          <li v-for="(des, index) in JSON.parse(form.mntWorkRequisitionItem.MntItem.description)" :key="index">
          {{ des.key + " => " + des.value  }}
          </li>
        </ul> -->
        <div class="justify-center w-full grid grid-cols-1 md:grid-cols-4 md:gap-2 " v-if="form.mntWorkRequisitionItem?.MntItem?.description">
          <label class="block w-full mt-2 text-sm" v-for="(des, index) in JSON.parse(form.mntWorkRequisitionItem?.MntItem?.description)" :key="index">
            <span class="text-gray-700 dark:text-gray-300">{{ des.key }}</span>
            <input type="text" :value="des.value" :placeholder="des.key" class="form-input vms-readonly-input"  readonly/>
        </label>
        </div>
      </fieldset>
    </div>

    <!-- <div class="mt-3">
      <ul class="flex flex-wrap gap-1 text-gray-700 dark:text-gray-300">
        <li><button type="button" class="px-3 py-1 md:rounded-l-sm bg-gray-200 hover:bg-purple-800 hover:text-white" :class="{ 'bg-purple-800 rounded-sm text-white' : tab === 'all_jobs' }" @click="currentTab('all_jobs')">All Jobs</button></li>
        <li><button type="button" class="px-3 py-1 bg-gray-200 hover:bg-purple-800 hover:text-white" :class="{ 'bg-purple-800 rounded-sm text-white' : tab === 'overdue_jobs' }" @click="currentTab('overdue_jobs')">Overdue Jobs</button></li>
        <li><button type="button" class="px-3 py-1 bg-gray-200 hover:bg-purple-800 hover:text-white" :class="{ 'bg-purple-800 rounded-sm text-white' : tab === 'upcoming_jobs' }" @click="currentTab('upcoming_jobs')">Upcoming Jobs</button></li>
        <li><button type="button" class="px-3 py-1 md:rounded-r-sm bg-gray-200 hover:bg-purple-800 hover:text-white" :class="{ 'bg-purple-800 rounded-sm text-white' : tab === 'added_jobs' }" @click="currentTab('added_jobs')">Added Jobs</button></li>
      </ul>
      <div class="mt-1">
        <div v-if="itemWiseJobLines[tab]?.length || (tab === 'added_jobs' && form.added_job_lines?.length)">
          <table class="w-full whitespace-no-wrap" id="table">
            <thead>
              <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="w-3/12 px-4 py-3 align-bottom">Job Description</th>
                <th class="w-2/12 px-4 py-3 align-bottom">Cycle</th>
                <th class="w-2/12 px-4 py-3 align-bottom">Last Done</th>
                <th class="w-2/12 px-4 py-3 align-bottom">Prev. Run Hrs.</th>
                <th class="w-1/12 px-4 py-3 align-bottom">Next Due</th>
                <th class="w-2/12 px-4 py-3 align-bottom text-center">Action</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr class="text-gray-700 dark:text-gray-400" v-for="(jobLine, index) in (tab === 'added_jobs' ?  form.added_job_lines : itemWiseJobLines[tab])" :key="index">
                  <td><input type="text"  class="form-input"  :value="jobLine.job_description" readonly /></td>
                  <td><input type="text"  class="form-input"  :value="jobLine.cycle + ' ' + jobLine.cycle_unit" readonly /></td>
                  <td><input type="text"  class="form-input"  :value="jobLine.last_done ? moment(jobLine.last_done).format('MM/DD/YYYY') : null" readonly /></td>
                  <td><input type="text"  class="form-input"  :value="jobLine.previous_run_hour" readonly /></td>
                  <td><input type="text"  class="form-input"  :value="jobLine.cycle_unit == 'Hours' ? jobLine.next_due : (jobLine.next_due ? moment(jobLine.next_due).format('MM/DD/YYYY') : null)" readonly /></td>
                  <td>
                    <button type="button" class="bg-green-600 text-white px-3 py-2 rounded-md" v-show="form.added_job_lines.indexOf(findAddedJobLine(jobLine)) == -1"  @click="addJobLine(jobLine)">Add</button>
                    <button type="button" class="bg-red-600 text-white px-3 py-2 rounded-md" v-show="form.added_job_lines.indexOf(findAddedJobLine(jobLine)) > -1" @click="removeJobLine(jobLine)" >Remove</button>
                  </td>
              </tr>
                
            </tbody>
          </table>
        </div>
        <div v-else class="py-10 bg-purple-100 text-center rounded-md">
          <p class="text-md font-bold">No job found</p>
        </div>
        
      </div>
    </div> -->

    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 text-gray-700 dark:text-gray-300">Spare Parts Consumed {{ form.mnt_work_requisition_materials }} </legend>
      <table class="w-full whitespace-no-wrap" id="table">
        <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="w-2/12 px-4 py-3 align-bottom">Material Name <span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom">Specification</th>
            <th class="w-1/12 px-4 py-3 align-bottom">Unit <span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom">Quantity <span class="text-red-500">*</span></th>
            <th class="w-2/12 px-4 py-3 align-bottom">Remarks</th>
            <th class="w-1/12 px-4 py-3 align-bottom text-center">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400" v-for="(mntWorkRequisitionMaterial, index) in form.mntWorkRequisitionMaterials" :key="index">
            <td class="px-1 py-1">
              <v-select :options="materials" placeholder="--Choose an option--" @search="fetchMaterials" v-model="mntWorkRequisitionMaterial.material_name" label="material_name_and_code" class="block form-input" @change="">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!mntWorkRequisitionMaterial.material_name"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
            </td>
            <!-- <td class="px-1 py-1">
              <select v-model="job_line.cycle_unit" required class="form-input bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="" disabled selected>Select Cycle Unit</option>
                        <option value="Hours" v-show="form.mnt_item_name?.has_run_hour">Hours</option>
                        <option value="Days">Days</option>
                        <option value="Weeks">Weeks</option>
                        <option value="Months">Months</option>
                    </select>
            </td>
            <td class="px-1 py-1"><input type="text" required class="form-input"  v-model="job_line.cycle" placeholder="Cycle" /></td>
            <td class="px-1 py-1"><input type="text" required class="form-input"  v-model="job_line.min_limit" placeholder="Add To Upcoming List" /></td>
            <td class="px-1 py-1"><input type="date" required class="form-input"  v-model="job_line.last_done"/></td>
            
            <td class="px-1 py-1"><input type="text" class="form-input"  v-model="job_line.remarks" placeholder="Remarks" /></td>
            <td class="px-1 py-1"><button type="button" class="bg-green-600 text-white px-3 py-2 rounded-md" v-show="index == 0" @click="addJob"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg></button> <button type="button" class="bg-red-600 text-white px-3 py-2 rounded-md" v-show="index != 0" @click="removeJob(index)" ><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg></button></td> -->
          </tr>
        </tbody>
      </table>
    </fieldset>
    
    
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
import useJob from "../../../composables/maintenance/useJob";
import useRunHour from "../../../composables/maintenance/useRunHour";
import useCrewCommonApiRequest from "../../../composables/crew/useCrewCommonApiRequest";
import useMaterial from "../../../composables/supply-chain/useMaterial";
import moment from 'moment';

const { vessels, getVesselsWithoutPaginate } = useVessel();
const { shipDepartments, getShipDepartmentsWithoutPagination } = useShipDepartment();
const { shipDepartmentWiseItemGroups, getShipDepartmentWiseItemGroups } = useItemGroup();
const { itemGroupWiseItems, vesselWiseJobItems, getItemGroupWiseItems, getVesselWiseJobItems } = useItem();
const { itemWiseJobLines, getJobsForRequisition } = useJob();
const { presentRunHour, getItemPresentRunHour } = useRunHour();
const { crews, getCrews } = useCrewCommonApiRequest();
const { material, materials, getMaterials,searchMaterial } = useMaterial();
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const tab = ref('all_jobs');
const currentTab = (tabValue) => {
  tab.value = tabValue;
};

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


watch(() => props.form.ops_vessel_name, (newValue, oldValue) => {
  props.form.ops_vessel_id = newValue?.id;
  if(props.form.ops_vessel_id){
    getVesselWiseJobItems( businessUnit.value, props.form.ops_vessel_id, props.form.mnt_ship_department_id, props.form.mnt_item_group_id );
  }
  else{
    vesselWiseJobItems.value = [];
  }
  if(oldValue !== '')
    props.form.mnt_item_name = ''; //vessel change

  if(props.form.ops_vessel_id && props.form.mnt_item_id){
    getItemPresentRunHour(props.form.ops_vessel_id, props.form.mnt_item_id);
  }
});

watch(() => presentRunHour.value, (value) => {
  props.form.present_run_hour = value?.previous_run_hour;
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
  if(props.form.ops_vessel_id && props.form.mnt_ship_department_id){
    getVesselWiseJobItems( businessUnit.value, props.form.ops_vessel_id, props.form.mnt_ship_department_id, props.form.mnt_item_group_id );
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
  if(props.form.ops_vessel_id && props.form.mnt_item_group_id){
    // getItemGroupWiseItems(props.form.mnt_item_group_id);
    getVesselWiseJobItems( businessUnit.value, props.form.ops_vessel_id, props.form.mnt_ship_department_id, props.form.mnt_item_group_id );
  }
});

watch(() => vesselWiseJobItems.value, (val) => {
  props.form.mnt_items = val;
});

watch(() => props.form.mnt_item_name, (value) => {
  props.form.mnt_item_id = value?.id;
  tab.value = 'all_jobs'; //vessel or item change
  if(props.form.ops_vessel_id && props.form.mnt_item_id){
    getItemPresentRunHour(props.form.ops_vessel_id, props.form.mnt_item_id);
    //vessel or item change
    getJobsForRequisition(businessUnit.value, props.form.ops_vessel_id, props.form.mnt_item_id);
  }
  else{
    props.form.present_run_hour = null;
    itemWiseJobLines.value = [];
  }
});

// watch(() => props.form.responsible_person_name, (value) => {
//   props.form.responsible_person = value?.name;
// });

watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;
  // console.log(newValue, oldValue, newValue !== oldValue , oldValue != '');
  if(newValue !== oldValue && oldValue != ''){
    props.form.ops_vessel_name = null;
    props.form.mnt_ship_department_name = null;
  }
});

function fetchMaterials(search, loading) {
    loading(true);
    searchMaterial(search, loading)
  }

function addJobLine(jobLine){
  props.form.added_job_lines.push(jobLine);
}

function removeJobLine(jobLine){
  const index = props.form.added_job_lines.indexOf(findAddedJobLine(jobLine));
  if (index > -1) { 
    props.form.added_job_lines.splice(index, 1);
  }
}

function findAddedJobLine(jobLine){
  return props.form.added_job_lines.find((addedJobLine) => addedJobLine.mnt_job_line_id === jobLine.mnt_job_line_id);
}

// const { shipDepartments, getShipDepartments } = useShipDepartment();


onMounted(() => {
  watchEffect(() => {
      if(businessUnit.value){
        getShipDepartmentsWithoutPagination(businessUnit.value);
        getVesselsWithoutPaginate(businessUnit.value);
                if(props.form.ops_vessel_id)
          getVesselWiseJobItems( businessUnit.value, props.form.ops_vessel_id, props.form.mnt_ship_department_id, props.form.mnt_item_group_id );
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