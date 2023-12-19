<script setup>
import Error from "../Error.vue";
import ActionButton from '../../components/buttons/ActionButton.vue';
import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import {onMounted, ref, watch, watchEffect} from "vue";
import Store from "../../store";
import useCrewDocument from "../../composables/crew/useCrewDocument";
const { crews, getCrews, crewDocuments, getCrewDocuments, getCrewDocumentRenewals, crewDocumentRenewals, isCrewDocumentRenewModalOpen, isLoading } = useCrewCommonApiRequest();
const { isCrewDocumentAddModalOpen, storeCrewDocument, storeCrewRenewDocument, updateCrewRenewDocument, currentCrewDocRenewData, deleteCrewRenewDocument, deleteCrewDocument, isDocumentEditModal, errors } = useCrewDocument();
import env from '../../config/env';
import Swal from "sweetalert2";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
});
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

let renewFormData = ref({
  crw_crew_document_id: '',
  issue_date: '',
  expire_date: '',
  reference_no: '',
  attachment: null,
});

function closeCrewDocumentAddModal(){
  isDocumentEditModal.value = 0;
  isCrewDocumentAddModalOpen.value = 0;
  resetCrewDocumentModalData();
}

watch(() => isCrewDocumentAddModalOpen.value, () => {
  if(!isCrewDocumentAddModalOpen.value){
    resetCrewDocumentModalData();
  }
});


function resetCrewDocumentModalData(){
  props.form.id = '';
  props.form.document_name = '';
  props.form.issuing_authority = '';
  props.form.validity_period = '';
  props.form.validity_period_in_month = '';
  props.form.issue_date = null;
  props.form.expire_date = null;
  props.form.reference_no = '';
  props.form.attachment = null;
}

watch(() => props.form.business_unit, (newValue, oldValue) => {
  businessUnit.value = newValue;
  if(newValue !== oldValue && oldValue != ''){
    props.form.crw_crew_name = null;
  }
});

watch(() => props.form, (value) => {
  if(value){
    props.form.crw_crew_id = props.form?.crw_crew_name?.id ?? '';
    props.form.crw_crew_profile_id = props.form?.crw_crew_name?.id ?? '';
    props.form.crw_crew_rank = props.form?.crw_crew_name?.crwCurrentRank?.name ?? '';
    props.form.crw_crew_contact = props.form?.crw_crew_name?.contact ?? '';
    props.form.crw_crew_email = props.form?.crw_crew_name?.email ?? '';
    props.form.validity_period_in_month = props.form?.validity_period ?? '';
  }
}, {deep: true});

function openCrewDocumentAddModal(){
  if(props.form.business_unit === "ALL"){
    alert("Please select a Business Unit")
    return;
  }
  if(!props.form.crw_crew_id){
    alert("Please select a Crew")
    return;
  }
  isCrewDocumentAddModalOpen.value = 1;
}

function showCrewDocumentRenewModal(crwDocumentId){
  if(crwDocumentId){
    getCrewDocumentRenewals(crwDocumentId);
  }
  renewFormData.value.crw_crew_document_id = crwDocumentId;
}

function closeCrewDocumentRenewModal(){
  isCrewDocumentRenewModalOpen.value = 0;
  currentCrewDocRenewData.value = null;
  resetCrewDocumentRenewModalData();
}

function resetCrewDocumentRenewModalData(){
  renewFormData.value.issue_date = '';
  renewFormData.value.expire_date = '';
  renewFormData.value.reference_no = '';
  renewFormData.value.attachment = null;
}

const selectedFile = (event) => {
  props.form.attachment = event.target.files[0];
};

const selectedRenewFile = (event) => {
  renewFormData.value.attachment = event.target.files[0];
};

function selectedRenewUpdateFile(index,event){
  crewDocumentRenewals.value[index].attachment = event.target.files[0];
}

function saveRenewData(){
  storeCrewRenewDocument(renewFormData.value,crewDocumentRenewals.value);
}

function updateCrewDocumentRenewData(singleRenewData){
  updateCrewRenewDocument(singleRenewData);
}

function deleteCrewDocumentRenewData(renewDataId,renewDataIndex){
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this item!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteCrewRenewDocument(renewDataId,renewDataIndex,crewDocumentRenewals.value);
    }
  })
}

