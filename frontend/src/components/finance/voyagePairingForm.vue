<script setup>
import Error from "../Error.vue";
import { watch, ref } from 'vue';
import useVoyage from "../../composables/operation/useVoyage";


const { voyages, voyageName, getVoyageName, showVoyage, voyage } = useVoyage();

const firstVoyageDetails = ref();
const secondVoyageDetails = ref();

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false }
});

function fetchVoyages(search) {
  getVoyageName(search, 'isPaired');
}

// watch(() => props.form.vessel_name, (value) => {
//   if (value) {
//     props.form.vessel = value.id;
//   }
// }, {deep: true});

watch(() => props.form.first_voyage_id, (value) => {
  if(value) {
    showVoyage(value)
  }
}, {deep: true})

watch(() => props.form.second_voyage_id, (value) => {
  if(value) {
    showVoyage(value)
  }
}, {deep: true})

watch(() => voyage, (value) => {
  let voyageId = value.value.id;
  if(voyageId == props.form.first_voyage_id) {
    firstVoyageDetails.value = value.value
  } else if(voyageId == props.form.second_voyage_id) {
    secondVoyageDetails.value = value.value
  }
}, { deep: true})

</script>
<template>
  <!-- Basic information -->
  <div class="flex flex-col w-full md:flex-row md:gap-2">
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Round Voyage Name <span class="text-red-500">*</span></span>
      <input type="text" v-model="form.combined_name" required placeholder="Name For Round Voyage" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.combined_name" :errors="errors.combined_name" />
    </label>
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Financial Closing Date <span class="text-red-500">*</span></span>
      <input type="date" v-model="form.financial_closing_date" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.financial_closing_date" :errors="errors.financial_closing_date" />
    </label>
    <label class="block w-full mt-3 text-sm md:w-2/6">
      <span class="text-gray-700 dark:text-gray-300">Exchange Rate <span class="text-red-500">*</span> <smal>(1 USD to BDT)</smal></span>
      <input type="number" step="any" v-model="form.exchange_rate" placeholder="Exchange Rate" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
      <Error v-if="errors?.exchange_rate" :errors="errors.exchange_rate" />
    </label>
  </div>


  <!-- Expense entries -->
  <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
    <legend class="px-2 text-gray-700 dark:text-gray-300 md:my-2">Voyages to be Paired </legend>
    
        <div class="px-1 py-1 flex justify-between items-center">
            <div class="w-5/12">
                <label class="block w-full mt-3 text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">First Voyage <span class="text-red-500">*</span></span>
                </label>
                <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchVoyages" v-model="form.first_voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required></v-select>
            </div>
            <div class="w-7/12 ml-5" v-if="firstVoyageDetails != NULL">
              <span class="text-red-600 flex justify-center font-semibold" v-if="firstVoyageDetails.is_paired == 1">Already Paired</span>
              <table class="w-full" id="table">
                <thead>
                  <tr>
                    <th>VVD</th>
                    <th>Vessel</th>
                    <th>Departure (First Port)</th>
                    <th>Arrival (Last Port)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ firstVoyageDetails.voyage }}</td>
                    <td>{{ firstVoyageDetails.vessel.name }}</td>
                    <td>{{ firstVoyageDetails.voyage_schedules[0].etd_date }}</td>
                    <td>{{ firstVoyageDetails.voyage_schedules[firstVoyageDetails.voyage_schedules.length - 1].eta_date }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            
        </div>
        <hr class="mt-4">
        <div class="px-1 py-1 flex justify-between items-center">
          <div class="w-5/12">
                <label class="block w-full mt-3 text-sm">
                    <span class="text-gray-700 dark:text-gray-300 font-semibold">Second Voyage <span class="text-red-500">*</span></span>
                </label>
                <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchVoyages" v-model="form.second_voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required></v-select>
            </div>
            <div class="w-7/12 ml-5" v-if="secondVoyageDetails != NULL">
              <span class="text-red-600 flex justify-center font-semibold" v-if="secondVoyageDetails.is_paired == 1">Already Paired</span>

              <table class="w-full" id="table">
                <thead>
                  <tr>
                    <th>VVD</th>
                    <th>Vessel</th>
                    <th>Departure (First Port)</th>
                    <th>Arrival (Last Port)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ secondVoyageDetails.voyage }}</td>
                    <td>{{ secondVoyageDetails.vessel.name }}</td>
                    <td>{{ secondVoyageDetails.voyage_schedules[0].etd_date }}</td>
                    <td>{{ secondVoyageDetails.voyage_schedules[secondVoyageDetails.voyage_schedules.length - 1].eta_date }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
        </div>
  </fieldset>
  <!-- Previous Method -->
  
</template>
<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

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

table th {
  text-transform: capitalize;
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