<script setup>
import Error from "../../../components/Error.vue";
import { watch, ref, onMounted, watchEffect } from 'vue';
import BusinessUnitInput from "../../../components/input/BusinessUnitInput.vue";
import ErrorComponent from '../../../components/utils/ErrorComponent.vue';
import useHeroIcon from "../../../assets/heroIcon";
import useVessel from '../../../composables/operations/useVessel';
import useOperationsReport from '../../../composables/operations/useOperationsReport';
import useVoyage from "../../../composables/operations/useVoyage";


const { vessels, searchVessels, isVesselLoading } = useVessel();
const { vesselBunkerReport, isLoading, getVesselBunkerReport, errors } = useOperationsReport();
const icons = useHeroIcon();
const { voyages, searchVoyages } = useVoyage();

const form = ref({
  business_unit: '',
  start: '',
  end: '',
  type: '',
  ops_vessel_id: '',
  ops_voyage_id: '',
})

watch(() => form.value.business_unit, (value) => {

  fetchVessels("", false);

}, { deep: true })

function fetchVessels(search, loading) {
      // loading(true);
      searchVessels(search, form.value.business_unit, loading);
}

watch(() => form.value.ops_vessel_id, (value) => {

  vesselBunkerReport.value = '';

}, { deep: true })

watch(() => form.value.business_unit, (value) => {

  vesselBunkerReport.value = '';

}, { deep: true })


function getReport() {
  vesselBunkerReport.value = '';
  getVesselBunkerReport(form.value)
}

watch(() => form.value.ops_vessel_id, (newValue, oldValue) => {

  if(newValue){
    form.value.ops_vessel_id = newValue?.id;
    fetchVesselWiseVoyages(form.value.ops_vessel_id, false);
  }

}, { deep: true });

function fetchVesselWiseVoyages(ops_vessel_id, loading) {
  searchVoyages("", form.value.business_unit, loading, ops_vessel_id)
}
</script>
<template>
  <!-- Basic information -->
  <h2 class="my-5 text-2xl text-center font-semibold">Vessel Bunker Report</h2>
  <form @submit.prevent="getReport()">
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
        
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700">From Date <span class="text-red-500">*</span></span>
          <input type="date" v-model="form.start" required placeholder="From" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700">Till Date <span class="text-red-500">*</span></span>
          <input type="date" v-model="form.end" required placeholder="Till" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm"></label>
        
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700">Type <span class="text-red-500">*</span></span>
        <select v-model="form.type" class="form-input" required>
          <option disabled selected value="">Select Option</option>
          <option value="Stock In">Stock In</option>
          <option value="Stock Out">Stock Out</option>
          <option value="Reconciliation">Reconciliation</option>
        </select>
      </label>
      <label v-if="form.type != ''" class="block w-full mt-2 text-sm">
        <span class="text-gray-700">{{ form.type }} Type <span class="text-red-500">*</span></span>
        <select v-model="form.usage_type" class="form-input" required>
          <option disabled selected value="">Select Option</option>
          <option value="Idle">Idle</option>
          <option value="Voyage Wise">Voyage Wise</option>
        </select>
      </label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>
      <!-- <label class="block w-full mt-2 text-sm" v-if="form.type=='Reconciliation'">
        <span class="text-gray-700">Reconfiliation Option <span class="text-red-500">*</span></span>
        <select v-model="form.reconciliation_type" class="form-input" required>
          <option disabled selected value="">Select Option</option>
          <option value="Addition">Addition</option>
          <option value="Deletion">Deletion</option>
        </select>
      </label> -->
    </div>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700">Vessel <span class="text-red-500">*</span></span>
            <v-select :options="vessels" placeholder="--Choose an option--" :loading="isVesselLoading"  v-model="form.ops_vessel_id" label="name" class="block form-input" :reduce="vessel=>vessel.id">
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
              <span class="text-gray-700 dark-disabled:text-gray-300">Voyage <span class="text-red-500">*</span></span>
              <v-select :options="voyages" placeholder="--Choose an option--" v-model="form.ops_voyage_id" label="voyage_sequence" class="block form-input" :reduce="voyage=>voyage.id">
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

  <div v-if="vesselBunkerReport != ''" class="mb-5">
    <h4 class="text-center text-xl font-semibold my-4">
    </h4>
    <div v-html="vesselBunkerReport"></div>
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