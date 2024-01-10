<script setup>
import Error from "../Error.vue";
import cloneDeep from 'lodash/cloneDeep';
import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import usePayrollBatch from "../../composables/crew/usePayrollBatch";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {onMounted, ref, watch, watchEffect} from "vue";
import Store from "../../store";
import useVessel from "../../composables/operations/useVessel";
const { vessels, getVesselsWithoutPaginate, isLoading } = useVessel();
const { payrollBatch, monthlyAttendance, getMonthlyAttendance, isAttendanceCrewAvailable } = usePayrollBatch();
const { vesselWiseMonthlyAttendances, getVesselMonthlyAttendances } = useCrewCommonApiRequest();
const { crews, getCrews } = useCrewCommonApiRequest();
import useNotification from '../../composables/useNotification.js';
import ErrorComponent from '../utils/ErrorComponent.vue';
import RemarksComponent from "../utils/RemarksComponent.vue";
import useHeroIcon from "../../assets/heroIcon";
import Swal from "sweetalert2";
const icons = useHeroIcon();

const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);
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
const notification = useNotification();

const selectedFile = (event) => {
  props.form.attachment = event.target.files[0];
};

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

const openTab = ref(1);

function changeTab(tabNumber, buttonType = null){
  if(openTab.value === 1 && !isAttendanceCrewAvailable.value && props.page === 'create'){
    notification.showError(422, '', 'No assigned crew found');
    return;
  }
  if(buttonType === 'back') {
    openTab.value = tabNumber;
  } else {
    openTab.value = tabNumber;
  }
}

let hasAdditionBatchHead = ref(false);
let hasDeductionBatchHead = ref(false);

function addAdditionHead(){
  hasAdditionBatchHead.value = true;
  let objHead =
      {
        head_name: '',
        particular: '',
        amount: '',
        head_type: 'addition',
        isHeadNameDuplicate: false,
        id: Math.random().toString()
      };
  props.form.crwPayrollBatchHeads.push(objHead);

  if(props.form.crwPayrollBatchHeads?.filter(item => item.head_type === 'addition').length === 1){
    props.form.crwPayrollBatchLines.forEach((batchLine,batchLineIndex) => {
      let batchLineObj = {
        crew_id: batchLine?.crw_crew_id,
        crew_name: batchLine?.crw_full_name,
        crw_contact_no: batchLine?.crw_contact_no,
        amount: '',
        head_type: 'addition',
        crew_batch_heads: cloneDeep(props.form.crwPayrollBatchHeads?.filter(item => item.head_type === 'addition'))
      }
      props.form.crwPayrollBatchHeadLines.push(batchLineObj);
    });
  }
  else if(props.form.crwPayrollBatchHeads?.filter(item => item.head_type === 'addition').length > 1) {

    let additions = props.form.crwPayrollBatchHeadLines?.filter(item => item.head_type === 'addition')

    for(let addition of additions) {
      addition.crew_batch_heads.push({
        amount:"",
        head_name:"",
        particular:"",
        id: objHead.id,
        head_type:"addition"
      });
    }
  }
};

function removeAdditionItem(itemId) {
  const batchHeadIndex = props.form.crwPayrollBatchHeads.findIndex(item => item.id === itemId);

  if (batchHeadIndex !== -1) {
    props.form.crwPayrollBatchHeads.splice(batchHeadIndex, 1);
  }

  props.form.crwPayrollBatchHeadLines.forEach((value, batchHeadLineIndex) => {
    const lineBatchHeadIndex = value.crew_batch_heads.findIndex(item => item.id === itemId);

    if (lineBatchHeadIndex !== -1) {
      value.crew_batch_heads.splice(lineBatchHeadIndex, 1);
    }
  });

  if (!props.form.crwPayrollBatchHeads.some(item => item.head_type === 'addition')) {
    props.form.crwPayrollBatchHeadLines = props.form.crwPayrollBatchHeadLines?.filter(item => item.head_type !== 'addition');
    hasAdditionBatchHead.value = false;
  }
}

