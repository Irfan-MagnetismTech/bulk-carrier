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
const { vessels, getVesselsWithoutPaginate } = useVessel();
const { recruitmentApprovals, getRecruitmentApprovals, crwRankLists, getCrewRankLists, crwAgencies, getCrewAgencyLists, isLoading } = useCrewCommonApiRequest();
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
const selectedFile = (event) => {
  props.form.attachment = event.target.files[0];
};

const profilePicture = (event) => {
  props.form.picture = event.target.files[0];
};

watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;
  if(newValue !== oldValue && oldValue != ''){
    props.form.crw_recruitment_approval_id = null;
    props.form.crw_recruitment_approval_name = null;
    props.form.agency_id = null;
    props.form.agency_name = null;
  }
});

watch(() => props.form, (value) => {
  if(value){
    props.form.crw_recruitment_approval_id = props.form?.crw_recruitment_approval_name?.id ?? null;
    props.form.agency_id = props.form?.agency_name?.id ?? null;

    if(props.form.hired_by === 'Company'){
      props.form.agency_name = null;
      props.form.agency_id = null;
    }
  }
}, {deep: true});

function addEducationItem() {
  let obj = {
    exam_title: '',
    major: '',
    institute: '',
    result: '',
    passing_year: '',
    duration: '',
    achievement: '',
  };
  props.form.educations.push(obj);
}

function removeEducationItem(index){
  props.form.educations.splice(index, 1);
}

function addTrainingItem() {
  let obj = {
    training_title: '',
    covered_topic: '',
    year: '',
    institute: '',
    duration: '',
    location: '',
  };
  props.form.trainings.push(obj);
  if(props.form.trainings.length === 1){
    props.form.is_training_data_required = true;
  }
}

function removeTrainingItem(index){
  if(props.form.trainings.length === 1){
    props.form.is_training_data_required = false;
  }
  props.form.trainings.splice(index, 1);
}

function addExperienceItem() {
  let obj = {
    employer_name: '',
    from_date: '',
    till_date: '',
    last_designation: '',
    reason_for_leave: '',
  };
  props.form.experiences.push(obj);
  if(props.form.experiences.length === 1){
    props.form.is_experience_data_required = true;
  }
}

function removeExperienceItem(index){
  if(props.form.experiences.length === 1){
    props.form.is_experience_data_required = false;
  }
  props.form.experiences.splice(index, 1);
}

function addLanguageItem() {
  let obj = {
    language_name: '',
    writing: '',
    reading: '',
    speaking: '',
    listening: '',
  };
  props.form.languages.push(obj);
  if(props.form.languages.length === 1){
    props.form.is_other_data_required = true;
  }
}

function removeLanguageItem(index){
  if(props.form.languages.length === 1){
    props.form.is_other_data_required = false;
  }
  props.form.languages.splice(index, 1);
}

function addReferenceItem() {
  let obj = {
    name: '',
    organization: '',
    designation: '',
    address: '',
    contact_office: '',
    contact_personal: '',
    email: '',
    relation: '',
  };
  props.form.references.push(obj);
  if(props.form.references.length === 1){
    props.form.is_reference_data_required = true;
  }
}

function removeReferenceItem(index){
  if(props.form.references.length === 1){
    props.form.is_reference_data_required = false;
  }
  props.form.references.splice(index, 1);
}

function addNomineeItem() {
  let obj = {
    name: '',
    profession: '',
    address: '',
    relationship: '',
    contact_no: '',
    email: '',
    is_relative: '',
  };
  props.form.nominees.push(obj);
  if(props.form.nominees.length === 1){
    props.form.is_nominee_data_required = true;
  }
}

function removeNomineeItem(index){
  if(props.form.nominees.length === 1){
    props.form.is_nominee_data_required = false;
  }
  props.form.nominees.splice(index, 1);
}

const openTab = ref(1);

