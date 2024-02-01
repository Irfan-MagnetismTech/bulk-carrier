<script setup>
import Error from "../../../components/Error.vue";
import { watch, ref, onMounted, watchEffect } from 'vue';
import BusinessUnitInput from "../../../components/input/BusinessUnitInput.vue";
import ErrorComponent from '../../../components/utils/ErrorComponent.vue';
import useHeroIcon from "../../../assets/heroIcon";
import useOperationsReport from '../../../composables/operations/useOperationsReport';
import useVessel from '../../../composables/operations/useVessel';
const { vessels, searchVessels, isVesselLoading } = useVessel();


const { monthWiseExpenseReport, isLoading, errors, getMonthWiseExpenseReport } = useOperationsReport();
const icons = useHeroIcon();
const dateFormat = ref(Store.getters.getVueDatePickerTextInputFormat.date);

const form = ref({
  business_unit: '',
  port: '',
  start: '',
  end: ''
})

watch(() => form.value.business_unit, (value) => {
  form.value.ops_vessel_id = ''
  fetchVessels("", false);

}, { deep: true })

function fetchVessels(search, loading) {
      // loading(true);
      searchVessels(search, form.value.business_unit, loading);
}


function getReport() {
  monthWiseExpenseReport.value = '';
  getMonthWiseExpenseReport(form.value)
}
</script>
<template>
  <!-- Basic information -->
  <h2 class="my-5 text-2xl font-semibold">Month Wise Expense Report</h2>

  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">

    <form @submit.prevent="getReport()">

      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          <business-unit-input v-model="form.business_unit" :page="formType"></business-unit-input>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700">Vessel Name<span class="text-red-500">*</span></span>
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
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700">From Date <span class="text-red-500">*</span></span>
            <VueDatePicker v-model="form.start" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
            <!-- <input type="date" v-model="form.start" required placeholder="From" class="form-input" autocomplete="off" /> -->
          </label>
          <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700">Till Date <span class="text-red-500">*</span></span>
            <VueDatePicker v-model="form.end" class="form-input" required auto-apply  :enable-time-picker = "false" placeholder="dd/mm/yyyy" format="dd/MM/yyyy" model-type="yyyy-MM-dd" :text-input="{ format: dateFormat }"></VueDatePicker>
            <!-- <input type="date" v-model="form.end" required placeholder="Till" class="form-input" autocomplete="off" /> -->
          </label>
      </div>

      <div class="flex items-center justify-center">
        <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
      </div>
    </form>

    <div v-if="monthWiseExpenseReport != ''" class="mb-5">
      <div v-html="monthWiseExpenseReport"></div>
    </div>
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