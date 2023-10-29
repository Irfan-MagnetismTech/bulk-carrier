<template>
    <h4 class="text-md font-semibold">Voyage Info</h4>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      
      <business-unit-input v-model="form.business_unit"></business-unit-input>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Customer Name <span class="text-red-500">*</span></span>
        <v-select :options="customers" placeholder="--Choose an option--" @search="fetchCustomers"  v-model="form.ops_customer_id" label="name" class="block form-input" :reduce="port=>port.code">
            <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.ops_customer_id"
                    v-bind="attributes"
                    v-on="events"
                    />
            </template>
        </v-select>
        <Error v-if="errors?.port_of_registry" :errors="errors.port_of_registry" />
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Mother Vessel Name <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.name" placeholder="Voyage Name" class="form-input" required autocomplete="off" />
            <Error v-if="errors?.name" :errors="errors.name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Select Vessel <span class="text-red-500">*</span></span>
        <v-select :options="vessels" placeholder="--Choose an option--" @search="fetchVessels"  v-model="form.ops_vessel_id" label="name" class="block form-input" :reduce="vessel=>vessel.id">
            <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.ops_vessel_id"
                    v-bind="attributes"
                    v-on="events"
                    />
            </template>
        </v-select>
      </label>

    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-1/2 mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Voyage No <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.voyage_no" placeholder="Voyage No" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.voyage_no" :errors="errors.voyage_no" />
        </label>
        <label class="block w-1/2 mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Voyage Sequence <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.voyage_squence" readonly class="form-input bg-gray-100" required autocomplete="off" />
          <Error v-if="errors?.voyage_squence" :errors="errors.voyage_squence" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Route <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.route" placeholder="Route" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.route" :errors="errors.route" />
        </label>
        
        
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Cargo Type <span class="text-red-500">*</span></span>
          <v-select :options="cargoTypes" placeholder="--Choose an option--" @search="fetchCargoTypes"  v-model="form.ops_cargo_type_id" label="cargo_type" class="block form-input" :reduce="vessel=>vessel.id">
              <template #search="{attributes, events}">
                  <input
                      class="vs__search"
                      :required="!form.ops_cargo_type_id"
                      v-bind="attributes"
                      v-on="events"
                      />
              </template>
          </v-select>
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Load Port Distance (NM) <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.load_port_distance" placeholder="Load Port Distance (NM)" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.load_port_distance" :errors="errors.load_port_distance" />
        </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Sail Date <span class="text-red-500">*</span></span>
        <input type="date" v-model="form.sail_date" placeholder="Sail Date " class="form-input" required autocomplete="off" />
        <Error v-if="errors?.sail_date" :errors="errors.sail_date" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Transit Date <span class="text-red-500">*</span></span>
        <input type="date" v-model="form.transit_date" placeholder="Transit Date" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.transit_date" :errors="errors.transit_date" />
      </label>
      
      
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Remarks <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.remarks" placeholder="Remarks" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.remarks" :errors="errors.remarks" />
      </label>
    </div>

    

    <div class="mt-3 md:mt-8">
      <h4 class="text-md font-semibold uppercase mb-2">Voyage Certificates</h4>
      <table class="w-full whitespace-no-wrap" >
        <thead v-once>
          <tr class="w-full">
            <th>SL</th>
            <th class="!w-72">Certificate Name</th>
            <th>Certificate Type</th>
            <th>Validity Period</th>
            <th class="w-16">
              <button type="button" @click="addVoyageSector()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(certificate, index) in form.opsVoyageCertificates">
            <td>
              {{ index+1 }}
            </td>
            <td>
              <v-select :options="ports" placeholder="--Choose an option--" @search="fetchPorts"  v-model="form.opsVoyageCertificates[index]" label="name" class="block form-input">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.opsVoyageCertificates[index]"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
            </td>
            <td>
              <span class="show-block" v-if="form.opsVoyageCertificates[index]?.type">{{ form.opsVoyageCertificates[index]?.type }}</span>
            </td>
            <td>
              <span class="show-block" v-if="form.opsVoyageCertificates[index]?.validity">{{ form.opsVoyageCertificates[index]?.validity }}</span>
            </td>
            <td>
              <button type="button" @click="removeVoyageSector(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button> 
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-3 md:mt-8">
      <h4 class="text-md font-semibold uppercase mb-2">Bunker Information</h4>
      <table class="w-full whitespace-no-wrap" >
        <thead v-once>
          <tr class="w-full">
            <th>SL</th>
            <th>Bunker Name</th>
            <th>Unit</th>
            <th>Presenet Stock</th>
            <th>Stock In</th>
            <th class="w-16" :class="formType=='edit' ? 'hidden' : '' ">
              <button type="button" @click="addBunker()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(certificate, index) in form.opsBunkers">
            <td>
              {{ index+1 }}
            </td>
            <td class="w-72">
              <v-select :options="materials" placeholder="--Choose an option--" @search="fetchBunker"  v-model="form.opsBunkers[index]" label="name" class="block form-input">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.opsBunkers[index]"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
            </td>
            <td>
              <span class="show-block !justify-center !bg-gray-100" v-if="form.opsBunkers[index]?.unit">{{ form.opsBunkers[index]?.unit }}</span>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                <input type="text" v-model="form.opsBunkers[index].opening_balance" readonly placeholder="Present Stock" class="form-input text-right bg-gray-100" autocomplete="off" :disabled="formType=='edit'"/>
                <Error v-if="errors?.opsBunkers[index]?.opening_balance" :errors="errors.opsBunkers[index]?.opening_balance" />
              </label>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                <input type="text" v-model="form.opsBunkers[index].quantity" placeholder="Stock In" class="form-input text-right" autocomplete="off" :disabled="formType=='edit'"/>
                <Error v-if="errors?.opsBunkers[index]?.quantity" :errors="errors.opsBunkers[index]?.quantity" />
              </label>
            </td>
            <td :class="formType=='edit' ? 'hidden' : '' ">
              <button type="button" @click="removeBunker(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button> 
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-3 md:mt-8">
      <h4 class="text-md font-semibold uppercase mb-2">Voyage Port Schedule</h4>
      <div id="customDataTable">
        <div class="table-responsive max-w-screen" :class="{ 'overflow-x-auto': tableScrollWidth > screenWidth }">
      
          <table class="w-full whitespace-no-wrap" >
            <thead v-once>
              <tr class="w-full">
                <th class="!w-48 border-0 flex justify-center items-center mt-2">Port Code</th>
                <th>ATA</th>
                <th>ATB</th>
                <th>Load Commence</th>
                <th>Load Completed</th>
                <th>Unload Commence</th>
                <th>Unload Completed</th>
                <th>ATD</th>
                <th class="!w-36 border-0 flex justify-center items-center mt-2">Operation Type</th>
                <th class="w-16" >
                  <button type="button" @click="addPortSchedule()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(certificate, index) in form.opsVoyagePortSchedules">
                <td class="w-48">
                  <v-select :options="ports" placeholder="--Choose an option--" @search="fetchPorts"  v-model="form.opsVoyagePortSchedules[index].port_code" label="name" class="block form-input">
                    <template #search="{attributes, events}">
                        <input
                            class="vs__search"
                            :required="!form.opsVoyagePortSchedules[index].port_code"
                            v-bind="attributes"
                            v-on="events"
                            />
                    </template>
                  </v-select>
                </td>
                <td>
                  <label class="block w-full mt-2 text-sm">
                    <input type="date" v-model="form.opsVoyagePortSchedules[index].ata" placeholder="" class="form-input text-right bg-gray-100" autocomplete="off"/>
                    <Error v-if="errors?.opsVoyagePortSchedules[index]?.ata" :errors="errors.opsVoyagePortSchedules[index]?.ata" />
                  </label>
                </td>
                <td>
                  <label class="block w-full mt-2 text-sm">
                    <input type="date" v-model="form.opsVoyagePortSchedules[index].atb" placeholder="" class="form-input text-right bg-gray-100" autocomplete="off"/>
                    <Error v-if="errors?.opsVoyagePortSchedules[index]?.atb" :errors="errors.opsVoyagePortSchedules[index]?.atb" />
                  </label>
                </td>
                <td>
                  <label class="block w-full mt-2 text-sm">
                    <input type="date" v-model="form.opsVoyagePortSchedules[index].load_commence" placeholder="" class="form-input text-right bg-gray-100" autocomplete="off"/>
                    <Error v-if="errors?.opsVoyagePortSchedules[index]?.load_commence" :errors="errors.opsVoyagePortSchedules[index]?.load_commence" />
                  </label>
                </td>
                <td>
                  <label class="block w-full mt-2 text-sm">
                    <input type="date" v-model="form.opsVoyagePortSchedules[index].load_complete" placeholder="" class="form-input text-right bg-gray-100" autocomplete="off"/>
                    <Error v-if="errors?.opsVoyagePortSchedules[index]?.load_complete" :errors="errors.opsVoyagePortSchedules[index]?.load_complete" />
                  </label>
                </td>            
                <td>
                  <label class="block w-full mt-2 text-sm">
                    <input type="date" v-model="form.opsVoyagePortSchedules[index].unload_commence" placeholder="" class="form-input text-right bg-gray-100" autocomplete="off"/>
                    <Error v-if="errors?.opsVoyagePortSchedules[index]?.unload_commence" :errors="errors.opsVoyagePortSchedules[index]?.unload_commence" />
                  </label>
                </td>
                <td>
                  <label class="block w-full mt-2 text-sm">
                    <input type="date" v-model="form.opsVoyagePortSchedules[index].unload_complete" placeholder="" class="form-input text-right bg-gray-100" autocomplete="off"/>
                    <Error v-if="errors?.opsVoyagePortSchedules[index]?.unload_complete" :errors="errors.opsVoyagePortSchedules[index]?.unload_complete" />
                  </label>
                </td>
                <td>
                  <label class="block w-full mt-2 text-sm">
                    <input type="date" v-model="form.opsVoyagePortSchedules[index].atd" placeholder="" class="form-input text-right bg-gray-100" autocomplete="off"/>
                    <Error v-if="errors?.opsVoyagePortSchedules[index]?.atd" :errors="errors.opsVoyagePortSchedules[index]?.atd" />
                  </label>
                </td>
                <td>
                  <label class="block w-full mt-2 text-sm">
                    <select v-model="form.opsVoyagePortSchedules[index].operation_type" class="form-input">
                      <option value="Loading">Loading</option>
                      <option value="Discharge">Discharge</option>
                    </select>
                    <Error v-if="errors?.form.opsVoyagePortSchedules[index]?.operation_type" :errors="errors?.form.opsVoyagePortSchedules[index]?.operation_type" />
                  </label>
                </td>
                <td>
                  <button type="button" @click="removePortSchedule(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                  </button> 
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
</template>
<script setup>
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import usePort from '../../composables/operations/usePort';
import useMaterial from '../../composables/supply-chain/useMaterial';
import useCustomer from '../../composables/operations/useCustomer';
import useVessel from '../../composables/operations/useVessel';
import useCargoType from '../../composables/operations/useCargoType';
import { ref, watch } from "vue";