function changeTab(tabNumber, buttonType = null){
  if(buttonType === 'back') {
    openTab.value = tabNumber;
  } else {
    if(buttonType !== null){
      if (openTab.value === 1) {
        let tab1Fields = ref(['business_unit','crw_recruitment_approval_name','hired_by','department_name','crw_rank_id','first_name','last_name','father_name','mother_name',
          'date_of_birth','gender','religion','marital_status','nationality','nid_no','pre_address','pre_city','pre_mobile_no','per_address','per_city','per_mobile_no']);

        // if (props.form.hired_by === 'agency' && !tab1Fields.value.includes('agency_name')) {
        //   tab1Fields.value.push('agency_name');
        // } else if (props.form.hired_by !== 'agency' && tab1Fields.value.includes('agency_name')) {
        //   tab1Fields = tab1Fields.value.filter(field => field !== 'agency_name');
        // }

        // if (props.form.hired_by === 'agency') {
        //   return [...tab1Fields, 'agency_name'];
        // } else {
        //   return tab1Fields;
        // }

        if(!checkValidation(openTab, tabNumber, props,tab1Fields)){
          return;
        }
      }
      if (openTab.value === 2) {
        let tab2Fields = ['exam_title','major','institute','result','passing_year','duration'];
        if (!checkValidation(openTab, tabNumber, props, tab2Fields)) {
          return;
        }
      }
      if (openTab.value === 3 && props.form.is_training_data_required) {
        let tab3Fields = ['training_title','covered_topic','year','institute','duration','location'];
        if (!checkValidation(openTab, tabNumber, props, tab3Fields)) {
          return;
        }
      }
      if (openTab.value === 4 && props.form.is_experience_data_required) {
        let tab4Fields = ['employer_name','from_date','till_date','last_designation'];
        if (!checkValidation(openTab, tabNumber, props, tab4Fields)) {
          return;
        }
      }
      if (openTab.value === 5 && props.form.is_other_data_required) {
        let tab5Fields = ['language_name','writing','reading','speaking','listening'];
        if (!checkValidation(openTab, tabNumber, props, tab5Fields)) {
          return;
        }
      }
      if (openTab.value === 6 && props.form.is_reference_data_required) {
        let tab6Fields = ['name','organization','designation','address','contact_personal','relation'];
        if (!checkValidation(openTab, tabNumber, props, tab6Fields)) {
          return;
        }
      }
      if (openTab.value === 7 && props.form.is_nominee_data_required) {
        let tab7Fields = ['name','profession','address','relationship','contact_no','is_relative'];
        if (!checkValidation(openTab, tabNumber, props, tab7Fields)) {
          return;
        }
      }
    }
    openTab.value = tabNumber;
  }
}

// function validateYear(e) {
//   const yearRegex = /^\d{4}$/;
//   if (!yearRegex.test(e.target.value)) {
//     alert("False");
//     return false;
//   } else {
//     alert("True");
//     return true;
//   }
// }

onMounted(() => {
  watchEffect(() => {
    getCrewRankLists(props.form.business_unit);
    getRecruitmentApprovals(props.form.business_unit);
    getCrewAgencyLists(props.form.business_unit);
  });
});

</script>

