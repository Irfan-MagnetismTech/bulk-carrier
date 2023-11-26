<template>
  <div class="justify-center w-full grid grid-cols-1 md:grid-cols-3 md:gap-2 ">
      <business-unit-input :page="page" v-model="form.business_unit"></business-unit-input>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Requisition Date </span>
            <input type="date" :value="form.requisition_date" placeholder="Requisition Date" class="form-input vms-readonly-input" readonly  />
          <Error v-if="errors?.requisition_date" :errors="errors.requisition_date" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Reference No </span>
            <input type="text" :value="form.reference_no" placeholder="Reference No" class="form-input vms-readonly-input"  />
          <Error v-if="errors?.reference_no" :errors="errors.reference_no" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Maintenance Type</span>
            <input type="text" :value="form.maintenance_type" placeholder="Maintenance Type" class="form-input vms-readonly-input"  />
          <Error v-if="errors?.maintenance_type" :errors="errors.maintenance_type" />
        </label>

        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Vessel</span>
              <input type="text" :value="form.opsVessel?.name" placeholder="Vessel Name" class="form-input vms-readonly-input"  readonly/>
          <Error v-if="errors?.ops_vessel_id" :errors="errors.ops_vessel_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Department </span>
          <input type="text" :value="form.mntWorkRequisitionItem?.MntItem?.MntItemGroup?.MntShipDepartment?.name" placeholder="Ship Department" class="form-input vms-readonly-input"  readonly/>
          <Error v-if="errors?.mnt_ship_department_id" :errors="errors.mnt_ship_department_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Item Group </span>
          <input type="text" :value="form.mntWorkRequisitionItem?.MntItem?.MntItemGroup?.name" placeholder="Item Group Name" class="form-input vms-readonly-input"  readonly/>
          <Error v-if="errors?.mnt_item_group_id" :errors="errors.mnt_item_group_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Item </span>
            <input type="text" :value="form.mntWorkRequisitionItem?.MntItem?.name" placeholder="Item Name" class="form-input vms-readonly-input"  readonly/>
          <Error v-if="errors?.mnt_item_id" :errors="errors.mnt_item_id" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Present Run Hour </span>
            <input type="text" :value="form.mntWorkRequisitionItem?.present_run_hour" placeholder="Present Run Hour" class="form-input vms-readonly-input" readonly />
          <Error v-if="errors?.present_run_hour" :errors="errors.present_run_hour" />
        </label>


        
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Act. Start Date </span>
            <input type="date" :value="form.act_start_date" placeholder="Act. Start Date" class="form-input vms-readonly-input"  readonly/>
          <Error v-if="errors?.act_start_date" :errors="errors.act_start_date" />
        </label>

        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Act. Completion Date </span>
            <input type="date" :value="form.act_completion_date" placeholder="Act. completion Date" class="form-input vms-readonly-input"  readonly/>
          <Error v-if="errors?.act_completion_date" :errors="errors.act_completion_date" />
        </label>

        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Responsible Person</span>
            <input type="text" :value="form.responsible_person" placeholder="Responsible Person Name" class="form-input vms-readonly-input"  readonly/>
          <Error v-if="errors?.responsible_person" :errors="errors.responsible_person" />
        </label>
    </div>

    <div>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Item Description </legend>
        <div class="justify-center w-full grid grid-cols-1 md:grid-cols-4 md:gap-2 " v-if="form.mntWorkRequisitionItem?.MntItem?.description">
          <label class="block w-full mt-2 text-sm" v-for="(des, index) in JSON.parse(form.mntWorkRequisitionItem?.MntItem?.description)" :key="index">
            <span class="text-gray-700 dark-disabled:text-gray-300">{{ des.key }}</span>
            <input type="text" :value="des.value" :placeholder="des.key" class="form-input vms-readonly-input"  readonly/>
        </label>
        </div>
      </fieldset>
    </div>

    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
      <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Assigned Job</legend>
      <table class="w-full whitespace-no-wrap" id="table">
        <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="px-4 py-3 align-bottom" :class="{ 'w-3/12': businessUnit !== 'PSML', 'w-4/12': businessUnit === 'PSML'  }">Description</th>
            <th class="w-1/12 px-4 py-3 align-bottom">Start Date</th>
            <th class="w-1/12 px-4 py-3 align-bottom">Completion Date</th>
            <th class="w-1/12 px-4 py-3 align-bottom" v-show="businessUnit !== 'PSML'" >Checking</th>
            <th class="w-1/12 px-4 py-3 align-bottom" v-show="businessUnit !== 'PSML'" >Replace</th>
            <th class="w-1/12 px-4 py-3 align-bottom" v-show="businessUnit !== 'PSML'" >Cleaning</th>
            <th class="px-4 py-3 align-bottom" :class="{ 'w-2/12': businessUnit !== 'PSML', 'w-4/12': businessUnit === 'PSML'  }">Remarks</th>
            <th class="w-2/12 px-4 py-3 align-bottom">Status</th>
            <!-- <th class="w-1/12 px-4 py-3 align-bottom text-center">Action</th> -->
          </tr>
        </thead>
        <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(mntWorkRequisitionLine, index) in form.mntWorkRequisitionLines" :key="index">
            <td class="px-1 py-1"> <input type="text" class="form-input vms-readonly-input"  v-model="mntWorkRequisitionLine.job_description" placeholder="Description" readonly /> </td>
            <td class="px-1 py-1"> 
              <input type="date" class="form-input vms-readonly-input"  v-model="mntWorkRequisitionLine.start_date" placeholder="Start Date" readonly/> 
              <Error class="pb-1" v-if="mntWorkRequisitionLine?.start_date_error" :errors="mntWorkRequisitionLine?.start_date_error" />
            </td>
            <td class="px-1 py-1"> <input type="date" class="form-input vms-readonly-input"  v-model="mntWorkRequisitionLine.completion_date" placeholder="Completion Date"  /> </td>
            <td class="px-1 py-1" v-show="businessUnit !== 'PSML'" > <input type="checkbox" v-model="mntWorkRequisitionLine.checking" readonly/> </td>
            <td class="px-1 py-1" v-show="businessUnit !== 'PSML'" > <input type="checkbox" v-model="mntWorkRequisitionLine.replace" readonly/> </td>
            <td class="px-1 py-1" v-show="businessUnit !== 'PSML'" > <input type="checkbox" v-model="mntWorkRequisitionLine.cleaning" readonly/> </td>
            <td class="px-1 py-1" > <input type="text" class="form-input vms-readonly-input"  v-model="mntWorkRequisitionLine.remarks" placeholder="Remarks"  readonly/> </td>
            <td class="px-1 py-1"> 
              <span :class="mntWorkRequisitionLine?.status === 0 ? 'text-yellow-700 bg-yellow-100' : (mntWorkRequisitionLine?.status === 1 ? 'text-blue-700 bg-blue-100' : 'text-green-700 bg-green-100') " class="px-2 py-1 font-semibold leading-tight rounded-full">{{ mntWorkRequisitionLine?.status === 0 ? 'Pending' : (mntWorkRequisitionLine?.status === 1 ? 'WIP' : 'Done') }}</span>
            </td>
            <!-- <td class="px-1 py-1"><button type="button" class="bg-green-600 text-white px-3 py-2 rounded-md" @click="submitWipWorkRequisitionLine(mntWorkRequisitionLine)">Submit</button> 
            </td> -->
          </tr>
        </tbody>
      </table>
    </fieldset>

    
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
      <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Spare Parts Consumed </legend>
      <table class="w-full whitespace-no-wrap" id="table">
        <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="w-2/12 px-4 py-3 align-bottom">Material Name </th>
            <th class="w-2/12 px-4 py-3 align-bottom">Specification</th>
            <th class="w-1/12 px-4 py-3 align-bottom">Unit</th>
            <th class="w-2/12 px-4 py-3 align-bottom">Quantity </th>
            <th class="w-2/12 px-4 py-3 align-bottom">Remarks</th>
            
          </tr>
        </thead>
        <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(mntWorkRequisitionMaterial, index) in form.mntWorkRequisitionMaterials" :key="index">
            <td class="px-1 py-1">
              <!-- <v-select :options="materials" placeholder="Enter Material Name" @search="fetchMaterials" v-model="mntWorkRequisitionMaterial.material_name_and_code" label="material_name_and_code" :reduce="materials => materials.material_name_and_code" class="block form-input" @change="setMaterialUnit(mntWorkRequisitionMaterial)">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!mntWorkRequisitionMaterial.material_name_and_code"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select> -->
            <input type="text" readonly class="form-input vms-readonly-input"  v-model="mntWorkRequisitionMaterial.material_name_and_code" placeholder="Specification" />
            </td> 
            <td class="px-1 py-1"><input type="text" readonly class="form-input vms-readonly-input"  v-model="mntWorkRequisitionMaterial.specification" placeholder="Specification" /></td>
            <td class="px-1 py-1"><input type="text" class="form-input vms-readonly-input"  v-model="mntWorkRequisitionMaterial.unit" placeholder="Unit" readonly /></td>
            <td class="px-1 py-1"><input type="number" readonly class="form-input vms-readonly-input"  v-model="mntWorkRequisitionMaterial.quantity" placeholder="Quantity" required /></td>
            <td class="px-1 py-1"><input type="text" readonly class="form-input vms-readonly-input"  v-model="mntWorkRequisitionMaterial.remarks" placeholder="Remarks" /></td>
            
          </tr>
        </tbody>
      </table>
    </fieldset>


    
    
