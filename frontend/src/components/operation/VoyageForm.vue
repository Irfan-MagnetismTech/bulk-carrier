<script setup>
import { ref, watch, onMounted } from 'vue';
import usePort from '../../composables/usePort';
import useService from "../../composables/commercial/useService";
import useVoyage from "../../composables/operation/useVoyage";
import Error from "../Error.vue";
import useVessel from "../../composables/dataencoding/useVessel";
const { voyages, voyageName, getVoyageName } = useVoyage();

const props = defineProps({
    form: { type: Object, required: true },
    vessels: { type: Array, required: true },
    services: { type: Array, required: true },
    errors: { type: [Object, Array], required: false },
    lastVoyageId: { type: Number, required: false, default: -1 },
    page: {
      required: false,
      default: {}
    },
});
const statuses = ['draft', 'published', 'closed'];

props.form.load_type = "Commercial";


const { service, sectors, uniqueServicePorts, serviceGroupById, getServiceUniquePorts } = useService();

const { vessel, isLoading, showVessel } = useVessel();

const { portName, getPortsByNameOrCode } = usePort();
const portDischarge = ref(null);
const portOrigin = ref(null);
const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  openTab.value = tabNumber;
}

function fetchOptions(search, loading) { 
  if (search.length === 0) {
    return;
  }
  if(!props.form.service_id) {
    alert('Please Choose a Service First.')
  } else {
    getPortsByNameOrCode(search, props.form.service_id);
  }
}

function fetchVoyages(search, loading) {
  getVoyageName(search);
}


watch(() => props.form.port_origin_name, (value) => {
  if (value) {
    props.form.port_origin = value.code;
  }
}, {deep: true});

watch(() => props.form.previous_voyage_name, (value) => {
  if(value) {
    props.form.previous_voyage_id = value.id;
  }
}, {deep: true});

watch(() => props.form.port_discharge_name, (value) => {
  if (value) {
    props.form.port_discharge = value.code;
  }
}, {deep: true});

watch(() => props.form.next_port_name, (value) => {
  if (value) {
    props.form.next_port = value.code;
  }
}, {deep: true});

  watch(() => props.form, (value) => {
    if(value?.voyage_schedules?.length) {
      value.voyage_schedules.forEach((schedule, index) => {
        props.form.voyage_schedules[index].port_code = schedule?.port_code_name?.code ?? '';
      });
    }

  }, {deep: true});

watch(() => props.form.service_id, (val) => {
  if (val) {
    serviceGroupById(val);
  }
});

watch(() => props.form.vessel_id, (val) => {
  if (val) {
    showVessel(val);
  }
});

watch(() => props.form.voyage, (val) => {
  props.form.document_no = val;
  props.form.message_reference_no = val;
});

/* add and remove schedule dynamic start */
function addSchedule() {
    let obj = {
        port_code: '',
        eta_date: '',
        etb_date: '',
        etd_date: '',
        commence_discharge: '',
        completed_discharge: '',
        commence_load: '',
        completed_load: '',
    };
    props.form.voyage_schedules.push(obj);
}
  function removeSchedule(index){
    props.form.voyage_schedules.splice(index, 1);
}
/* add and remove schedule dynamic end */


watch(() => [vessel,props.form.voyage_no, props.form.bound], (value) => {
  props.form.voyage = value[0]?.value?.code+value[1]+value[2].charAt(0).toUpperCase();

}, {deep: true});

</script>