function addDeductionHead(){
  hasDeductionBatchHead.value = true;
  let objHead =
      {
        head_name: '',
        particular: '',
        amount: '',
        head_type: 'deduction',
        isHeadNameDuplicate: false,
        id: Math.random().toString()
      };
  props.form.crwPayrollBatchHeads.push(objHead);

  if(props.form.crwPayrollBatchHeads?.filter(item => item.head_type === 'deduction').length === 1){
    props.form.crwPayrollBatchLines.forEach((batchLine,batchLineIndex) => {
      let batchLineObj = {
        crew_id: batchLine?.crw_crew_id,
        crew_name: batchLine?.crw_full_name,
        crw_contact_no: batchLine?.crw_contact_no,
        amount: '',
        head_type: 'deduction',
        crew_batch_heads: cloneDeep(props.form.crwPayrollBatchHeads?.filter(item => item.head_type === 'deduction'))
      }
      props.form.crwPayrollBatchHeadLines.push(batchLineObj);
    });
  }
  else if(props.form.crwPayrollBatchHeads?.filter(item => item.head_type === 'deduction').length > 1) {
    let deductions = props.form.crwPayrollBatchHeadLines?.filter(item => item.head_type === 'deduction')

    for(let deduction of deductions) {
      deduction.crew_batch_heads.push({
        amount:"",
        head_name:"",
        particular:"",
        id: objHead.id,
        head_type:"deduction"
      });
    }
  }
};

function removeDeductionItem(itemId){

  const batchHeadIndex = props.form.crwPayrollBatchHeads.findIndex(item => item.id === itemId);

  if (batchHeadIndex !== -1) {
    props.form.crwPayrollBatchHeads.splice(batchHeadIndex, 1);
  }

  props.form.crwPayrollBatchHeadLines.forEach((value, batchHeadLineIndex) => {
    const lineBatchHeadIndex = value.crew_batch_heads.findIndex(item => item.id === itemId);

    if (lineBatchHeadIndex !== -1) {
      value.crew_batch_heads.splice(lineBatchHeadIndex, 1);
    }
  });

  if (!props.form.crwPayrollBatchHeads.some(item => item.head_type === 'deduction')) {
    props.form.crwPayrollBatchHeadLines = props.form.crwPayrollBatchHeadLines?.filter(item => item.head_type !== 'deduction');
    hasDeductionBatchHead.value = false;
  }

}

watch(() => monthlyAttendance.value, (value) => {
  if(value){
    props.form.crwPayrollBatchLines = value?.crwAttendanceLines;
    props.form.working_days = value?.working_days;
    props.form.total_crew = value?.crwAttendanceLines?.length;
  }
}, {deep: true});

function getAssignedCrewList(){
  if(props.form.business_unit && props.form.ops_vessel_id && props.form.crw_attendance_id && props.form.process_date && props.form.currency){
    let formData = {
      ops_vessel_id: props.form.ops_vessel_id,
      crw_attendance_id: props.form.crw_attendance_id
    }
    getMonthlyAttendance(formData);
  } else {
    Swal.fire({
      icon: "",
      title: "Correct Please!",
      html: "Please select all required field.",
      customClass: "swal-width",
    });
  }
}

function additionChangeHead(e,itemId){
  let additions = props.form.crwPayrollBatchHeadLines?.filter(item => item.head_type === 'addition')
  for(let addition of additions) {
    addition.crew_batch_heads.find(item => item.id === itemId).particular = e.target.value;
  }
}

function additionAmountSet(e,itemId){
  let additions = props.form.crwPayrollBatchHeadLines?.filter(item => item.head_type === 'addition')
  for(let addition of additions) {
    addition.crew_batch_heads.find(item => item.id === itemId).amount = e.target.value;

    let totalAddition = addition.crew_batch_heads.reduce((sum, item) => sum + parseFloat(item.amount || 0), 0);
    addition.amount = totalAddition.toFixed(2);
    props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === addition.crew_id).total_earnings = totalAddition.toFixed(2);

    let totalDeductions = parseFloat(props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === addition.crew_id).total_deductions);
    let payableAmount = parseFloat(props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === addition.crew_id).payable_amount);
    props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === addition.crew_id).net_payable_amount = (payableAmount+totalAddition-totalDeductions).toFixed(2);
  }
}

function deductionChangeHead(e,itemId){
  let deductions = props.form.crwPayrollBatchHeadLines?.filter(item => item.head_type === 'deduction')
  for(let deduction of deductions) {
    deduction.crew_batch_heads.find(item => item.id === itemId).particular = e.target.value;
  }
}

