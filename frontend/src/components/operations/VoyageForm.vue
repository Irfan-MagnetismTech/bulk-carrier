<template>

    <ul class="flex flex-wrap -mb-px">
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-on:click="toggleTabs(1)" v-bind:class="{'text-purple-600 bg-white': openTab !== 1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 bg-gray-100': openTab === 1}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Voyage Information
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-on:click="toggleTabs(2)" v-bind:class="{'text-purple-600 bg-white': openTab !== 2, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 bg-gray-100': openTab === 2}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Sectors
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-on:click="toggleTabs(3)" v-bind:class="{'text-purple-600 bg-white': openTab !== 3, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 bg-gray-100': openTab === 3}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Bunker Information
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark-disabled:text-gray-400 group" v-on:click="toggleTabs(4)" v-bind:class="{'text-purple-600 bg-white': openTab !== 4, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 bg-gray-100': openTab === 4}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Voyage Schedule
        </a>
      </li>
    </ul>

    <div v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">

      <div class="border border-gray-200 rounded-md my-3 p-2">
        <h4 class="text-md font-semibold">Voyage Info</h4>

        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          
          <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Customer Name <span class="text-red-500">*</span></span>
            <v-select :options="customers" placeholder="--Choose an option--" :loading="isCustomerLoading"   v-model="form.ops_customer_name" label="name" class="block form-input" >
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.ops_customer_id"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
            <input type="hidden" v-model="form.ops_customer_id">
          </label>
        </div>

        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
                <span class="text-gray-700 dark-disabled:text-gray-300">Mother Vessel Name <span class="text-red-500">*</span></span>
                <input type="text" v-model.trim="form.mother_vessel" placeholder="Mother Vessel Name" class="form-input" required autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Vessel <span class="text-red-500">*</span></span>
            <v-select :options="vessels" placeholder="--Choose an option--" :loading="isVesselLoading"  v-model="form.ops_vessel_name" label="name" class="block form-input" :class="{ 'bg-gray-100': formType === 'edit' }" :disabled="formType=='edit'" >
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.ops_vessel_id"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
            <input type="hidden" v-model="form.ops_vessel_id">
          </label>

        </div>

        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
            <label class="block w-1/2 mt-2 text-sm">
                <span class="text-gray-700 dark-disabled:text-gray-300">Voyage No <span class="text-red-500">*</span></span>
                <input type="text" v-model.trim="form.voyage_no" placeholder="Voyage No" class="form-input" required autocomplete="off" :class="{ 'bg-gray-100': formType === 'edit' }" :disabled="formType=='edit'" />
            </label>
            <label class="block w-1/2 mt-2 text-sm">
                <span class="text-gray-700 dark-disabled:text-gray-300">Voyage Sequence <span class="text-red-500">*</span></span>
                <input type="text" v-model.trim="form.voyage_sequence" readonly class="form-input bg-gray-100" required autocomplete="off" />
            </label>
            <label class="block w-full mt-2 text-sm">
                <span class="text-gray-700 dark-disabled:text-gray-300">Route <span class="text-red-500">*</span></span>
                <input type="text" v-model.trim="form.route" placeholder="Route" class="form-input" required autocomplete="off" />
            </label>
            
            
        </div>

        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Cargo Type <span class="text-red-500">*</span></span>
              <v-select :options="cargoTypes" placeholder="--Choose an option--" :loading="isCargoTypeLoading"  v-model="form.ops_cargo_type_name" label="cargo_type" class="block form-input" >
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.ops_cargo_type_id"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <input type="hidden" v-model="form.ops_cargo_type_id">
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Load Port Distance (NM) <span class="text-red-500">*</span></span>
              <input type="number" v-model.trim="form.load_port_distance" placeholder="Load Port Distance (NM)" class="form-input" required autocomplete="off" />
            </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Sail Date <span class="text-red-500">*</span></span>
            <input type="datetime-local" v-model="form.sail_date" placeholder="Sail Date " class="form-input" required autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Transit Date <span class="text-red-500">*</span></span>
            <input type="datetime-local" v-model="form.transit_date" placeholder="Transit Date" class="form-input" required autocomplete="off" />
          </label>
          
          
        </div>
        <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">

          <RemarksComponent v-model="form.remarks" :maxlength="500" :fieldLabel="'Remarks'"></RemarksComponent>
        </div>
      </div>
    </div>

    
    <div v-bind:class="{'hidden': openTab !== 2, 'block': openTab === 2}" class="border border-gray-200 rounded-md my-3 p-2">
      <div class="">
        <h4 class="text-md font-semibold uppercase mb-2">Voyage Sectors</h4>
        <table class="w-full whitespace-no-wrap" >
          <thead v-once>
            <tr class="w-full">
              <th>SL</th>
              <th class="!w-80">Loading Point <span class="text-red-500">*</span></th>
              <th class="!w-80">Unloading Point <span class="text-red-500">*</span></th>
              <th>Tonnage (Initial Survey) <span class="text-red-500">*</span></th>
              <th class="w-16">
                Action
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(certificate, index) in form.opsVoyageSectors">
              <td>
                {{ index+1 }}
              </td>
              <td>
                <v-select :options="ports" placeholder="--Choose an option--" :loading="isPortLoading"  v-model="form.opsVoyageSectors[index].loading_point" label="code_name" class="block form-input" :reduce="port=>port.code">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsVoyageSectors[index].loading_point"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              </td>
              <td>
                <v-select :options="ports" placeholder="--Choose an option--" :loading="isPortLoading"  v-model="form.opsVoyageSectors[index].unloading_point" label="code_name" class="block form-input" :reduce="port=>port.code">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsVoyageSectors[index].unloading_point"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              </td>
              <td>
                <label class="block w-full text-sm">
                  <input type="number" v-model.trim="form.opsVoyageSectors[index].initial_survey_qty" placeholder="Initial Survey Quantity" class="form-input" autocomplete="off" required />
                </label>
              </td>
              <td>
                <button v-if="index == 0" type="button" @click="addVoyageSector()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                </button>
                <button v-else type="button" @click="removeVoyageSector(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button> 
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-bind:class="{'hidden': openTab !== 3, 'block': openTab === 3}" class="border border-gray-200 rounded-md my-3 p-2">
      <div class="min-h-[200px]" >
        <h4 class="text-md font-semibold uppercase mb-2">Bunker Information</h4>
        <table class="w-full whitespace-no-wrap" v-show="form.ops_vessel_id">
          <thead v-once>
            <tr class="w-full">
              <th>SL</th>
              <th class="w-72">Bunker Name</th>
              <th>Unit</th>
              <th>Presenet Stock</th>
              <th>Stock In</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(certificate, index) in form.opsBunkers">
              <td>
                {{ index+1 }}
              </td>
              <td>
                <!-- <v-select :options="materials" placeholder="--Choose an option--" @search="fetchBunker"  v-model="form.opsBunkers[index]" label="name" class="block form-input">
                    <template #search="{attributes, events}">
                        <input
                            class="vs__search"
                            :required="!form.opsBunkers[index]"
                            v-bind="attributes"
                            v-on="events"
                            :reaonly="true"
                            />
                    </template>
                </v-select> -->
                <span class="show-block !bg-gray-100">{{ form.opsBunkers[index].name }}</span>

              </td>
              <td>
                <span class="show-block !justify-center !bg-gray-100" v-if="form.opsBunkers[index]?.unit">{{ form.opsBunkers[index]?.unit }}</span>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="text" v-model.trim="form.opsBunkers[index].opening_balance" readonly placeholder="Present Stock" class="form-input text-right bg-gray-100" autocomplete="off" :disabled="formType=='edit'"/>
                </label>
              </td>
              <td>
                <label class="block w-full mt-2 text-sm">
                  <input type="number" v-model.trim="form.opsBunkers[index].quantity" placeholder="Stock In" class="form-input text-right" :class="{ '!bg-gray-100' : formType=='edit' }" autocomplete="off" :disabled="formType=='edit'"/>
                </label>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-bind:class="{'hidden': openTab !== 4, 'block': openTab === 4}" class="border border-gray-200 rounded-md my-3 p-2">
      <div class="">
        <h4 class="text-md font-semibold uppercase mb-2">Voyage Port Schedule</h4>
        
        <div v-for="(certificate, index) in form.opsVoyagePortSchedules" class="w-full mx-auto p-2 border rounded-md border-gray-400 mb-5 shadow-md">
          <label class="block w-1/2 mt-2 text-sm">

<span class="text-gray-700 dark-disabled:text-gray-300">Port Code <span class="text-red-500">*</span></span>

<v-select :options="ports" placeholder="Search Port" :loading="isPortLoading"  v-model="form.opsVoyagePortSchedules[index].port_code" label="code_name" class="block form-input" :reduce="port=>port.code">
  <template #search="{attributes, events}">
      <input
          class="vs__search"
          :required="!form.opsVoyagePortSchedules[index].port_code"
          v-bind="attributes"
          v-on="events"
          />
  </template>
</v-select>
</label>
          <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
                    
                    <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Operation Type <span class="text-red-500">*</span></span>

            <select v-model="form.opsVoyagePortSchedules[index].operation_type" class="form-input" required>
              <option value="" selected disabled>Select Type</option>
              <option value="Loading">Loading</option>
              <option value="Discharge">Discharge</option>
            </select>
          </label>

                    <label class="block w-full mt-2 text-sm">
                      <span class="text-gray-700 dark-disabled:text-gray-300">ATA </span>

                      <input type="datetime-local" v-model.trim="form.opsVoyagePortSchedules[index].ata" placeholder="" class="form-input text-right" autocomplete="off"/>
                    </label>

                    <label class="block w-full mt-2 text-sm">
                      <span class="text-gray-700 dark-disabled:text-gray-300">ATB</span>

                      <input type="datetime-local" v-model="form.opsVoyagePortSchedules[index].atb" placeholder="" class="form-input text-right" autocomplete="off"/>
                    </label>
                    <label class="block w-full mt-2 text-sm">
                      <span class="text-gray-700 dark-disabled:text-gray-300">ATD </span>

                      <input type="datetime-local" v-model="form.opsVoyagePortSchedules[index].atd" placeholder="" class="form-input text-right" autocomplete="off"/>
                    </label>
          </div>
          <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">

                    <label class="block w-full mt-2 text-sm">
                      <span class="text-gray-700 dark-disabled:text-gray-300">Load Commence </span>

                      <input type="datetime-local" v-model="form.opsVoyagePortSchedules[index].load_commence" placeholder="" class="form-input text-right" autocomplete="off"/>
                    </label>
                    <label class="block w-full mt-2 text-sm">
                      <span class="text-gray-700 dark-disabled:text-gray-300">Load Completed </span>

                      <input type="datetime-local" v-model="form.opsVoyagePortSchedules[index].load_complete" placeholder="" class="form-input text-right" autocomplete="off"/>
                    </label>
                    <label class="block w-full mt-2 text-sm">
                      <span class="text-gray-700 dark-disabled:text-gray-300">Unload Commence </span>

                      <input type="datetime-local" v-model="form.opsVoyagePortSchedules[index].unload_commence" placeholder="" class="form-input text-right" autocomplete="off"/>
                    </label>
                    <label class="block w-full mt-2 text-sm">
                      <span class="text-gray-700 dark-disabled:text-gray-300">Unload Completed </span>

                      <input type="datetime-local" v-model="form.opsVoyagePortSchedules[index].unload_complete" placeholder="" class="form-input text-right" autocomplete="off"/>
                    </label>
                    
                    
          </div>
          <div class="flex justify-center items-center my-3">
                    <button type="button" @click="addPortSchedule()" class="px-3 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center">
                      Add More
                    </button>
                    <button type="button" v-if="index>0" @click="removePortSchedule(index)" class="px-3 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple flex w-32 justify-center text-center ml-3">
                      Remove
                    </button> 
          </div>
        </div>      
      </div>
    </div>




    <button v-if="openTab==4" type="submit" :disabled="isLoading" class="flex float-right items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
      <span v-if="formType=='create'">Create Voyage</span>
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
    <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<script setup>
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import usePort from '../../composables/operations/usePort';
import useMaterial from '../../composables/supply-chain/useMaterial';
import useCustomer from '../../composables/operations/useCustomer';
import useVessel from '../../composables/operations/useVessel';
import useCargoType from '../../composables/operations/useCargoType';
import useVoyage from '../../composables/operations/useVoyage';
import { ref, watch, onMounted, watchEffect } from "vue";
import RemarksComponent from "../utils/RemarksComponent.vue";
import ErrorComponent from "../utils/ErrorComponent.vue";


const { ports, searchPorts, isPortLoading } = usePort();
const { customers, getCustomersByNameOrCode, isCustomerLoading } = useCustomer();
const { vessel, vessels, searchVessels, showVessel, isVesselLoading } = useVessel();
const { cargoTypes, searchCargoTypes, isCargoTypeLoading } = useCargoType();
const { materials, searchMaterialWithCategory } = useMaterial();
const { checkValidation } = useVoyage();

const props = defineProps({
    form: {
        required: false,
        default: {}
    },
    errors: { type: [Object, Array], required: false },
    portScheduleObject: { type: Object, required: false },
    voyageSectorObject: { type: Object, required: false },
    bunkerObject: { type: Object, required: false },
    formType: { type: Object, required: false },
});

const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  console.log("object "+ tabNumber);
  if (openTab.value === 1) {
    let tab1RequiredFields = ['business_unit', 'ops_customer_id', 'mother_vessel', 'ops_vessel_id', 'voyage_no', 'voyage_sequence', 'route', 'ops_cargo_type_id', 'load_port_distance',
      'sail_date', 'transit_date'];
    if (!checkValidation(openTab, tabNumber, props, tab1RequiredFields)) {
      return;
    }
  }

  else if (openTab.value === 2) {
    let tab2RequiredFields = ['loading_point', 'unloading_point', 'initial_survey_qty'];
    if (!checkValidation(openTab, tabNumber, props, tab2RequiredFields)) {
      return;
    }
  }
  else if (openTab.value === 4) {
    let tab4RequiredFields = ['operation_type', 'port_code'];
    if (!checkValidation(openTab, tabNumber, props, tab4RequiredFields)) {
      return;
    }
  }
  openTab.value = tabNumber;
}
const editInitiated = ref(false);

