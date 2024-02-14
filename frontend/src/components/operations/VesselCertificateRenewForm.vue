<template>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input v-model="form.business_unit" :page="'edit'"></business-unit-input>

      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Name <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.vessel_name" placeholder="Certificate Type" class="form-input bg-[#e7e6e6]" readonly disabled autocomplete="off" />
        <input type="hidden" v-model.trim="form.ops_vessel_id" class="form-input bg-[#e7e6e6]" readonly disabled autocomplete="off" />

      </label>
      
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Certificate <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.certificate_name" placeholder="Certificate Type" class="form-input bg-[#e7e6e6]" readonly disabled autocomplete="off" />
        <input type="hidden" v-model.trim="form.ops_maritime_certification_id" placeholder="Certificate Type" class="form-input bg-[#e7e6e6]" readonly disabled autocomplete="off" />
        
      </label>

      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Certificate Type </span>
          <input type="text" v-model.trim="form.type" placeholder="Certificate Type" class="form-input bg-[#e7e6e6]" readonly disabled autocomplete="off" />
        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Validity Period </span>
            <div v-if="form.validity_period != ''">
              <span class="!bg-[#e7e6e6] show-block" v-if="form?.validity_period=='0'">Permanent</span>  
              <span class="!bg-[#e7e6e6] show-block" v-if="form?.validity_period=='3'">3 Months</span>  
              <span class="!bg-[#e7e6e6] show-block" v-if="form?.validity_period=='6'">6 Months</span>  
              <span class="!bg-[#e7e6e6] show-block" v-if="form?.validity_period=='12'">1 Years</span>  
              <span class="!bg-[#e7e6e6] show-block" v-if="form?.validity_period=='24'">2 Years</span>  
              <span class="!bg-[#e7e6e6] show-block" v-if="form?.validity_period=='36'">3 Years</span>  
              <span class="!bg-[#e7e6e6] show-block" v-if="form?.validity_period=='48'">4 Years</span>  
              <span class="!bg-[#e7e6e6] show-block" v-if="form?.validity_period=='60'">5 Years</span>  
              <span class="!bg-[#e7e6e6] show-block" v-if="form?.validity_period=='120'">10 Years</span> 
            </div> 
            <div v-else>
              <span class="!bg-[#e7e6e6] show-block"></span> 
            </div>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Reference Number <span class="text-red-500">*</span></span>
            <input type="text" v-model.trim="form.reference_number" required placeholder="Reference Number" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Issue Date <span class="text-red-500">*</span></span>
            <VueDatePicker v-model="form.issue_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>

        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Expire Date </span>
            <VueDatePicker v-model="form.expire_date" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>

        </label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <div class="w-full">
        <h2 class="text-gray-600 dark-disabled:text-gray-300 text-2xl font-semibold py-3 text-center">
          Attachment (Image of Certificate)
        </h2>

        <label class="block w-full mt-2 text-sm">
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
    
  </div>

<ErrorComponent :errors="errors"></ErrorComponent>
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
import ErrorComponent from '../../components/utils/ErrorComponent.vue';

import { formatDate } from "../../utils/helper.js";
const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);
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
  props.form.isRenew='renew';

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
  @apply text-gray-700 dark-disabled:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
}
</style>