<template>
  <div class="dark-disabled:border-gray-700">
    <ul class="flex flex-wrap -mb-px border-b">
      <li class="mr-2">
        <a href="#" @click="changeTab(1)" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-bind:class="{'text-purple-600 bg-white': openTab !== 1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 1}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Personal <span class="ml-1 text-red-500">*</span>
        </a>
      </li>
      <li class="mr-2">
        <a href="#" @click="changeTab(2)" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-bind:class="{'text-purple-600 bg-white': openTab !== 2, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 2}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Education <span class="ml-1 text-red-500">*</span>
        </a>
      </li>
      <li class="mr-2">
        <a href="#" @click="changeTab(3)" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-bind:class="{'text-purple-600 bg-white': openTab !== 3, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 3}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Training
        </a>
      </li>
      <li class="mr-2">
        <a href="#" @click="changeTab(4)" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-bind:class="{'text-purple-600 bg-white': openTab !== 4, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 4}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Experience
        </a>
      </li>
      <li class="mr-2">
        <a href="#" @click="changeTab(5)" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-bind:class="{'text-purple-600 bg-white': openTab !== 5, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 5}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Others
        </a>
      </li>
      <li class="mr-2">
        <a href="#" @click="changeTab(6)" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-bind:class="{'text-purple-600 bg-white': openTab !== 6, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 6}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>References
        </a>
      </li>
      <li class="mr-2">
        <a href="#" @click="changeTab(7)" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-bind:class="{'text-purple-600 bg-white': openTab !== 7, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 7}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Nominees
        </a>
      </li>
    </ul>
    <div @click="changeTab(1)" v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark-disabled:text-gray-300">Personal Info</legend>
        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <business-unit-input v-model.trim="form.business_unit"></business-unit-input>
          <label class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>
        </div>
        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Recruitment Approval <span class="text-red-500">*</span></span>
            <v-select :loading="isLoading" :options="recruitmentApprovals" placeholder="--Choose an option--"  v-model.trim="form.crw_recruitment_approval_name" label="page_title" id="crw_recruitment_approval_name" class="block form-input">
              <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    id="crw_recruitment_approval_name"
                    :required="!form.crw_recruitment_approval_name"
                    v-bind="attributes"
                    v-on="events"
                />
              </template>
            </v-select>
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Hired By <span class="text-red-500">*</span></span>
            <select class="form-input" v-model.trim="form.hired_by" required id="hired_by">
              <option value="" selected disabled>--Choose an option--</option>
              <option value="Agency">Agency</option>
              <option value="Company">Company</option>
            </select>
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Agency Name <span v-if="form.hired_by==='Agency'" class="text-red-500">*</span></span>
            <v-select :loading="isLoading" :options="crwAgencies" placeholder="--Choose an option--" id="agency_name" v-model.trim="form.agency_name" label="agency_name" class="block form-input">
              <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    id="agency_name"
                    v-bind="attributes"
                    v-on="events"
                />
              </template>
            </v-select>
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Department <span class="text-red-500">*</span></span>
            <select class="form-input" v-model.trim="form.department_name" id="department_name" required>
              <option value="" selected disabled>--Choose an option--</option>
              <option value="Engine">Engine</option>
              <option value="Deck">Deck</option>
              <option value="Electrical">Electrical</option>
            </select>
          </label>
        </div>
        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Rank Name <span class="text-red-500">*</span></span>
            <select class="form-input" id="crw_rank_id" v-model.trim="form.crw_rank_id" required>
              <option value="" selected disabled>--Choose an option--</option>
              <option v-for="(crwRank,index) in crwRankLists" :value="crwRank.id">{{ crwRank?.name }}</option>
            </select>
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">First Name <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.first_name" id="first_name" placeholder="First Name" class="form-input" autocomplete="off" required />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Last Name <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.last_name" id="last_name" placeholder="Last Name" class="form-input" autocomplete="off" required />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Father's Name <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.father_name" id="father_name" placeholder="Father Name" class="form-input" autocomplete="off" required />
          </label>
        </div>
        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Mother's Name <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.mother_name" id="mother_name" placeholder="Mother Name" class="form-input" autocomplete="off" required />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Date of Birth <span class="text-red-500">*</span></span>
            <VueDatePicker v-model="form.date_of_birth" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Gender <span class="text-red-500">*</span></span>
            <select class="form-input" v-model.trim="form.gender" id="gender" required>
              <option value="" selected disabled>--Choose an option--</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Religion <span class="text-red-500">*</span></span>
            <select class="form-input" v-model.trim="form.religion" id="religion" required>
              <option value="" selected disabled>--Choose an option--</option>
              <option value="Islam">Islam</option>
              <option value="Hinduism">Hinduism</option>
              <option value="Buddhism">Buddhism</option>
              <option value="Christianity">Christianity</option>
              <option value="Other">Other</option>
            </select>
          </label>
        </div>
        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Marital Status <span class="text-red-500">*</span></span>
            <select class="form-input" v-model.trim="form.marital_status" id="marital_status" required>
              <option value="" selected disabled>--Choose an option--</option>
              <option value="Single">Single</option>
              <option value="Married">Married</option>
            </select>
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Nationality <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.nationality" id="nationality" placeholder="Ex: Bangladeshi" class="form-input" autocomplete="off" required />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">National ID <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.nid_no" id="nid_no" placeholder="Ex: 0001552005" class="form-input" autocomplete="off" required />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Passport No</span>
            <input type="text" v-model.trim="form.passport_no" id="passport_no" placeholder="Ex: 01522025" class="form-input" autocomplete="off" />
          </label>
        </div>
        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Passport Issue Date</span>
            <VueDatePicker v-model="form.passport_issue_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Blood Group</span>
            <select class="form-input" v-model.trim="form.blood_group">
              <option value="" selected disabled>--Choose an option--</option>
              <option value="A+">A+</option>
              <option value="A-">A-</option>
              <option value="B+">B+</option>
              <option value="B-">B-</option>
              <option value="AB+">AB+</option>
              <option value="AB-">AB-</option>
              <option value="O+">O+</option>
              <option value="O-">O-</option>
            </select>
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Height (Meters)</span>
            <input type="text" v-model.trim="form.height" placeholder="Ex: 69" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Weight (KG)</span>
            <input type="text" v-model.trim="form.weight" placeholder="65" class="form-input" autocomplete="off" />
          </label>
        </div>
        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Profile Picture</span>
            <input @change="profilePicture" class="block form-input text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark-disabled:text-gray-400 focus:outline-none dark-disabled:bg-gray-700 dark-disabled:border-gray-600 dark-disabled:placeholder-gray-400" type="file">
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Attachment</span>
            <input @change="selectedFile" class="block form-input text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark-disabled:text-gray-400 focus:outline-none dark-disabled:bg-gray-700 dark-disabled:border-gray-600 dark-disabled:placeholder-gray-400" type="file">
          </label>
          <label class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>
        </div>
      </fieldset>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark-disabled:text-gray-300">Present Contact Info</legend>
        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Address <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.pre_address" id="pre_address" placeholder="Present Address" class="form-input" autocomplete="off" required />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">City <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.pre_city" id="pre_city" placeholder="Ex: Chattogram" class="form-input" autocomplete="off" required />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Mobile No. <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.pre_mobile_no" id="pre_mobile_no" placeholder="Ex: 018-XXXXXXXX" class="form-input" autocomplete="off" required />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Email</span>
            <input type="email" v-model.trim="form.pre_email" placeholder="Email" class="form-input" autocomplete="off" />
          </label>
        </div>
      </fieldset>
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 uppercase dark-disabled:text-gray-300">Permanent Contact Info</legend>
        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Address <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.per_address" id="per_address" placeholder="Present Address" class="form-input" autocomplete="off" required />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">City <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.per_city" id="per_city" placeholder="Ex: Chattogram" class="form-input" autocomplete="off" required />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Mobile No. <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.per_mobile_no" id="per_mobile_no" placeholder="Ex: 018-XXXXXXXX" class="form-input" autocomplete="off" required />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Email</span>
            <input type="email" v-model.trim="form.per_email" placeholder="Email" class="form-input" autocomplete="off" />
          </label>
        </div>
      </fieldset>
    </div>
    <div @click="changeTab(2)" v-bind:class="{'hidden': openTab !== 2, 'block': openTab === 2}">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Educational Info</legend>
        <table class="w-full mt-2 whitespace-no-wrap" id="table">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="px-4 py-3 align-bottom"><nobr>Name of Degree <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Major <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Institute/University <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Result <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Passing Year <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Duration <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Achievement</nobr></th>
            <th class="px-4 py-3 text-center align-bottom w-14"><nobr>Action</nobr></th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(crewEducation, index) in form.educations" :key="crewEducation.id">
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.educations[index].exam_title" :id="'exam_title_'+index" placeholder="Ex: HSC/B.Sc" class="form-input" autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.educations[index].major" :id="'major_'+index" placeholder="Ex: Science" class="form-input" autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.educations[index].institute" :id="'institute_'+index" placeholder="Institute Name" class="form-input" autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.educations[index].result" :id="'result_'+index" placeholder="Ex: 3.50" class="form-input" autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="number" v-model.trim="form.educations[index].passing_year" :id="'passing_year_'+index" placeholder="Ex: 2013" class="form-input" autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.educations[index].duration" :id="'duration_'+index" placeholder="Ex: 4 years" class="form-input" autocomplete="off" required />
            </td>
            <td class="px-1 py-1">
              <input type="text" v-model.trim="form.educations[index].achievement" :id="'achievement_'+index" placeholder="Achievement" class="form-input" autocomplete="off" />
            </td>
            <td class="px-1 py-1 text-center">
              <button v-if="index!==0" type="button" @click="removeEducationItem(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button>
              <button v-else type="button" @click="addEducationItem()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </td>
          </tr>
          </tbody>
        </table>
      </fieldset>
    </div>
    <div @click="changeTab(3)" v-bind:class="{'hidden': openTab !== 3, 'block': openTab === 3}">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
          <span class="mr-2">Training Info</span>