const fetchCustomers = (search, loading) => {
  // loading(true);
  getCustomersByNameOrCode(search, props.form.business_unit, loading);
}

function fetchVessels(search, loading) {
      // loading(true);
      searchVessels(search, props.form.business_unit, loading);
}

function fetchCargoTypes(search, loading) {
      // loading(true);
      searchCargoTypes(search, loading)
}

watch(() => props.form.ops_customer_name, (value) => {
  props.form.ops_customer_id = value?.id;
});

watch(() => props.form.ops_vessel_name, (value) => {
  props.form.ops_vessel_id = value?.id;
});

watch(() => props.form.ops_cargo_type_name, (value) => {
  props.form.ops_cargo_type_id = value?.id;
});



watch(() => props.form.voyage_no, (value) => {
  if(props.form.business_unit == 'TSLL') {
    props.form.voyage_sequence = value+'L'
  } else {
    props.form.voyage_sequence = value+'B'
  }
}, { deep: true })

watch(() => props.form.business_unit, (value) => {
  if((props?.formType == 'edit' && editInitiated.value == true) || (props.formType != 'edit')){
    props.form.ops_customer_name = null;
    props.form.ops_vessel_name = null;

    props.form.opsVoyageSectors = [];
    props.form.opsVoyageSectors.push({... props.voyageSectorObject });
  }

  fetchPorts("", false);

})