function deductionAmountSet(e,itemId){
  let deductions = props.form.crwPayrollBatchHeadLines?.filter(item => item.head_type === 'deduction')
  for(let deduction of deductions) {
    deduction.crew_batch_heads.find(item => item.id === itemId).amount = e.target.value;

    let totalDeduction = deduction.crew_batch_heads.reduce((sum, item) => sum + parseFloat(item.amount || 0), 0);
    deduction.amount = totalDeduction.toFixed(2);

    props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === deduction.crew_id).total_deductions = totalDeduction.toFixed(2);

    let totalEarnings = parseFloat(props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === deduction.crew_id).total_earnings);
    let payableAmount = parseFloat(props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === deduction.crew_id).payable_amount);
    props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === deduction.crew_id).net_payable_amount = (payableAmount+totalEarnings-totalDeduction).toFixed(2);
  }
}

function setAdditionBatchHeadAmount(payrollBatchHeadLineIndex){
  //props.form.payrollBatchHeadLines[payrollBatchHeadLineIndex].batch_heads

  const batchHeads = props.form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_batch_heads;

  const additionAmount = batchHeads.reduce((sum, item) => sum + parseFloat(item.amount || 0), 0);

  props.form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].amount = additionAmount.toFixed(2);

  props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === props.form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_id).total_earnings = additionAmount.toFixed(2);

  let totalDeductions = parseFloat(props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === props.form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_id).total_deductions);
  let payableAmount = parseFloat(props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === props.form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_id).payable_amount);
  props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === props.form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_id).net_payable_amount = (payableAmount + additionAmount - totalDeductions).toFixed(2);

}

function setDeductionBatchHeadAmount(payrollBatchHeadLineIndex){
  //props.form.payrollBatchHeadLines[payrollBatchHeadLineIndex].batch_heads

  const batchHeads = props.form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_batch_heads;

  const deductionAmount = batchHeads.reduce((sum, item) => sum + parseFloat(item.amount || 0), 0);

  props.form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].amount = deductionAmount.toFixed(2);

  props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === props.form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_id).total_deductions = deductionAmount.toFixed(2);

  let totalEarnings = parseFloat(props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === props.form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_id).total_earnings);
  let payableAmount = parseFloat(props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === props.form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_id).payable_amount);
  props.form.crwPayrollBatchLines.find(line => line.crw_crew_id === props.form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_id).net_payable_amount = (payableAmount+totalEarnings-deductionAmount).toFixed(2);
}

function resetFormData(){
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to reset the data !",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      props.form.ops_vessel_id =  '',
      props.form.ops_vessel_name=  '',
      props.form.crw_attendance_id =  '',
      props.form.compensation_type = 'salary',
      props.form.process_date = '',
      props.form.net_payment = 0,
      props.form.currency = 'BDT',
      props.form.working_days = 0,
      props.form.total_crew = 0,
      props.form.crwPayrollBatchLines = [],
      props.form.crwPayrollBatchHeads = [],
      props.form.crwPayrollBatchHeadLines = []
      isAttendanceCrewAvailable.value = false;
    }
  })
}

onMounted(() => {
  watchEffect(() => {
    getVesselsWithoutPaginate(props.form.business_unit);
    getVesselMonthlyAttendances(props.form.ops_vessel_id);
  });
});

</script>