<template>
<div class="border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px">
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(1)" v-bind:class="{'text-purple-600 bg-white': openTab !== 1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 1}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Voyage Information
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(2)" v-bind:class="{'text-purple-600 bg-white': openTab !== 2, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 2}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>EDI Information
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(3)" v-bind:class="{'text-purple-600 bg-white': openTab !== 3, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 3}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Voyage Schedule
        </a>
      </li>
    </ul>
    <div v-on:click="toggleTabs(1)" v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">
        <!-- Sender, Recipient & Vessel -->
      <div class="input-group">
        <label class="label-group">
          <span class="label-item-title">VVD</span>
          <input type="text" v-model="form.voyage" required placeholder="VVD" readonly class="label-item-input bg-gray-200" />
          <Error v-if="errors?.voyage" :errors="errors.voyage" />
        </label>
      </div>
        <div class="input-group">
            <label class="label-group">
              <span class="label-item-title">Vessel <span class="required-style">*</span></span>
              <select v-model="form.vessel_id" class="label-item-input">
                <option value>-- Select Vessel --</option>
                <option v-for="vessel in vessels" :value="vessel.id" :key="vessel.id">{{ vessel.name }}</option>
              </select>
              <Error v-if="errors?.vessel_id" :errors="errors.vessel_id" />
            </label>
            <label class="label-group">
              <span class="label-item-title">Service <span class="required-style">*</span></span>
              <select v-model="form.service_id" class="label-item-input">
                <option value>-- Select Service --</option>
                <option v-for="service in services" :value="service.id" :key="service.id">{{ service.name }}</option>
              </select>
              <Error v-if="errors?.service_id" :errors="errors.service_id" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Voyage No <span class="required-style">*</span></span>
                <input type="text" v-model="form.voyage_no" required placeholder="Enter Voyage No." class="label-item-input" />
              <Error v-if="errors?.voyage_no" :errors="errors.voyage_no" />
            </label>
          <label class="label-group">
            <span class="label-item-title">Bound <span class="required-style">*</span></span>
            <select v-model="form.bound" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <option value="">--Choose an Option--</option>
              <option v-for="(bound,index) in service?.bounds" :value="bound" :key="index">{{ bound.toUpperCase() }}</option>
            </select>
            <Error v-if="errors?.bound" :errors="errors.bound" />
          </label>
          <label class="label-group">
              <span class="label-item-title">Previous Voyage </span>
              <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchVoyages"  v-model="form.previous_voyage_name" label="voyage" :reduce="voyageName => voyageName" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
              <Error v-if="errors?.service_id" :errors="errors.service_id" />
              <input type="hidden" name="" v-model="form.previous_voyage_id">
            </label>
        </div>
        <div class="input-group">
<!--            <label class="label-group">-->
<!--                <span class="label-item-title">Exchange Rate</span>-->
<!--                <input type="number" v-model="form.exchange_rate" min="0" step="any" placeholder="Enter Exchange Rate" class="label-item-input" />-->
<!--              <Error v-if="errors?.exchange_rate" :errors="errors.exchange_rate" />-->
<!--            </label>-->
            <label class="label-group">
                <span class="label-item-title">Sailing Status <span class="required-style">*</span></span>
                <select v-model="form.load_type" class="label-item-input capitalize">
                  <option value>-- Select Type --</option>
                  <option value="Commercial">Commercial</option>
                  <option value="Non-Commercial">Non-Commercial</option>
<!--                    <option v-for="(loadtype, index) in loadTypes" :value="loadtype.value" :key="index">{{ loadtype?.label }}</option>-->
                </select>
              <Error v-if="errors?.load_type" :errors="errors.load_type" />
            </label>

          <label class="label-group">
            <span class="label-item-title">Port of Loading <span class="required-style">*</span></span>
            <v-select v-model="form.port_origin_name" :id="'port_origin_name' + index" @search="fetchOptions" value="id" :options="portName" label="code_name" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600 w-full"></v-select>
            <input type="hidden" v-model="form.port_origin" name="port_code" :id="'port_code' + index" />
          </label>
<!--            <label class="label-group">-->
<!--                <span class="label-item-title">Voyage Status <span class="required-style">*</span></span>-->
<!--                <select v-model="form.status" class="label-item-input">-->
<!--                    <option value>&#45;&#45; Select Status &#45;&#45;</option>-->
<!--                    <option v-for="(status, index) in statuses" :value="status" :key="index">{{ status.toUpperCase() }}</option>-->
<!--                </select>-->
<!--              <Error v-if="errors?.status" :errors="errors.status" />-->
<!--            </label>-->
            
<!--            <label class="label-group">-->
<!--                <span class="label-item-title">Voyage Expire Date</span>-->
<!--                <input type="date" v-model="form.expire_date" placeholder="Enter Expire Date" class="label-item-input" />-->
<!--              <Error v-if="errors?.expire_date" :errors="errors.expire_date" />-->
<!--            </label>-->
        </div>
        <!-- Voyage no & Service -->
