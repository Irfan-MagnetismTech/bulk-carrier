<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useFuse } from '@vueuse/integrations/useFuse';
import moment from 'moment';
import Title from '../../../services/title';
import useVoyage from '../../../composables/operation/useVoyage';
import Heading from '../../../components/Heading.vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import PermissionBlockedModal from "../../../components/utils/PermissionBlockedModal.vue";

const { setTitle } = Title();
const title = 'Voyage Details';
const route = useRoute();
const voyageId = route.params.voyageId;
const { voyage, containers, isLoading, getVoyageDetails, getContainersByVoyage, deleteVoyageContainer } = useVoyage();
const search = ref('');
const options = ref({
    fuseOptions: {
        keys: ['stow', 'slot_partner', 'gross_wt', 'pol_code', 'pod_code', 'container', 'status', 'iso', 'mlo', 'delivery_port_code'],
        threshold: 0,
    },
    matchAllWhenSearchEmpty: true,
});
const { results: searchResults } = useFuse(search, containers, options);
const isContainersShowing = ref(false);

let permissionBlockedModal = ref(false);
let message = ref('');

watch(voyage, (value) => {
    if (value === null || value === undefined) {
        setTitle(title);
    } else {
        setTitle(`${value.voyage} - ${title}`);
    }
});

function toggleStatus(msg) {
  //set message to be displayed in the modal here
  message.value = msg;
  permissionBlockedModal.value = !permissionBlockedModal.value;
}

onMounted(() => {
    getVoyageDetails(voyageId);
    getContainersByVoyage(voyageId);
});
</script>

