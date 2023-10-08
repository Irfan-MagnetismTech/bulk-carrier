<template>
    <!-- Basic information -->
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
          <select v-model="form.mnt_ship_department_id" required class="form-input" >
            <option value="" disabled selected>Select Department</option>
            <option v-for="shipDepartment in shipDepartments" :value="shipDepartment.id">{{ shipDepartment.name }}</option>
              
            </select>
          <Error v-if="errors?.mnt_ship_department_id" :errors="errors.mnt_ship_department_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Item <span class="text-red-500">*</span></span>
          <select v-model="form.mnt_item_id" required class="form-input" >
            <option value="" disabled selected>Select Item</option>
            <option v-for="item in items" :value="item.id">{{ item.item_code + ' ' + item.name }}</option>
              
            </select>
          <Error v-if="errors?.mnt_item_id" :errors="errors.mnt_item_id" />
        </label>
        
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <!-- <span class="text-gray-700 dark:text-gray-300">Description <span class="text-red-500">*</span></span> -->
            <table class="w-full border border-gray-500">
              <thead>
                <tr>
                  <th>Job Description</th>
                  <th>Cycle Unit</th>
                  <th>Cycle</th>
                  <th>Add To Upcoming List</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(job_detail, index) in form.job_details" :key="index">
                  <td><input class="form-input bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="job_detail.job_description" placeholder="Job Description" /></td>

                  <td>
                    <!-- <input class="form-input bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="job_detail.cycle_unit" placeholder="Cycle Unit" /> -->
                
                    <select v-model="job_detail.cycle_unit" class="form-input bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="" disabled selected>Select Cycle Unit</option>
                        <option v-for="jobCycleUnit in jobCycleUnits" :value="jobCycleUnit">{{ jobCycleUnit }}</option>
                    </select>
                </td>

                  <td><input class="form-input bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="job_detail.cycle" placeholder="Cycle" /></td>
                  
                  <td><input class="form-input bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" v-model="job_detail.min_limit" placeholder="Add To Upcoming List" /></td>
                  
                  
                  <td><button type="button" class="bg-green-600 text-white px-3 py-2 rounded-md" v-show="index==0" @click="addJob">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                
                </button> <button type="button" class="bg-red-600 text-white px-3 py-2 rounded-md" v-show="index!=0" @click="removeJob(index)" >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
                </button></td>
                </tr>
              </tbody>
            </table>
          <!-- <Error v-if="errors?.description" :errors="errors.description" /> -->
        </label>
    </div>
    
</template>
<script setup>
import Error from "../../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import useShipDepartment from "../../../composables/maintenance/useShipDepartment";
import {ref, onMounted} from "vue";
import useItem from "../../../composables/maintenance/useItem";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});

const jobCycleUnits = ref(['Hrs', 'Weeks', 'Months', 'Years']);

function addJob() {
  props.form.job_details.push({ job_description: '', cycle_unit: '', cycle: '', min_limit: '', remarks: '' });
}
function removeJob(index) {
  props.form.job_details.splice(index, 1);
}

const { shipDepartments, getShipDepartmentsWithoutPagination } = useShipDepartment();
const { items, getItemsWithoutPagination } = useItem();
// const { shipDepartments, getShipDepartments } = useShipDepartment();


onMounted(() => {
    getShipDepartmentsWithoutPagination();
    // getItemsWithoutPagination();
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