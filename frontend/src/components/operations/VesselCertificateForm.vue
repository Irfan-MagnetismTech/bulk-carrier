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
        <span class="text-gray-700 dark:text-gray-300">Certificate <span class="text-red-500">*</span></span>
        <v-select :options="vesselCertificates" placeholder="--Choose an option--" v-model="form.ops_maritime_certification_id" label="name" class="block form-input" :reduce="certificate=>certificate.id">
            <template #search="{attributes, events}">
                <input
                    class="vs__search"
                    :required="!form.ops_maritime_certification_id"
                    v-bind="attributes"
                    v-on="events"
                    />
            </template>
        </v-select>
      </label>

      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Certificate Type </span>
          <input type="text" v-model="form.certificate_type" placeholder="Certificate Type" class="form-input bg-gray-300" readonly disabled autocomplete="off" />
          <Error v-if="errors?.certificate_type" :errors="errors.certificate_type" />
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Validity Period </span>
            <input type="text" v-model="form.validity_period" placeholder="Validity Period" class="form-input bg-gray-300" readonly disabled autocomplete="off" />
          <Error v-if="errors?.validity_period" :errors="errors.validity_period" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Reference Number </span>
            <input type="text" v-model="form.reference_number" placeholder="Reference Number" class="form-input" autocomplete="off" />
          <Error v-if="errors?.reference_number" :errors="errors.reference_number" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Issue Date </span>
            <input type="date" v-model="form.issue_date" placeholder="Issue Date" class="form-input" autocomplete="off" />
          <Error v-if="errors?.issue_date" :errors="errors.issue_date" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Expire Date </span>
            <input type="date" v-model="form.expire_date" placeholder="Expire Date" class="form-input" autocomplete="off" />
          <Error v-if="errors?.expire_date" :errors="errors.expire_date" />
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <h2 class="text-gray-600 dark:text-gray-300 text-2xl font-semibold py-3 text-center">
        Attachment (Image of Certificate)
      </h2>

        
      <DropZoneV2 :form="form" :page="page"></DropZoneV2>
    </label>
  </div>


</template>
<script setup>
import { ref, computed, watch } from "vue";
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useVessel from '../../composables/operations/useVessel';
import DropZoneV2 from '../../components/DropZoneV2.vue';
import {useStore} from "vuex";
import env from '../../config/env';

const store = useStore();
const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));
const vesselCertificates = ref([]);
const editInitiated = ref(false);

const props = defineProps({
    form: {
        required: false,
        default: {},
    },
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required : false }
});

const { vessel, vessels, searchVessels, showVessel } = useVessel();

function fetchVessels(search, loading) {
      loading(true);
      searchVessels(search, props.form.business_unit, loading);
}

watch(() => props.form, (value) => {

  if(props?.formType == 'edit' && editInitiated.value != true) {

    vessels.value = [props?.form?.opsVessel];

    vesselCertificates.value = props.form?.opsVessel?.opsVesselCertificates;
    props.form.validity_period = props.form?.opsMaritimeCertification?.validity
    props.form.certificate_type = props.form?.opsMaritimeCertification?.type

    if(vessels.value.length > 0) {
        console.log("Changing editInitatedValue ")
        editInitiated.value = true
      }
  }
}, {deep: true});

watch(() => props.form.ops_vessel_id, (value) => {
  if(value) {
    if((props?.formType == 'edit' && editInitiated.value == true) || (props.formType != 'edit')) {
      console.log("showing vessel")
      showVessel(value)
    }
  }
}, {deep: true})

watch(() => vessel, (value) => {
  vesselCertificates.value = value?.value?.opsVesselCertificates
}, {deep: true})

watch(() => props.form.ops_maritime_certification_id, (value) => {
  if(value) {
    
    const certificate = vesselCertificates.value.find(obj => obj["ops_maritime_certification_id"] === value);
    props.form.validity_period = certificate?.opsMaritimeCertification?.validity
    props.form.certificate_type = certificate?.opsMaritimeCertification?.type
  }
}, { deep: true })

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