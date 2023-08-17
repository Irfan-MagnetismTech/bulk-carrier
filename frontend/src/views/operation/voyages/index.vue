<script setup>
import { onMounted, computed, ref, watchEffect, watch } from 'vue';
import { orderBy } from 'lodash';
import { onClickOutside } from '@vueuse/core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import moment from 'moment';
import Heading from '../../../components/Heading.vue';
import useVoyage from '../../../composables/operation/useVoyage';
import IconExcel from '../../../components/icons/IconExcel.vue';
import IconAnchor from '../../../components/icons/IconAnchor.vue';
import IconFile from '../../../components/icons/IconFile.vue';
import IconCodeDownload from '../../../components/icons/IconCodeDownload.vue';
import IconDownload from '../../../components/icons/IconDownload.vue';
import useReport from '../../../composables/operation/useReport';
import Title from "../../../services/title";
import Paginate from '../../../components/utils/paginate.vue';
import useDebouncedRef from "../../../composables/useDebouncedRef";
import useVessel from "../../../composables/dataencoding/useVessel";
import useService from "../../../composables/commercial/useService";
import usePort from "../../../composables/usePort";
import env from "../../../config/env.js";
import PermissionBlockedModal from "../../../components/utils/PermissionBlockedModal.vue";

const props = defineProps({
    page: {
        type: Number,
        default: 1,
    },
});

const { voyages, isLoading, voyageName, voyageCustomers, getVoyageName, getVoyageCustomer, getVoyages, clearVoyage, deleteVoyage } = useVoyage();
const { vesselName, getVesselName } = useVessel();
const { services, getServices } = useService();
const { portName, getPortsByNameOrCode } = usePort();
const { generateBayPlanPDF, generateVoyageEdi, generateVoyageExcel, generateVoyageSummary, generateCopinoBooking, downloadDraftTdr } = useReport();

const { setTitle } = Title();
setTitle('Voyages');
const showEdiDownloadDialogue = ref(false)
const showDraftTdrDownloadDialogue = ref(false)
const draftTdrs = ref([]);
const draftTdrVoyageInfo = ref(null);
const ediSectors = ref([]);
const ediVoyage = ref(null);
const showHistoricalFile = ref(false)
const activeVoyageName = ref(null);
let ediType = '';
let letterCount = 3;
// Code for global search starts here
const columns = ["voyage"];
const searchKey = useDebouncedRef('', 800);
const table = "voyages";

let message = ref('');

watch(searchKey, newQuery => {
  getVoyages(props.page, columns, searchKey.value, table);
});
// Code for global search end here
const menu = ref(false);
const showingMenuIndex = ref(-1);

let permissionBlockedModal = ref(false);

onClickOutside(menu, (event) => showingMenuIndex.value = -1);

function toggleMenu(index) {
    if (showingMenuIndex.value === index) {
        showingMenuIndex.value = -1;
    } else {
        showingMenuIndex.value = index;
    }
}

function toggleStatus(msg) {
  //set message to be displayed in the modal here
  message.value = msg;
  permissionBlockedModal.value = !permissionBlockedModal.value;
}

function toggleHistoricalFile() {
    showHistoricalFile.value = !showHistoricalFile.value;
}

const searchParams = ref({
  voyage_no: null,
  voyage_id: '',
  vessel_no: null,
  vessel_id: '',
  etd_date: '',
  eta_date: '',
});

function clearFormData(){
  searchParams.value = {
    voyage_no: null,
    voyage_id: '',
    vessel_no: null,
    vessel_id: '',
    etd_date: '',
    eta_date: '',
  };
}

function fetchVoyages(search, loading) {
  getVoyageName(search);
}

function fetchVessels(search, loading) {
  getVesselName(search);
}

function fetchPortCode(search, loading) {
  getPortsByNameOrCode(search);
}

function ediPopUp(voyage) {
  if(showEdiDownloadDialogue.value) {
    showEdiDownloadDialogue.value = false
  } else {
    showEdiDownloadDialogue.value = true
  }

  ediSectors.value = [...new Set(voyage?.service?.route.split("-"))];
  ediVoyage.value = voyage

}

