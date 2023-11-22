<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
    <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">Report Type </span>
        <select v-model="form.report_type" class="form-input">
          <option value="" disabled>Select Option</option>
          <option value="Noon Report">Noon Report</option>
          <option value="Arrival Report">Arrival Report</option>
          <option value="Departure Report">Departure Report</option>
        </select>
        <Error v-if="errors?.report_type" :errors="errors.report_type" />
    </label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Vessel </span>
            <v-select :options="vessels" placeholder="--Choose an option--" v-model="form.opsVessel" label="name" class="block form-input">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.opsVessel"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
            <input type="hidden" v-model="form.ops_vessel_id" />
    </label>
    <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Voyage </span>
            <v-select :options="voyages" placeholder="--Choose an option--" @search="fetchVoyages"  v-model="form.opsVoyage" label="voyage_sequence" class="block form-input">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.opsVoyage"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
            <input type="hidden" v-model="form.ops_voyage_id" />
    </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Date/Time </span>
      <input type="text" v-model="form.model_name" placeholder="Date/Time" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">GMT Time </span>
      <input type="text" v-model="form.model_name" placeholder="GMT Time" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Location </span>
      <input type="text" v-model="form.model_name" placeholder="Location" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Latitude </span>
      <input type="text" v-model="form.model_name" placeholder="Latitude" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">Longitude </span>
        <input type="text" v-model="form.model_name" placeholder="Longitude" class="form-input" autocomplete="off" />
        <Error v-if="errors?.model_name" :errors="errors.model_name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">Fuel Figures From </span>
        <input type="text" v-model="form.model_name" placeholder="Fuel Figures From" class="form-input" autocomplete="off" />
        <Error v-if="errors?.model_name" :errors="errors.model_name" />
      </label>
  </div>

  <h4 class="text-md font-semibold">Upcoming Ports</h4>

  <div class="dt-responsive table-responsive">
    <table id="dataTable" class="w-full table table-striped table-bordered">
      <thead>
        <tr>
          <th class="w-64">Last Port</th>
          <th class="w-64">Next Port</th>
          <th>ETA</th>
          <th>Distance Run</th>
          <th>DTG</th>
          <th>Remarks</th>
          <th class="w-16">
            <button type="button" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
            </button>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>

        </tr>
      </tbody>
    </table>
  </div>
  <div class="hidden flex flex-col justify-center w-full md:flex-row md:gap-2">

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">Last Port <span class="text-red-500">*</span></span>
        <v-select :options="ports" placeholder="Search Port" v-model="form.lastPort" label="code_name" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.lastPort"
                  v-bind="attributes"
                  v-on="events"
                  />
          </template>
        </v-select>
        <input type="hidden" v-model="form.last_port" />
      </label>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">Next Port <span class="text-red-500">*</span></span>
        <v-select :options="ports" placeholder="Search Port" v-model="form.nextPort" label="code_name" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.nextPort"
                  v-bind="attributes"
                  v-on="events"
                  />
          </template>
        </v-select>
        <input type="hidden" v-model="form.next_port" />
      </label>


  </div>

  <h4 class="text-md font-semibold">Distance and Vessel</h4>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">CP/Ordered Speed </span>
        <input type="text" v-model="form.model_name" placeholder="CP/Ordered Speed" class="form-input" autocomplete="off" />
        <Error v-if="errors?.model_name" :errors="errors.model_name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">Average RPM </span>
        <input type="text" v-model="form.model_name" placeholder="Average RPM" class="form-input" autocomplete="off" />
        <Error v-if="errors?.model_name" :errors="errors.model_name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">Reported Speed </span>
        <input type="text" v-model="form.model_name" placeholder="Reported Speed" class="form-input" autocomplete="off" />
        <Error v-if="errors?.model_name" :errors="errors.model_name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">Fwd Draft </span>
        <input type="text" v-model="form.model_name" placeholder="Fwd Draft" class="form-input" autocomplete="off" />
        <Error v-if="errors?.model_name" :errors="errors.model_name" />
      </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Observed Distance </span>
      <input type="text" v-model="form.model_name" placeholder="Observed Distance" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Mild Draft </span>
      <input type="text" v-model="form.model_name" placeholder="Mild Draft" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Engine Distance </span>
      <input type="text" v-model="form.model_name" placeholder="Engine Distance" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Aft Draft </span>
      <input type="text" v-model="form.model_name" placeholder="Aft Draft" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Main Engine Revs </span>
      <input type="text" v-model="form.model_name" placeholder="Main Engine Revs" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Heading </span>
      <input type="text" v-model="form.model_name" placeholder="Heading" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Slip % </span>
      <input type="text" v-model="form.model_name" placeholder="Slip %" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Steaming Hours </span>
      <input type="text" v-model="form.model_name" placeholder="Steaming Hours" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Salinity </span>
      <input type="text" v-model="form.model_name" placeholder="Salinity" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">S. DWT </span>
      <input type="text" v-model="form.model_name" placeholder="S. DWT" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">Ballast </span>
      <input type="text" v-model="form.model_name" placeholder="Ballast" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 ">S. Displacement </span>
      <input type="text" v-model="form.model_name" placeholder="S. Displacement" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">FW Last Day Noon ROB </span>
      <input type="text" v-model="form.model_name" placeholder="FW Last Day Noon ROB" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">FW Production </span>
      <input type="text" v-model="form.model_name" placeholder="FW Production" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">FW Consumption </span>
      <input type="text" v-model="form.model_name" placeholder="FW Consumption" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">FW Today Noon ROB </span>
      <input type="text" v-model="form.model_name" placeholder="FW Today Noon ROB" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
  </div>

  <div class="mt-5">
    <h4 class="text-md font-semibold my-3">Cargo Tank Info</h4>

    <table class="w-full whitespace-no-wrap" >
        <thead v-once>
          <tr class="w-full">
            <th class="w-72">Cargo Tanks</th>
            <th>Liq Level</th>
            <th><nobr> Pressure </nobr></th>
            <th><nobr> Vapor Temp </nobr></th>
            <th><nobr> Liq Temp </nobr></th>
            <th><nobr> Quantity (MT) </nobr></th>
            <th class="w-16">
              <button type="button" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </th>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
      </table>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">Master </span>
      <input type="text" v-model="form.model_name" placeholder="Master" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700">Chief Engineer </span>
      <input type="text" v-model="form.model_name" placeholder="Chief Engineer" class="form-input" autocomplete="off" />
      <Error v-if="errors?.model_name" :errors="errors.model_name" />
    </label>
  </div>

  <div class="dt-responsive table-responsive">
    <table id="dataTable" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Type</th>
          <th>Previous ROB</th>
          <th>Received</th>
          <th colspan="2">Consumption Used For</th>
          <th>ROB</th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        
      </tbody>
    </table>
  </div>

  <div id="bunkers" class="mt-5" v-if="form.opsBunkers?.length > 0">
    <h4 class="text-md font-semibold my-3">Bunker Info</h4>

    <table class="w-full whitespace-no-wrap" >
        <thead v-once>
          <tr class="w-full">
            <th>SL</th>
            <th class="w-72">Bunker Name</th>
            <th>Unit</th>
            <th><nobr> Present Stock </nobr></th>
            <th><nobr> FUEL - CON/24H </nobr></th>
            <th><nobr> FUEL - CON/Voyage </nobr></th>
            <th class="hidden"><nobr> FUEL - Stock/L </nobr></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(certificate, index) in form.opsBunkers">
            <td>
              {{ index+1 }}
            </td>
            <td>
              
              <span class="show-block !bg-gray-100">{{ form.opsBunkers[index].name }}</span>
            </td>
            <td>
              <span class="show-block !justify-center !bg-gray-100" v-if="form.opsBunkers[index]?.unit">{{ form.opsBunkers[index]?.unit }}</span>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                <span class="show-block !block !bg-gray-100 !text-right">{{ form.opsBunkers[index].opening_balance }}</span>
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                <input type="number" step="0.001" v-model.trim="form.opsBunkers[index].fuel_con_24h" placeholder="FUEL - CON/24H" class="form-input text-right" autocomplete="off"/>
                <Error v-if="errors?.opsBunkers[index]?.fuel_con_24h" :errors="errors.opsBunkers[index]?.fuel_con_24h" />
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                <input type="number" step="0.001" v-model.trim="form.opsBunkers[index].fuel_con_voyage" placeholder="FUEL - CON/Voyage" class="form-input text-right" autocomplete="off"/>
                <Error v-if="errors?.opsBunkers[index]?.fuel_con_voyage" :errors="errors.opsBunkers[index]?.fuel_con_voyage" />
              </label>
            </td>
            <td class="hidden">
              <label class="block w-full mt-2 text-sm">
                <input type="number" step="0.001" v-model.trim="form.opsBunkers[index].fuel_stock_l" placeholder="FUEL - Stock/L" class="form-input text-right" autocomplete="off"/>
                <Error v-if="errors?.opsBunkers[index]?.fuel_stock_l" :errors="errors.opsBunkers[index]?.fuel_stock_l" />
              </label>
            </td>
          </tr>
        </tbody>
      </table>
  </div>
  
