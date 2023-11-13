<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input v-model="form.business_unit"></business-unit-input>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Vessel <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.vessel_name" placeholder="Certificate Type" class="form-input bg-gray-300" readonly disabled autocomplete="off" />
        <input type="hidden" v-model="form.ops_vessel_id" class="form-input bg-gray-300" readonly disabled autocomplete="off" />

      </label>
      
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Certificate <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.certificate_name" placeholder="Certificate Type" class="form-input bg-gray-300" readonly disabled autocomplete="off" />
        <input type="hidden" v-model="form.ops_maritime_certification_id" placeholder="Certificate Type" class="form-input bg-gray-300" readonly disabled autocomplete="off" />
        
      </label>

      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Certificate Type </span>
          <input type="text" v-model="form.type" placeholder="Certificate Type" class="form-input bg-gray-300" readonly disabled autocomplete="off" />
          <Error v-if="errors?.type" :errors="errors.type" />
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
import { ref, computed, watch, onMounted } from "vue";
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useVessel from '../../composables/operations/useVessel';
import useMaritimeCertificate from "../../composables/operations/useMaritimeCertificate";
import DropZoneV2 from '../../components/DropZoneV2.vue';
import {useStore} from "vuex";
import env from '../../config/env';
import { useRoute } from 'vue-router';


const store = useStore();
const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));
const route = useRoute();
const vesselId = route.params.vesselId;
const marineCertificateId = route.params.marineCertificateId;

const props = defineProps({
    form: {
        required: false,
        default: {},
    },
    errors: { type: [Object, Array], required: false },
    formType: { type: String, required : false }
});

const { vessel, searchVessels, showVessel } = useVessel();
const { maritimeCertificate, showMaritimeCertificate } = useMaritimeCertificate();

watch(() => vessel, (value) => {
  props.form.vessel_name = value?.value?.name;
  props.form.ops_vessel_id = value?.value?.id;
  props.form.business_unit = value?.value?.business_unit
}, {deep: true})

watch(() => maritimeCertificate, (value) => {
  props.form.ops_maritime_certification_id = value?.value?.id;

  props.form.certificate_name = value?.value?.name;
  props.form.type = value?.value?.type;
  props.form.validity_period = value?.value?.validity;
}, {deep: true})

watch(dropZoneFile, (value) => {
  if (value !== null && value !== undefined) {
    props.form.attachment = value;
  }
});

onMounted(() => {
  showVessel(vesselId);
  showMaritimeCertificate(marineCertificateId);
  // marineCertificateId
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