<!--          <label class="relative inline-flex items-center cursor-pointer">-->
<!--            <input type="checkbox" v-model="form.is_training_data_required" class="sr-only peer">-->
<!--            <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>-->
<!--            <span class="ml-2 text-xs font-bold dark:text-gray-300" :class="form.is_training_data_required ? 'text-red-600' : 'text-green-800'">-->
<!--              <template v-if="form.is_training_data_required">Applicable</template>-->
<!--              <template v-else>Not Applicable</template>-->
<!--            </span>-->
<!--          </label>-->
        </legend>
        <table class="w-full mt-2 whitespace-no-wrap" id="table">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="px-4 py-3 align-bottom"><nobr>Training Title <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Covered Topics <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Training Year <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Institute <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Address <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Duration <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 text-center align-bottom w-14">
              <button type="button" @click="addTrainingItem()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <template v-if="form.is_training_data_required">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(crewTraining, index) in form.trainings" :key="crewTraining.id">
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.trainings[index].training_title" placeholder="Title" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.trainings[index].covered_topic" placeholder="Covered Topic" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.trainings[index].year" placeholder="Ex: 2018" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.trainings[index].institute" placeholder="Institute Name" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.trainings[index].location" placeholder="Address" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.trainings[index].duration" placeholder="Ex: 2 month" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1 text-center">
                <button type="button" @click="removeTrainingItem(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
              </td>
            </tr>
          </template>
          <template v-else>
            <tr class="text-gray-700 dark-disabled:text-gray-400">
              <td colspan="7">Not Applicable</td>
            </tr>
          </template>
          </tbody>
        </table>
      </fieldset>
    </div>
    <div @click="changeTab(4)" v-bind:class="{'hidden': openTab !== 4, 'block': openTab === 4}">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
          <span class="mr-2">Experience Info</span>
        </legend>
        <table class="w-full mt-2 whitespace-no-wrap" id="table">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="px-4 py-3 align-bottom"><nobr>Employer Name <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>From <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>To <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Last Designation <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Reason For Leave </nobr></th>
            <th class="px-4 py-3 text-center align-bottom w-14">
              <button type="button" @click="addExperienceItem()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <template v-if="form.is_experience_data_required">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(crewExperience, index) in form.experiences" :key="crewExperience.id">
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.experiences[index].employer_name" placeholder="Employer Name" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <VueDatePicker v-model="form.experiences[index].from_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
              </td>
              <td class="px-1 py-1">
                <VueDatePicker v-model="form.experiences[index].till_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.experiences[index].last_designation" placeholder="Ex: Master" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.experiences[index].reason_for_leave" placeholder="Leave Reason" class="form-input" autocomplete="off" />
              </td>
              <td class="px-1 py-1 text-center">
                <button type="button" @click="removeExperienceItem(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
              </td>
            </tr>
          </template>
          <template v-else>
            <tr class="text-gray-700 dark-disabled:text-gray-400">
              <td colspan="6">Not Applicable</td>
            </tr>
          </template>
          </tbody>
        </table>
      </fieldset>
    </div>
    <div @click="changeTab(5)" v-bind:class="{'hidden': openTab !== 5, 'block': openTab === 5}">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
          <span class="mr-2">Language Info</span>
