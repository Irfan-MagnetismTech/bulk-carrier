<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Vessel <span class="text-red-500">*</span></span>
        <v-select :options="vessels" placeholder="--Choose an option--" v-model="form.ops_vessel_id" label="name" class="block form-input" :reduce="vessel => vessel.id">
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

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Certificate <span class="text-red-500">*</span></span>
        <v-select :options="maritimeCertificates" placeholder="--Choose an option--" v-model="form.ops_maritime_certification_id" label="name" class="block form-input" :reduce="certificate => certificate.id">
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
          <span class="text-gray-700 dark-disabled:text-gray-300">Certificate Type </span>
          <input type="text" v-model.trim="form.type" placeholder="Certificate Type" class="form-input bg-gray-300" readonly autocomplete="off" />
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Validity Period <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.validity_period" required placeholder="Validity Period" class="form-input bg-gray-300" readonly disabled autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Reference Number <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.reference_number" required placeholder="Reference Number" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Issue Date <span class="text-red-500">*</span></span>
            <input type="date" v-model="form.issue_date" required placeholder="Issue Date" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Expire Date <span class="text-red-500">*</span></span>
            <input type="date" v-model="form.expire_date" required placeholder="Expire Date" class="form-input" autocomplete="off" />
        </label>
    </div>

    <div class="w-full">
      <h2 class="text-gray-600 dark-disabled:text-gray-300 text-2xl font-semibold py-3 text-center">
        Attachment (Image of Certificate)
      </h2>
      <label class="block w-full mt-2 text-sm">
        <DropZoneV2 :form="form" :page="page"></DropZoneV2>
      </label>
  </div>

<ErrorComponent :errors="errors"></ErrorComponent>
</template>
<script setup>
import { ref, computed, watch, onMounted, watchEffect } from "vue";
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useVessel from '../../composables/operations/useVessel';
import DropZoneV2 from '../../components/DropZoneV2.vue';
import {useStore} from "vuex";
import env from '../../config/env';
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import useMaritimeCertificate from "../../composables/operations/useMaritimeCertificate";

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
const { searchMaritimeCertificates, maritimeCertificates } = useMaritimeCertificate();

function fetchVessels(search, loading) {
  searchVessels(search, props.form.business_unit, loading);
}

function fetchCertificates(search, loading) {
  searchMaritimeCertificates(search, loading);
}


watch(() => props.form, (value) => {

  if(props?.formType == 'edit' && editInitiated.value != true) {

    props.form.validity_period = props.form?.opsMaritimeCertification?.validity
    props.form.type = props.form?.opsMaritimeCertification?.type

    props.form.ops_maritime_certification_name = props.form?.opsMaritimeCertification?.name
    props.form.ops_vessel_name = props?.form?.opsVessel?.name

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
    
    const certificate = maritimeCertificates.value.find(obj => obj["id"] === value);
    props.form.validity_period = certificate?.validity
    props.form.type = certificate?.type
  }
}, { deep: true })

watch(dropZoneFile, (value) => {
  if (value !== null && value !== undefined) {
    props.form.attachment = value;
  }
});

onMounted(() => {
    watchEffect(() => {
      if(props.form.business_unit && props.form.business_unit != 'ALL'){
        fetchVessels("", false);
      }

      fetchCertificates("", false)
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
</style>