<!--        <div class="input-group">-->
<!--            -->
<!--            <label class="label-group">-->
<!--                <span class="label-item-title">Service <span class="required-style">*</span></span>-->
<!--                <select v-model="form.service_id" class="label-item-input">-->
<!--                    <option value>&#45;&#45; Select Service &#45;&#45;</option>-->
<!--                    <option v-for="service in services" :value="service.id" :key="service.id">{{ service.name }}</option>-->
<!--                </select>-->
<!--              <Error v-if="errors?.service_id" :errors="errors.service_id" />-->
<!--            </label>-->
<!--&lt;!&ndash;            <label class="label-group">&ndash;&gt;-->
<!--&lt;!&ndash;              <span class="label-item-title">Sectors</span>&ndash;&gt;-->
<!--&lt;!&ndash;                <v-select multiple v-model="form.voyage_sectors" :options="uniqueServicePorts" placeholder="Select Sectors" class="mt-1 placeholder-gray-600 w-full"></v-select>&ndash;&gt;-->
<!--&lt;!&ndash;            </label>&ndash;&gt;-->
<!--            <label class="label-group">-->
<!--                    <span class="label-item-title">Bound <span class="required-style">*</span></span>-->
<!--                <select v-model="form.bound" class="block w-full mt-1 text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">-->
<!--                    <option value="">&#45;&#45;Choose an Option&#45;&#45;</option>-->
<!--                    <option v-for="(bound,index) in service?.bounds" :value="bound" :key="index">{{ bound.toUpperCase() }}</option>-->
<!--                </select>-->
<!--              <Error v-if="errors?.bound" :errors="errors.bound" />-->
<!--            </label>-->
<!--        </div>-->
        <!-- Port -->
        <div class="input-group">

            <label class="label-group">
                <span class="label-item-title">Final Port of Discharge <span class="required-style">*</span></span>
              <v-select v-model="form.port_discharge_name" :id="'port_discharge_name' + index" @search="fetchOptions" value="id" :options="portName" label="code_name" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600 w-full"></v-select>
              <input type="hidden" v-model="form.port_discharge" name="port_code" :id="'port_code' + index" />
            </label>
            <label class="label-group">
              <span class="label-item-title">Next Port of Call<span class="required-style">*</span></span>
              <v-select v-model="form.next_port_name" :id="'next_port_name' + index" @search="fetchOptions" value="id" :options="portName" label="code_name" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600 w-full"></v-select>
              <input type="hidden" v-model="form.next_port" name="port_code" :id="'port_code' + index" />
            </label>
        </div>
        <!-- Rotation -->
        <div class="input-group">
            <label class="label-group">
                <span class="label-item-title">Import Rotation</span>
                <input type="text" v-model="form.import_rotation" placeholder="Enter Import Rotation" class="label-item-input" />
              <Error v-if="errors?.import_rotation" :errors="errors.import_rotation" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Export Rotation</span>
                <input type="text" v-model="form.export_rotation" placeholder="Enter Export Rotation" class="label-item-input" />
              <Error v-if="errors?.export_rotation" :errors="errors.export_rotation" />
            </label>
        </div>
        <!-- Departure, Arrival & Berthing date -->
        <div class="input-group">
            <!-- <label class="label-group">
                <span class="label-item-title">Arrival Date <span class="required-style">*</span></span>
                <input type="datetime-local" v-model="form.arrival_date" placeholder="Enter Arrival Date" class="label-item-input" />
              <Error v-if="errors?.arrival_date" :errors="errors.arrival_date" />
            </label> -->
            <!-- <label class="label-group">
                <span class="label-item-title">Berthing Date</span>
                <input type="datetime-local" v-model="form.berthing_date" placeholder="Enter Berthing Date" class="label-item-input" />
              <Error v-if="errors?.berthing_date" :errors="errors.berthing_date" />
            </label> -->
            <label class="label-group">
                <span class="label-item-title">Sailed From</span>
                <input type="text" v-model="form.sailed_from" placeholder="Enter Sailed From" class="label-item-input" />
              <Error v-if="errors?.sailed_from" :errors="errors.sailed_from" />
            </label>
            <!-- <label class="label-group">
                <span class="label-item-title">Departure Date</span>
                <input type="datetime-local" v-model="form.departure_date" placeholder="Enter Departure Date" class="label-item-input" />
              <Error v-if="errors?.departure_date" :errors="errors.departure_date" />
            </label> -->
            <label class="label-group">
                <span class="label-item-title">Import Pilot Boarding Time</span>
                <input type="datetime-local" v-model="form.import_pilot_boarding_time" placeholder="Enter Pilot Boarding Time" class="label-item-input" />
              <Error v-if="errors?.import_pilot_boarding_time" :errors="errors.import_pilot_boarding_time" />
            </label>
            <label class="label-group">
                <span class="label-item-title">Export Pilot Boarding Time</span>
                <input type="datetime-local" v-model="form.export_pilot_boarding_time" placeholder="Enter Pilot Boarding Time" class="label-item-input" />
              <Error v-if="errors?.export_pilot_boarding_time" :errors="errors.export_pilot_boarding_time" />
            </label>
            
        </div>
       

    </div>
    <div v-on:click="toggleTabs(2)" v-bind:class="{'hidden': openTab !== 2, 'block': openTab === 2}">
         <!-- Optional -->
         
        <fieldset class="px-4 pb-4 mt-3 border rounded border-gray-700 dark:border-gray-400">
            <div class="input-group">
             <label class="label-group">
                <span class="label-item-title">Sender</span>
                <input type="text" v-model="form.sender" placeholder="Enter Sender Name" class="label-item-input vms-readonly-input" />
               <Error v-if="errors?.sender" :errors="errors.sender" />
             </label>
            <label class="label-group">
                <span class="label-item-title">Recipient</span>
                <input type="text" v-model="form.recipient" placeholder="Enter Recipient Name" class="label-item-input" />
              <Error v-if="errors?.recipient" :errors="errors.recipient" />
            </label>
         </div>

            <div class="input-group">
                <label class="label-group">
                    <span class="label-item-title">Document No</span>
                    <input type="text" v-model="form.document_no" readonly placeholder="Enter Document No" class="label-item-input vms-readonly-input" />
                  <Error v-if="errors?.document_no" :errors="errors.document_no" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Message Reference No</span>
                    <input type="text" v-model="form.message_reference_no" readonly placeholder="Enter Message Reference No" class="label-item-input vms-readonly-input" />
                  <Error v-if="errors?.message_reference_no" :errors="errors.message_reference_no" />
                </label>
            </div>
            <!-- Preparation Time, Message Compilation Time & Message Type -->
            <div class="input-group !hidden">
                <label class="label-group">
                    <span class="label-item-title">Message Compilation Time</span>
                    <input type="datetime-local" v-model="form.message_compilation_time" readonly placeholder="Enter Message Compilation Time" class="label-item-input vms-readonly-input" />
                  <Error v-if="errors?.message_compilation_time" :errors="errors.message_compilation_time" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Date of Preparation</span>
                    <input type="datetime-local" v-model="form.time_of_preparation" readonly placeholder="Enter Date of Preparation" class="label-item-input vms-readonly-input" />
                  <Error v-if="errors?.time_of_preparation" :errors="errors.time_of_preparation" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Message Type</span>
                    <input type="text" v-model="form.message_type" readonly placeholder="Enter Message Type" class="label-item-input vms-readonly-input" />
                  <Error v-if="errors?.message_type" :errors="errors.message_type" />
                </label>
            </div>

        </fieldset>

        
    </div>
    <div v-on:click="toggleTabs(3)" v-bind:class="{'hidden': openTab !== 3, 'block': openTab === 3}">
        <!-- Voyage Schedule -->
        <fieldset v-for="(schedule, index) in form.voyage_schedules" :key="schedule.id" class="px-4 pb-4 mt-3 border rounded border-gray-700 dark:border-gray-400">
            <legend class="text-gray-700 dark:text-gray-300">Voyage Schedule</legend>
            <!-- Document & Message reference -->
            <div class="input-group !w-1/3 mx-auto">
                
                <label class="label-group">
                    <span class="label-item-title">Port Code</span>
                    <!-- <input type="text" v-model="form.schedule.port_code" placeholder="Enter Message Reference No" class="label-item-input" /> -->
                    <v-select v-model="form.voyage_schedules[index].port_code_name" :id="'port_code_name' + index" name="port_code_name" @search="fetchOptions" value="id" :options="portName" label="code_name" placeholder="Enter Port Code or Name" class="mt-1 placeholder-gray-600 w-full"></v-select>
                    <input type="hidden" v-model="form.voyage_schedules[index].port_code" name="port_code" :id="'port_code' + index" />
                </label>
                
            </div>
            <div class="input-group">
              <label class="label-group">
                    <span class="label-item-title">ETA</span>
                    <input type="datetime-local" v-model="form.voyage_schedules[index].eta_date" placeholder="Enter Bound" class="label-item-input" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">ETB</span>
                    <input type="datetime-local" v-model="form.voyage_schedules[index].etb_date" placeholder="Enter Bound" class="label-item-input" />
                </label><label class="label-group">
                    <span class="label-item-title">ETD</span>
                    <input type="datetime-local" v-model="form.voyage_schedules[index].etd_date" placeholder="Enter Bound" class="label-item-input" />
                </label>
            </div>
            <div class="input-group">
              <label class="label-group">
                    <span class="label-item-title">ATA</span>
                    <input type="datetime-local" v-model="form.voyage_schedules[index].actual_arrival_date" placeholder="Enter Bound" class="label-item-input" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">ATB</span>
                    <input type="datetime-local" v-model="form.voyage_schedules[index].actual_berth_date" placeholder="Enter Bound" class="label-item-input" />
                </label><label class="label-group">
                    <span class="label-item-title">ATD</span>
                    <input type="datetime-local" v-model="form.voyage_schedules[index].actual_departure_date" placeholder="Enter Bound" class="label-item-input" />
                </label>
            </div>
            <div class="input-group">
                <label class="label-group">
                    <span class="label-item-title">Commence Discharge</span>
                    <input type="datetime-local" v-model="form.voyage_schedules[index].commence_discharge" placeholder="Enter Bound" class="label-item-input" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Completed Discharge</span>
                    <input type="datetime-local" v-model="form.voyage_schedules[index].completed_discharge" placeholder="Enter Bound" class="label-item-input" />
                </label>
            </div>
            <div class="input-group">

                <label class="label-group">
                    <span class="label-item-title">Commence Load</span>
                    <input type="datetime-local" v-model="form.voyage_schedules[index].commence_load" placeholder="Enter Bound" class="label-item-input" />
                </label>
                <label class="label-group">
                    <span class="label-item-title">Completed Load</span>
                    <input type="datetime-local" v-model="form.voyage_schedules[index].completed_load" placeholder="Enter Bound" class="label-item-input" />
                </label>
            </div>
            <div class="input-group mt-5">
                <button v-if="form.voyage_schedules.length == index+1" type="button" @click="addSchedule()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Add More Schedule
                </button>
                <button type="button" v-if="index!=0" @click="removeSchedule(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Remove Schedule
                </button>
                
            </div>
        </fieldset>
    </div>
</div>
    <button v-if="openTab==3" type="submit" :disabled="isLoading" class="flex float-right items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
      <span v-if="page=='create'">Create Voyage</span>
      <span v-else>Update Voyage</span>
    </button>

    <button type="button" v-else v-on:click="toggleTabs(openTab + 1)" :disabled="isLoading" class="flex float-right items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 uppercase focus:outline-none focus:shadow-outline-purple">Next
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
      <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
      <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
    </svg>
  </button>
  <button type="button" v-if="openTab!=1" v-on:click="toggleTabs(openTab - 1)" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 uppercase focus:outline-none focus:shadow-outline-purple">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
      <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
    </svg>
    Back
  </button>
</template>

<style lang="postcss" scoped>
.input-group {
    @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
}
.label-group {
    @apply block w-full mt-3 text-sm;
}
.label-item-title {
    @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
    @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
    @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}
.vs__selected{
  display: none !important;
}
.required-style{
    @apply text-red-400 font-semibold
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