<!--          <label class="relative inline-flex items-center cursor-pointer">-->
<!--            <input type="checkbox" v-model="form.is_other_data_required" class="sr-only peer">-->
<!--            <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>-->
<!--            <span class="ml-2 text-xs font-bold dark:text-gray-300" :class="form.is_other_data_required ? 'text-red-600' : 'text-green-800'">-->
<!--              <template v-if="form.is_other_data_required">Applicable</template>-->
<!--              <template v-else>Not Applicable</template>-->
<!--            </span>-->
<!--          </label>-->
        </legend>
        <table class="w-full mt-2 whitespace-no-wrap" id="table">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="px-4 py-3 align-bottom"><nobr>Language <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Writing <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Reading <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Speaking <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Listening <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 text-center align-bottom w-14">
              <button type="button" @click="addLanguageItem()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <template v-if="form.is_other_data_required">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(crewLanguage, index) in form.languages" :key="crewLanguage.id">
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.languages[index].language_name" placeholder="Ex: Bangla" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <select v-model.trim="form.languages[index].writing" class="form-input" required>
                  <option value="" selected disabled>--Choose an option--</option>
                  <option value="Average">Average</option>
                  <option value="Good">Good</option>
                  <option value="Excellent">Excellent</option>
                </select>
              </td>
              <td class="px-1 py-1">
                <select v-model.trim="form.languages[index].reading" class="form-input" required>
                  <option value="" selected disabled>--Choose an option--</option>
                  <option value="Average">Average</option>
                  <option value="Good">Good</option>
                  <option value="Excellent">Excellent</option>
                </select>
              </td>
              <td class="px-1 py-1">
                <select v-model.trim="form.languages[index].speaking" class="form-input" required>
                  <option value="" selected disabled>--Choose an option--</option>
                  <option value="Average">Average</option>
                  <option value="Good">Good</option>
                  <option value="Excellent">Excellent</option>
                </select>
              </td>
              <td class="px-1 py-1">
                <select v-model.trim="form.languages[index].listening" class="form-input" required>
                  <option value="" selected disabled>--Choose an option--</option>
                  <option value="Average">Average</option>
                  <option value="Good">Good</option>
                  <option value="Excellent">Excellent</option>
                </select>
              </td>
              <td class="px-1 py-1 text-center">
                <button type="button" @click="removeLanguageItem(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
              </td>
            </tr>
          </template>
          <template v-else>
            <tr class="text-gray-700 dark-disabled:text-gray-400">
              <td colspan="6">Not Applicable</td>
            </tr>
          </template>
          </tbody>
        </table>
      </fieldset>
    </div>
    <div @click="changeTab(6)" v-bind:class="{'hidden': openTab !== 6, 'block': openTab === 6}">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
          <span class="mr-2">Reference Info</span>