<template>
  <template v-if="page==='create' && !isAttendanceCrewAvailable">
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input v-model.trim="form.business_unit"></business-unit-input>
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
        <span class="text-gray-700 dark-disabled:text-gray-300">Attendance Month <span class="text-red-500">*</span></span>
        <select v-model.trim="form.crw_attendance_id" class="form-input" autocomplete="off" required>
          <option value="">Select</option>
          <option :value="vesselMonthAttendance?.id" v-for="(vesselMonthAttendance,index) in vesselWiseMonthlyAttendances">{{ vesselMonthAttendance?.month_year_name }}</option>
        </select>
      </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Process Date <span class="text-red-500">*</span></span>
        <VueDatePicker v-model="form.process_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Currency <span class="text-red-500">*</span></span>
        <select class="form-input" v-model.trim="form.currency" required>
          <option value="BDT">BDT</option>
          <option value="USD">USD</option>
        </select>
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Working Days</span>
        <input type="text" v-model.trim="form.working_days" placeholder="Ex: 30" class="form-input vms-readonly-input" autocomplete="off" readonly />
      </label>
    </div>
  </template>
  <template v-else>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Business Unit <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.business_unit" class="form-input vms-readonly-input" autocomplete="off" readonly />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.ops_vessel_name.name" class="form-input vms-readonly-input" autocomplete="off" readonly />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Attendance Month<span class="text-red-500">*</span></span>
        <input type="text" :value="form?.crwAttendance?.month_year_name" class="form-input vms-readonly-input" autocomplete="off" readonly />
      </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Process Date <span class="text-red-500">*</span></span>
        <input type="date" v-model.trim="form.process_date" class="form-input vms-readonly-input" autocomplete="off" readonly />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Currency <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.currency" class="form-input vms-readonly-input" autocomplete="off" readonly />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Working Days <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.working_days" class="form-input vms-readonly-input" autocomplete="off" readonly />
      </label>
    </div>
  </template>
  <div v-if="page==='create'" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <button type="button" v-if="!isAttendanceCrewAvailable" @click="getAssignedCrewList" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Process</button>
      <button type="button" v-if="isAttendanceCrewAvailable" :class="{'cursor-not-allowed opacity-50': isAttendanceCrewAvailable, '': !isAttendanceCrewAvailable}" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Process</button>
      <button type="button" v-if="!isAttendanceCrewAvailable" :class="{'cursor-not-allowed opacity-50': !isAttendanceCrewAvailable, '': isAttendanceCrewAvailable}" class="px-3 py-1 ml-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-700 border border-transparent rounded-md active:bg-gray-600 hover:bg-gray-600 focus:outline-none focus:shadow-outline-purple">Reset</button>
      <button type="button" v-if="isAttendanceCrewAvailable" @click="resetFormData" class="px-3 py-1 ml-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-700 border border-transparent rounded-md active:bg-gray-600 hover:bg-gray-600 focus:outline-none focus:shadow-outline-purple">Reset</button>
    </label>
  </div>
  <template v-if="form?.crwPayrollBatchLines?.length">
    <div class="border-b-2 border-purple-700 mt-3"></div>
    <div class="dark-disabled:border-gray-700">
      <ul class="flex flex-wrap -mb-px border-b">
        <li class="mr-2">
          <a href="#" @click="changeTab(1)" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-bind:class="{'text-purple-600 bg-white': openTab !== 1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 1}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Attendance
          </a>
        </li>
        <li class="mr-2">
          <a href="#" @click="changeTab(2)" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-bind:class="{'text-purple-600 bg-white': openTab !== 2, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 2}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Addition
          </a>
        </li>
        <li class="mr-2">
          <a href="#" @click="changeTab(3)" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-bind:class="{'text-purple-600 bg-white': openTab !== 3, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 3}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Deduction
          </a>
        </li>
        <li class="mr-2">
          <a href="#" @click="changeTab(4)" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-bind:class="{'text-purple-600 bg-white': openTab !== 4, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active-tab': openTab === 4}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Review
          </a>
        </li>
      </ul>
      <div @click="changeTab(1)" v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
            <span class="mr-2">Assigned Crew List</span>
          </legend>
          <table class="w-full mt-2 whitespace-no-wrap" id="table">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="px-4 py-3 align-bottom"><nobr>Crew Name</nobr></th>
              <th class="px-4 py-3 align-bottom"><nobr>Contact</nobr></th>
              <th class="px-4 py-3 align-bottom"><nobr>Net Salary</nobr></th>
              <th class="px-4 py-3 align-bottom"><nobr>Present Days</nobr></th>
              <th class="px-4 py-3 align-bottom"><nobr>Absent Days</nobr></th>
              <th class="px-4 py-3 align-bottom"><nobr>Payable Days</nobr></th>
              <th class="px-4 py-3 align-bottom"><nobr>Payable Amount</nobr></th>
            </tr>
            </thead>

            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(batchLine, index) in form.crwPayrollBatchLines" :key="batchLine.crw_crew_id">
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.crwPayrollBatchLines[index].crw_full_name" placeholder="Crew name" class="form-input vms-readonly-input" autocomplete="off" readonly />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.crwPayrollBatchLines[index].crw_contact_no" placeholder="Crew contact no" class="form-input vms-readonly-input" autocomplete="off" readonly />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.crwPayrollBatchLines[index].net_salary" placeholder="Net salary" class="form-input vms-readonly-input" autocomplete="off" readonly />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.crwPayrollBatchLines[index].present_days" placeholder="Present days" class="form-input vms-readonly-input" autocomplete="off" readonly />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.crwPayrollBatchLines[index].absent_days" placeholder="Absent days" class="form-input vms-readonly-input" autocomplete="off" readonly />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.crwPayrollBatchLines[index].payable_days" placeholder="Payable days" class="form-input vms-readonly-input" autocomplete="off" readonly />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.crwPayrollBatchLines[index].payable_amount" placeholder="Payable amount" class="form-input vms-readonly-input" autocomplete="off" readonly />
              </td>
            </tr>
            </tbody>
            <tfoot v-if="!form.crwPayrollBatchLines?.length">
            <tr v-if="isLoading">
              <td colspan="8">Loading</td>
            </tr>
            <tr v-else-if="!form.crwPayrollBatchLines?.length">
              <td colspan="8">No data found.</td>
            </tr>
            </tfoot>
          </table>
        </fieldset>
      </div>
      <div @click="changeTab(2)" v-bind:class="{'hidden': openTab !== 2, 'block': openTab === 2}">
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
            <span class="mr-2">Addition Head List</span>
          </legend>
          <table class="w-full mt-2 whitespace-no-wrap" id="table">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th><nobr>Addition Head</nobr></th>
              <th><nobr>Amount</nobr></th>
              <!--            <th><nobr>Based On</nobr></th>-->
              <th>
                <button type="button" @click="addAdditionHead" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                </button>
              </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <template v-for="(batchHead, index) in form.crwPayrollBatchHeads">
              <tr v-if="batchHead?.head_type === 'addition'" class="text-gray-700 dark-disabled:text-gray-400" :key="batchHead.key">
                <td class="px-1 py-1">
                  <input type="text" v-model.trim="form.crwPayrollBatchHeads[index].head_name" @input="additionChangeHead($event,batchHead.id)" placeholder="Head name" class="form-input" autocomplete="off" required />
                </td>
                <td class="px-1 py-1">
                  <input type="number" step=".01" v-model.trim="form.crwPayrollBatchHeads[index].amount" @input="additionAmountSet($event,batchHead.id)" placeholder="Amount" class="form-input" autocomplete="off" required />
                </td>
                <!--              <td class="px-1 py-1">-->
                <!--                <input type="text" v-model.trim="form.crwPayrollBatchHeads[index].head_type" placeholder="Crew rank" class="form-input" autocomplete="off" required />-->
                <!--              </td>-->
                <td class="px-1 py-1">
                  <button type="button" @click="removeAdditionItem(batchHead.id)" class="px-2 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                  </button>
                </td>
              </tr>
            </template>
            </tbody>
            <tfoot>
            <tr v-if="!form.crwPayrollBatchHeads?.filter(item => item.head_type === 'addition').length">
              <td colspan="4">No data found</td>
            </tr>
            </tfoot>
          </table>
        </fieldset>
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
            <span class="mr-2">Assigned Crew List</span>
          </legend>
          <table class="w-full mt-2 whitespace-no-wrap" id="table">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="px-4 py-3 align-bottom"><nobr>Crew Name</nobr></th>
              <th class="px-4 py-3 align-bottom"><nobr>Crew Contact</nobr></th>
              <template v-for="(batchHead, index) in form.crwPayrollBatchHeads">
                <th class="px-4 py-3 align-bottom" v-if="batchHead?.head_type==='addition'"><nobr>{{ batchHead?.head_name }}</nobr></th>
              </template>
              <th class="px-4 py-3 align-bottom"><nobr>Addition Amount</nobr></th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <template v-for="(batchHeadLine, payrollBatchHeadLineIndex) in form.crwPayrollBatchHeadLines">
              <tr v-if="batchHeadLine?.head_type==='addition'" class="text-gray-700 dark-disabled:text-gray-400" :key="batchHeadLine.crw_crew_id">
                <td class="px-1 py-1">
                  <input type="text" v-model.trim="form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_name" placeholder="Crew name" class="form-input vms-readonly-input" autocomplete="off" readonly />
                </td>
                <td class="px-1 py-1">
                  <input type="text" v-model.trim="form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crw_contact_no" placeholder="Crew contact no" class="form-input vms-readonly-input" autocomplete="off" readonly />
                </td>
                <template v-for="(lineBatchHead, lineBatchHeadIndex) in form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_batch_heads">
                  <td v-if="lineBatchHead?.head_type==='addition'" class="px-1 py-1">
                    <input type="number" step=".01" v-model.trim="form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_batch_heads[lineBatchHeadIndex].amount" @input="setAdditionBatchHeadAmount(payrollBatchHeadLineIndex)" placeholder="Amount" class="form-input" autocomplete="off" required />
                  </td>
                </template>
                <td class="px-1 py-1">
                  <input type="number" step=".01" v-model.trim="form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].amount" placeholder="Amount" class="form-input vms-readonly-input" autocomplete="off" readonly />
                </td>
              </tr>
            </template>
            </tbody>
            <tfoot>
            <tr v-if="!form.crwPayrollBatchHeadLines?.filter(item => item.head_type === 'addition').length">
              <td colspan="4">No data found</td>
            </tr>
            </tfoot>
          </table>
        </fieldset>
      </div>
      <div @click="changeTab(3)" v-bind:class="{'hidden': openTab !== 3, 'block': openTab === 3}">
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
            <span class="mr-2">Deduction Head List</span>
          </legend>
          <table class="w-full mt-2 whitespace-no-wrap" id="table">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th><nobr>Deduction Head</nobr></th>
              <th><nobr>Amount</nobr></th>
              <!--            <th><nobr>Based On</nobr></th>-->
              <th>
                <button type="button" @click="addDeductionHead" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                </button>
              </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <template v-for="(batchHead, index) in form.crwPayrollBatchHeads">
              <tr v-if="batchHead?.head_type === 'deduction'" class="text-gray-700 dark-disabled:text-gray-400" :key="batchHead.key">
                <td class="px-1 py-1">
                  <input type="text" v-model.trim="form.crwPayrollBatchHeads[index].head_name" @input="deductionChangeHead($event,batchHead.id)" placeholder="Head name" class="form-input" autocomplete="off" required />
                </td>
                <td class="px-1 py-1">
                  <input type="number" step=".01" v-model.trim="form.crwPayrollBatchHeads[index].amount" @input="deductionAmountSet($event,batchHead.id)" placeholder="Amount" class="form-input" autocomplete="off" required />
                </td>
                <!--              <td class="px-1 py-1">-->
                <!--                <input type="text" v-model.trim="form.crwPayrollBatchHeads[index].head_type" placeholder="Crew rank" class="form-input" autocomplete="off" required />-->
                <!--              </td>-->
                <td class="px-1 py-1">
                  <button type="button" @click="removeDeductionItem(batchHead.id)" class="px-2 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                  </button>
                </td>
              </tr>
            </template>
            </tbody>
            <tfoot>
            <tr v-if="!form.crwPayrollBatchHeads?.filter(item => item.head_type === 'deduction').length">
              <td colspan="4">No data found</td>
            </tr>
            </tfoot>
          </table>
        </fieldset>
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
            <span class="mr-2">Assigned Crew List</span>
          </legend>
          <table class="w-full mt-2 whitespace-no-wrap" id="table">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="px-4 py-3 align-bottom"><nobr>Crew Name</nobr></th>
              <th class="px-4 py-3 align-bottom"><nobr>Crew Contact</nobr></th>
              <template v-for="(batchHead, index) in form.crwPayrollBatchHeads">
                <th class="px-4 py-3 align-bottom" v-if="batchHead?.head_type==='deduction'"><nobr>{{ batchHead?.head_name }}</nobr></th>
              </template>
              <th class="px-4 py-3 align-bottom"><nobr>Deduction Amount</nobr></th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <template v-for="(batchHeadLine, payrollBatchHeadLineIndex) in form.crwPayrollBatchHeadLines">
              <tr v-if="batchHeadLine?.head_type==='deduction'" class="text-gray-700 dark-disabled:text-gray-400" :key="batchHeadLine.crw_crew_id">
                <td class="px-1 py-1">
                  <input type="text" v-model.trim="form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_name" placeholder="Crew name" class="form-input vms-readonly-input" autocomplete="off" readonly />
                </td>
                <td class="px-1 py-1">
                  <input type="text" v-model.trim="form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crw_contact_no" placeholder="Crew contact no" class="form-input vms-readonly-input" autocomplete="off" readonly />
                </td>
                <template v-for="(lineBatchHead, lineBatchHeadIndex) in form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_batch_heads">
                  <td v-if="lineBatchHead?.head_type==='deduction'" class="px-1 py-1">
                    <input type="number" step=".01" v-model.trim="form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].crew_batch_heads[lineBatchHeadIndex].amount" @input="setDeductionBatchHeadAmount(payrollBatchHeadLineIndex)" placeholder="Amount" class="form-input" autocomplete="off" required />
                  </td>
                </template>
                <td class="px-1 py-1">
                  <input type="number" step=".01" v-model.trim="form.crwPayrollBatchHeadLines[payrollBatchHeadLineIndex].amount" placeholder="Crew rank" class="form-input vms-readonly-input" autocomplete="off" readonly />
                </td>
              </tr>
            </template>
            </tbody>
            <tfoot>
            <tr v-if="!form.crwPayrollBatchHeadLines?.filter(item => item.head_type === 'deduction').length">
              <td colspan="4">No data found</td>
            </tr>
            </tfoot>
          </table>
        </fieldset>
      </div>
      <div @click="changeTab(4)" v-bind:class="{'hidden': openTab !== 4, 'block': openTab === 4}">
        <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="flex items-center px-2 text-gray-700 dark-disabled:text-gray-300">
            <span class="mr-2">Assigned Crew List</span>
          </legend>
          <table class="w-full mt-2 whitespace-no-wrap" id="table">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
              <th class="px-4 py-3 align-bottom"><nobr>Crew Name</nobr></th>
              <th class="px-4 py-3 align-bottom"><nobr>Crew Contact</nobr></th>
              <th class="px-4 py-3 align-bottom"><nobr>Payable Days</nobr></th>
              <th class="px-4 py-3 align-bottom"><nobr>Payable Amount</nobr></th>
              <th class="px-4 py-3 align-bottom"><nobr>Addition Amount</nobr></th>
              <th class="px-4 py-3 align-bottom"><nobr>Deduction Amount</nobr></th>
              <th class="px-4 py-3 align-bottom"><nobr>Net Payable Amount</nobr></th>
            </tr>
            </thead>

            <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
            <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(batchLine, index) in form.crwPayrollBatchLines" :key="batchLine.crw_crew_id">
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.crwPayrollBatchLines[index].crw_full_name" placeholder="Crew name" class="form-input vms-readonly-input" autocomplete="off" readonly />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.crwPayrollBatchLines[index].crw_contact_no" placeholder="Crew ID" class="form-input vms-readonly-input" autocomplete="off" readonly />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.crwPayrollBatchLines[index].payable_days" placeholder="Payable days" class="form-input vms-readonly-input" autocomplete="off" readonly />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.crwPayrollBatchLines[index].payable_amount" placeholder="Payable amount" class="form-input vms-readonly-input" autocomplete="off" readonly />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.crwPayrollBatchLines[index].total_earnings" placeholder="Total earnings" class="form-input vms-readonly-input" autocomplete="off" readonly />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.crwPayrollBatchLines[index].total_deductions" placeholder="Total deductions" class="form-input vms-readonly-input" autocomplete="off" readonly />
              </td>
              <td class="px-1 py-1">
                <input type="text" v-model.trim="form.crwPayrollBatchLines[index].net_payable_amount" placeholder="Net payable amount" class="form-input vms-readonly-input" autocomplete="off" readonly />
              </td>
            </tr>
            </tbody>
            <tfoot v-if="!form.crwPayrollBatchLines?.length">
            <tr v-if="isLoading">
              <td colspan="7">Loading</td>
            </tr>
            <tr v-else-if="!form.crwPayrollBatchLines?.length">
              <td colspan="7">No data found.</td>
            </tr>
            </tfoot>
          </table>
        </fieldset>
      </div>
    </div>
    <button v-if="openTab===4" type="submit" :disabled="isLoading" class="flex items-center justify-between float-right px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
      <span>Submit</span>
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
  </template>
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