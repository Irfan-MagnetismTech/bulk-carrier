<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input v-model="form.business_unit"></business-unit-input>

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
      
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel Type </span>
           
            <input type="text" v-model="form.vessel_type" placeholder="Vessel Type" class="form-input" autocomplete="off" />

          <Error v-if="errors?.vessel_type" :errors="errors.vessel_type" />
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Owner Name </span>
          <input type="text" v-model="form.owner_name" placeholder="Owner Name" class="form-input" autocomplete="off" />
          <Error v-if="errors?.owner_name" :errors="errors.owner_name" />
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Previous Name </span>
            <input type="text" v-model="form.previous_name" placeholder="Previous Name" class="form-input" autocomplete="off" />
          <Error v-if="errors?.previous_name" :errors="errors.previous_name" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Vessel Short Code </span>
            <input type="text" v-model="form.short_code" placeholder="Vessel Short Code" class="form-input" autocomplete="off" />
          <Error v-if="errors?.short_code" :errors="errors.short_code" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Call Sign </span>
            <input type="text" v-model="form.call_sign" placeholder="Call Sign" class="form-input" autocomplete="off" />
          <Error v-if="errors?.call_sign" :errors="errors.call_sign" />
        </label>
        <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Manager/Operator </span>
        <input type="text" v-model="form.manager" placeholder="Manager/Operator" class="form-input" autocomplete="off" />
        <Error v-if="errors?.manager" :errors="errors.manager" />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Classification </span>
        <input type="text" v-model="form.classification" placeholder="Classification" class="form-input" autocomplete="off" />
        <Error v-if="errors?.classification" :errors="errors.classification" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Flag </span>
        <input type="text" v-model="form.flag" placeholder="Flag" class="form-input" autocomplete="off" />
        <Error v-if="errors?.flag" :errors="errors.flag" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Previous Flag</span>
        <input type="text" v-model="form.previous_flag" placeholder="Previous Flag" class="form-input" autocomplete="off" />
        <Error v-if="errors?.previous_flag" :errors="errors.previous_flag" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Delivery Date </span>
        <input type="date" v-model="form.delivery_date" placeholder="Delivery Date" class="form-input" autocomplete="off" />
        <Error v-if="errors?.delivery_date" :errors="errors.delivery_date" />
      </label>
      
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">GRT </span>
        <input type="text" v-model="form.grt" placeholder="GRT" class="form-input" autocomplete="off" />
        <Error v-if="errors?.grt" :errors="errors.grt" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">NRT </span>
        <input type="text" v-model="form.nrt" placeholder="NRT" class="form-input" autocomplete="off" />
        <Error v-if="errors?.nrt" :errors="errors.nrt" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">DWT </span>
        <input type="text" v-model="form.dwt" placeholder="DWT" class="form-input" autocomplete="off" />
        <Error v-if="errors?.dwt" :errors="errors.dwt" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">IMO Number </span>
        <input type="text" v-model="form.imo" placeholder="IMO Number" class="form-input" autocomplete="off" />
        <Error v-if="errors?.imo" :errors="errors.imo" />
      </label>
    </div>
    
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Class No </span>
            <input type="text" v-model="form.class_no" placeholder="Class No" class="form-input" autocomplete="off" />
          <Error v-if="errors?.class_no" :errors="errors.class_no" />
        </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Official Number </span>
        <input type="text" v-model="form.official_number" placeholder="Official Number" class="form-input" autocomplete="off" />
        <Error v-if="errors?.official_number" :errors="errors.official_number" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Keel Laying Date </span>
        <input type="date" v-model="form.keel_laying_date" placeholder="Keel Laying Date" class="form-input" autocomplete="off" />
        <Error v-if="errors?.keel_laying_date" :errors="errors.keel_laying_date" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Launching Date </span>
        <input type="date" v-model="form.launching_date" placeholder="Launching Date" class="form-input" autocomplete="off" />
        <Error v-if="errors?.launching_date" :errors="errors.launching_date" />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">MMSI </span>
        <input type="text" v-model="form.mmsi" placeholder="MMSI" class="form-input" autocomplete="off" />
        <Error v-if="errors?.mmsi" :errors="errors.mmsi" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Length Overall </span>
        <input type="text" v-model="form.overall_length" placeholder="Length Overall" class="form-input" autocomplete="off" />
        <Error v-if="errors?.overall_length" :errors="errors.overall_length" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Width Overall </span>
        <input type="text" v-model="form.overall_width" placeholder="Width Overall" class="form-input" autocomplete="off" />
        <Error v-if="errors?.overall_width" :errors="errors.overall_width" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Year Built </span>
        <input type="text" v-model="form.year_built" placeholder="Year Built" class="form-input" autocomplete="off" />
        <Error v-if="errors?.year_built" :errors="errors.year_built" />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Breadth </span>
        <input type="text" v-model="form.breadth" placeholder="Breadth" class="form-input" autocomplete="off" />
        <Error v-if="errors?.breadth" :errors="errors.breadth" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Depth (Moulded) </span>
        <input type="text" v-model="form.depth_moulded" placeholder="Depth (Moulded)" class="form-input" autocomplete="off" />
        <Error v-if="errors?.depth_moulded" :errors="errors.depth_moulded" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Maker SSAS </span>
        <input type="text" v-model="form.maker_ssas" placeholder="Maker SSAS" class="form-input" autocomplete="off" />
        <Error v-if="errors?.maker_ssas" :errors="errors.maker_ssas" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Port of Registry </span>
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
            <span class="text-gray-700 dark:text-gray-300">Engine Type </span>
            <input type="text" v-model="form.engine_type" placeholder="Engine Type" class="form-input" autocomplete="off" />
          <Error v-if="errors?.engine_type" :errors="errors.engine_type" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">LOA </span>
            <input type="text" v-model="form.loa" placeholder="LOA" class="form-input" autocomplete="off" />
          <Error v-if="errors?.loa" :errors="errors.loa" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Depth </span>
            <input type="text" v-model="form.depth" placeholder="Depth" class="form-input" autocomplete="off" />
          <Error v-if="errors?.depth" :errors="errors.depth" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">ECDIS Type </span>
          <input type="text" v-model="form.ecdis_type" placeholder="ECDIS Type" class="form-input" autocomplete="off" />
          <Error v-if="errors?.ecdis_type" :errors="errors.ecdis_type" />
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">BHP </span>
            <input type="text" v-model="form.bhp" placeholder="BHP" class="form-input" autocomplete="off" />
          <Error v-if="errors?.bhp" :errors="errors.bhp" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Email </span>
            <input type="text" v-model="form.email" placeholder="Email" class="form-input" autocomplete="off" />
          <Error v-if="errors?.email" :errors="errors.email" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">LBC </span>
          <input type="text" v-model="form.lbc" placeholder="LBC" class="form-input" autocomplete="off" />
          <Error v-if="errors?.lbc" :errors="errors.lbc" />
        </label>
        <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Total Cargo Hold </span>
        <input type="text" v-model="form.total_cargo_hold" placeholder="Total Cargo Hold" class="form-input" autocomplete="off" />
        <Error v-if="errors?.total_cargo_hold" :errors="errors.total_cargo_hold" />
      </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Capacity </span>
        <input type="text" v-model="form.capacity" placeholder="Capacity" class="form-input" autocomplete="off" />
        <Error v-if="errors?.capacity" :errors="errors.capacity" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Tues Capacity </span>
        <input type="text" v-model="form.tues_capacity" placeholder="Capacity" class="form-input" autocomplete="off" />
        <Error v-if="errors?.tues_capacity" :errors="errors.tues_capacity" />
      </label>
      
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <h2 class="text-gray-600 dark:text-gray-300 text-2xl font-semibold py-3 text-center">
        Attachment (Vessel Particular For Charterer)
      </h2>

        <template v-if="form.attachment">
           <a class="text-red-700" target="_blank" :href="env.BASE_API_URL+'/'+form?.attachment">{{
            (typeof $props.form?.attachment === 'string')
                ? '('+$props.form?.attachment.split('/').pop()+')'
                : ''
          }}</a>
        </template>
      <DropZoneV2 :form="form" :page="page"></DropZoneV2>
    </label>
  </div>


