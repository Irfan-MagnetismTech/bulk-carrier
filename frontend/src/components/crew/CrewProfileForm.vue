<script setup>
import Error from "../Error.vue";
import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {onMounted, ref, watch, watchEffect} from "vue";
import Store from "../../store";
import useVessel from "../../composables/operations/useVessel";
const { vessels, getVesselsWithoutPaginate } = useVessel();
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
    props.form.ops_vessel_id = props.form?.ops_vessel_name?.id ?? '';
    value?.crwIncidentParticipants?.forEach((line, index) => {
      props.form.crwIncidentParticipants[index].crw_crew_id = props.form.crwIncidentParticipants[index]?.crw_crew_name?.id ?? '';
      props.form.crwIncidentParticipants[index].crw_crew_rank = props.form.crwIncidentParticipants[index].crw_crew_name?.crwRank?.name ?? '';
    });
  }
}, {deep: true});

function addItem() {
  let obj = {
    crw_crew_id: '',
    crw_crew_name: '',
    crw_crew_rank: '',
    injury_status: '',
    notes: '',
  };
  props.form.crwIncidentParticipants.push(obj);
}

function removeItem(index){
  props.form.crwIncidentParticipants.splice(index, 1);
}

const openTab = ref(1);
const toggleTabs = (tabNumber, buttonType = null) => {
  if(buttonType === 'back') {
    openTab.value = tabNumber;
  } else {
    // check required fields is empty or not
    if (openTab.value === 1) {
      if (props.form.customer_code === "" || props.form.customer_name === "" || props.form.company_name === "" || props.form.country === "" || props.form.similar_codes === "") {

        // form validation start for customer code start
        if(props.form.customer_code === ""){
          document.getElementById('customer_code').classList.add('vms-required-input-border');
        }else{
          document.getElementById('customer_code').classList.remove('vms-required-input-border');
        }

        if(props.form.company_name === ""){
          document.getElementById('company_name').classList.add('vms-required-input-border');
        }else{
          document.getElementById('company_name').classList.remove('vms-required-input-border');
        }

        if(props.form.country === ""){
          document.getElementById('country').classList.add('vms-required-input-border');
        }else{
          document.getElementById('country').classList.remove('vms-required-input-border');
        }
        // form validation start for customer code end

        if(buttonType === 'next') {
          notification.showError(422,'','Please fill all required fields');
        }
        return;
      }
    }
    if(openTab.value === 2) {
      if (props.form.customer_general_email === "") {

        if(props.form.customer_general_email === ""){
          document.getElementById('customer_general_email').classList.add('vms-required-input-border');
        }else{
          document.getElementById('customer_general_email').classList.remove('vms-required-input-border');
        }
        // return with a message
        if(buttonType === 'next') {
          notification.showError(422,'','Please fill all required fields');
        }
        return;
      }
    }
    openTab.value = tabNumber;
  }
}

onMounted(() => {
  props.form.business_unit = businessUnit.value;
  watchEffect(() => {
    getVesselsWithoutPaginate(props.form.business_unit);
    getCrews(props.form.business_unit);
  });
});

</script>

<template>
  <div class="dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px border-b">
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(1)" v-bind:class="{'text-purple-600 bg-white': openTab !== 1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 1}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Personal
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(2)" v-bind:class="{'text-purple-600 bg-white': openTab !== 2, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 2}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Education
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(3)" v-bind:class="{'text-purple-600 bg-white': openTab !== 3, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 3}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Training
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(4)" v-bind:class="{'text-purple-600 bg-white': openTab !== 4, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 4}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Experience
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(5)" v-bind:class="{'text-purple-600 bg-white': openTab !== 5, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 5}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Others
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(6)" v-bind:class="{'text-purple-600 bg-white': openTab !== 6, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 6}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>References
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(7)" v-bind:class="{'text-purple-600 bg-white': openTab !== 7, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 7}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Nominees
        </a>
      </li>
    </ul>
    <div v-on:click="toggleTabs(1)" v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Personal Info</legend>
        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Incident Date & Time <span class="text-red-500">*</span></span>
            <select class="form-input" v-model="form.hired_by">
              <option value="" disabled selected>Select</option>
              <option value="Agency">Agency</option>
              <option value="Company">Company</option>
            </select>
            <Error v-if="errors?.hired_by" :errors="errors.hired_by" />
          </label>
        </div>
      </fieldset>
    </div>
  </div>
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