function draftTdrPopUp(voyage) {
  if(showDraftTdrDownloadDialogue.value) {
    showDraftTdrDownloadDialogue.value = false
  } else {
    showDraftTdrDownloadDialogue.value = true
  }



  showHistoricalFile.value = false

  try {
      draftTdrs.value = JSON.parse(voyage.draft_tdr)
      // draftTdrs.value.reverse();
    } catch (e) {
      draftTdrs.value = [];
      if(voyage.draft_tdr) {
        draftTdrs.value.push(voyage.draft_tdr)
      }
    }

    if(draftTdrs.value) {
      draftTdrs.value.reverse();
    } else {
      draftTdrs.value = []
    }
    activeVoyageName.value = voyage.voyage

    draftTdrVoyageInfo.value = voyage
}

function closeDraftTdrDialogue() {
  if(showDraftTdrDownloadDialogue.value) {
    showDraftTdrDownloadDialogue.value = false
  } else {
    showDraftTdrDownloadDialogue.value = true
  }
}

function getDraftTdrContent(fileurl) {
  //alert(fileurl);
}

function closeEdiDialogue() {
  if(showEdiDownloadDialogue.value) {
    showEdiDownloadDialogue.value = false
  } else {
    showEdiDownloadDialogue.value = true
  }
}

onMounted(() => {
  getServices();
  watchEffect(() => {

    if(searchParams.value.voyage_no){
      searchParams.value.voyage_id = searchParams.value.voyage_no.id;
    }
    if(searchParams.value.service_data){
      searchParams.value.service_id = searchParams.value.service_data.id;
    }

    if(searchParams.value.port_origin_name){
      searchParams.value.port_origin = searchParams.value.port_origin_name.code;
    }

    if(searchParams.value.port_discharge_name){
      searchParams.value.port_discharge = searchParams.value.port_discharge_name.code;
    }

    getVoyages(props.page, searchParams.value);
    //getVoyages(props.page, columns, searchKey.value, table);
  });
});
</script>
<template>
    <!-- Heading -->
    <Heading :to="{ name: 'operation.voyages.create' }" type="create" label="Voyages"></Heading>
  <div class="w-full flex items-center justify-between select-none">
    <fieldset class="w-full px-2 pb-3 border border-gray-700 rounded mb-2 dark:border-gray-400">
      <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Search Voyage</legend>
      <div class="w-full grid grid-cols-7 gap-1">
        <div class="">
          <label for="" class="text-xs" style="margin-left: .01rem">Voyage No</label>
          <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchVoyages" v-model="searchParams.voyage_no" label="voyage"></v-select>
          <input type="hidden" v-model="searchParams.voyage_id">
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Vessel</label>
          <v-select :options="vesselName" placeholder="--Choose an option--" @search="fetchVessels" v-model="searchParams.vessel_name" label="name"></v-select>
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Service</label>
          <v-select :options="services" placeholder="--Choose an option--" v-model="searchParams.service_data" label="code" ></v-select>
          <input type="hidden" v-model="searchParams.service_id">
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">FPOL</label>
          <v-select v-model="searchParams.port_origin_name" @search="fetchPortCode" :options="portName" label="code" placeholder="Port Code"></v-select>
          <input type="hidden" v-model="searchParams.port_origin">
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">FPOD</label>
          <v-select v-model="searchParams.port_discharge_name" @search="fetchPortCode" :options="portName" label="code" placeholder="Port Code"></v-select>
          <input type="hidden" v-model="searchParams.port_discharge">
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">(Dep. Date) FPOL</label>
          <input class="block w-full text-sm rounded dark:text-gray-300 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" type="date" v-model="searchParams.etd_date">
        </div>
        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">(Arr. Date) FPOD</label>
          <input class="block w-full text-sm rounded dark:text-gray-300 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" type="date" v-model="searchParams.eta_date">
        </div>