</template>
<script setup>
import Error from "../../Error.vue";
import Editor from '@tinymce/tinymce-vue';

import Swal from "sweetalert2";

import useShipDepartment from "../../../composables/maintenance/useShipDepartment";
import {onMounted, ref, watch, watchEffect} from "vue";
// import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import BusinessUnitInput from "../../input/BusinessUnitInput.vue";
import useVessel from "../../../composables/operations/useVessel";
import useItem from "../../../composables/maintenance/useItem";
import useWipWorkRequisition from "../../../composables/maintenance/useWipWorkRequisition";
import useItemGroup from "../../../composables/maintenance/useItemGroup";
import useJob from "../../../composables/maintenance/useJob";
import useRunHour from "../../../composables/maintenance/useRunHour";
import useCrewCommonApiRequest from "../../../composables/crew/useCrewCommonApiRequest";
import useMaterial from "../../../composables/supply-chain/useMaterial";
import moment from 'moment';

const { updateWipWorkRequisitionLine } = useWipWorkRequisition();
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




watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;
});

watch(() => props.form.mntWorkRequisitionMaterials, (value) => {
  value?.forEach(val => {
    if (!val.material_name_and_code) {
      val.specification = '';
      val.unit = '';
      val.quantity = 0;
      val.remarks = '';
    }
  });
}, { deep: true });

