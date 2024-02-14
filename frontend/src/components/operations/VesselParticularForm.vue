<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>

  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">

    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
      <v-select :options="vessels" :loading="isVesselLoading" placeholder="--Choose an option--" v-model="form.opsVessel" label="name" class="block form-input">
          <template #search="{attributes, events}">
              <input
                  class="vs__search"
                  :required="!form.opsVessel"
                  v-bind="attributes"
                  v-on="events"
                  />
          </template>
      </v-select>
      <input type="hidden" v-mode="form.ops_vessel_id" />
    </label>
    
    <label class="block w-1/2 mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Type </span>
          
          <input type="text" v-model.trim="form.vessel_type" placeholder="Vessel Type" class="form-input" autocomplete="off" />

    </label>
    <label class="block w-1/2 mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Owner Name </span>
        <input type="text" v-model.trim="form.owner_name" placeholder="Owner Name" class="form-input" autocomplete="off" />
      </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Previous Name </span>
          <input type="text" v-model.trim="form.previous_name" placeholder="Previous Name" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Short Code </span>
          <input type="text" v-model.trim="form.short_code" maxlength="10" placeholder="Vessel Short Code" class="form-input" autocomplete="off" :class="{ 'bg-gray-100': formType === 'edit' }" :disabled="formType=='edit'"  />
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Call Sign </span>
          <input type="text" v-model.trim="form.call_sign" placeholder="Call Sign" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Manager/Operator </span>
      <input type="text" v-model.trim="form.manager" placeholder="Manager/Operator" class="form-input" autocomplete="off" />
    </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Classification </span>
      <input type="text" v-model.trim="form.classification" placeholder="Classification" class="form-input" autocomplete="off" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Flag </span>
      <input type="text" v-model.trim="form.flag" placeholder="Flag" class="form-input" autocomplete="off" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Previous Flag</span>
      <input type="text" v-model.trim="form.previous_flag" placeholder="Previous Flag" class="form-input" autocomplete="off" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Delivery Date </span>
      <VueDatePicker v-model="form.delivery_date" class="form-input" auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>

    </label>
    
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">GRT </span>
      <input type="text" v-model.trim="form.grt" placeholder="GRT" class="form-input" autocomplete="off" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">NRT </span>
      <input type="text" v-model.trim="form.nrt" placeholder="NRT" class="form-input" autocomplete="off" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">DWT </span>
      <input type="text" v-model.trim="form.dwt" placeholder="DWT" class="form-input" autocomplete="off" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">IMO Number </span>
      <input type="number" v-model.trim="form.imo" placeholder="IMO Number" class="form-input" autocomplete="off" />
    </label>
  </div>
  
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    
    <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Class No</span>
          <input type="text" v-model.trim="form.class_no" placeholder="Class No" class="form-input" autocomplete="off" />
      </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Official Number </span>
      <input type="text" v-model.trim="form.official_number" placeholder="Official Number" class="form-input" autocomplete="off" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Keel Laying Date </span>
      <VueDatePicker v-model="form.keel_laying_date" class="form-input" auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>

    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Launching Date </span>
      <VueDatePicker v-model="form.launching_date" class="form-input" auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
    </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">MMSI </span>
      <input type="text" v-model.trim="form.mmsi" placeholder="MMSI" class="form-input" autocomplete="off" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Length Overall </span>
      <input type="number" step="0.001" v-model.trim="form.overall_length" placeholder="Length Overall" class="form-input" autocomplete="off" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Width Overall </span>
      <input type="number" step="0.001" v-model.trim="form.overall_width" placeholder="Width Overall" class="form-input" autocomplete="off" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Year Built </span>
      <input type="text" v-model.trim="form.year_built" placeholder="Year Built" class="form-input" autocomplete="off" />
    </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-1/2 mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Breadth </span>
      <input type="number" step="0.001" v-model.trim="form.breadth" placeholder="Breadth" class="form-input" autocomplete="off" />
    </label>
    <label class="block w-1/2 mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Depth (Moulded) </span>
      <input type="number" step="0.001" v-model.trim="form.depth_moulded" placeholder="Depth (Moulded)" class="form-input" autocomplete="off" />
    </label>
    
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Port of Registry </span>
      <v-select :options="ports" :loading="isPortLoading" placeholder="--Choose an option--" @search="fetchPorts"  v-model="form.port_of_registry" label="code_name" class="block form-input" :reduce="port=>port.code">
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
  </div> 

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Engine Type </span>
          <input type="text" v-model.trim="form.engine_type" placeholder="Engine Type" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">LOA </span>
          <input type="text" v-model.trim="form.loa" placeholder="LOA" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Depth </span>
          <input type="number" step="0.001" v-model.trim="form.depth" placeholder="Depth" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">ECDIS Type </span>
        <input type="text" v-model.trim="form.ecdis_type" placeholder="ECDIS Type" class="form-input" autocomplete="off" />
      </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">BHP </span>
          <input type="text" v-model.trim="form.bhp" placeholder="BHP" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Email </span>
          <input type="email" v-model.trim="form.email" placeholder="Email" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">LBC </span>
        <input type="text" v-model.trim="form.lbc" placeholder="LBC" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Maker SSAS </span>
        <input type="text" v-model.trim="form.maker_ssas" placeholder="Maker SSAS" class="form-input" autocomplete="off" />
      </label>
      
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Total Cargo Hold </span>
      <input type="number" v-model.trim="form.total_cargo_hold" placeholder="Total Cargo Hold" class="form-input" autocomplete="off" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Capacity </span>
      <input type="number" step="0.001" v-model.trim="form.capacity" placeholder="Capacity" class="form-input" autocomplete="off" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Tues Capacity </span>
      <input type="number" step="0.001" v-model.trim="form.tues_capacity" placeholder="Capacity" class="form-input" autocomplete="off" />
    </label>
    
    <label class="block w-full mt-2 text-sm"></label>
    
  </div>

  <div class="w-full">
    <h2 class="text-gray-600 dark-disabled:text-gray-300 text-2xl font-semibold py-3 text-center">
      Attachment (Vessel Particular For Charterer)
    </h2>
    <label class="block w-full mt-2 text-sm">

      <DropZoneV2 :form="form" :page="page"></DropZoneV2>
    </label>
  </div>

  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<script setup>
