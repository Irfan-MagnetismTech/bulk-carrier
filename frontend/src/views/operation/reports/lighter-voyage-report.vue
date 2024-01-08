<script setup>
import Error from "../../../components/Error.vue";
import { watch, ref, onMounted, watchEffect } from 'vue';
import ErrorComponent from '../../../components/utils/ErrorComponent.vue';
import useHeroIcon from "../../../assets/heroIcon";
import useOperationsReport from '../../../composables/operations/useOperationsReport';

const { lighterVoyageReport, isLoading, getLighterVoyageReport } = useOperationsReport();
const icons = useHeroIcon();

const form = ref({
  start: '',
  end: ''
})


watch(() => form.value.port, (value) => {

  lighterVoyageReport.value = '';

}, { deep: true })


function getReport() {
  lighterVoyageReport.value = '';
  getLighterVoyageReport(form.value)
}
</script>
<template>
  <!-- Basic information -->
  <h2 class="my-5 text-2xl text-center font-semibold">Voyage Report (Lighter)</h2>
  <form @submit.prevent="getReport()">

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700">From Date <span class="text-red-500">*</span></span>
          <input type="date" v-model="form.start" required placeholder="From" class="form-input" autocomplete="off" />
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700">Till Date <span class="text-red-500">*</span></span>
          <input type="date" v-model="form.end" required placeholder="Till" class="form-input" autocomplete="off" />
        </label>
    </div>

    <div class="flex items-center justify-center">
      <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
    </div>
  </form>

  <div v-if="lighterVoyageReport != ''" class="mb-5">
    <h4 class="text-center text-xl font-semibold my-4">
      Report for {{ form.port }}
    </h4>
    <div v-html="lighterVoyageReport"></div>
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