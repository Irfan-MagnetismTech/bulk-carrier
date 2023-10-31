<template>

    <h4 class="text-md font-semibold">Basic Info</h4>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>

        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Charterer Name <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.name" placeholder="Charterer Name" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.name" :errors="errors.name" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Company Legal Name <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.company_legal_name" placeholder="Company Legal Name" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.company_legal_name" :errors="errors.company_legal_name" />
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Charterer Owner Code <span class="text-red-500">*</span></span>
            <input type="text" v-model="form.owner_code" placeholder="Charterer Owner Code" class="form-input" required autocomplete="off" />
          <Error v-if="errors?.owner_code" :errors="errors.owner_code" />
        </label>
        
        
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Contact No</span>
            <input type="text" v-model="form.contact_no" placeholder="Contact No" class="form-input" autocomplete="off" />
          <Error v-if="errors?.contact_no" :errors="errors.contact_no" />
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Country</span>
            <input type="text" v-model="form.country" placeholder="Country" class="form-input" autocomplete="off" />
          <Error v-if="errors?.country" :errors="errors.country" />
        </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Address</span>
          <input type="text" v-model="form.address" placeholder="Address" class="form-input" autocomplete="off" />
        <Error v-if="errors?.address" :errors="errors.address" />
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Billing Address</span>
            <input type="text" v-model="form.billing_address" placeholder="Billing Address" class="form-input" autocomplete="off" />
          <Error v-if="errors?.billing_address" :errors="errors.billing_address" />
        </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">

      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Billing Email </span>
            <input type="text" v-model="form.billing_email" placeholder="Billing Email" class="form-input" autocomplete="off" />
          <Error v-if="errors?.billing_email" :errors="errors.billing_email" />
        </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Email</span>
            <input type="text" v-model="form.email" placeholder="Email" class="form-input" autocomplete="off" />
          <Error v-if="errors?.email" :errors="errors.email" />
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Website</span>
          <input type="text" v-model="form.website" placeholder="Website" class="form-input" autocomplete="off" />
        <Error v-if="errors?.website" :errors="errors.website" />
      </label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>
    
    <h4 class="text-md font-semibold mt-4">Bank Account Info</h4>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Bank Name</span>
            <input type="text" v-model="form.opsChartererBankAccounts[0].bank_name" placeholder="Bank Name" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Bank Branch </span>
            <input type="text" v-model="form.opsChartererBankAccounts[0].bank_branch_name" placeholder="Bank Branch" class="form-input" autocomplete="off" />
        </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Account No</span>
          <input type="text" v-model="form.opsChartererBankAccounts[0].account_no" placeholder="Account No" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Account Name</span>
            <input type="text" v-model="form.opsChartererBankAccounts[0].account_name" placeholder="Account Name" class="form-input" autocomplete="off" />
        </label>
        
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Swift Code</span>
            <input type="text" v-model="form.opsChartererBankAccounts[0].swift_code" placeholder="Swift Code" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Routing No</span>
          <input type="text" v-model="form.opsChartererBankAccounts[0].routing_no" placeholder="Routing No" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Currency</span>
            <input type="text" v-model="form.opsChartererBankAccounts[0].currency" placeholder="Currency" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>

</template>
<script setup>
import { ref, watch, onMounted } from "vue";
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import useVessel from '../../composables/operations/useVessel';
import {useStore} from "vuex";
import { getCurrencies } from "../../composables/useBusinessInfo"

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

const { vessel, vessels, searchVessels, showVessel } = useVessel();

const currencies = ref([]);

watch(() => props.form, (value) => {

  if(props?.formType == 'edit' && editInitiated.value != true) {

    vessels.value = [props?.form?.opsVessel]

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

onMounted(() => {
  
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
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}
</style>