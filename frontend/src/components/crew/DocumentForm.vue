<script setup>
import Error from "../Error.vue";
import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {onMounted, ref, watch, watchEffect} from "vue";
import Store from "../../store";
const { crews, getCrews } = useCrewCommonApiRequest();

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

watch(() => props.form, (value) => {
  if(value){
    props.form.crw_crew_id = props.form?.crw_crew_name?.id ?? '';
  }
}, {deep: true});

function addItem() {
  let obj = {
    issue_date: '',
    expire_date: '',
    reference_no: '',
    attachment: '',
  };
  props.form.CrwCrewDocumentRenewal.push(obj);
}

function removeItem(index){
  props.form.CrwCrewDocumentRenewal.splice(index, 1);
}

onMounted(() => {
  props.form.business_unit = businessUnit.value;
  watchEffect(() => {
    getCrews(props.form.business_unit);
  });
});

</script>

<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
        <v-select :options="vessels" placeholder="--Choose an option--"  v-model="form.ops_vessel_name" label="name" class="block form-input">
          <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.ops_vessel_name"
                v-bind="attributes"
                v-on="events"
            />
          </template>
        </v-select>
        <Error v-if="errors?.ops_vessel_name" :errors="errors.ops_vessel_name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Incident Date & Time <span class="text-red-500">*</span></span>
        <input type="datetime-local" v-model="form.date_time" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.date_time" :errors="errors.date_time" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Incident Type <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.type" placeholder="Ex: Collision, Robbery" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.type" :errors="errors.type" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Incident Location <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.location" placeholder="Ex: Deck, Engine room" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.location" :errors="errors.location" />
      </label>
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Reported Person<span class="text-red-500">*</span></span>
      <input type="text" v-model="form.reported_by" placeholder="Reporting person" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.reported_by" :errors="errors.reported_by" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300 text-sm font-medium text-gray-900 dark:text-white">Attachment </span>
      <input @change="selectedFile" class="block form-input text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file">
      <Error v-if="errors?.attachment" :errors="errors.attachment" />
    </label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Description <span class="text-red-500">*</span></span>
      <textarea v-model="form.description" placeholder="Type here....." class="form-input" autocomplete="off" required></textarea>
      <Error v-if="errors?.description" :errors="errors.description" />
    </label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Candidate List</legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 w-1/6 align-bottom">Crew Name <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 w-1/6 align-bottom">Rank <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 w-1/6 align-bottom">Injury Status <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Notes</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(incidentParticipant, index) in form.crwIncidentParticipants" :key="incidentParticipant.id">
        <td class="px-1 py-1">
          <v-select :options="crews" placeholder="--Choose an option--" v-model="form.crwIncidentParticipants[index].crw_crew_name" label="name" class="block form-input">
            <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.crwIncidentParticipants[index].crw_crew_name"
                  v-bind="attributes"
                  v-on="events"
              />
            </template>
          </v-select>
          <Error v-if="errors?.crwIncidentParticipants[index].crw_crew_name" :errors="errors.crwIncidentParticipants[index].crw_crew_name" />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.crwIncidentParticipants[index].crw_crew_rank" placeholder="Crew rank" class="form-input vms-readonly-input" autocomplete="off" required />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.crwIncidentParticipants[index].injury_status" placeholder="Injury status" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.crwIncidentParticipants[index].notes" placeholder="Notes" class="form-input" autocomplete="off" />
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