function fetchMaterials(search, loading) {
    if (search?.length > 1) {
      loading(true);
      searchMaterial(search, loading)
    }
  }


function setMaterialUnit(mntWorkRequisitionMaterial) {
  let material = materials.value.find(mat => mat.material_name_and_code === mntWorkRequisitionMaterial.material_name_and_code);
  mntWorkRequisitionMaterial.unit = material?.unit;
  materials.value = [];
}

function addConsumedSparePart() {
  props.form.mntWorkRequisitionMaterials.push({
                material_name_and_code: '',
                specification: '',
                unit: '',
                quantity: 0,
                remarks: '',
            });
}
function removeConsumedSparePart(index) {
  props.form.mntWorkRequisitionMaterials.splice(index, 1);
}

function submitWipWorkRequisitionLine(mntWorkRequisitionLine) {
  // console.log(mntWorkRequisitionLine);
  mntWorkRequisitionLine.start_date_error = [''];
  if (!mntWorkRequisitionLine.start_date) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Something went wrong!",
    }).then((result)=>{
      if (result.isConfirmed) {
        mntWorkRequisitionLine.start_date_error = ["Start date field is required."]
      }
    });
  }
  else
    updateWipWorkRequisitionLine(mntWorkRequisitionLine, mntWorkRequisitionLine.id);
}


onMounted(() => {
  
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