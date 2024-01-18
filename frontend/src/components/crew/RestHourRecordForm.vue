<script setup>
import Error from "../Error.vue";
import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import {onMounted, ref, watch, watchEffect} from "vue";
import Store from "../../store";
import useVessel from "../../composables/operations/useVessel";
import useCrewProfile from "../../composables/crew/useCrewProfile";
import Swal from "sweetalert2";
const { vessels, searchVessels, getVesselsWithoutPaginate, isLoading } = useVessel();
const { getVesselAssignedCrews, vesselAssignedCrews } = useCrewCommonApiRequest();
const { checkValidation } = useCrewProfile();
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
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);

watch(() => props.form, (value) => {
  if(value){
    props.form.ops_vessel_id = props.form?.ops_vessel_name?.id ?? '';
    props.form.ops_vessel_imo = props.form?.ops_vessel_name?.imo ?? '';
    props.form.ops_vessel_flag = props.form?.ops_vessel_name?.flag ?? '';
  }
}, {deep: true});

watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;
  if(newValue !== oldValue && oldValue != ''){
    props.form.ops_vessel_name = null;
    props.form.ops_vessel_id = '';
  }
});

function vesselChanged(){
  props.form.selectedCrews = [];
  getVesselAssignedCrews(props.form.ops_vessel_name.id);
}

watch(() => vesselAssignedCrews.value, (items) => {
  props.form.total_crews = items.length;
  items.forEach(function(item){
    props.form.selectedCrews.push({
      crw_full_name: item?.crwCrew?.full_name,
      crw_position_onboard: item?.position_onboard,
      crw_crew_assignment_id : item.id,
      crw_crew_id : item.crwCrew.id,
      service_start : '',
      comment : '',
      is_checked : false,
    })
  });
});

function toggleHourlyRecord(index) {
  if (props.form.hourlyRecords[index]) {
    props.form.hourlyRecords[index].hour = index;
    props.form.hourlyRecords[index].type = props.form.location_type;
    props.form.hourlyRecords[index].is_selected = !props.form.hourlyRecords[index].is_selected;
    if(!props.form.hourlyRecords[index].is_selected){
      props.form.hourlyRecords[index].type = '';
      props.form.hourlyRecords[index].hour = '';
    }
  }
}

function toggleCrewChecked(index){
  if (props.form.selectedCrews[index]) {
    props.form.selectedCrews[index].is_checked = !props.form.selectedCrews[index].is_checked;

    const allChecked = props.form.selectedCrews.every((crew) => crew.is_checked);
    props.form.is_all_crew_checked = allChecked;
  }
}

function toggleAllCrewChecked(e){
  props.form.is_all_crew_checked = !props.form.is_all_crew_checked;
  if(props.form.is_all_crew_checked){
    props.form.selectedCrews.forEach((crew) => {
      crew.is_checked = true;
    });
  } else {
    props.form.selectedCrews.forEach((crew) => {
      crew.is_checked = false;
    });
  }
}

let startHour = 0;
let endHour = 0;

function formatIndex(index) {
  if(index === 1){
    return '01';
  }
  index = Math.floor(index/2);
  if(index===0){
    startHour = String(index).padStart(2, '0');
    endHour = String(index + 1).padStart(2, '0');
  } else {
    startHour = endHour;
    endHour = String(index + 1).padStart(2, '0');
  }

  return `${startHour}`;
}


onMounted(() => {
  watchEffect(() => {
    getVesselsWithoutPaginate(props.form.business_unit);
  });

  for (let index = 0; index < 48; index++) {
    props.form.hourlyRecords.push(
        {
          'is_selected': false,
          'hour'       : '',
          'type'       : '',
        }
    );
  }
});

</script>