const { ports, searchPorts } = usePort();
const { customers, getCustomersByNameOrCode } = useCustomer();
const { vessel, vessels, searchVessels, showVessel } = useVessel();
const { cargoTypes, searchCargoTypes } = useCargoType();
const { materials, searchMaterialWithCategory } = useMaterial();

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

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;
const portScheduleInitialize = ref(false)

const fetchCustomers = (search, loading) => {
  loading(true);
  getCustomersByNameOrCode(search, props.form.business_unit, loading);
}

function fetchVessels(search, loading) {
      loading(true);
      searchVessels(search, props.form.business_unit, loading);
}

function fetchCargoTypes(search, loading) {
      loading(true);
      searchCargoTypes(search, loading)
}

watch(() => props.form.voyage_no, (value) => {
  if(props.form.business_unit == 'TSLL') {
    props.form.voyage_squence = value+'L'
  } else {
    props.form.voyage_squence = value+'B'
  }
})
// OLD Functions
function addVoyageSector() {
  // console.log(props.maritimeCertificateObject, "dfdf")
  props.form.opsVoyageSectors.push({... props.maritimeCertificateObject });
}

function addBunker() {
  props.form.opsBunkers.push({... props.bunkerObject });
}

function addPortSchedule() {
  props.form.opsVoyagePortSchedules.push({... props.portScheduleObject })

  if(!portScheduleInitialize.value) {
    setTimeout(function() {
      const customDataTable = document.getElementById("customDataTable");

      if (customDataTable) {
        tableScrollWidth.value = customDataTable.scrollWidth;
      }
      portScheduleInitialize.value = true;
    }, 1000);
  }
  

}

function removePortSchedule() {
  props.form.opsVoyagePortSchedules.splice(index, 1);
}

function removeVoyageSector(index){
  props.form.opsVoyageSectors.splice(index, 1);
}

function removeBunker(index){
  props.form.opsBunkers.splice(index, 1);
}

function fetchBunker(search, loading) {
  loading(true);
  searchMaterialWithCategory(search, 1, loading)
}

function fetchPorts(search, loading) {
      loading(true);
      searchPorts(search, loading)
}
</script>
<style lang="postcss" scoped>
.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
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
</style>