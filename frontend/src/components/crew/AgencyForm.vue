<script setup>
import Error from "../Error.vue";
import useCommonApiRequest from "../../composables/crew/useCommonApiRequest";
import useAgency from "../../composables/crew/useAgency";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {onMounted, ref, watchEffect} from "vue";
import env from '../../config/env';
import Store from "../../store";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);
const { crwRankLists, getCrewRankLists } = useCommonApiRequest();


function addItem() {
  let obj = {
    name: '',
    contact_no: '',
    email: '',
    position: '',
    purpose: '',
  };
  props.form.crwAgencyContactPersons.push(obj);
}

function removeItem(index){
  props.form.crwAgencyContactPersons.splice(index, 1);
}

const selectedFile = (event) => {
  props.form.logo = event.target.files[0];
};

onMounted(() => {
  props.form.business_unit = businessUnit.value;
});

</script>

<template>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <business-unit-input v-model="form.business_unit"></business-unit-input>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
    <label class="block w-full mt-2 text-sm"></label>
  </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Agency Name <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.name" placeholder="Agency name" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.name" :errors="errors.name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Legal Name <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.legal_name" placeholder="Legal name" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.legal_name" :errors="errors.legal_name" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Tax Identification <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.tax_identification" placeholder="Tax identification" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.tax_identification" :errors="errors.tax_identification" />
      </label>
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark:text-gray-300">Business License No. <span class="text-red-500">*</span></span>
        <input type="text" v-model="form.business_license_no" placeholder="bin/gst/sst no" class="form-input" autocomplete="off" required />
        <Error v-if="errors?.business_license_no" :errors="errors.business_license_no" />
      </label>
    </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Company Reg No. <span class="text-red-500">*</span></span>
      <input type="number" v-model="form.company_reg_no" placeholder="Company reg no" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.company_reg_no" :errors="errors.company_reg_no" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Address <span class="text-red-500">*</span></span>
      <input type="text" v-model="form.address" placeholder="Address" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.address" :errors="errors.address" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Phone <span class="text-red-500">*</span></span>
      <input type="text" v-model="form.phone" placeholder="Phone" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.phone" :errors="errors.phone" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Email <span class="text-red-500">*</span></span>
      <input type="email" v-model="form.email" placeholder="Email" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.email" :errors="errors.email" />
    </label>
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Country <span class="text-red-500">*</span></span>
      <input type="text" v-model="form.country" placeholder="Country" class="form-input" autocomplete="off" required />
      <Error v-if="errors?.country" :errors="errors.country" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300">Website</span>
      <input type="text" v-model="form.website" placeholder="Website" class="form-input" autocomplete="off" />
      <Error v-if="errors?.website" :errors="errors.website" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark:text-gray-300 text-sm font-medium text-gray-900 dark:text-white">Logo </span>
      <input @change="selectedFile" class="block form-input text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file">
      <Error v-if="errors?.logo" :errors="errors.logo" />
    </label>
    <label class="block w-full mt-2 text-sm">
      <span v-if="form.prev_logo" class="text-gray-700 dark:text-gray-300 text-sm font-medium text-gray-900 dark:text-white">Previous Logo </span>
      <template v-if="form.prev_logo">
        <a class="text-red-700" target="_blank" :href="env.BASE_API_URL+'/'+form?.prev_logo">{{
            (typeof $props.form?.prev_logo === 'string')
                ? '('+$props.form?.prev_logo.split('/').pop()+')'
                : ''
          }}</a>
      </template>
    </label>
  </div>
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300">Contact Person</legend>
    <table class="w-full whitespace-no-wrap" id="table">
      <thead>
      <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
        <th class="px-4 py-3 align-bottom">Name <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Position <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Contact No. <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Email <span class="text-red-500">*</span></th>
        <th class="px-4 py-3 align-bottom">Purpose</th>
        <th class="px-4 py-3 text-center align-bottom">Action</th>
      </tr>
      </thead>
      <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
      <tr class="text-gray-700 dark:text-gray-400" v-for="(crewAgencyContact, index) in form.crwAgencyContactPersons" :key="crewAgencyContact.id">
        <td class="px-1 py-1">
          <input type="text" v-model="form.crwAgencyContactPersons[index].name" placeholder="Name" class="form-input" autocomplete="off" required />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.crwAgencyContactPersons[index].position" placeholder="Position" class="form-input" autocomplete="off" required />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.crwAgencyContactPersons[index].contact_no" placeholder="Contact no" class="form-input" autocomplete="off" required />
        </td>
        <td class="px-1 py-1">
          <input type="email" v-model="form.crwAgencyContactPersons[index].email" placeholder="Email" class="form-input" autocomplete="off" required />
        </td>
        <td class="px-1 py-1">
          <input type="text" v-model="form.crwAgencyContactPersons[index].purpose" placeholder="Purpose" class="form-input" autocomplete="off" />
        </td>
        <td class="px-1 py-1 text-center">
          <button v-if="index!==0" type="button" @click="removeItem(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <button v-else type="button" @click="addItem()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </td>
      </tr>
      </tbody>
    </table>
  </fieldset>
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