</template>
<script setup>
import { ref, computed, watch } from "vue";
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import usePort from '../../composables/operations/usePort';
import useVessel from '../../composables/operations/useVessel';
import DropZoneV2 from '../../components/DropZoneV2.vue';
import {useStore} from "vuex";
import env from '../../config/env';

const store = useStore();
const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));

const props = defineProps({
    form: {
        required: false,
        default: {}
    },
    errors: { type: [Object, Array], required: false },
    maritimeCertificateObject: { type: Object, required: false }
});

const { ports, searchPorts } = usePort();
const { vessel, vessels, searchVessels, showVessel } = useVessel();

function fetchVessels(search, loading) {
      loading(true);
      searchVessels(search, props.form.business_unit, loading);
}

function fetchPorts(search, loading) {
      loading(true);
      searchPorts(search, loading)
}

watch(() => props.form, (value) => {

  if(props?.formType == 'edit' && editInitiated.value != true) {

    vessels.value = [props?.form?.opsVessel]

    if(vessels.value.length > 0) {
      editInitiated.value = true
    }
  }
}, {deep: true});

watch(() => props.form.ops_vessel_id, (value) => {
  if(value) {
    showVessel(value)
  }
}, {deep: true})

watch(() => vessel, (value) => {
  props.form.business_unit = value.value?.business_unit;
  props.form.vessel_type = value.value?.vessel_type;
  props.form.owner_name = value.value?.owner_name;
  props.form.previous_name = value.value?.previous_name;
  props.form.short_code = value.value?.short_code;
  props.form.call_sign = value.value?.call_sign;
  props.form.manager = value.value?.manager;
  props.form.classification = value.value?.classification;
  props.form.flag = value.value?.flag;
  props.form.previous_flag = value.value?.previous_flag;
  props.form.delivery_date = value.value?.delivery_date;
  props.form.grt = value.value?.grt;
  props.form.nrt = value.value?.nrt;
  props.form.dwt = value.value?.dwt;
  props.form.imo = value.value?.imo;
  props.form.official_number = value.value?.official_number;
  props.form.keel_laying_date = value.value?.keel_laying_date;
  props.form.launching_date = value.value?.launching_date;
  props.form.mmsi = value.value?.mmsi;
  props.form.overall_length = value.value?.overall_length;
  props.form.overall_width = value.value?.overall_width;
  props.form.year_built = value.value?.year_built;
  props.form.port_of_registry = value.value?.port_of_registry;
  props.form.total_cargo_hold = value.value?.total_cargo_hold;
  props.form.capacity = value.value?.capacity;
}, {deep: true})

watch(dropZoneFile, (value) => {
  if (value !== null && value !== undefined) {
    props.form.attachment = value;
  }
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
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}
</style>