</template>
<script setup>
import { ref, watch, onMounted } from "vue";
import Error from "../Error.vue";
import useVoyage from "../../composables/operations/useVoyage";
import useVessel from "../../composables/operations/useVessel";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import usePort from '../../composables/operations/usePort';

const editInitiated = ref(false);

const { ports, getPortList } = usePort();
const { voyage, voyages, showVoyage, getVoyageList } = useVoyage();
const { vessel, vessels, getVesselList, showVessel } = useVessel();

const props = defineProps({
  form: {
      required: false,
      default: {},
  },
  errors: { type: [Object, Array], required: false },
  formType: { type: String, required : false }
});

// function fetchPorts(search, loading) {
//       loading(true);
//       searchPorts(search, loading)
// }

// function fetchVoyages(searchParam, loading) {
//   loading(true)
//   searchVoyages(searchParam, props.form.business_unit, loading)
// }

// function fetchVessels(search, loading) {
//       loading(true);
//       searchVessels(search, props.form.business_unit, loading);
// }

watch(() => props.form.business_unit, (value) => {
if(props?.formType != 'edit') {
  props.form.opsVoyage = null;
  props.form.ops_voyage_id = null;
  props.form.opsVessel = null;
  props.form.ops_vessel_id = null;
}

getVesselList(props.form.business_unit);

}, { deep : true })

