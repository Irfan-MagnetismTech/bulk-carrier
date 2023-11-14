<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      
      <business-unit-input v-model="form.business_unit"></business-unit-input>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel Type <span class="text-red-500">*</span></span>
            <select name="" id="" class="form-input" v-model="form.vessel_type">
              <option value="TSLL">TSLL</option>
              <option value="PSML">PSML</option>
            </select>
          <Error v-if="errors?.vessel_type" :errors="errors.vessel_type" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Flag <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.flag" placeholder="Flag" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.flag" :errors="errors.flag" />
      </label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.name" placeholder="Vessel Name" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel Short Code <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.short_code" placeholder="Vessel Short Code" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.short_code" :errors="errors.short_code" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Call Sign <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.call_sign" placeholder="Call Sign" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.call_sign" :errors="errors.call_sign" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Owner Name <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.owner_name" placeholder="Owner Name" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.owner_name" :errors="errors.owner_name" />
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-1/2 mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Manager/Operator <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.manager" placeholder="Manager/Operator" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.manager" :errors="errors.manager" />
      </label>
      <label class="block w-1/2 mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Classification <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.classification" placeholder="Classification" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.classification" :errors="errors.classification" />
      </label>
      
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Port of Registry <span class="text-red-500">*</span></span>
        <v-select :options="ports" placeholder="--Choose an option--" @search="fetchPorts"  v-model="form.port_of_registry" label="name" class="block form-input" :reduce="port=>port.code">
            <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.port_of_registry"
                    v-bind="attributes"
                    v-on="events"
                    />
            </template>
        </v-select>
        <Error v-if="errors?.port_of_registry" :errors="errors.port_of_registry" />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Delivery Date <span class="text-red-500">*</span></span>
        <input type="date" v-model="form.delivery_date" placeholder="Delivery Date" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.delivery_date" :errors="errors.delivery_date" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">NRT <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.nrt" placeholder="NRT" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.nrt" :errors="errors.nrt" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">DWT <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.dwt" placeholder="DWT" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.dwt" :errors="errors.dwt" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">IMO Number <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.imo" placeholder="IMO Number" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.imo" :errors="errors.imo" />
      </label>
    </div>
    
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">GRT <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.grt" placeholder="GRT" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.grt" :errors="errors.grt" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Official Number <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.official_number" placeholder="Official Number" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.official_number" :errors="errors.official_number" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Keel Laying Date <span class="text-red-500">*</span></span>
        <input type="date" v-model="form.keel_laying_date" placeholder="Keel Laying Date" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.keel_laying_date" :errors="errors.keel_laying_date" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Launching Date <span class="text-red-500">*</span></span>
        <input type="date" v-model="form.launching_date" placeholder="Launching Date" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.launching_date" :errors="errors.launching_date" />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">MMSI <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.mmsi" placeholder="MMSI" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.mmsi" :errors="errors.mmsi" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Length Overall <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.overall_length" placeholder="Length Overall" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.overall_length" :errors="errors.overall_length" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Width Overall <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.overall_width" placeholder="Width Overall" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.overall_width" :errors="errors.overall_width" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Year Built <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.year_built" placeholder="Year Built" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.year_built" :errors="errors.year_built" />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Total Cargo Hold <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.total_cargo_hold" placeholder="Total Cargo Hold" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.total_cargo_hold" :errors="errors.total_cargo_hold" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Capacity <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.capacity" placeholder="Capacity" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.capacity" :errors="errors.capacity" />
      </label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Remarks </span>
        <input type="text" v-model="form.remarks" placeholder="Remarks" class="form-input" autocomplete="off" />
        <Error v-if="errors?.remarks" :errors="errors.remarks" />
      </label>
    </div>

    <div class="mt-3 md:mt-8">
      <h4 class="text-md font-semibold uppercase mb-2">Vessel Certificates</h4>
      <table class="w-full whitespace-no-wrap" >
        <thead v-once>
          <tr class="w-full">
            <th class="!w-12">SL</th>
            <th class="!w-80">Certificate Name</th>
            <th>Certificate Type</th>
            <th>Validity Period</th>
            <th class="w-16">
              <button type="button" @click="addVesselCertificate()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
              </button>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(certificate, index) in form.opsVesselCertificates">
            <td>
              {{ index+1 }}
            </td>
            <td>
              <v-select :options="maritimeCertificates" placeholder="--Choose an option--" @search="fetchMaritimeCertificates"  v-model="form.opsVesselCertificates[index]" label="name" class="w-full block form-input">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.opsVesselCertificates[index]"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
            </v-select>
            </td>
            <td>
              <span class="show-block" v-if="form.opsVesselCertificates[index]?.type">{{ form.opsVesselCertificates[index]?.type }}</span>
            </td>
            <td>
              <span class="show-block" v-if="form.opsVesselCertificates[index]?.validity">{{ form.opsVesselCertificates[index]?.validity }}</span>
            </td>
            <td>
              <button type="button" @click="removeVesselCertificate(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
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
            <th class="!w-12">SL</th>
            <th class="!w-80">Bunker Name</th>
            <th class="!w-20">Unit</th>
            <th>Opening Balance</th>
            <th class="w-16">
              <!--  :class="formType=='edit' ? 'hidden' : '' " -->
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
            <td>
              <!-- <v-select :options="materials" placeholder="--Choose an option--" @search="fetchBunker"  v-model="form.opsBunkers[index]" label="name" class="w-full block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsBunkers[index]"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select> -->
              <span class="show-block !justify-center !bg-gray-100" v-if="form.opsBunkers[index]?.name">{{ form.opsBunkers[index]?.name }}</span>

            </td>
            <td>
              <span class="show-block !justify-center !bg-gray-100" v-if="form.opsBunkers[index]?.unit">{{ form.opsBunkers[index]?.unit }}</span>
            </td>
            <td>
              <label class="block w-full mt-2 text-sm">
                <input type="number" step="0.001" v-model="form.opsBunkers[index].opening_balance" placeholder="Opening Balance" class="form-input text-right" autocomplete="off" :disabled="formType=='edit'"/>
                <Error v-if="errors?.opsBunkers[index]?.opening_balance" :errors="errors.opsBunkers[index]?.opening_balance" />
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
</template>
<script setup>
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useMaritimeCertificates from "../../composables/operations/useMaritimeCertificate";
import usePort from '../../composables/operations/usePort';
import useMaterial from '../../composables/supply-chain/useMaterial';

const props = defineProps({
    form: {
        required: false,
        default: {}
    },
    errors: { type: [Object, Array], required: false },
    maritimeCertificateObject: { type: Object, required: false },
    bunkerObject: { type: Object, required: false },
    formType: { type: Object, required: false }
});

const { maritimeCertificates, searchMaritimeCertificates } = useMaritimeCertificates();
const { ports, searchPorts } = usePort();
const { materials, searchMaterialWithCategory } = useMaterial();
function addVesselCertificate() {
  // console.log(props.maritimeCertificateObject, "dfdf")
  props.form.opsVesselCertificates.push({... props.maritimeCertificateObject });
}

function addBunker() {
  props.form.opsBunkers.push({... props.bunkerObject });
}

function removeVesselCertificate(index){
  props.form.opsVesselCertificates.splice(index, 1);
}

function removeBunker(index){
  props.form.opsBunkers.splice(index, 1);
}

function fetchMaritimeCertificates(search, loading) {
  loading(true);
  searchMaritimeCertificates(search, loading)
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