<!--          <label class="relative inline-flex items-center cursor-pointer">-->
<!--            <input type="checkbox" v-model="form.is_reference_data_required" class="sr-only peer">-->
<!--            <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>-->
<!--            <span class="ml-2 text-xs font-bold dark:text-gray-300" :class="form.is_reference_data_required ? 'text-red-600' : 'text-green-800'">-->
<!--              <template v-if="form.is_reference_data_required">Applicable</template>-->
<!--              <template v-else>Not Applicable</template>-->
<!--            </span>-->
<!--          </label>-->
        </legend>
        <table class="w-full mt-2 whitespace-no-wrap" id="table">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="px-4 py-3 align-bottom"><nobr>Name <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Organization <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Designation <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Address <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Contact No. <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Email </nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Relation <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 text-center align-bottom w-14">
              <button type="button" @click="addReferenceItem()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <template v-if="form.is_reference_data_required">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(crewReference, index) in form.references" :key="crewReference.id">
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.references[index].name" placeholder="Name" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.references[index].organization" placeholder="Organization" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.references[index].designation" placeholder="Ex: Sukani" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.references[index].address" placeholder="Address" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.references[index].contact_personal" placeholder="Contact No" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="email" v-model.trim="form.references[index].email" placeholder="Email" class="form-input" autocomplete="off" />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.references[index].relation" placeholder="Relation" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1 text-center">
                <button type="button" @click="removeReferenceItem(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
              </td>
            </tr>
          </template>
          <template v-else>
            <tr class="text-gray-700 dark-disabled:text-gray-400">
              <td colspan="8">Not Applicable</td>
            </tr>
          </template>
          </tbody>
        </table>
      </fieldset>
    </div>
    <div @click="changeTab(7)" v-bind:class="{'hidden': openTab !== 7, 'block': openTab === 7}">
      <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
          <span class="mr-2">Nominee Info</span>