watch(() => props.form.opsVoyage, (value) => {

if(value) {
  props.form.ops_voyage_id = value?.id
}
}, { deep: true })

watch(() => props.form.opsVessel, (value) => {

if(value) {
  props.form.ops_vessel_id = value?.id
  showVessel(value?.id);
  getVoyageList(props.form.business_unit, props.form.ops_vessel_id);
}
}, { deep: true })

watch(() => props.form.lastPort, (value) => {
if(value) {
  props.form.last_port = value?.code
}
}, { deep: true })

watch(() => props.form.nextPort, (value) => {
if(value) {
  props.form.next_port = value?.code
}
}, { deep: true })


watch(() => vessel, (value) => {
if(value?.value) {
  if(props?.formType != 'edit' || (props?.formType == 'edit' && editInitiated.value == true)) {
    props.form.opsBunkers = value?.value?.opsBunkers
  }
}
}, { deep: true })

watch(() => props.form, (value) => {

if(props?.formType == 'edit' && editInitiated.value != true) {
 
  if(vessels.value.length > 0) {
      editInitiated.value = true
    }
}
}, {deep: true});


onMounted(() => {
getVesselList(props.form.business_unit);
getPortList();
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
@apply text-gray-700 ;
}
.label-item-input {
@apply block w-full mt-1 text-sm rounded    focus:border-purple-400 focus:outline-none focus:shadow-outline-purple  disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed 
}
.form-input {
@apply block mt-1 text-sm rounded    focus:border-purple-400 focus:outline-none focus:shadow-outline-purple 
}
</style>