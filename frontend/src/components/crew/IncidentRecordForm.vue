<script setup>
import Error from "../Error.vue";
import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {onMounted, ref, watch, watchEffect} from "vue";
import Store from "../../store";
import useVessel from "../../composables/operations/useVessel";
const { vessels, getVesselsWithoutPaginate, isLoading } = useVessel();
const { crews, getCrews } = useCrewCommonApiRequest();
import ErrorComponent from '../utils/ErrorComponent.vue';
import RemarksComponent from "../utils/RemarksComponent.vue";
import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();

const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);
const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const selectedFile = (event) => {
  props.form.attachment = event.target.files[0];
};

// watch(() => props.form, (value) => {
//   if(value){
//     props.form.ops_vessel_id = props.form?.ops_vessel_name?.id ?? '';
//     props.form.ops_vessel_name = value?.opsVessel;
//     value?.crwIncidentParticipants?.forEach((line, index) => {
//       props.form.crwIncidentParticipants[index].crw_crew_id = props.form.crwIncidentParticipants[index]?.crw_crew_name?.id ?? '';
//
//       props.form.crwIncidentParticipants[index].crw_crew_name = value?.crwIncidentParticipants[index]?.crwCrew ?? '';
//
//       props.form.crwIncidentParticipants[index].crw_crew_rank = props.form.crwIncidentParticipants[index].crw_crew_name?.crwCurrentRank?.name ?? '';
//     });
//   }
// }, {deep: true});

function addItem() {
  let obj = {
    crw_crew_id: '',
    crw_crew_name: '',
    crw_crew_rank: '',
    injury_status: '',
    notes: '',
    isCrewNameDuplicate: false,
  };
  props.form.crwIncidentParticipants.push(obj);
}

function removeItem(index){
  props.form.crwIncidentParticipants.splice(index, 1);
}

watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;
  if(newValue !== oldValue && oldValue != ''){
    props.form.ops_vessel_name = '';
  }
});

function changeVessel(){
  props.form.ops_vessel_id = props.form?.ops_vessel_name?.id ?? '';
}

function changeCrew(index){
  props.form.crwIncidentParticipants[index].crw_crew_id = props.form.crwIncidentParticipants[index]?.crw_crew_name?.id ?? '';
  props.form.crwIncidentParticipants[index].crw_crew_contact = props.form.crwIncidentParticipants[index]?.crw_crew_name?.pre_mobile_no ?? '';
}

onMounted(() => {
  watchEffect(() => {
    getVesselsWithoutPaginate(props.form.business_unit);
    getCrews(props.form.business_unit);
  });
});

</script>

<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model.trim="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
        <v-select :options="vessels" :loading="isLoading" placeholder="--Choose an option--" v-model.trim="form.ops_vessel_name" @update:modelValue="changeVessel" label="name" class="block form-input">
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
        <span class="text-gray-700 dark-disabled:text-gray-300">Incident Date & Time <span class="text-red-500">*</span></span>
        <VueDatePicker v-model.trim="form.date_time" utc class="form-input" required auto-apply  :enable-time-picker = "true" placeholder="Select Date Time" :text-input="{ format: dateFormat }"></VueDatePicker>
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Incident Type <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.type" placeholder="Ex: Collision, Robbery" class="form-input" autocomplete="off" required />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Incident Location <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.location" placeholder="Ex: Deck, Engine room" class="form-input" autocomplete="off" required />
      </label>
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Reported Person<span class="text-red-500">*</span></span>
      <input type="text" v-model.trim="form.reported_by" placeholder="Reported Person" class="form-input" autocomplete="off" required />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300 text-sm font-medium text-gray-900 dark-disabled:text-white">Attachment </span>
      <input @change="selectedFile" class="block form-input text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark-disabled:text-gray-400 focus:outline-none dark-disabled:bg-gray-700 dark-disabled:border-gray-600 dark-disabled:placeholder-gray-400" type="file">
    </label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <RemarksComponent v-model.trim="form.description" :maxlength="500" :fieldLabel="'Description'" :isRequired="true"></RemarksComponent>
<!--      <textarea v-model.trim="form.description" placeholder="Type here....." class="form-input" autocomplete="off" required></textarea>-->
    </label>
  </div>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
    <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Participant List</legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
        <th class="px-4 py-3 w-2/6 align-bottom">Crew Name <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 w-1/6 align-bottom">Contact No. <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 w-1/6 align-bottom">Injury Status <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Notes</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
      <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(incidentParticipant, index) in form.crwIncidentParticipants" :key="incidentParticipant.id">
        <td class="px-1 py-1">
          <div style="position: relative;">
            <v-select :options="crews" :loading="isLoading" placeholder="--Choose an option--" v-model.trim="form.crwIncidentParticipants[index].crw_crew_name" label="full_name" @update:modelValue="changeCrew(index)" class="block form-input">
              <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.crwIncidentParticipants[index].crw_crew_name"
                    v-bind="attributes"
                    v-on="events"
                />
              </template>
            </v-select>
            <span
                v-show="incidentParticipant.isCrewNameDuplicate"
                class="text-yellow-600 pl-3"
                title="Duplicate Rank Name"
                v-html="icons.ExclamationTriangle"
                style="position: absolute; top: 50%; transform: translateY(-50%); right: 30px;"
            ></span>
          </div>
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model.trim="form.crwIncidentParticipants[index].crw_crew_contact" placeholder="Contact No" class="form-input vms-readonly-input" autocomplete="off" disabled />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model.trim="form.crwIncidentParticipants[index].injury_status" placeholder="Injury Status" class="form-input" autocomplete="off" required />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model.trim="form.crwIncidentParticipants[index].notes" placeholder="Notes" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1 text-center">
          <button v-if="index!==0" type="button" @click="removeItem(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <button v-else type="button" @click="addItem()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </td>
      </tr>
      </tbody>
    </table>
  </fieldset>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
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