<template>
    <!-- Heading -->
    <Heading :label="`${title}: #${voyage?.voyage}`" type="list" :to="{ name: 'operation.voyages.index' }"></Heading>
    <!-- Content -->
    <div class="w-full flex px-4 py-3 mb-4 bg-white rounded-lg shadow-md dark:divide-gray-600 dark:bg-gray-800">
      <div class="w-2/4 overflow-x-auto p-1">
        <table class="w-full whitespace-no-wrap text-left table">
          <thead v-once>
          <tr class="text-xs tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">Field</th>
            <th class="px-4 py-3">Data</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Vessel</td>
            <td class="text-sm">{{ voyage?.vessel?.name ?? 'N/A' }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Voyage No</td>
            <td class="text-sm">{{ voyage?.voyage }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Service</td>
            <td class="text-sm">{{ voyage?.service.name ?? 'N/A' }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Port Origin</td>
            <td class="text-sm">{{ voyage?.port_origin ?? 'N/A' }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Port Discharge</td>
            <td class="text-sm">{{ voyage?.port_discharge ?? 'N/A' }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Export Rotation</td>
            <td class="text-sm">{{ voyage?.export_rotation ?? 'N/A' }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Import Rotation</td>
            <td class="text-sm">{{ voyage?.import_rotation ?? 'N/A' }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Dep. Date(FPOL)</td>
            <td>{{ voyage?.voyage_schedule_first_port?.etd_date ? moment(voyage?.voyage_schedule_first_port?.etd_date).format('DD-MM-YYYY') : null}}</td>
          </tr>

          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Dep. Date(NPOL)</td>
            <td>{{ voyage?.voyage_schedule_last_port?.etd_date ? moment(voyage?.voyage_schedule_last_port?.etd_date).format('DD-MM-YYYY') : null}}</td>
          </tr>

          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Arr. Date(FPOD)</td>
            <td>{{ voyage?.voyage_schedule_first_port?.eta_date ? moment(voyage?.voyage_schedule_first_port?.eta_date).format('DD-MM-YYYY') : null}}</td>
          </tr>



          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Import Pilot Boarding Time</td>
            <td class="text-sm">{{ voyage?.import_pilot_boarding_time ? moment(voyage?.import_pilot_boarding_time).format('DD-MM-YYYY') : 'N/A' }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Export Pilot Boarding Time</td>
            <td class="text-sm">{{ voyage?.export_pilot_boarding_time ? moment(voyage?.export_pilot_boarding_time).format('DD-MM-YYYY') : 'N/A' }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">EDI Sender</td>
            <td class="text-sm">{{ voyage?.sender ?? 'N/A' }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">EDI Recipient</td>
            <td class="text-sm">{{ voyage?.recipient ?? 'N/A' }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">EDI Document No</td>
            <td class="text-sm">{{ voyage?.document_no ?? 'N/A' }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">EDI Message Reference No</td>
            <td class="text-sm">{{ voyage?.message_reference_no ?? 'N/A' }}</td>
          </tr>
          <!-- <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Message Compilation Time</td>
            <td class="text-sm">{{ voyage?.message_compilation_time ? moment(voyage?.message_compilation_time).format('DD-MM-YYYY') : 'N/A' }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Time Of Preparation</td>
            <td class="text-sm"> {{ voyage?.time_of_preparation ? moment(voyage?.time_of_preparation).format('DD-MM-YYYY') : 'N/A' }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Message Type</td>
            <td class="text-sm">{{ voyage?.message_type ?? 'N/A' }}</td>
          </tr> -->
          </tbody>
        </table>
      </div>
      <div class="w-2/4 overflow-x-auto p-1">
        <div class="w-full p-3 text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800" style="background-color: honeydew;border: 1px solid forestgreen">
          <p class="text-center mb-7 text-blue-800 font-semibold">Voyage Life Cycle</p>
          <ol class="relative border-l border-gray-400 dark:border-gray-700 m-3 ml-5">
            <li class="mb-5 ml-6">
              <span v-if="voyage?.life_cycle?.voyage_created" style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              </span>
              <span v-else style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              </span>
              <h6 class="flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white">Voyage Created
              </h6>
              <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Updated on January 13th, 2022</time>
            </li>
            <li class="mb-5 ml-6">
              <span v-if="voyage?.life_cycle?.tdr_uploaded" style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              </span>
              <span v-else style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              </span>
              <h6 class="flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white">TDR Uploaded</h6>
              <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Updated on January 13th, 2022</time>
            </li>
            <li class="mb-5 ml-6">
              <span v-if="voyage?.life_cycle?.customer_advisory" style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              </span>
              <span v-else style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>              </span>
              <h6 class="flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white">Customer Advisory Sent</h6>
              <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Updated on January 13th, 2022</time>
            </li>
            <li class="mb-5 ml-6">
              <span v-if="voyage?.life_cycle?.customer_assign" style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              </span>
              <span v-else style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>              </span>
              <h6 class="flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white">Customer Assign</h6>
              <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Updated on January 13th, 2022</time>
            </li>
            <li class="mb-5 ml-6">
              <span v-if="voyage?.life_cycle?.contract_assign" style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              </span>
              <span v-else style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>              </span>
              <h6 class="flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white">Contract Assign</h6>
              <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Updated on January 13th, 2022</time>
            </li>
            <li class="mb-5 ml-6">
              <span v-if="voyage?.life_cycle?.dg_assign" style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              </span>
              <span v-else style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>              </span>
              <h6 class="flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white">DG Group Assign</h6>
              <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Updated on January 13th, 2022</time>
            </li>
            <li class="mb-5 ml-6">
              <span v-if="voyage?.life_cycle?.fr_assign" style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              </span>
              <span v-else style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>              </span>
            <h6 class="flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white">FR Container Checked</h6>
            <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Updated on January 13th, 2022</time>
          </li>
            <li class="mb-5 ml-6">
              <span v-if="voyage?.life_cycle?.rf_assign" style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              </span>
              <span v-else style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>              </span>
              <h6 class="flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white">RF Container Checked</h6>
              <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Updated on January 13th, 2022</time>
            </li>
            <li class="mb-5 ml-6">
               <span v-if="voyage?.life_cycle?.invoice_generated" style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              </span>
              <span v-else style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>              </span>
              <h6 class="flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white">Invoice Generate</h6>
              <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Updated on January 13th, 2022</time>
            </li>
            <li class="mb-5 ml-6">
               <span v-if="voyage?.life_cycle?.voyage_closed" style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              </span>
              <span v-else style="background-color: azure" class="flex absolute -left-3 justify-center items-center w-6 h-6 rounded-full ring-8 ring-white dark:ring-gray-900">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>              </span>
              <h6 class="flex items-center mb-1 text-sm font-semibold text-gray-900 dark:text-white">Voyage Closed</h6>
              <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Updated on January 13th, 2022</time>
            </li>

          </ol>
        </div>
      </div>
    </div>
    <!-- containers -->
    <div class="flex items-center w-full my-6 gap-2">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Containers</h2>
        <button @click="isContainersShowing = !isContainersShowing">
            <svg v-if="isContainersShowing" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--fluent w-5 h-5 text-gray-600 dark:text-gray-300" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20">
                <path fill="currentColor" d="M3.26 11.602C3.942 8.327 6.793 6 10 6c3.206 0 6.057 2.327 6.74 5.602a.5.5 0 0 0 .98-.204C16.943 7.673 13.693 5 10 5c-3.693 0-6.943 2.673-7.72 6.398a.5.5 0 0 0 .98.204ZM10 8a3.5 3.5 0 1 0 0 7a3.5 3.5 0 0 0 0-7Zm-2.5 3.5a2.5 2.5 0 1 1 5 0a2.5 2.5 0 0 1-5 0Z" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--fluent w-5 h-5 text-gray-600 dark:text-gray-300" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20">
                <g fill="none">
                    <path d="M2.854 2.146a.5.5 0 1 0-.708.708l3.5 3.498a8.097 8.097 0 0 0-3.366 5.046a.5.5 0 1 0 .98.204a7.09 7.09 0 0 1 3.107-4.528L7.953 8.66a3.5 3.5 0 1 0 4.886 4.886l4.307 4.308a.5.5 0 0 0 .708-.708l-15-15zm9.265 10.68A2.5 2.5 0 1 1 8.673 9.38l3.446 3.447z" fill="currentColor" />
                    <path d="M10.123 8.002l3.375 3.374a3.5 3.5 0 0 0-3.374-3.374z" fill="currentColor" />
                    <path d="M10 6c-.57 0-1.129.074-1.666.213l-.803-.803A7.648 7.648 0 0 1 10 5c3.693 0 6.942 2.673 7.72 6.398a.5.5 0 0 1-.98.204C16.058 8.327 13.207 6 10 6z" fill="currentColor" />
                </g>
            </svg>
        </button>
    </div>
    <div v-show="isContainersShowing">
        <div class="flex items-center justify-between mb-2 select-none">
            <span class="w-full text-xs font-medium text-gray-500 whitespace-no-wrap">Showing: {{ searchResults?.length ?? 0 }} of {{ containers?.length ?? 0 }}</span>
            <!-- Search -->
            <div class="relative w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
                <input type="text"  v-model.trim="search" placeholder="Search..." class="search" />
            </div>
        </div>
        <!-- Table -->
        <div class="w-full mb-6 overflow-hidden rounded-lg shadow-xs" v-memo>
            <div class="w-full md:overflow-x-auto">
                <table class="w-full whitespace-no-wrap border dark:border-0">
                    <thead>
                        <tr>
                            <th>Slot</th>
                            <th data-title="Slot Partner">S/P</th>
                            <th>Weight</th>
                            <th>Tues</th>
                            <th>POL</th>
                            <th>POD</th>
                            <th data-title="Container no">Cont. No</th>
                            <th>Status</th>
                            <th>ISO</th>
                            <!-- <th>Group</th> -->
                            <th>MLO</th>
                            <!-- <th>Del. Port</th> -->
                            <th class="px-4 py-3 text-center">Actions</th>

                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        <tr class="text-gray-700 dark:text-gray-400" v-for="result in searchResults" :key="result?.item?.id">
                            <td>{{ result?.item?.stow }}</td>
                            <td>{{ result?.item?.slot_partner }}</td>
                            <td>{{ result?.item?.gross_wt }}</td>
                            <td>{{ result?.item?.tues }}</td>
                            <td>{{ result?.item?.pol_code }}</td>
                            <td>{{ result?.item?.pod_code }}</td>
                            <td>{{ result?.item?.container }}</td>
                            <td>{{ result?.item?.status }}</td>
                            <td>{{ result?.item?.iso }}</td>
                            <!-- <td>{{ result?.item?.group }}</td> -->
                            <td>{{ result?.item?.mlo }}</td>
                            <!-- <td class="border-r-0">{{ result?.item?.delivery_port_code }}</td> -->
                            <td class="flex items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">

                              <template v-if="voyage?.life_cycle?.customer_assign == 0">
                                <action-button :action="'edit'" :to="{ name: 'operation.containers.edit', params: { containerId: result?.item?.id } }"></action-button>
                                <action-button @click="deleteVoyageContainer(result?.item?.id,voyageId)" :action="'delete'"></action-button>
                              </template>

                              <template v-if="voyage?.life_cycle?.customer_assign == 1">
                                <a @click="toggleStatus('Customer already assigned for this voyage.')" class="relative custom_text_opacity" data-v-4af53e06="" data-v-9f4e2b8c="">
                                  <div class="tooltip" data-v-4af53e06=""><svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500" viewBox="0 0 20 20" fill="currentColor" data-v-4af53e06=""><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" data-v-4af53e06=""></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" data-v-4af53e06=""></path></svg>
                                    <span class="tooltiptext" data-v-4af53e06="">edit</span>
                                  </div>
                                </a>
                                <a @click="toggleStatus('Customer already assigned for this voyage.')" aria-current="page" class="router-link-active router-link-exact-active relative custom_text_opacity" data-v-4af53e06="" data-v-9f4e2b8c="">
                                  <div class="tooltip" data-v-4af53e06=""><svg xmlns="http://www.w3.org/2000/svg" class="icn text-red-500 dark:text-gray-400 dark:hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" data-v-4af53e06=""><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" data-v-4af53e06=""></path>
                                    <span class="tooltiptext" data-v-4af53e06="">delete</span></svg>
                                    <span class="tooltiptext" data-v-4af53e06="">delete</span>
                                  </div>
                                </a>
                              </template>
                            </td>
                        </tr>
                        <tr v-if="searchResults.length === 0">
                            <td colspan="13" class="search-result">No data available</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  <permission-blocked-modal :message="message" v-if="permissionBlockedModal" @close-permission-blocked-modal="toggleStatus"></permission-blocked-modal>

</template>

<style lang="postcss" scoped>
@tailwind components;

@layer components {
    .group {
        @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
    }
    .group-item {
        @apply block w-full mt-3 text-sm;
    }
    .group-title {
        @apply font-semibold uppercase dark:text-gray-200;
    }
    .group-content {
        @apply ml-1 capitalize dark:text-gray-300 text-xs;
    }
    .group-empty {
        @apply hidden md:block md:w-full;
    }
    .label-item-title {
        @apply text-gray-700 dark:text-gray-300;
    }
    .label-item-input {
        @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
    th {
        @apply px-3 py-2;
    }
    td {
        @apply px-3 py-2 text-xs border-r dark:border-r-gray-600;
    }
    thead tr {
        @apply text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
    }
    tbody {
        @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
    }
    .search-result {
        @apply px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-300;
    }
    .search {
        @apply float-right  pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
    }
    .filter-wrapper {
        @apply border border-gray-300 rounded dark:border-none dark:bg-gray-700;
    }
    .filter-item {
        @apply px-3 py-1 leading-loose text-gray-400 capitalize cursor-pointer select-none hover:text-purple-500 hover:font-semibold dark:hover:text-gray-300;
    }
    .filter-item-active {
        @apply font-semibold text-purple-500 dark:text-gray-300;
    }

    .table{
      @apply border border-gray-300;
    }

  thead th{
    @apply border border-gray-300;
  }
}

</style>