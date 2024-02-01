<template>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
        <label class="block w-full mt-2 text-sm"></label>
        <label class="block w-full mt-2 text-sm"></label>
        <label class="block w-full mt-2 text-sm"></label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Type <span class="text-red-500">*</span></span>
            <select name="" id="" class="form-input" v-model="form.vessel_type">
              <option value="" disabled>--Choose an option--</option>
              <option value="Lighter">Lighter</option>
              <option value="Bulk Carrier">Bulk Carrier</option>
            </select>
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Flag <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.flag" placeholder="Flag" class="form-input" required autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.name" placeholder="Vessel Name" class="form-input" required autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Short Code <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.short_code" maxlength="10" placeholder="Vessel Short Code" class="form-input" required autocomplete="off" :class="{ 'bg-gray-100': formType === 'edit' }" :disabled="formType=='edit'" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Call Sign <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.call_sign" placeholder="Call Sign" class="form-input" required autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Owner Name <span class="text-red-500">*</span></span>
          <input type="text" v-model.trim="form.owner_name" placeholder="Owner Name" class="form-input" required autocomplete="off" />
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-1/2 mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Manager/Operator <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.manager" placeholder="Manager/Operator" class="form-input" required autocomplete="off" />
      </label>
      <label class="block w-1/2 mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Classification <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.classification" placeholder="Classification" class="form-input" required autocomplete="off" />
      </label>
      
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Port of Registry <span class="text-red-500">*</span></span>
        <v-select :options="ports" placeholder="--Choose an option--" v-model="form.portOfRegistry" label="code_name" class="block form-input">
            <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.portOfRegistry"
                    v-bind="attributes"
                    v-on="events"
                    />
            </template>
        </v-select>
        <input type="hidden" v-model.trim="form.port_of_registry" />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Delivery Date <span class="text-red-500">*</span></span>
        <VueDatePicker v-model="form.delivery_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">NRT <span class="text-red-500">*</span></span>
        <input type="number" step="0.001" v-model="form.nrt" placeholder="NRT" class="form-input" required autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">DWT <span class="text-red-500">*</span></span>
        <input type="number" step="0.001" v-model="form.dwt" placeholder="DWT" class="form-input" required autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">IMO Number <span class="text-red-500">*</span></span>
        <input type="number" step="0.001" v-model="form.imo" placeholder="IMO Number" class="form-input" required autocomplete="off" />
      </label>
    </div>
    
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">GRT <span class="text-red-500">*</span></span>
        <input type="number" stpe="0.001" v-model="form.grt" placeholder="GRT" class="form-input" required autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Official Number <span class="text-red-500">*</span></span>
        <input type="number" v-model="form.official_number" placeholder="Official Number" class="form-input" required autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Keel Laying Date <span class="text-red-500">*</span></span>
        <VueDatePicker v-model="form.keel_laying_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Launching Date <span class="text-red-500">*</span></span>
        <VueDatePicker v-model="form.launching_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">MMSI <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.mmsi" placeholder="MMSI" class="form-input" required autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Length Overall <span class="text-red-500">*</span></span>
        <input type="number" step="0.001" v-model="form.overall_length" placeholder="Length Overall" class="form-input" required autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Width Overall <span class="text-red-500">*</span></span>
        <input type="number" step="0.001" v-model="form.overall_width" placeholder="Width Overall" class="form-input" required autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Year Built <span class="text-red-500">*</span></span>
        <input type="number" step="0.001" v-model="form.year_built" placeholder="Year Built" class="form-input" required autocomplete="off" />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Total Cargo Hold <span class="text-red-500">*</span></span>
        <input type="number" v-model="form.total_cargo_hold" placeholder="Total Cargo Hold" class="form-input" required autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Capacity <span class="text-red-500">*</span></span>
        <input type="number" step="0.001" v-model="form.capacity" placeholder="Capacity" class="form-input" required autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      
      <RemarksComponent v-model="form.remarks" :maxlength="500" :fieldLabel="'Remarks'"></RemarksComponent>
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
          <tr v-for="(certificate, index) in form.opsVesselCertificates" :key="index">
            <td>
              {{ index+1 }}
            </td>
            <td class="flex items-center">
              <v-select :options="maritimeCertificates" placeholder="--Choose an option--" v-model="form.opsVesselCertificates[index]" label="name" class="w-full block form-input">
                <template #search="{attributes, events}">
                    <input
                        class="vs__search"
                        :required="!form.opsVesselCertificates[index]"
                        v-bind="attributes"
                        v-on="events"
                        />
                </template>
              </v-select>
              <span v-show="isCertificateDuplicate" class="text-yellow-600 pl-1" title="Duplicate Material" v-html="icons.ExclamationTriangle"></span>

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
          <tr v-for="(bunker, index) in form.opsBunkers" :key="index">
            <td>
              {{ index+1 }}
            </td>
            <td class="flex items-center">
              <v-select v-if="!form.opsBunkers[index]?.is_readonly" :options="materials" placeholder="--Choose an option--" v-model="form.opsBunkers[index]" label="name" class="w-full block form-input">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.opsBunkers[index]"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
              <span v-else class="show-block !justify-center !bg-gray-100">{{ form.opsBunkers[index]?.name }}</span>
              <span v-show="isBunkerDuplicate" class="text-yellow-600 pl-1" title="Duplicate Material" v-html="icons.ExclamationTriangle"></span>

            </td>
            <td>
              <span class="show-block !justify-center !bg-gray-100" v-if="form.opsBunkers[index]?.unit">{{ form.opsBunkers[index]?.unit }}</span>
            </td>
            <td>
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
    <ErrorComponent :errors="errors"></ErrorComponent>