import { ref, computed, watch, onMounted } from "vue";
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import usePort from '../../composables/operations/usePort';
import useVessel from '../../composables/operations/useVessel';
import DropZoneV2 from '../../components/DropZoneV2.vue';
import {useStore} from "vuex";
import env from '../../config/env';
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);

const store = useStore();
const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));
const editInitiated = ref(false);

const props = defineProps({
    form: {
        required: false,
        default: {},
    },
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required : false }
});

const { ports, searchPorts, isPortLoading } = usePort();
const { vessel, vessels, getVesselList, showVessel, isVesselLoading } = useVessel();

watch(() => props.form.opsVessel, (value) => {
  if(value) {
    props.form.ops_vessel_id = value?.id
    props.form.vessel_owner = value?.owner_name
  } else {
    props.form.ops_vessel_id = null;
    props.form.vessel_owner = ''
  }
}, { deep: true})

watch(() => props.form.business_unit, (value) => {
  if(props?.formType != 'edit') {
    props.form.opsVessel = null;
    vessels.value = []
    props.form.ops_vessel_id = null;
    props.form.vessel_owner = ''
  }

  getVesselList(props.form.business_unit);
  searchPorts("", props.form.business_unit);
  ports.value = []
  props.form.port_of_registry = null;

  
}, { deep : true })


// watch(() => props.form, (value) => {

//   if(props?.formType == 'edit' && editInitiated.value != true) {

//     vessels.value = [props?.form?.opsVessel]

//   }
// }, {deep: true});

watch(() => props.form.ops_vessel_id, (value) => {
  if(value) {
    if((props?.formType == 'edit' && editInitiated.value == true) || (props.formType != 'edit')) {
      console.log("showing vessel")
      let loadStatus = false;
      showVessel(value, loadStatus);
    }
    
    editInitiated.value = true
  }
}, {deep: true})

watch(() => vessel, (value) => {
  if((props?.formType == 'edit' && editInitiated.value == true) || (props.formType != 'edit')) {
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
          props.form.tues_capacity = value.value?.capacity;
  }

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