<!--        <div>-->
<!--          <label for="" class="text-xs" style="margin-left: .01rem">Pod</label>-->
<!--          <v-select v-model="searchParams.pod_code_name" @search="fetchPortCode" :options="ports" label="code" placeholder="Port Code" required></v-select>-->
<!--          <input type="hidden" v-model="searchParams.pod_code">-->
<!--        </div>-->
<!--        <div>-->
<!--          <label for="" class="text-xs" style="margin-left: .01rem">CID</label>-->
<!--          <v-select placeholder="&#45;&#45;Choose an option&#45;&#45;" :options="customerCodeName" @search="fetchCustomer" v-model="searchParams.customer_codes_name" label="customer_code"></v-select>-->
<!--          <input type="hidden" v-model="searchParams.customer_id">-->
<!--        </div>-->
<!--        <div>-->
<!--          <label for="" class="text-xs" style="margin-left: .01rem">From</label>-->
<!--          <input type="date" id="issue_date_from" class="search w-full" v-model="searchParams.issue_date_from">-->
<!--        </div>-->

<!--        <div>-->
<!--          <label for="" class="text-xs" style="margin-left: .01rem">To</label>-->
<!--          <input type="date" id="issue_date_true" name="issue_date_to" class="search w-full" v-model="searchParams.issue_date_to">-->
<!--        </div>-->

      </div>
    </fieldset>
  </div>

  <div class="flex flex-row-reverse items-center justify-between mb-2">
    <div class="float-right">
      <button @click="clearFormData" class="w-32 flex items-center justify-center px-2 py-2 text-sm font-medium text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Clear</span>
      </button>
    </div>
  </div>

<!--  <div class="flex items-center justify-between mb-2 select-none">-->
<!--    <span class="w-full text-xs font-medium text-gray-500 whitespace-no-wrap">Showing {{ voyages?.from }}-{{ voyages?.to }} of {{ voyages?.total }}</span>-->
<!--    &lt;!&ndash; Search &ndash;&gt;-->
<!--    <div class="relative w-full">-->
<!--      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">-->
<!--        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />-->
<!--      </svg>-->
<!--      <input type="text"  v-model.trim="searchKey" placeholder="Search with voyage no..." class="search" />-->
<!--    </div>-->
<!--  </div>-->
  <a style="background-color: darkcyan" class="flex items-center justify-between p-2 mb-2 text-sm font-semibold text-gray-100 bg-gray-400 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple">
    <div class="flex items-center">
      <span><span>Activities: </span>(Copino Upload, Copino Booking Summary Download, TDR Upload, EDI Generate, Summary Download, Bay Plan)</span>
    </div>
  </a>
    <!-- Table -->
    <!-- <div class="max-w-screen h-full overflow-x-auto bg-white overflow-y-auto mb-4">
    </div>-->
    <div class="w-full">
        <div class="w-full">
            <table class="w-full whitespace-no-wrap mb-5">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
                        <th class="text-center">#</th>
                        <th class="text-center">Vessel</th>
                        <th class="text-center" data-title="Voyage No">Voy. no</th>
                        <th class="text-center">Service</th>
                        <th class="text-center" data-title="First port of Loading">FPOL</th>
                        <th class="text-center" data-title="Next port of Calling">NPOC</th>
                        <th class="text-center" data-title="Final port of Discharge">FPOD</th>
                        <th class="text-center" data-title="Departure date of first port of loading"><nobr>Dep. Date(FPOL)</nobr></th>
                        <th class="text-center" data-title="Departure date of next port of loading"><nobr>Dep. Date(NPOL)</nobr></th>
                        <th class="text-center" data-title="Arrival date of first port of discharge">Arr. Date(FPOD)</th>
                        <th class="text-center" data-title="Sailing Status">Status</th>
                        <th class="text-center" data-title="TDR Status">TDR Status</th>


