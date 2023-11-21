<script setup>
import Error from "../Error.vue";
import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import useRecruitmentApproval from "../../composables/crew/useRecruitmentApproval";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import ErrorComponent from '../../components/utils/ErrorComponent.vue';
import {onMounted, ref, watchEffect} from "vue";
import Store from "../../store";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const { crwAgencies, getCrewAgencyLists } = useCrewCommonApiRequest();

const selectedFile = (event) => {
  props.form.attachment = event.target.files[0];
};

onMounted(() => {
  watchEffect(() => {
    getCrewAgencyLists(props.form.business_unit);
  });
});

</script>

<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model.trim="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Agency Name <span class="text-red-500">*</span></span>
        <select class="form-input" v-model="form.crw_agency_id" required>
          <option value="" selected disabled>Select</option>
          <option v-for="(agency,index) in crwAgencies" :value="agency.id" :key="index">{{ agency?.agency_name }} ({{ agency?.phone }})</option>
        </select>
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Validity From <span class="text-red-500">*</span></span>
        <input type="date" v-model.trim="form.validity_from" class="form-input" autocomplete="off" required />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Validity Till <span class="text-red-500">*</span></span>
        <input type="date" v-model.trim="form.validity_till" class="form-input" autocomplete="off" required />
      </label>
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Billing Cycle (In Days) <span class="text-red-500">*</span></span>
      <input type="number" v-model.trim="form.billing_cycle" placeholder="Billing Cycle" class="form-input" autocomplete="off" required />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Currency <span class="text-red-500">*</span></span>
      <input type="text" v-model.trim="form.billing_currency" placeholder="Currency" class="form-input" autocomplete="off" required />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300 text-sm font-medium text-gray-900 dark:text-white">Attachment </span>
      <input @change="selectedFile" class="block form-input text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file">
    </label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Terms & Condition <span class="text-red-500">*</span></span>
      <textarea v-model.trim="form.terms_and_conditions" placeholder="Terms & Condition" class="form-input" autocomplete="off" required></textarea>
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Services Offered <span class="text-red-500">*</span></span>
      <textarea type="text" v-model.trim="form.service_offered" placeholder="Service Offered" class="form-input" autocomplete="off" required></textarea>
    </label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Remarks</span>
      <textarea type="text" v-model.trim="form.remarks" placeholder="Remarks..." class="form-input" autocomplete="off"></textarea>
    </label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Bank Info</legend>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Account Holder <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.account_holder_name" placeholder="A/C Holder" class="form-input" autocomplete="off" required />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Bank Name <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.bank_name" placeholder="Bank Name" class="form-input" autocomplete="off" required />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Bank Address <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.bank_address" placeholder="Bank Address" class="form-input" autocomplete="off" required />
      </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Account No <span class="text-red-500">*</span></span>
        <input type="text" v-model.trim="form.account_no" placeholder="A/C No" class="form-input" autocomplete="off" required />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Swift Code</span>
        <input type="text" v-model.trim="form.swift_code" placeholder="Swift Code" class="form-input" autocomplete="off" />
      </label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>
  </fieldset>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}

.label-group {
  @apply block w-full mt-2 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}

>>> {
  --vs-controls-color: #374151;
  --vs-border-color: #4b5563;

  --vs-dropdown-bg: #282c34;
  --vs-dropdown-color: #eeeeee;
  --vs-dropdown-option-color: #eeeeee;

  --vs-selected-bg: #664cc3;
  --vs-selected-color: #374151;

  --vs-search-input-color: #4b5563;

  --vs-dropdown-option--active-bg: #664cc3;
  --vs-dropdown-option--active-color: #eeeeee;
}
</style>