//let isDocumentEditModal = ref(0);

function updateDocumentBasicData(crwDocumentData){
  props.form.id = crwDocumentData?.id;
  props.form.document_name = crwDocumentData?.document_name;
  props.form.issuing_authority = crwDocumentData?.issuing_authority;
  props.form.validity_period = crwDocumentData?.validity_period_in_month;
  props.form.validity_period_in_month = crwDocumentData?.validity_period_in_month;
  isDocumentEditModal.value = 1;
  isCrewDocumentAddModalOpen.value = 1;
}

function deleteDocumentBasicData(crwDocumentDataId,index){
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to change delete this item!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteCrewDocument(crwDocumentDataId,index,crewDocuments.value);
    }
  })
}

onMounted(() => {
  watchEffect(() => {
    getCrews(props.form.business_unit);
    getCrewDocuments(props.form.business_unit,props.form.crw_crew_profile_id);
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
        <span class="text-gray-700 dark-disabled:text-gray-300">Crew Name <span class="text-red-500">*</span></span>
        <v-select :loading="isLoading" :options="crews" placeholder="--Choose an option--" v-model.trim="form.crw_crew_name" label="full_name" class="block form-input">
          <template #search="{attributes, events}">
            <input
                class="vs__search"
                :required="!form.crw_crew_name"
                v-bind="attributes"
                v-on="events"
            />
          </template>
        </v-select>
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Rank</span>
        <input type="text" v-model.trim="form.crw_crew_rank" placeholder="Rank" class="form-input vms-readonly-input" autocomplete="off" required />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Contact</span>
        <input type="text" :value="form?.crw_crew_name?.pre_mobile_no" placeholder="Contact" class="form-input vms-readonly-input" autocomplete="off" required />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Email</span>
        <input type="text" :value="form?.crw_crew_name?.pre_email" placeholder="Email" class="form-input vms-readonly-input" autocomplete="off" required />
      </label>
    </div>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
    <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Document List</legend>
    <table class="w-full" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
        <th class="w-1/6"><nobr>Document Name</nobr></th>
        <th><nobr>Issuing Authority</nobr></th>
        <th><nobr>Validity Period</nobr></th>
        <th><nobr>Latest <br>Issue Date</nobr></th>
        <th><nobr>Latest <br>Expire Date</nobr></th>
        <th><nobr>Reference No.</nobr></th>
        <th><nobr>Attachment</nobr></th>
        <th>
          <button type="button" @click="openCrewDocumentAddModal()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </th>
      </tr>
      </thead>

      <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
      <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(crwDocumentData, index) in crewDocuments" :key="crwDocumentData.id">
        <td>{{ crwDocumentData?.document_name }}</td>
        <td>{{ crwDocumentData?.issuing_authority }}</td>
        <td>
          <span class="custom_badge dark-disabled:bg-yellow-700 dark-disabled:text-yellow-100 text-black-700 bg-yellow-200">{{ crwDocumentData?.validity_period }}</span>
        </td>
        <td>{{ crwDocumentData?.crwCrewDocumentRenewals[0]?.issue_date ?? '---'}}</td>
        <td>{{ crwDocumentData?.crwCrewDocumentRenewals[0]?.expire_date ?? '---'}}</td>
        <td>{{ crwDocumentData?.crwCrewDocumentRenewals[0]?.reference_no ?? '---'}}</td>
        <td>
          <template v-if="crwDocumentData?.crwCrewDocumentRenewals[0]?.attachment">
            <a class="custom_link" :href="env.BASE_API_URL+'/'+crwDocumentData?.crwCrewDocumentRenewals[0]?.attachment" target="_blank">
              Link
            </a>
          </template>
          <template v-else>---</template>
        </td>
        <td class="px-1 py-1 text-center">
          <nobr>
            <a @click="showCrewDocumentRenewModal(crwDocumentData?.id)" :class="{'custom_renew_active_button': crwDocumentData?.validity_period_in_month,'custom_renew_disable_button': !crwDocumentData?.validity_period_in_month}" class="relative tooltip">
              <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
              </svg>
              <span class="tooltiptext">Renew</span>
            </a>
            <action-button :action="'edit'" @click="updateDocumentBasicData(crwDocumentData,index)"></action-button>
            <action-button :action="'delete'" @click="deleteDocumentBasicData(crwDocumentData?.id,index)"></action-button>
          </nobr>
        </td>
      </tr>
      </tbody>
      <tfoot v-if="!crewDocuments?.length">
      <tr v-if="isLoading">
        <td colspan="8">Loading...</td>
      </tr>
      <tr v-else-if="!crewDocuments?.length">
        <td colspan="8">No data found.</td>
      </tr>
      </tfoot>
    </table>
  </fieldset>
  <div v-show="isCrewDocumentAddModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="storeCrewDocument(form,crewDocuments)" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark-disabled:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark-disabled:hover:text-gray-200 hover: hover:text-gray-700"
                  aria-label="close" @click="closeCrewDocumentAddModal">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
              <path
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
          </button>
        </header>
        <!-- Modal body -->
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead>
          <tr style="background-color: #04AA6D;color: white"
              class="text-xs font-semibold tracking-wide text-gray-500 border-b dark-disabled:border-gray-700 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th colspan="5" v-if="!isDocumentEditModal">Add Crew Document</th>
            <th colspan="2" v-else>Update Crew Document</th>
          </tr>
          </thead>
        </table>

        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Basic Info</legend>
          <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Document Name <span class="text-red-500">*</span></span>
              <input type="text" v-model.trim="form.document_name" placeholder="Document Name" class="form-input" autocomplete="off" required />
            </label>
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Issuing Authority <span class="text-red-500">*</span></span>
              <input type="text" v-model.trim="form.issuing_authority" placeholder="Issuing Authority" class="form-input" autocomplete="off" required />
            </label>
          </div>
          <div v-if="!isDocumentEditModal" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Validity Period <span class="text-red-500">*</span></span>
              <select v-model.trim="form.validity_period" class="form-input" required>
                <option value="" disabled selected>Select</option>
                <option value="3">3 Months</option>
                <option value="6">6 Months</option>
                <option value="12">1 Year</option>
                <option value="24">2 Years</option>
                <option value="36">3 Years</option>
                <option value="48">4 Years</option>
                <option value="60">5 Years</option>
                <option value="120">10 Years</option>
                <option value="0">Permanent</option>
              </select>
            </label>
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Validity(In Months) <span class="text-red-500">*</span></span>
              <input type="text" v-model.trim="form.validity_period_in_month" placeholder="Ex: 60" class="form-input vms-readonly-input" readonly autocomplete="off" required />
            </label>
          </div>
        </fieldset>
        <fieldset v-if="!isDocumentEditModal" class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Validity Info</legend>
          <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Issue Date <span v-if="form.validity_period !== '0'" class="text-red-500">*</span></span>
              <input type="date" v-model.trim="form.issue_date" class="form-input" autocomplete="off" :required="form.validity_period !== '0'" />
            </label>
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Expire Date <span v-if="form.validity_period !== '0'" class="text-red-500">*</span></span>
              <input type="date" v-model.trim="form.expire_date" class="form-input" autocomplete="off" :required="form.validity_period !== '0'" />
            </label>
          </div>
          <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Reference No</span>
              <input type="text" v-model.trim="form.reference_no" placeholder="Reference No" class="form-input" autocomplete="off" />
            </label>
            <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Attachment</span>
              <input @change="selectedFile" type="file" class="form-input" autocomplete="off" />
            </label>
          </div>
        </fieldset>

        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark-disabled:bg-gray-800">
          <button type="button" @click="closeCrewDocumentAddModal" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            Cancel
          </button>
          <button
              class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Submit
          </button>
        </footer>
      </div>
    </form>
  </div>

  <div v-show="isCrewDocumentRenewModalOpen" class="fixed inset-0 z-30 flex items-end overflow-y-auto bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="" style="position: absolute;top: 0;">
      <div class="w-full px-6 py-4 overflow-y-auto bg-white rounded-t-lg dark-disabled:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button type="button"
                  class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark-disabled:hover:text-gray-200 hover: hover:text-gray-700"
                  aria-label="close" @click="closeCrewDocumentRenewModal">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
              <path
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
          </button>
        </header>
        <!-- Modal body -->
        <table class="w-full mb-2 whitespace-no-wrap border-collapse contract-assign-table table2">
          <thead v-once>
          <tr style="background-color: #04AA6D;color: white"
              class="text-xs font-semibold tracking-wide text-gray-500 border-b dark-disabled:border-gray-700 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
            <th colspan="5">Renew Crew Document</th>
          </tr>
          </thead>
        </table>
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Add Renew Data</legend>
          <table class="w-full whitespace-no-wrap" id="table">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="px-4 py-3 align-bottom">Issue Date <span class="text-red-500">*</span></th>
              <th class="px-4 py-3 align-bottom">Expire Date <span class="text-red-500">*</span></th>
              <th class="px-4 py-3 align-bottom">Reference No</th>
              <th class="px-4 py-3 align-bottom">Attachment</th>
              <th class="px-4 py-3 text-center align-bottom">Action</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <tr class="text-gray-700 dark-disabled:text-gray-400">
              <td class="px-1 py-1">
                <input type="date" v-model.trim="renewFormData.issue_date" class="form-input" autocomplete="off" required/>
              </td>
              <td class="px-1 py-1">
                <input type="date" v-model.trim="renewFormData.expire_date" class="form-input" autocomplete="off" required/>
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="renewFormData.reference_no" placeholder="Reference" class="form-input" autocomplete="off" />
              </td>
              <td class="px-1 py-1">
                <input type="file" @change="selectedRenewFile" class="form-input" autocomplete="off" />
              </td>
              <td class="px-1 py-1 text-center">
                <button type="button" @click="saveRenewData" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  Save
                </button>
              </td>
            </tr>
            </tbody>
          </table>
        </fieldset>
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Previous Renew Data</legend>
          <table class="w-full whitespace-no-wrap" id="table">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="px-4 py-3 align-bottom">Issue Date <span class="text-red-500">*</span></th>
              <th class="px-4 py-3 align-bottom">Expire Date <span class="text-red-500">*</span></th>
              <th class="px-4 py-3 align-bottom">Reference No</th>
              <th class="px-4 py-3 align-bottom"><nobr>Prev. Attachment</nobr></th>
              <th class="px-4 py-3 align-bottom">Attachment</th>
              <th class="px-4 py-3 text-center align-bottom">Action</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(renewData,renewDataIndex) in crewDocumentRenewals" :key="renewDataIndex">
              <td class="px-1 py-1">
                <input type="date" v-model.trim="crewDocumentRenewals[renewDataIndex].issue_date" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="date" v-model.trim="crewDocumentRenewals[renewDataIndex].expire_date" class="form-input" autocomplete="off" required />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="crewDocumentRenewals[renewDataIndex].reference_no" placeholder="Reference" class="form-input" autocomplete="off" />
              </td>
              <td class="px-1 py-1">
                <a type="button" v-if="crewDocumentRenewals[renewDataIndex]?.attachment" class="text-green-800" target="_blank" :href="env.BASE_API_URL+'/'+crewDocumentRenewals[renewDataIndex]?.attachment">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                  </svg>
                </a>
                <a v-else>---</a>
<!--                <template v-if="crewDocumentRenewals[renewDataIndex]?.attachment">-->
<!--                  <a class="custom_link" :href="env.BASE_API_URL+'/'+crewDocumentRenewals[renewDataIndex]?.attachment" target="_blank">-->
<!--                    Link-->
<!--                  </a>-->
<!--                </template>-->
<!--                <template v-else>-&#45;&#45;</template>-->
              </td>
              <td class="px-1 py-1">
                <input type="file" @change="selectedRenewUpdateFile(renewDataIndex,$event)" class="form-input" autocomplete="off" />
              </td>
              <td class="px-1 py-1 text-center">
                <div class="flex items-center gap-1">
                  <button type="button" @click="updateCrewDocumentRenewData(renewData)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Update
                  </button>
                  <button type="button" @click="deleteCrewDocumentRenewData(renewData.id,renewDataIndex)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
            </tbody>
          </table>
        </fieldset>
        <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark-disabled:bg-gray-800">
          <button type="button" @click="closeCrewDocumentRenewModal" style="color: #1b1e21"
                  class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark-disabled:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
            Cancel
          </button>
        </footer>
      </div>
    </form>
  </div>
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

#modal {
  max-width: 60rem;
}

</style>