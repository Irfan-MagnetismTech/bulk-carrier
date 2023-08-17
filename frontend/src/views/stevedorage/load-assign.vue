<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';
import Title from '../../services/title';
import DropZone from '../../components/DropZone.vue';
import Heading from '../../components/Heading.vue';
import useEdiConverter from "../../composables/operation/useEdiConverter";
import useVoyage from "../../composables/operation/useVoyage";
import useTariff from "../../composables/stevedorage/useTariff";

import useSweetAlert2 from "../../composables/useSweetAlert2";
import Swal from "sweetalert2";
const sweetAlert = useSweetAlert2();

const route = useRoute();
const store = useStore();
const { setTitle } = Title();
const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));
const { formParams, tariff, tariffContainerExport, tariffContainerFile, processTariffImportFile, isLoading, errors } = useTariff();
const isTooltipShowing = ref(false);
const { voyages, voyageName, voyageCustomers, getVoyageCustomer, getVoyageName, getVoyageDetails, voyage } = useVoyage();

setTitle('Update Containers Load Status');

watch(dropZoneFile, (value) => {
    if (value !== null && value !== undefined) {
      tariffContainerFile.file = value;
    }
});

//watch formParams.voyage_id
// watch(formParams, (value) => {
//   //set tariffContainerFile to null
//   tariffContainerFile.file = null;
// }, { deep: true });

function fetchOptions(search) {
  getVoyageName(search);
}

function containerExport(formParams) {
  //get voyage from voyageName where id = formParams.voyage_id
  const voyage = voyageName.value.find(voyage => voyage.id === formParams.voyage_id);
  tariffContainerExport(formParams.voyage_id, voyage.voyage);
}

watch(() => formParams.value.voyage_id, (value) => {
  getVoyageDetails(value);
}, { deep: true });

function submitTariffImportFile(tariffContainerFile,voyage_id) {
  if(voyage.value.life_cycle.is_load_assign){
    Swal.fire({
      title: '',
      text: 'Load status has already been uploaded. Would you like to overwrite with another upload?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Overwrite it!'
    }).then((result) => {
      if (result.isConfirmed) {
        processTariffImportFile(tariffContainerFile,voyage_id);
      }
    })
  } else{
    processTariffImportFile(tariffContainerFile,voyage_id);
  }
}
</script>

<template>
    <Heading :label="'Update Containers Load Status'" type="none"></Heading>
  <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800 mb-2">
    <label class="block w-full text-sm">
      <span class="text-gray-700 dark:text-gray-300">Voyage No</span>
      <v-select :options="voyageName" v-model="formParams.voyage_id" placeholder="--Choose an option--" @search="fetchOptions" label="voyage" :reduce="voyageName => voyageName.id" required></v-select>
    </label>
  </div>
  <div v-if="formParams.voyage_id">
    <button type="submit" v-if="formParams.voyage_id != null" :disabled="isLoading" @click="containerExport(formParams)" class="flex w-1/6 items-center mb-2 justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
      Export Container List
    </button>
    <button v-if="formParams.voyage_id == null" class="px-4 py-2 w-1/6 items-center mb-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg opacity-50 cursor-not-allowed focus:outline-none">
      Export Container List
    </button>
    <div class="px-4 py-3 mb-10 bg-white rounded-lg shadow-md dark:bg-gray-800">
      <DropZone></DropZone>
      <form v-if="dropZoneFile !== null" @submit.prevent="submitTariffImportFile(tariffContainerFile,formParams.voyage_id)" class="flex flex-col items-center justify-center w-full gap-2 mt-2" enctype="multipart/form-data">
        <button :disabled="isLoading" class="flex items-center justify-center px-3 py-1 mt-2 text-gray-100 bg-purple-600 rounded">
          <span v-if="!isLoading">Upload</span>
          <svg v-else xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="w-6 h-6 iconify iconify--eos-icons" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
            <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z" opacity=".5" fill="currentColor" />
            <path d="M20 12h2A10 10 0 0 0 12 2v2a8 8 0 0 1 8 8z" fill="currentColor">
              <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite" />
            </path>
          </svg>
        </button>
      </form>
    </div>
  </div>
</template>

<style lang="postcss" scoped>
.label-item-input {
    @apply block text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}
.tooltip {
  @apply absolute px-2 py-1 text-gray-200 bg-gray-700 rounded dark:bg-gray-600 z-50;
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