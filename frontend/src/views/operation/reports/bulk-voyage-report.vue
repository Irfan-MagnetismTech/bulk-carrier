<script setup>
import Error from "../../../components/Error.vue";
import { watch, ref, onMounted, watchEffect } from 'vue';
import ErrorComponent from '../../../components/utils/ErrorComponent.vue';
import useHeroIcon from "../../../assets/heroIcon";
import useOperationsReport from '../../../composables/operations/useOperationsReport';
import useVoyage from "../../../composables/operations/useVoyage";
import useVessel from "../../../composables/operations/useVessel";


const { bulkVoyageReport, isLoading, getBulkVoyageReport } = useOperationsReport();
const icons = useHeroIcon();
const { voyage, voyages, showVoyage, getVoyageList,searchVoyages } = useVoyage();
const { vessel, vessels, getVesselList, showVessel } = useVessel();

const form = ref({
  type: '',
  ops_vessel_id: null,
  ops_voyage_id: null
})


watch(() => form.value.ops_vessel_id, (value) => {

  getVoyageList('PSML', form.value.ops_vessel_id);
  bulkVoyageReport.value = '';

}, { deep: true })

watch(() => form.value.type, (value) => {
  bulkVoyageReport.value = '';
}, { deep: true })


function getReport() {
  getBulkVoyageReport(form.value)
}

onMounted(() => {
  getVesselList('PSML');
})
</script>
<template>
  <!-- Basic information -->
  <h2 class="my-5 text-2xl text-center font-semibold">Voyage Report (Bulk)</h2>
  <form @submit.prevent="getReport()">

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 ">Report Type <span class="text-red-500">*</span></span>
        <select v-model="form.type" class="form-input" required :class="{ 'bg-gray-100 text-gray-900': formType === 'edit' }" :disabled="formType=='edit'" >
          <option value="" disabled selected>Select Option</option>
          <option value="Noon Report">Noon Report</option>
          <option value="Arrival Report">Arrival Report</option>
          <option value="Departure Report">Departure Report</option>
        </select>
      </label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Vessel <span class="text-red-500">*</span></span>
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
      <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Voyage <span class="text-red-500">*</span></span>
              <v-select :options="voyages" placeholder="--Choose an option--" v-model="form.ops_voyage_id" label="voyage_sequence" class="block form-input" :reduce="voyage => voyage.id">
                  <template #search="{attributes, events}">
                      <input
                          class="vs__search"
                          :required="!form.ops_voyage_id"
                          v-bind="attributes"
                          v-on="events"
                          />
                  </template>
              </v-select>
      </label>
    </div>

    <div class="flex items-center justify-center">
      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
    </div>
  </form>

  <div v-if="bulkVoyageReport != ''" class="mb-5">
    <h4 class="text-center text-xl font-semibold my-4">
      {{ form.type }}
    </h4>
    <div v-html="bulkVoyageReport"></div>
  </div>
  <ErrorComponent :errors="errors"></ErrorComponent>
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1 text-xs
}

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