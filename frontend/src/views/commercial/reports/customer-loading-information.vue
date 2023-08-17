<script setup>
import {onMounted, watchEffect, watch, ref} from 'vue';
import { useRoute } from 'vue-router';
import moment from 'moment';

import useCustomer from "../../../composables/commercial/useCustomer";
import Title from "../../../services/title";
import useVoyage from "../../../composables/operation/useVoyage";
import useCustomerLoadingInformationReport from "../../../composables/commercial/useCustomerLoadingInformationReport";
import useService from "../../../composables/commercial/useService";
import useSlotPartner from "../../../composables/dataencoding/useSlotPartner";
import useTableExportExcel from "../../../services/tableExportExcel";
import CustomerAllReport from "./customer-all-report.vue";
import CustomerSummarizedReport from "./customer-summarized-report.vue";
import CustomerDetailedReport from "./customer-detailed-report.vue";
import CustomerDetailedPercentageReport from "./customer-detailed-percentage-report.vue";

const route = useRoute();

const { tableToExcel } = useTableExportExcel();
const { customers, getCustomerWithoutPaginate } = useCustomer();
const { voyageName, getVoyageName } = useVoyage();
const { customerCodeName, getCustomerByNameOrCode } = useCustomer();
const { formParams, customerLoadingInformations, voyages, getCustomerLoadingInformation, isLoading } = useCustomerLoadingInformationReport();
const { slotPartnerCode, getSlotPartnerByNameOrCode } = useSlotPartner();
const { service, services, getServices, serviceGroupById, showService, uniqueServicePorts, getServiceUniquePorts } = useService();
const { setTitle } = Title();

setTitle('Customer Loading Information');

let totalAmount = ref(0);

const json_data = [
      {
        name: "Tony Peña",
        city: "New York",
        country: "United States",
        birthdate: "1978-03-15",
        atb: "1588-57"
      },
      {
        name: "Thessaloniki",
        city: "Athens",
        country: "Greece",
        birthdate: "1987-11-23",
        atb: "1588-56"
      },
    ];

function fetchVoyages(search, loading) {
  getVoyageName(search);
}

function fetchCustomer(search, loading){
  getCustomerByNameOrCode(search);
}

const form = ref({
  voyage_no: null,
  voyage_id: null,
  service_id: '',
  pol: '',
  pod: '',
  slot_partner_id: null,
  sector: '',
  report_style: '',
  filter_on: '',
  contract_type: '',
  slot_owner_code: '',
  customer_id: null,
  rate_validity_from: '',
  rate_validity_to: '',
  from_date: '',
  till_date: '',
});

function clearFormData(){
  form.value = {
    voyage_no: null,
    voyage_id: null,
    service_id: '',
    pol: '',
    pod: '',
    slot_partner_id: null,
    sector: '',
    report_style: '',
    filter_on: '',
    contract_type: '',
    slot_owner_code: '',
    customer_id: null,
    rate_validity_from: '',
    rate_validity_to: '',
    from_date: '',
    till_date: '',
  };
}

function fetchSlotPartner(search, loading) {
  getSlotPartnerByNameOrCode(search);
}

watch(() => form.value.service_id, (val) => {
  if (val) {
    //serviceGroupById(val);
    getServiceUniquePorts(val);
  }
});

watch(() => form.value.report_style, (val) => {
  if (val) {
    getCustomerLoadingInformation(form.value);
  }
});

// watchEffect(() => {
//   if (customerLoadingInformations.value) {
//     customerLoadingInformations.value.forEach((customerLoadingInformation) => {
//       customerLoadingInformation.services = JSON.parse(customerLoadingInformation.services);
//     });
//   }
// });

onMounted(() => {
  getServices();
  watchEffect(() => {

    if(formParams.value.voyage_no){
      formParams.value.voyage_id = formParams.value.voyage_no.id;
    }
    if(formParams.value.customer_codes_name){
      formParams.value.customer_id = formParams.value.customer_codes_name.id;
    }

    //getContracts(props.page, form.value);

  });
});


</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Loading Information By Customer</h2>
    </div>

  <form class="flex items-end justify-between gap-4" @submit.prevent="getCustomerLoadingInformation(form)">
  <div class="w-full flex items-center justify-between mb-2 select-none">
    <fieldset class="w-full grid grid-cols-7 gap-1 px-2 pb-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Search Contract</legend>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Service</label>
        <select v-model="form.service_id" class="search w-full" name="" id="">
          <option value="" selected disabled>Choose Service </option>
          <option :value="service?.id" v-for="(service,index) in services" :key="index">{{ service?.code }}</option>
        </select>
      </div>
      <div>
      <label for="" class="text-xs" style="margin-left: .01rem">Voyage</label>
      <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchVoyages" v-model="form.voyage_id" :reduce="voyageName => voyageName.id" label="voyage"></v-select>
      <input type="hidden" v-model="form.voyage_id">
    </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Customer </label>
        <v-select placeholder="--Choose an option--" :options="customerCodeName" @search="fetchCustomer" v-model="form.customer_id" :reduce="customerCodeName => customerCodeName.id" label="code_name"></v-select>
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Slot Partner</label>
        <v-select placeholder="--Choose an option--" :options="slotPartnerCode" @search="fetchSlotPartner" v-model="form.slot_partner_id" :reduce="slotPartnerCode => slotPartnerCode.id" label="code_name" class="block w-full text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
        <input type="hidden" v-model="form.slot_partner_id">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Pol</label>
        <select v-model="form.pol" id="valid_from_at" class="block w-full text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="">--Choose an Option--</option>
          <option :value="port" v-for="(port,index) in uniqueServicePorts" :key="index">{{ port }}</option>
        </select>
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Pod</label>
        <select v-model="form.pod" id="valid_from_at" class="block w-full text-xs rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
          <option value="">--Choose an Option--</option>
          <option :value="port" v-for="(port,index) in uniqueServicePorts" :key="index">{{ port }}</option>
        </select>
      </div>

      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Filter On</label>
        <select style="height: 34px" v-model="form.filter_on" class="search w-full" name="" id="">
          <option value="" selected disabled>Choose Option</option>
          <option value="arrival">Arrival Date</option>
          <option value="departure">Departure Date</option>
          <option value="berthing">Berthing Date</option>
        </select>
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">From Date</label>
        <input type="date" class="search w-full" v-model="form.from_date">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Till Date</label>
        <input type="date" class="search w-full" v-model="form.till_date">
      </div>
      <div>
        <label for="">&nbsp;</label>
        <button type="submit" class="w-full flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
          <span>Submit</span>
        </button>
      </div>
      <div>
        <label for="">&nbsp;</label>
        <button type="button" @click="clearFormData" :disabled="isLoading" class="w-full flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Clear</span>
        </button>
      </div>
      <div v-if="customerLoadingInformations.length">
        <label for="">&nbsp;</label>
        <button type="button" @click="tableToExcel('customer-loading-information-table','customer-loading-information')" class="w-full flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
          <span>Download Excel</span>
        </button>
      </div>