<template>
  <div class="grid lg:grid-cols-2 gap-2 md:grid-cols-1">
    <div>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark-disabled:text-gray-300">Basic Info</legend>
        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <business-unit-input v-model.trim="form.business_unit"></business-unit-input>
          <div class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Record Date<span class="text-red-500">*</span></span>
            <VueDatePicker v-model="form.record_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
          </div>
          <label class="block w-full mt-2 text-sm"></label>
        </div>
        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Name<span class="text-red-500">*</span></span>
            <v-select :options="vessels" :loading="isLoading" placeholder="--Choose an option--"  v-model.trim="form.ops_vessel_name" label="name" @update:modelValue="vesselChanged" class="block form-input">
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
            <span class="text-gray-700 dark-disabled:text-gray-300">IMO Number</span>
            <input type="text" v-model.trim="form.ops_vessel_imo" class="form-input vms-readonly-input" autocomplete="off" readonly/>
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Flag of Ship</span>
            <input type="text" v-model.trim="form.ops_vessel_flag" class="form-input vms-readonly-input" autocomplete="off" readonly/>
          </label>
        </div>
        <div v-if="form?.selectedCrews?.length" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <table class="w-full whitespace-no-wrap mt-2" id="table1">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th colspan="5">Crew List</th>
            </tr>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="px-4 py-3 align-bottom no-wrap">
                <input type="checkbox" @click="toggleAllCrewChecked" v-model.trim="form.is_all_crew_checked" class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
              </th>
              <th class="px-4 py-3 align-bottom no-wrap">Seafarer</th>
              <th class="px-4 py-3 align-bottom no-wrap">Position / Rank</th>
              <th class="px-4 py-3 align-bottom no-wrap">Service Start</th>
              <th class="px-4 py-3 align-bottom no-wrap">Comment</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(crwAttendanceLine, index) in form.selectedCrews" :key="crwAttendanceLine.id">
              <td class="px-1 py-1">
                <input type="checkbox" v-model.trim="form.selectedCrews[index].is_checked" @click="toggleCrewChecked(index)" class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
              </td>
              <td class="px-1 py-1">
                <input type="text" :value="form.selectedCrews[index].crw_full_name" class="form-input vms-readonly-input" autocomplete="off" readonly/>
              </td>
              <td class="px-1 py-1">
                <input type="text" :value="form.selectedCrews[index].crw_position_onboard" class="form-input vms-readonly-input" autocomplete="off" readonly/>
              </td>
              <td class="px-1 py-1">
                <input type="number" v-model.trim="form.selectedCrews[index].service_start" min="0" max="31" class="form-input vms-readonly-input" autocomplete="off" required readonly />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.selectedCrews[index].comment" placeholder="Comment.." class="form-input" autocomplete="off"/>
              </td>
            </tr>
            </tbody>
            <tfoot v-if="!form.selectedCrews.length">
            <tr v-if="isLoading">
              <td colspan="3">Loading...</td>
            </tr>
            <tr v-else-if="!form.selectedCrews.length">
              <td colspan="3">No data found.</td>
            </tr>
            </tfoot>
          </table>
        </div>
      </fieldset>
    </div>
    <div>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark-disabled:text-gray-300">Rest Hour Record</legend>
        <div v-if="form?.selectedCrews?.length">
          <div class="grid lg:grid-cols-1 gap-2 md:grid-cols-1">
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Location <span class="text-red-500">*</span></span>
              <select class="form-input" v-model.trim="form.location_type" required>
                <option value="X">Periods of Work at Sea (X)</option>
                <option value="P">Periods of Work in Port (P)</option>
                <option value="S">Proposed Periods of Work (S)</option>
              </select>
            </label>
          </div>
          <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
            <legend class="px-2 text-gray-700 uppercase dark-disabled:text-gray-300">Hourly Records</legend>
          <div class="grid lg:grid-cols-8 md:grid-cols-6 sm:grid-cols-3 flex items-center">
            <template v-for="(data,index) in form.hourlyRecords">
              <div class="!text-center" :class="{ 'mr-1': (index+1)%2===0 }">
                <span class="text-sm text-center">{{ formatIndex(index) }}</span>
                <hr class="border-t-2 border-gray-900">
                <input type="text" v-model.trim="form.hourlyRecords[index].type" :id="'hourly_input_'+index" @click="toggleHourlyRecord(index)" class="form-input vms-readonly-input text-center"
                       :class="data.is_selected ? 'active_hour' : ''"
                       autocomplete="off" readonly/>
              </div>
            </template>
          </div>
          </fieldset>
          <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm text-white bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Save</button>
        </div>
      </fieldset>
    </div>
  </div>
</template>

<style lang="postcss" scoped>
#table1 th,tr,td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.active_hour{
  background-color: #a5dc86;
}

.form-input:focus{
  outline: none !important;
  box-shadow: none;
  border-color: #707275;
}

</style>