const bunkerReset = ref([]);

watch(() => props.form.ops_vessel_id, (newValue, oldValue) => {
 
// }, { deep: true })


// function bunkerInfo(){
//   let value = props.form.ops_vessel_id;
  props.form.ops_vessel_id = newValue;
  if(newValue !== oldValue && oldValue != '' && newValue != undefined){
    showVessel(newValue)
    .then(() => {
        bunkerReset.value = vessel?.value?.opsBunkers?.map(obj => {
      // Assuming you want to reset the resettableValue property to some default value
      return {
        ...obj,
        exchange_rate_bdt: null,
        exchange_rate_usd: null,
        rate: null,
        amount_usd: null,
        amount_bdt: null,
        quantity: null
      };
    });

      if((props?.formType == 'edit' && editInitiated.value == true) || (props.formType != 'edit')) {
          props.form.opsBunkers = bunkerReset.value
      // }
      } else {
      //   bunkerReset.value;
        editInitiated.value = true
      }
    })
    .catch((error) => {
      console.error("Error fetching data.", error);
    });
  }
});


watch(() => props.form, (value) => {

  if(props?.formType == 'edit' && editInitiated.value != true) {

    // customers.value = [props?.form?.opsCustomer]
    // vessels.value = [props?.form?.opsVessel]
    // cargoTypes.value = [props?.form?.opsCargoType]
    props.form.ops_customer_name = value?.opsCustomer;
    props.form.ops_vessel_name = value?.opsVessel;
    props.form.ops_cargo_type_name = value?.opsCargoType;

    if(props.form.opsVoyageSectors?.length < 1) {
      props.form.opsVoyageSectors.push({... props.voyageSectorObject });
    }

    if(props.form.opsVoyagePortSchedules?.length < 1) {
      props.form.opsVoyagePortSchedules.push({... props.portScheduleObject })
    }

    }
  });