<!--      <div>-->
<!--      <label for="" class="text-xs" style="margin-left: .01rem">Report Style</label>-->
<!--      <select v-model="form.report_style" class="search w-full" name="" id="">-->
<!--        <option value="" selected disabled>Choose Sector</option>-->
<!--        <option value="Summarized">Summarized</option>-->
<!--        <option value="Detailed">Detailed</option>-->
<!--        <option value="Detailed with %">Detailed with %</option>-->
<!--      </select>-->
<!--    </div>-->

    </fieldset>
  </div>
  </form>

    <!-- Table -->
<!--    <div class="px-4 py-3 mb-2 bg-white rounded-lg shadow-md dark:bg-gray-800">-->
<!--        <form class="flex items-end justify-between gap-4" @submit.prevent="getCustomerLoadingInformation(formParams)">-->
<!--            <label class="block w-full text-sm">-->
<!--                <span class="text-gray-700 dark:text-gray-300">Voyage No</span>-->
<!--                 <v-select :options="voyageName" placeholder="&#45;&#45;Choose an option&#45;&#45;" @search="fetchVoyages" v-model="formParams.voyage_no" label="voyage" required></v-select>-->
<!--                 <input type="hidden" v-model="formParams.voyage_id">-->
<!--            </label>-->

<!--            <label class="block w-full text-sm">-->
<!--                <span class="text-gray-700 dark:text-gray-300">Customer Code</span>-->
<!--              <v-select placeholder="&#45;&#45;Choose an option&#45;&#45;" :options="customerCodeName" @search="fetchCustomer" v-model="formParams.customer_codes_name" label="code_name"></v-select>-->
<!--              <input type="hidden" v-model="formParams.customer_id">-->
<!--            </label>-->

<!--            &lt;!&ndash; Submit button &ndash;&gt;-->
<!--            <button type="submit" :disabled="isLoading"-->
<!--                class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>-->
<!--        </form>-->
<!--    </div>-->
    <div class="w-full relative mb-2 overflow-hidden border rounded-lg shadow-xs dark:border-0">
      <h1 class="py-2 font-bold text-center text-white bg-green-500">
        Loading Information ({{ form?.report_style ? form?.report_style : 'Detailed with %' }})
      </h1>
      <select style="top: 1px" v-model="form.report_style" class="search absolute top-0 h-auto right-0" name="" id="">
        <option value="" selected disabled>Report Style</option>
        <option value="Summarized">Summarized</option>
        <option value="Detailed">Detailed</option>
        <option value="Detailed with %">Detailed with %</option>
      </select>
    </div>
  <div class="mb-8 border overflow-x-auto rounded-lg shadow-xs dark:border-0 scroll-show">
    <div class="w-full">

      <template v-if="!form.report_style">
        <customer-all-report :customerLoadingInformations="customerLoadingInformations"></customer-all-report>
      </template>
      <template v-if="form.report_style === 'Summarized'">
        <customer-summarized-report :customerLoadingInformations="customerLoadingInformations"></customer-summarized-report>
      </template>
      <template v-if="form.report_style === 'Detailed'">
        <customer-detailed-report :customerLoadingInformations="customerLoadingInformations"></customer-detailed-report>
      </template>
      <template v-if="form.report_style === 'Detailed with %'">
        <customer-detailed-percentage-report :customerLoadingInformations="customerLoadingInformations"></customer-detailed-percentage-report>
      </template>
      
    </div>
  </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
  .search {
    @apply float-right m-0 text-sm border border-gray-600 rounded;
  }

  .scroll-show::-webkit-scrollbar {
    height: 8px !important;
    border-radius: 15px !important;
    background: lightgray !important;
  }

  .scroll-show::-webkit-scrollbar-thumb {
    background: gray !important;
    border-radius: 10px !important;
  }
    th {
        @apply p-10 border-r text-center;
    }

  thead td{
    text-transform: capitalize;
  }

    tbody td,
    thead td,
    thead th {
        @apply px-1 py-1 text-xs border-r text-center;
        /*font-size: 10px !important;*/
    }
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