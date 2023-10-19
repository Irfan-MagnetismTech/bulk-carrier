<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      
      <business-unit-input v-model="form.business_unit"></business-unit-input>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Select Vessel <span class="text-red-500">*</span></span>
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
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel Type <span class="text-red-500">*</span></span>
            <select name="" id="" class="form-input" v-model="form.vessel_type">
              <option value="TSLL">TSLL</option>
              <option value="PSML">PSML</option>
            </select>
          <Error v-if="errors?.vessel_type" :errors="errors.vessel_type" />
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Owner Name <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.owner_name" placeholder="Owner Name" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.owner_name" :errors="errors.owner_name" />
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.name" placeholder="Vessel Name" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Previous Name <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.name" placeholder="Previous Name" class="form-input" required autocomplete="off" />
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
        
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Manager/Operator <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.manager" placeholder="Manager/Operator" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.manager" :errors="errors.manager" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Classification <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.classification" placeholder="Classification" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.classification" :errors="errors.classification" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Flag <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.flag" placeholder="Flag" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.flag" :errors="errors.flag" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Previous Flag <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.previous_flag" placeholder="Previous Flag" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.previous_flag" :errors="errors.previous_flag" />
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
        <span class="text-gray-700 dark:text-gray-300">Breadth <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.breadth" placeholder="Breadth" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.breadth" :errors="errors.breadth" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Depth (Moulded) <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.moulded_depth" placeholder="Depth (Moulded)" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.moulded_depth" :errors="errors.moulded_depth" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Maker SSAS <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.maker_ssas" placeholder="Maker SSAS" class="form-input" required autocomplete="off" />
        <Error v-if="errors?.maker_ssas" :errors="errors.maker_ssas" />
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
            <span class="text-gray-700 dark:text-gray-300">Class No <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.class_no" placeholder="Class No" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.class_no" :errors="errors.class_no" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">LOA <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.loa" placeholder="LOA" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.loa" :errors="errors.loa" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Depth <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.depth" placeholder="Depth" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.depth" :errors="errors.depth" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">ECDIS Type <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.ecdis_type" placeholder="ECDIS Type" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.ecdis_type" :errors="errors.ecdis_type" />
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Engine Type <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.engine_type" placeholder="Engine Type" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.engine_type" :errors="errors.engine_type" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">BHP <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.bhp" placeholder="BHP" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.bhp" :errors="errors.bhp" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Email <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.email" placeholder="Email" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.email" :errors="errors.email" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">LBC <span class="text-red-500">*</span></span>
          <input type="text" v-model="form.lbc" placeholder="LBC" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.lbc" :errors="errors.lbc" />
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


</template>
<script setup>
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useMaritimeCertificates from "../../composables/operations/useMaritimeCertificate";
import usePort from '../../composables/operations/usePort';

const props = defineProps({
    form: {
        required: false,
        default: {}
    },
    errors: { type: [Object, Array], required: false },
    maritimeCertificateObject: { type: Object, required: false }
});

const { maritimeCertificates, searchMaritimeCertificates } = useMaritimeCertificates();
const { ports, searchPorts } = usePort();

function addVesselCertificate() {
  console.log(props.maritimeCertificateObject, "dfdf")
  props.form.opsVesselCertificates.push({... props.maritimeCertificateObject });
}

function removeVesselCertificate(index){
  props.form.opsVesselCertificates.splice(index, 1);
}

function fetchMaritimeCertificates(search, loading) {
  loading(true);
  searchMaritimeCertificates(search, loading)
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