// watch(() => props.form.ops_vessel_id, (value) => {
// if(value) {
//   if((props?.formType == 'edit' && editInitiated.value == true) || (props.formType != 'edit')) {
//     console.log("showing vessel")
//     showVessel(value)
//   }
// }
// }, {deep: true})
// OLD Functions
function addVoyageSector() {
  props.form.opsVoyageSectors.push({... props.voyageSectorObject });
}


function addPortSchedule() {
  props.form.opsVoyagePortSchedules.push({... props.portScheduleObject })
}

function removePortSchedule(index) {
  props.form.opsVoyagePortSchedules.splice(index, 1);
}

function removeVoyageSector(index){
  props.form.opsVoyageSectors.splice(index, 1);
}

function fetchBunker(search, loading) {
  loading(true);
  searchMaterialWithCategory(search, 1, loading)
}

function fetchPorts(search, loading) {
      // loading(true);
      searchPorts(search, props.form.business_unit, loading)
}

onMounted(() => {
  fetchCargoTypes("", false);
    watchEffect(() => {
      if(props.form.business_unit && props.form.business_unit != 'ALL'){
        fetchCustomers("", false);
        fetchVessels("", false);
      }
    });
});
</script>
<style lang="postcss" scoped>
.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}
.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark-disabled:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
}

/* Hide the default number input arrows */
input[type=number] {
  -moz-appearance: textfield; /* Firefox */
  -webkit-appearance: textfield; /* Chrome, Safari, Edge */
  appearance: textfield; /* Standard syntax */
}

/* Hide the spin buttons in Chrome */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>