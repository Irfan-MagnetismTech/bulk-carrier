<script setup>
import Error from "../Error.vue";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {computed, onMounted, ref, watch, watchEffect} from "vue";
import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import ErrorComponent from '../utils/ErrorComponent.vue';
import Store from "../../store";
import RemarksComponent from "../utils/RemarksComponent.vue";

const { vessels, getVesselsWithoutPaginate } = useVessel();
const { getVesselAssignedCrews, vesselAssignedCrews, isLoading } = useCrewCommonApiRequest();

const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);
const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});


watch(() => props.form, (value) => {
  if(value){
    props.form.ops_vessel_id = props.form?.ops_vessel_name?.id ?? '';
  }
}, {deep: true});


watch(() => props.form.opsVessel, (value) => {
  if(value){
    props.form.ops_vessel_name = value ?? '';
  }
});

watch(() => vesselAssignedCrews.value, (items) => {
  props.form.total_crews = items.length;
  items.forEach(function(item){
    props.form.crwAttendanceLines.push({
      crwCrewAssignment : item, 
      crw_crew_assignment_id : item.id,
      crw_crew_assignment_name : item,
      crw_crew_id : item.crwCrewProfile.id,
      attendance_line_composite : "BDCGP",
      present_days : "",
      absent_days : '',
      payable_days : 0,
    })
  }); 
  // console.log(vesselAssignedCrews); 
});
function vesselChanged(){
  props.form.crwAttendanceLines = []; 
  getVesselAssignedCrews(props.form.ops_vessel_name.id); 
}

function setPresentDays(){
  props.form.working_days = Math.min(props.form.working_days, 31);
  props.form.crwAttendanceLines?.forEach((item,index) => {
    props.form.crwAttendanceLines[index].present_days = props.form.working_days;
    props.form.crwAttendanceLines[index].payable_days = parseFloat(props.form.crwAttendanceLines[index].present_days) - parseFloat(props.form.crwAttendanceLines[index].absent_days);
  });
}

function calculatePayableDays(index){
  if(parseFloat(props.form.crwAttendanceLines[index].absent_days) > parseFloat(props.form.working_days)){
    props.form.crwAttendanceLines[index].absent_days = props.form.working_days;
  }
  //props.form.crwAttendanceLines[index].absent_days = Math.min(props.form.crwAttendanceLines[index].absent_days,props.form.working_days) ?? '';
  props.form.crwAttendanceLines[index].payable_days = parseFloat(props.form.working_days) - parseFloat(props.form.crwAttendanceLines[index].absent_days);
  props.form.crwAttendanceLines[index].present_days = parseFloat(props.form.working_days) - parseFloat(props.form.crwAttendanceLines[index].absent_days);
}

onMounted(() => {
  watchEffect(() => {
    getVesselsWithoutPaginate(props.form.business_unit);
  });  
});

</script>

<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model.trim="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300"> Vessel Name <span class="text-red-500">*</span></span>
      <v-select :options="vessels" :loading="isLoading" placeholder="--Choose an option--" v-model="form.ops_vessel_name" label="name" class="block form-input" @update:modelValue="vesselChanged">
        <template #search="{attributes, events}">
          <input
              class="vs__search"
              :required="!form.ops_vessel_name"
              v-bind="attributes"
              v-on="events"
          />
        </template>
      </v-select>
    </label>

    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300"> Total Crew</span>
      <input type="text" :value="form.total_crews" class="form-input vms-readonly-input" autocomplete="off" required readonly/>
    </label>

    <div class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300"> Month - Year <span class="text-red-500">*</span></span>
      <VueDatePicker v-model.trim="form.year_month" month-picker class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="mm/yyyy" format="MMMM/yyyy" model-type="yyyy-MM" :text-input="{ format: dateFormat }"></VueDatePicker>
    </div>
    
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300"> Working Days <span class="text-red-500">*</span></span>
      <input type="number" v-model.trim="form.working_days" min="0" max="31" placeholder="Working Days" class="form-input" autocomplete="off" @input="setPresentDays" required />
    </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <RemarksComponent v-model.trim="form.remarks" :maxlength="500" :fieldLabel="'Remarks'"></RemarksComponent>
  </div>

  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
    <legend class="px-2 text-gray-700 dark-disabled:text-gray-300"> Assigned Crew List </legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
          <th class="px-4 py-3 align-bottom no-wrap"> Crew Name </th>
          <th class="px-4 py-3 align-bottom no-wrap"> Crew Contact </th>
          <th class="px-4 py-3 align-bottom no-wrap"> Onboard Position </th>
          <th class="px-4 py-3 align-bottom no-wrap w-24"> Present Days <span class="text-red-500">*</span></th>
          <th class="px-4 py-3 align-bottom no-wrap w-24"> Absent Days <span class="text-red-500">*</span></th>
          <th class="px-4 py-3 align-bottom no-wrap w-24"> Payable Days <span class="text-red-500">*</span></th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
        <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(crwAttendanceLine, index) in form.crwAttendanceLines" :key="crwAttendanceLine.id">
          <td class="px-1 py-1">
            <input type="text" :value="form.crwAttendanceLines[index]?.crwCrewAssignment?.crwCrewProfile?.full_name" class="form-input vms-readonly-input" autocomplete="off" readonly/>
          </td>
          <td class="px-1 py-1">
            <input type="text" :value="form.crwAttendanceLines[index]?.crwCrewAssignment?.crwCrewProfile?.pre_mobile_no" class="form-input vms-readonly-input" autocomplete="off" readonly/>
          </td>
          <td class="px-1 py-1">
            <input type="text" :value="form.crwAttendanceLines[index]?.crwCrewAssignment?.position_onboard" class="form-input vms-readonly-input" autocomplete="off" readonly/>
          </td>
          <td class="px-1 py-1">
            <input type="number" v-model.trim="form.crwAttendanceLines[index].present_days" min="0" max="31" class="form-input vms-readonly-input" autocomplete="off" required readonly />
          </td>
          <td class="px-1 py-1">
            <input type="number" v-model.trim="form.crwAttendanceLines[index].absent_days" min="0" max="31" class="form-input" autocomplete="off" @input="calculatePayableDays(index)" required />
          </td>
          <td class="px-1 py-1">
            <input type="number" v-model.trim="form.crwAttendanceLines[index].payable_days" min="0" class="form-input vms-readonly-input" autocomplete="off" readonly required />
          </td>
        </tr>
      </tbody>
      <tfoot v-if="!form.crwAttendanceLines.length">
      <tr v-if="isLoading">
        <td colspan="6">Loading...</td>
      </tr>
      <tr v-else-if="!form.crwAttendanceLines.length">
        <td colspan="6">No data found.</td>
      </tr>
      </tfoot>
    </table>
  </fieldset>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}
</style>