<!--                        <th class="text-center">POD</th>-->
<!--                        <th>Arrival Date</th>-->
<!--                        <th>Berthing Date</th>-->
<!--                        <th>Departure Date</th>-->
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400 hover:bg-green-300 transition-all ease-in-out duration-200" v-for="(voyage, index) in (voyages?.data ? voyages?.data : voyages)" :key="voyage.id">
                        <td class="px-1 text-center">{{ (voyages?.from) ? voyages?.from + index : index+1 }}</td>
                        <td class="font-semibold text-center dark:text-gray-300"><nobr>{{ voyage?.vessel?.name }}</nobr></td>
                        <td class="font-semibold text-center dark:text-gray-300">{{ voyage?.voyage }}</td>
                        <td class="font-semibold text-center dark:text-gray-300">{{ voyage?.service?.code }}</td>
                        <td class="font-semibold text-center dark:text-gray-300">{{ voyage?.port_origin }}</td>
                        <td class="font-semibold text-center dark:text-gray-300">{{ voyage?.next_port }}</td>
                        <td class="font-semibold text-center dark:text-gray-300">{{ voyage?.port_discharge }}</td>
                        <td>{{ voyage?.voyage_schedule_first_port?.etd_date ? moment(voyage?.voyage_schedule_first_port?.etd_date).format('DD-MM-YYYY') : null}}</td>
                        <td>{{ voyage?.voyage_schedule_last_port?.etd_date ? moment(voyage?.voyage_schedule_last_port?.etd_date).format('DD-MM-YYYY') : null}}</td>
                        <td>{{ voyage?.voyage_schedule_first_port?.eta_date ? moment(voyage?.voyage_schedule_first_port?.eta_date).format('DD-MM-YYYY') : null}}</td>
                        <td class="font-semibold text-center dark:text-gray-300" v-if="voyage?.load_type == 'Commercial'">Com.</td>
                        <td class="font-semibold text-center dark:text-gray-300" v-else>Non.</td>
                        <td class="font-semibold text-center dark:text-gray-300" v-if="voyage?.life_cycle?.tdr_uploaded == 1">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                          </svg>

                        </td>
                        <td class="font-semibold text-center dark:text-gray-300" v-else>
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto text-red-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                        </td>


