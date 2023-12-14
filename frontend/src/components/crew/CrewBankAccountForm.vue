<script setup>
import Error from "../Error.vue";
import BusinessUnitInput from "../input/BusinessUnitInput.vue";
import {onMounted, ref, watch, watchEffect} from "vue";
import useCrewCommonApiRequest from "../../composables/crew/useCrewCommonApiRequest";
import ErrorComponent from '../utils/ErrorComponent.vue';
import Store from "../../store";
import RemarksComponent from "../utils/RemarksComponent.vue";

const { crews, getCrews } = useCrewCommonApiRequest();


const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
});
const businessUnit = ref(Store.getters.getCurrentUser.business_unit);

const selectedFile = (event) => {
  props.form.attachment = event.target.files[0];
};

watch(() => props.form, (value) => {
  if(value){
    props.form.crw_crew_id        = props.form?.crw_crew_name?.id ?? '';
  }
}, {deep: true});


watch(() => props.form.crwCrew, (value) => {
  if(value){
    props.form.crw_crew_name = value ?? '';
  }
}, {deep: true});


onMounted(() => {
  watchEffect(() => {
    getCrews(props.form.business_unit);
  });
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
      <span class="text-gray-700 dark-disabled:text-gray-300">Crew Name <span class="text-red-500">*</span></span>
      <v-select :options="crews" placeholder="--Choose an option--"  v-model="form.crw_crew_name" label="full_name" class="block form-input">
        <template #search="{attributes, events}">
          <input
              class="vs__search"
              :required="!form.crw_crew_name"
              v-bind="attributes"
              v-on="events"
          />
        </template>
      </v-select>
    </label>

    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300"> Crew Contact </span>
      <input type="text" :value="form.crw_crew_name?.pre_mobile_no" class="form-input vms-readonly-input" autocomplete="off" readonly/>
    </label>

    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300"> Bank Name <span class="text-red-500">*</span></span>
      <input type="text" v-model.trim="form.bank_name" class="form-input" placeholder="Bank Name" autocomplete="off" required/>
    </label>

    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300"> Branch Name <span class="text-red-500">*</span></span>
      <input type="text" v-model.trim="form.branch_name" class="form-input" placeholder="Branch Name" autocomplete="off" required/>
    </label>
  </div>

  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2"> 
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300"> Account Name <span class="text-red-500">*</span></span>
      <input type="text" v-model.trim="form.account_name" class="form-input" placeholder="Account Name" autocomplete="off" required/>
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300"> Account Number <span class="text-red-500">*</span></span>
      <input type="text" v-model.trim="form.account_number" class="form-input" placeholder="Account Number" autocomplete="off" required/>
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300"> Routing Number </span>
      <input type="text" v-model.trim="form.routing_number" class="form-input" placeholder="Routing Number" autocomplete="off"/>
    </label>
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300"> Benificiary Name <span class="text-red-500">*</span></span>
      <input type="text" v-model.trim="form.benificiary_name" class="form-input" placeholder="Benificiary Name" autocomplete="off" required/>
    </label>              
  </div>
  <div class="flex flex-col justify-center w-full md:flex-row md:gap-2"> 
    <label class="block w-full mt-2 text-sm">
      <span class="text-gray-700 dark-disabled:text-gray-300">Attachment</span>
      <input @change="selectedFile" class="block form-input text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark-disabled:text-gray-400 focus:outline-none dark-disabled:bg-gray-700 dark-disabled:border-gray-600 dark-disabled:placeholder-gray-400" type="file">
    </label> 
  </div>

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
  @apply text-gray-700 dark-disabled:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
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