<!--          <label class="relative inline-flex items-center cursor-pointer">-->
<!--            <input type="checkbox" v-model="form.is_nominee_data_required" class="sr-only peer">-->
<!--            <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>-->
<!--            <span class="ml-2 text-xs font-bold dark:text-gray-300" :class="form.is_nominee_data_required ? 'text-red-600' : 'text-green-800'">-->
<!--              <template v-if="form.is_nominee_data_required">Applicable</template>-->
<!--              <template v-else>Not Applicable</template>-->
<!--            </span>-->
<!--          </label>-->
        </legend>
        <table class="w-full mt-2 whitespace-no-wrap" id="table">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th class="px-4 py-3 align-bottom"><nobr>Name <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Profession <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Address <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Relationship <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Contact No. <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Email </nobr></th>
            <th class="px-4 py-3 align-bottom"><nobr>Is Relative <span class="text-red-500">*</span></nobr></th>
            <th class="px-4 py-3 text-center align-bottom w-14">
              <button type="button" @click="addNomineeItem()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </th>
          </tr>
          </thead>

          <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
          <template v-if="form.is_nominee_data_required">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(crewNominee, index) in form.nominees" :key="crewNominee.id">
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.nominees[index].name" placeholder="Name" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.nominees[index].profession" placeholder="Profession" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.nominees[index].address" placeholder="Address" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.nominees[index].relationship" placeholder="Relationship" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.nominees[index].contact_no" placeholder="Contact No." class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="email" v-model.trim="form.nominees[index].email" placeholder="Email" class="form-input" autocomplete="off" />
              </td>
              <td class="px-1 py-1">
                <select v-model.trim="form.nominees[index].is_relative" class="form-input" required>
                  <option value="" selected disabled>--Choose an option--</option>
                  <option value="0">No</option>
                  <option value="1">Yes</option>
                </select>
              </td>
              <td class="px-1 py-1 text-center">
                <button type="button" @click="removeNomineeItem(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
              </td>
            </tr>
          </template>
          <template v-else>
            <tr class="text-gray-700 dark-disabled:text-gray-400">
              <td colspan="8">Not Applicable</td>
            </tr>
          </template>
          </tbody>
        </table>
      </fieldset>
    </div>
  </div>
  <button v-if="openTab===7" type="submit" :disabled="isLoading" class="flex items-center justify-between float-right px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
    <span v-if="page==='create'">Create</span>
    <span v-else>Update</span>
  </button>
  <button type="button" v-else @click="changeTab(openTab + 1,'next')" :disabled="isLoading" class="flex items-center justify-between float-right px-4 py-2 mt-4 text-sm leading-5 text-white uppercase transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Next
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
      <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
      <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
    </svg>
  </button>
  <button type="button" v-if="openTab!==1" @click="changeTab(openTab - 1, 'back')" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white uppercase transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
      <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
    </svg>
    Back
  </button>
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