<!--                        <td class="font-semibold text-center dark:text-gray-300">{{ voyage?.port_discharge }}</td>-->
<!--                        <td>{{ voyage?.voyage_schedule_first_port?.eta_date ? moment(voyage?.voyage_schedule_first_port?.eta_date).format('DD-MM-YYYY') : null}}</td>-->
<!--                        <td>{{ voyage?.voyage_schedule_first_port?.etb_date ? moment(voyage?.voyage_schedule_first_port?.etb_date).format('DD-MM-YYYY') : null }}</td>-->
<!--                        <td>{{ voyage?.voyage_schedule_first_port?.etd_date ? moment(voyage?.voyage_schedule_first_port?.etd_date).format('DD-MM-YYYY') : null }}</td>-->
                        <td>
                          <nobr>
                            <action-button :action="'show'" :to="{ name: 'operation.voyages.show', params: { voyageId: voyage?.id ?? -1 } }"></action-button>

                            <template v-if="voyage?.life_cycle?.tdr_uploaded == 0">
                              <action-button :action="'edit'" :to="{ name: 'operation.voyages.edit', params: { voyageId: voyage?.id ?? -1 } }"></action-button>
                              <action-button @click="clearVoyage((voyage?.id ?? -1))" :action="'clear data'"></action-button>
                              <action-button @click="deleteVoyage(voyage?.id ?? -1)" :action="'delete'"></action-button>
                            </template>

                            <template v-if="voyage?.life_cycle?.tdr_uploaded == 1">
                              <a style="display: inline-block" class="relative custom_text_opacity" data-v-4af53e06="" data-v-c2953836="" @click="toggleStatus('Final TDR already uploaded for this voyage.')">
                                <svg style="height: 1.25rem;width: 1.25rem" xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500" viewBox="0 0 20 20" fill="currentColor" data-v-4af53e06=""><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" data-v-4af53e06=""></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" data-v-4af53e06=""></path></svg>
                              </a>
                              <a style="display: inline-block" @click="toggleStatus('Final TDR already uploaded for this voyage.')" aria-current="page" class="router-link-active router-link-exact-active relative custom_text_opacity" data-v-4af53e06="" data-v-c2953836="">
                                <svg class="w-6 h-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999" xml:space="preserve" data-v-4af53e06=""><path d="m131.221 197.202-32.488 8.735 74.045 296.978h116.358z" data-v-4af53e06="" style="fill: rgb(255, 220, 100);"></path><path d="m98.733 205.937 74.045 296.978h50.684L115.876 201.16z" data-v-4af53e06="" style="fill: rgb(255, 200, 80);"></path><path d="M250.078 25.103 104.704 170.477l30.857 25.655L255.21 56.206z" data-v-4af53e06="" style="fill: rgb(255, 220, 100);"></path><path d="m395.452 119.169 28.945 23.328-18.14-54.421-42.327-6.047-48.375 48.374 6.047 42.328 54.421 18.14-23.328-28.945 5.188-37.57z" data-v-4af53e06="" style="fill: rgb(127, 132, 153);"></path><circle cx="113.252" cy="187.585" r="25.654" data-v-4af53e06="" style="fill: rgb(225, 165, 70);"></circle><path d="m314.518 56.804-24.187 24.188a8.553 8.553 0 0 1-12.094 0L249.04 51.795c-10.019-10.019-10.019-26.262 0-36.28 10.019-10.019 26.262-10.019 36.28 0l29.197 29.197a8.548 8.548 0 0 1 .001 12.092z" data-v-4af53e06="" style="fill: rgb(255, 200, 80);"></path><circle cx="339.737" cy="106.219" r="17.103" data-v-4af53e06="" style="fill: rgb(149, 156, 181);"></circle><path d="m432.003 139.962-18.14-54.422a8.014 8.014 0 0 0-6.472-5.401l-42.327-6.047a8.02 8.02 0 0 0-6.803 2.267l-7.35 7.35a25.012 25.012 0 0 0-11.168-2.614 25 25 0 0 0-11.169 2.614l-14.811-14.811 6.424-6.424c6.46-6.46 6.46-16.971 0-23.431L290.99 9.846c-13.129-13.129-34.49-13.129-47.619 0-5.667 5.667-8.883 12.871-9.657 20.283l-123.96 123.962c-16.926 1.758-30.169 16.106-30.169 33.489 0 10.667 4.992 20.185 12.756 26.359l70.506 282.027h-15.385a8.017 8.017 0 0 0 0 16.034h162.477a8.017 8.017 0 0 0 0-16.034h-12.323L141.125 206.458a33.568 33.568 0 0 0 4.56-9.83L254.86 68.952l17.709 17.709a16.515 16.515 0 0 0 11.715 4.845c4.243 0 8.485-1.615 11.715-4.845l6.424-6.424 14.831 14.831a25.21 25.21 0 0 0 0 22.299l-7.368 7.369a8.021 8.021 0 0 0-2.268 6.803l6.047 42.327a8.02 8.02 0 0 0 5.401 6.472l54.421 18.14c.836.279 1.69.413 2.534.413a8.016 8.016 0 0 0 5.67-13.686l-21.353-21.353 4.63-32.407 32.407-4.63 21.353 21.353a8.017 8.017 0 0 0 13.275-8.206zm-318.757 29.981H113.267c9.721.005 17.629 7.915 17.629 17.637 0 9.726-7.912 17.638-17.638 17.638S95.62 197.306 95.62 187.58c-.002-9.722 7.905-17.631 17.626-17.637zm66.128 326.023-68.705-274.822c.855.065 1.717.109 2.588.109a33.451 33.451 0 0 0 15.568-3.83L279.39 495.966H179.374zm-35.357-322.059a33.876 33.876 0 0 0-15.22-16.185L237.315 49.204a33.583 33.583 0 0 0 6.057 8.26l.115.115-99.47 116.328zm140.645-98.583a.536.536 0 0 1-.756 0L254.71 46.127c-6.876-6.877-6.876-18.066 0-24.943a17.58 17.58 0 0 1 12.471-5.158c4.516 0 9.033 1.72 12.471 5.158l29.196 29.197a.536.536 0 0 1 0 .756l-24.186 24.187zm61.505 24.467a9.027 9.027 0 0 1 2.662 6.424 9.022 9.022 0 0 1-2.662 6.424c-1.716 1.716-3.998 2.662-6.424 2.662s-4.709-.945-6.424-2.661c-3.542-3.542-3.542-9.307 0-12.85a9.028 9.028 0 0 1 6.425-2.662 9.035 9.035 0 0 1 6.423 2.663zm-17.329 66.901-4.779-33.454 4.515-4.515a25.013 25.013 0 0 0 11.169 2.614c3.237 0 6.378-.614 9.298-1.777l-5.142 35.991a8.018 8.018 0 0 0 2.268 6.803l.173.173-17.502-5.835zm77.041-54.051a8.016 8.016 0 0 0-6.803-2.267l-35.991 5.142a25.08 25.08 0 0 0 1.777-9.299c0-3.941-.908-7.74-2.614-11.169l4.515-4.515 33.453 4.779 5.834 17.503-.171-.174z" data-v-4af53e06=""></path></svg>
                              </a>
                              <a style="display: inline-block" @click="toggleStatus('Final TDR already uploaded for this voyage.')" aria-current="page" class="relative custom_text_opacity" data-v-4af53e06="" data-v-c2953836="">
                                <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.25rem;width: 1.25rem" class="icn text-red-500 dark:text-gray-400 dark:hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" data-v-4af53e06=""><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" data-v-4af53e06=""></path></svg>
                              </a>
                            </template>

                            <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: voyage.subject_type,subject_id: voyage.id } }"></action-button>
                            <div class="relative inline-block text-left select-none" ref="menu">
                              <div>
                                <svg @click="toggleMenu(index)" style="cursor: pointer;height: 1.3rem" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                              </div>
                              <div v-if="showingMenuIndex === index" class="absolute z-50 right-0 w-56 mt-2 origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                <div class="" role="none">
                                  <router-link :to="{ name: 'operation.voyages.copino', params: { voyageId: voyage?.id ?? -1 } }" class="flex items-center px-4 py-2 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                                    <IconExcel class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500 stroke-2"></IconExcel>COPINO Upload
                                  </router-link>
                                  <router-link :to="{ name: 'operation.voyages.load_list', params: { voyageId: voyage?.id ?? -1, fileType: 'draft' } }" class="flex items-center px-4 py-2 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                                    <IconAnchor class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500 stroke-1"></IconAnchor> TDR Baplie Upload
                                  </router-link>
                                  <router-link :to="{ name: 'operation.voyages.load_list', params: { voyageId: voyage?.id ?? -1, fileType: 'final' } }" class="flex items-center px-4 py-2 text-gray-700 group bg-red-100 hover:bg-green-300 transition-all ease-in-out duration-200">
                                    <IconAnchor class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500 stroke-1"></IconAnchor> Final TDR Upload ({{ voyage?.life_cycle?.customer_assign }})
                                  </router-link>