</template>
<script setup>
import { ref, watch, onMounted } from 'vue';
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useMaritimeCertificates from "../../composables/operations/useMaritimeCertificate";
import usePort from '../../composables/operations/usePort';
import useMaterial from '../../composables/supply-chain/useMaterial';
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import RemarksComponent from '../../components/utils/RemarksComponent.vue';
const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);
import useHeroIcon from "../../assets/heroIcon";
const icons = useHeroIcon();

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

const { maritimeCertificates, getMaritimeCertificateList } = useMaritimeCertificates();
const { ports, searchPorts } = usePort();
const { materials, getBunkerList } = useMaterial();

const isCertificateDuplicate = ref(false);
const isBunkerDuplicate = ref(false);

function addVesselCertificate() {
  // console.log(props.maritimeCertificateObject, "dfdf")
  props.form.opsVesselCertificates.push({... props.maritimeCertificateObject });
}

watch(() => props.form.portOfRegistry, (value) => {
  if(value) {
    props.form.port_of_registry = props.form.portOfRegistry.code
  }
})

function addBunker() {
  props.form.opsBunkers.push({... props.bunkerObject });
}

function removeVesselCertificate(index){
  props.form.opsVesselCertificates.splice(index, 1);
}

function removeBunker(index){
  props.form.opsBunkers.splice(index, 1);
}

// function fetchMaritimeCertificates(search, loading) {
//   loading(true);
//   searchMaritimeCertificates(search, loading)
// }

// function fetchBunker(search, loading) {
//   loading(true);
//   searchMaterialWithCategory(search, 1, loading)
// }

// function fetchPorts(search, loading) {
//       loading(true);
//       searchPorts(search, loading)
// }

watch(() => props.form.business_unit, (value) => {
  searchPorts("", props.form.business_unit);
}, { deep : true })

watch(() => props.form.opsVesselCertificates, (value) => {
  props.form.opsVesselCertificates.some((certificate, index) => {
            if (props.form.opsVesselCertificates.filter(val => val.name === certificate.name)?.length > 1) {
              isCertificateDuplicate.value = true;
            } else {
              isCertificateDuplicate.value = false;

            }
          })
}, { deep : true })

watch(() => props.form.opsBunkers, (value) => {

    props.form.opsBunkers.some((material, index) => {
            if (props.form.opsBunkers.filter(val => val.name === material.name)?.length > 1) {
              isBunkerDuplicate.value = true;
            } else {
              isBunkerDuplicate.value = false;

            }
		});
}, { deep : true })

onMounted(() => {
  getMaritimeCertificateList();
  getBunkerList();
  // getCurrencies();
  // getCargoTypeList();
  // getVesselList(props.form.business_unit);
  // searchPorts(null, true);
  // searchCargoTypes(null,);
})
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
</style>