<!--                                  <a v-if="voyage?.life_cycle?.customer_assign == 1" @click="toggleStatus('Customer already assigned for this voyage.')" class="custom_text_opacity flex items-center px-4 py-2 text-gray-700 group bg-red-100 hover:bg-green-300 transition-all ease-in-out duration-200" data-v-c2953836="" :class="{'custom_text_opacity' : voyage?.life_cycle?.customer_assign }">-->
<!--                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500 stroke-1 iconify iconify&#45;&#45; w-5 h-5 w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500 stroke-1" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" data-v-c2953836=""><path d="M17 15l1.55 1.55c-.96 1.69-3.33 3.04-5.55 3.37V11h3V9h-3V7.82C14.16 7.4 15 6.3 15 5c0-1.65-1.35-3-3-3S9 3.35 9 5c0 1.3.84 2.4 2 2.82V9H8v2h3v8.92c-2.22-.33-4.59-1.68-5.55-3.37L7 15l-4-3v3c0 3.88 4.92 7 9 7s9-3.12 9-7v-3l-4 3zM12 4c.55 0 1 .45 1 1s-.45 1-1 1s-1-.45-1-1s.45-1 1-1z" fill="currentColor"></path></svg>-->
<!--                                    <a data-v-c2953836="">Final TDR Upload</a>-->
<!--                                  </a>-->
                                </div>
                                <div class="block" role="none">
                                  <button @click="generateBayPlanPDF(voyage?.id ?? -1, voyage?.voyage)" class="w-full flex items-center px-4 py-2 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                                    <IconFile class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500 stroke-2"></IconFile>Download Bay Plan
                                  </button>
                                  <button @click="ediPopUp(voyage)" class="w-full flex items-center px-4 py-2 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                                    <IconCodeDownload class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500 stroke-2"></IconCodeDownload>Download EDI
                                  </button>
                                  <button @click="generateVoyageExcel(voyage?.id ?? -1, voyage?.voyage)" class="w-full flex items-center px-4 py-2 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                                    <IconDownload class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500 stroke-2"></IconDownload>Download Container List
                                  </button>
                                  <button @click="generateCopinoBooking(voyage?.id ?? -1, voyage?.voyage)" class="w-full flex items-center px-4 py-2 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                                    <IconDownload class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500 stroke-2"></IconDownload>Dwnld. COPINO Booking
                                  </button>
                                  <button @click="draftTdrPopUp(voyage)" class="w-full flex items-center px-4 py-2 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                                    <IconDownload class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500 stroke-2"></IconDownload>
                                    <p>
                                      Download TDR <br/>
                                      <small>(Draft)</small>
                                    </p>
                                  </button>
                                  <button @click="generateVoyageSummary(voyage?.id ?? -1, voyage?.voyage, 'OWNER')" class="w-full flex items-center px-4 py-2 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                                    <IconDownload class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500 stroke-2"></IconDownload>
                                    <p>
                                      Download TDR <br/>
                                      <small>(Summary by Owner)</small>
                                    </p>
                                  </button>
                                  <button @click="generateVoyageSummary(voyage?.id ?? -1, voyage?.voyage, 'CUSTOMER')" class="w-full flex items-center px-4 py-2 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                                    <IconDownload class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500 stroke-2"></IconDownload>
                                    <p>
                                      Download TDR <br/>
                                      <small>(Summary by Customer)</small>
                                    </p>
                                  </button>
                                  <router-link :to="{ name: 'operation.voyages.sof', params: { voyageId: voyage?.id ?? -1 } }" class="w-full flex items-center px-4 py-2 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                                    <svg style="color: rgba(112, 114, 117, var(--tw-text-opacity));" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" class="iconify iconify--ic w-5 h-5" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                      <path fill="currentColor" fill-rule="evenodd" d="M20 3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H4V5h16v14z"></path>
                                      <path fill="currentColor" fill-rule="evenodd" d="M19.41 10.42L17.99 9l-3.17 3.17l-1.41-1.42L12 12.16L14.82 15zM5 7h5v2H5zm0 4h5v2H5zm0 4h5v2H5z"></path>
                                    </svg> <p style="margin-left: 12px">SOF</p>
                                  </router-link>
                                </div>
                              </div>
                            </div>
                          </nobr>
                        </td>
                    </tr>
                    <tr v-if="(voyages?.data ? voyages?.data?.length : voyages?.length) === 0">
                        <td colspan="12" class="text-center dark:text-gray-400">
                            <span v-if="!isLoading">No records available.</span>
                            <span v-else-if="isLoading" class="flex items-center justify-center w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="w-5 h-5 iconify iconify--eos-icons" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                    <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z" opacity=".5" fill="currentColor" />
                                    <path d="M20 12h2A10 10 0 0 0 12 2v2a8 8 0 0 1 8 8z" fill="currentColor">
                                        <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite" />
                                    </path>
                                </svg>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
      <Paginate :data="voyages" to="operation.voyages.index" :page="page"></Paginate>
    </div>

    <!-- Modal -->
    <div v-if="showEdiDownloadDialogue" class="absolute mx-auto z-30" style="width: calc(100vw - 248px)">
      <div class="w-5/12 bg-purple-100 mx-auto rounded-md px-3 py-2 my-5 shadow-lg ">
        <div class="flex justify-between items-center">
          <span>EDI Download</span>
          <button @click="closeEdiDialogue" class="my-3 w-auto flex items-center justify-center px-2 py-2 text-sm font-medium text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Close
          </button>
        </div>
        <hr>
        <strong class="mt-3">Voyage:</strong> {{ ediVoyage?.voyage }}

        <div class="flex justify-between">
          <div>
            <label class="font-semibold block my-3">Select Destination Port</label>
            <select v-model="ediType">
              <option >Full</option>
              <option v-for="port in ediSectors" :value="port">{{ port }} COMPLETE</option>
              <option v-for="port in ediSectors" :value="port+'only'">{{ port }} ONLY</option>
            </select>
          </div>
          <div>
            <label class="font-semibold block my-3">Select Letter Count</label>
            <select v-model="letterCount">
              <option value="3">3 Letter</option>
              <option value="2">2 Letter</option>
            </select>
          </div>
        </div>

        <button @click="generateVoyageEdi(ediVoyage?.id ?? -1, ediVoyage?.voyage, ediType, letterCount)" class="my-3 w-32 flex items-center justify-center px-2 py-2 text-sm font-medium text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
          Download EDI
        </button>

      </div>
    </div>

    <div v-if="showDraftTdrDownloadDialogue" class="absolute mx-auto z-30" style="width: calc(100vw - 248px)">
      <div class="w-5/12 bg-purple-100 mx-auto rounded-md px-3 py-2 my-5 shadow-lg ">
        <div class="flex justify-between items-center">
          <span>Draft TDR Download</span>
          <button @click="closeDraftTdrDialogue" class="my-3 w-auto flex items-center justify-center px-2 py-2 text-sm font-medium text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Close
          </button>
        </div>

        <button @click="downloadDraftTdr(draftTdrVoyageInfo?.id ?? -1, draftTdrVoyageInfo?.voyage)" class="w-full flex items-center justify-center p-2 mb-2 text-sm font-semibold text-gray-100 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple bg-green-600 hover:bg-green-700 py-3">
          Download Latest File
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 pl-1 animate-bounce">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
          </svg>

        </button>

        <!-- <h3 class="text-center text-2xl flex justify-between my-3">
          Historical Files
          <button type="button" @click="toggleHistoricalFile">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
            </svg>
          </button>
        </h3>
        <div v-if="showHistoricalFile === true && draftTdrs != null">
          <a v-for="(tdr, index) in draftTdrs" :key="index" :href="env.BASE_API_URL+'storage/'+tdr" :download="activeVoyageName+' Draft Tdr.xlsx'" target="_blank" class="my-3 w-full flex items-center justify-center px-2 py-2 text-sm font-medium text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Download: {{ tdr.split('/').pop() }}
          </a>
        </div>
        <div v-if="showHistoricalFile === true && draftTdrs.length == 0">
            <span class="flex justify-center text-red-500">No Historical File Found</span>
        </div> -->

      </div>
    </div>

    <permission-blocked-modal :message="message" v-if="permissionBlockedModal" @close-permission-blocked-modal="toggleStatus"></permission-blocked-modal>

</template>
<style lang="postcss" scoped>
th {
    @apply px-3 py-2;
}

td {
    @apply px-3 py-2 text-xs;
}

table th,tr,td {
    font-size: 11px;
}

.dropdown-btn {
    @apply bg-gray-900 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-gray-50 text-white font-semibold py-2 px-3 rounded-lg w-full flex items-center justify-center gap-1.5 sm:w-auto dark:bg-blue-500 dark:hover:bg-blue-400;
}

table, th,td{
  @apply border border-collapse;
}
.search-result {
  @apply px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-300;
}
.search {
  @apply float-right  pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
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