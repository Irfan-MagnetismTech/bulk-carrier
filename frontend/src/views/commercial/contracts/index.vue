<script setup>
import {onMounted, computed, watch, watchEffect, ref} from "vue";
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useContract from '../../../composables/commercial/useContract';
import useOpenSlotContract from "../../../composables/commercial/useOpenSlotContract";
import Title from "../../../services/title";
import useDebouncedRef from "../../../composables/useDebouncedRef";
import Paginate from '../../../components/utils/paginate.vue';
import moment from 'moment';
import useService from "../../../composables/commercial/useService";
import useSlotPartner from "../../../composables/dataencoding/useSlotPartner";
import useCustomer from "../../../composables/commercial/useCustomer";
import IconAnchor from '../../../components/icons/IconAnchor.vue';
import {onClickOutside} from "@vueuse/core/index";
import useApprovalManagement from "../../../composables/authorization/useApprovalManagement";
import useForceLoadContract from "../../../composables/commercial/useForceLoadContract";
import PermissionBlockedModal from "../../../components/utils/PermissionBlockedModal.vue";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { contracts, copyContract, getContracts, updateContractStatus, deleteContract, isLoading } = useContract();
const { deleteOpenSlotContract, copyOsContract } = useOpenSlotContract();
const { deleteForceLoadContract, copyForceContract } = useForceLoadContract();
const { services, getServices } = useService();
const { partners, getSlotPartnerWithoutPaginate } = useSlotPartner();
const { customers, getCustomerWithoutPaginate } = useCustomer();
const { approved } = useApprovalManagement();

let permissionBlockedModal = ref(false);
let message = ref('');

function toggleStatus(msg) {
  message.value = msg;
  permissionBlockedModal.value = !permissionBlockedModal.value;
}

function copyContractData(contractId){
  copyContract(contractId);
}

function copyOsContractData(contractId){
  copyOsContract(contractId);
}

function copyForceContractData(contractId){
  copyForceContract(contractId);
}

const { setTitle } = Title();

setTitle('Contracts');

const form = ref({
  service_id: '',
  contract_type: '',
  slot_owner_code: '',
  customer_id: '',
  rate_validity_from: '',
  rate_validity_to: '',
});

function clearFormData(){
  form.value = {
    service_id: '',
    contract_type: '',
    slot_owner_code: null,
    customer_id: null,
    valid_from: '',
    valid_to: '',
  };
}

// Code for global search starts here
const columns = ["contract_no", "contract_type", "rate_type"];
const searchKey = useDebouncedRef('', 800);
const table = "contracts";

// watch(searchKey, newQuery => {
//   getContracts(props.page, columns, searchKey.value, table, form.value);
// });
// Code for global search end here

const menu = ref(false);
const showingMenuIndex = ref(-1);

onClickOutside(menu, (event) => showingMenuIndex.value = -1);

function toggleMenu(index) {
  if (showingMenuIndex.value === index) {
    showingMenuIndex.value = -1;
  } else {
    showingMenuIndex.value = index;
  }
}

function approveContract(is_approved,approver_composite_key,approvable_id,approvable_type){
  let data = {
    "is_approved": is_approved,
    "approver_composite_key": approver_composite_key,
    "approvable_id": approvable_id,
    "approvable_type": approvable_type,
  }
  approved(data);
}

function updateStatus(contractId){
  //get current full url path
  let currentUrl = window.location.href;
  updateContractStatus(contractId,currentUrl);
}


onMounted(() => {
  getServices();
  getSlotPartnerWithoutPaginate();
  getCustomerWithoutPaginate();
  watchEffect(() => {
    getContracts(props.page, form.value);
  });
});
</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row">
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Contracts</h2>
  </div>
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
          <label for="" class="text-xs" style="margin-left: .01rem">Contract Type</label>
          <select v-model="form.contract_type" class="search w-full" name="" id="">
            <option value="" selected disabled>Contract Type </option>
            <option value="master-contract">Master Contract</option>
            <option value="sub-contract">Sub Contract</option>
          </select>
        </div>

        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Slot Partner</label>
          <v-select :options="partners" class="w-full" placeholder="Slot Partner" v-model="form.slot_owner_code" :reduce="partners => partners.code" label="code"></v-select>
        </div>

        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Customer Code</label>
          <v-select :options="customers" class="w-full" placeholder="Customer Code" v-model="form.customer_id" :reduce="customers => customers.id" label="customer_code"></v-select>
        </div>

        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Valid From</label>
          <input type="date" class="search w-full" v-model="form.valid_from">
        </div>

        <div>
          <label for="" class="text-xs" style="margin-left: .01rem">Valid Till</label>
          <input type="date" class="search w-full" v-model="form.valid_till">
        </div>

        <div>
          <label for="">&nbsp;</label>
          <button @click="clearFormData" class="w-full flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Clear</span>
          </button>
        </div>

      </fieldset>
    </div>

  <!-- Table -->
  <div class="w-full overflow-hidden">
    <div class="w-full overflow-x-auto scroll-show">
      <table class="w-full mb-5">
        <thead>
        <tr style="text-transform: capitalize" class="text-xs capitalize tracking-wide text-center text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">SL. </th>
          <th class="px-4 py-3">Contract No </th>
          <th class="px-4 py-3" data-title="Customer Name">C. Name </th>
          <th class="px-4 py-3" data-title="Customer ID">CID </th>
          <th class="px-4 py-3" data-title="Slot Owner">S/O </th>
          <th class="px-4 py-3">Service</th>
          <th class="px-4 py-3">Nature</th>
          <th class="px-4 py-3">Agreement</th>
          <th class="px-4 py-3">Validity</th>
          <th class="px-4 py-3" style="min-width: 250px">Short Note</th>
          <th class="">Status</th>
          <th class="px-4 py-3 text-center">Action</th>
        </tr>
        </thead>
        <tbody v-if="contracts?.data?.length" class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <template v-for="(contract,masterIndex) in contracts?.data" :key="contract.id">
          <tr :title="contract.is_archived ? 'This contract is in archived' : ''" class="text-gray-700 dark:text-gray-400 text-center">
            <td :class="contract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">{{ contracts.from + masterIndex }}</td>
            <td :class="contract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px"><nobr>{{ contract?.contract_no }}</nobr></td>
            <td :class="contract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">{{ contract.customer?.customer_name }}</td>
            <td :class="contract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">{{ contract.customer?.customer_code }}</td>
            <td :class="contract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">{{ contract?.customer?.slot_partner?.code }}</td>
            <td :class="contract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">{{ contract?.service?.code }}</td>
            <td :class="contract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">
              <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-200 rounded-full dark:bg-red-700 dark:text-red-100 font-bold"
                  v-if="contract?.contract_type === 'master-contract'">MC</span>
              <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-200 rounded-full dark:bg-green-700 dark:text-green-100 font-bold"
                  v-else>SC</span>
            </td>
            <td :class="contract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">
              <span v-if="contract.rate_type === 'Open'">OP</span>
              <span v-else-if="contract.rate_type === 'Force'">FL</span>
              <span v-else>DF</span>
            </td>
            <td :class="contract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-xs" style="text-align: left;font-size: 11px"><nobr>From: {{ moment(contract?.valid_from).format('DD-MM-YYYY') }}</nobr>  <br>
              To: {{ moment(contract?.valid_till).format('DD-MM-YYYY') }}
            </td>
            <td :class="contract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="text-align: left;font-size: 11px">{{ contract?.short_note }}</td>
            <td :class="contract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">

              <span v-if="contract?.approved_status == 'Pending'" class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-200 rounded-full dark:bg-red-700 dark:text-red-200 font-bold">{{ contract?.approved_status }}</span>
              <span v-if="contract?.approved_status == 'Approved'" class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-200 rounded-full dark:bg-green-700 dark:text-green-200 font-bold">{{ contract?.approved_status }}</span>
              <span v-if="contract?.current_approver" style="font-size: 10px" class="text-xs mr-2 mb-2 mt-1 inline-block py-1 px-2.5 leading-none whitespace-nowrap align-baseline font-bold bg-gray-200 text-gray-700 rounded">Waiting For: {{ contract?.current_approver }}</span>
            </td>
            <td class="items-center justify-center space-x-2 text-gray-600">
<!--              <button v-if="contract?.get_approve_button" @click="approveContract(1,contract?.approver_composite_key,contract?.id,contract?.subject_type)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">-->
<!--                Approve-->
<!--              </button>-->
              <div class="relative inline-block text-left select-none" ref="menu">
                <div>
                  <svg :class="contract.is_archived ? 'archived_row_dot' : ''" @click="toggleMenu(contract?.id)" style="cursor: pointer;height: 1.3rem" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                  </svg>
                </div>
                <div v-if="showingMenuIndex === contract?.id" style="max-width: 150px" class="absolute z-50 right-0 w-56 mt-2 origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                  <div class="" role="none">
                    <template v-if="contract?.rate_type == 'Fixed'">
                          <span v-if="contract?.get_approve_button" @click="approveContract(1,contract?.approver_composite_key,contract?.id,contract?.subject_type)" class="flex items-center px-4 cursor-pointer py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Approve">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                            </svg>
                            <strong class="ml-1">Approve</strong>
                         </span>

                          <span class="flex items-center px-4 cursor-pointer py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Copy Contract" @click="copyContractData(contract.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <strong class="ml-1">Copy</strong>
                          </span>

<!--                      <router-link :to="{ name: 'commercial.contracts.edit', params: { contractId: contract.id } }" :title="contract.invoices_count ? 'Invoice Generated' : ''" :class="contract.invoices_count ? 'custom_disabled' : ''" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">-->
<!--                        <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">-->
<!--                          <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />-->
<!--                          <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />-->
<!--                        </svg>-->
<!--                        <strong class="ml-1">Edit</strong>-->
<!--                      </router-link>-->

                      <template v-if="contract.invoices_count == 0">
                        <router-link :to="{ name: 'commercial.contracts.edit', params: { contractId: contract.id } }" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                          </svg>
                          <strong class="ml-1">Edit</strong>
                        </router-link>
                      </template>
                      <template v-if="contract.invoices_count != 0">
                        <a @click="toggleStatus('Invoice already generated for this contract.')" :class="contract.invoices_count ? 'custom_text_opacity' : ''" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                          </svg>
                          <strong class="ml-1">Edit</strong>
                        </a>
                      </template>


                      <router-link :to="{ name: 'commercial.slot-charter-contracts.show', params: { contractId: contract.id } }" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icn h-6 w-6 text-green-400 dark:text-gray-400 dark:hover:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <strong class="ml-1">Show</strong>
                      </router-link>
<!--                      <span class="flex items-center px-4 py-1 cursor-pointer text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Copy Contract" @click="deleteContract(contract.id)">-->
<!--                                    <svg xmlns="http://www.w3.org/2000/svg" class="icn h-6 w-6 text-red-500 dark:text-gray-400 dark:hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
<!--                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />-->
<!--                                    </svg>-->
<!--                                    <strong class="ml-1">Delete</strong>-->
<!--                            </span>-->
                    </template>
                    <template v-else-if="contract?.rate_type == 'Open'">
                      <span v-if="contract?.get_approve_button" @click="approveContract(1,contract?.approver_composite_key,contract?.id,contract?.subject_type)" class="flex items-center px-4 cursor-pointer py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Approve">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                            </svg>
                            <strong class="ml-1">Approve</strong>
                      </span>

                    <span class="flex items-center px-4 cursor-pointer py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Copy Contract" @click="copyOsContractData(contract.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <strong class="ml-1">Copy</strong>
                          </span>
<!--                      <router-link :to="{ name: 'commercial.open-slot-contract.edit', params: { contractId: contract.id } }" :title="contract.invoices_count ? 'Invoice Generated' : ''" :class="contract.invoices_count ? 'custom_disabled' : ''" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">-->
<!--                        <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">-->
<!--                          <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />-->
<!--                          <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />-->
<!--                        </svg>-->
<!--                        <strong class="ml-1">Edit</strong>-->
<!--                      </router-link>-->

                      <template v-if="contract.invoices_count == 0">
                        <router-link :to="{ name: 'commercial.open-slot-contract.edit', params: { contractId: contract.id } }" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                          </svg>
                          <strong class="ml-1">Edit</strong>
                        </router-link>
                      </template>
                      <template v-if="contract.invoices_count != 0">
                        <a @click="toggleStatus('Invoice already generated for this contract.')" :class="contract.invoices_count ? 'custom_text_opacity' : ''" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                          </svg>
                          <strong class="ml-1">Edit</strong>
                        </a>
                      </template>

                      <router-link :to="{ name: 'commercial.open-slot-contract.show', params: { contractId: contract.id } }" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icn h-6 w-6 text-green-400 dark:text-gray-400 dark:hover:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <strong class="ml-1">Show</strong>
                      </router-link>
<!--                      <span class="flex items-center px-4 py-1 cursor-pointer text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Copy Contract" @click="deleteOpenSlotContract(contract.id)">-->
<!--                                          <svg xmlns="http://www.w3.org/2000/svg" class="icn h-6 w-6 text-red-500 dark:text-gray-400 dark:hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
<!--                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />-->
<!--                                          </svg>-->
<!--                                          <strong class="ml-1">Delete</strong>-->
<!--                          </span>-->
                    </template>
                    <template v-else>
                      <span v-if="contract?.get_approve_button" @click="approveContract(1,contract?.approver_composite_key,contract?.id,contract?.subject_type)" class="flex items-center px-4 cursor-pointer py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Approve">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                            </svg>
                            <strong class="ml-1">Approve</strong>
                      </span>

                      <span class="flex items-center px-4 cursor-pointer py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Copy Contract" @click="copyForceContractData(contract.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            <strong class="ml-1">Copy</strong>
                          </span>
<!--                      <router-link :to="{ name: 'commercial.force-load-contract.edit', params: { contractId: contract.id } }" :title="contract.invoices_count ? 'Invoice Generated' : ''" :style="{ cursor: contract.invoices_count ? 'not-allowed !important' : 'default' }" :class="contract.invoices_count ? 'custom_disabled' : ''" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">-->
<!--                        <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">-->
<!--                          <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />-->
<!--                          <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />-->
<!--                        </svg>-->
<!--                        <strong class="ml-1">Edit</strong>-->
<!--                      </router-link>-->

                      <template v-if="contract.invoices_count == 0">
                        <router-link :to="{ name: 'commercial.force-load-contract.edit', params: { contractId: contract.id } }" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                          </svg>
                          <strong class="ml-1">Edit</strong>
                        </router-link>
                      </template>
                      <template v-if="contract.invoices_count != 0">
                        <a @click="toggleStatus('Invoice already generated for this contract.')" :class="contract.invoices_count ? 'custom_text_opacity' : ''" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                          </svg>
                          <strong class="ml-1">Edit</strong>
                        </a>
                      </template>

                      <router-link :to="{ name: 'commercial.force-load-contract.show', params: { contractId: contract.id } }" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icn h-6 w-6 text-green-400 dark:text-gray-400 dark:hover:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <strong class="ml-1">Show</strong>
                      </router-link>
<!--                      <span class="flex items-center px-4 py-1 cursor-pointer text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Copy Contract" @click="deleteForceLoadContract(contract.id)">-->
<!--                                          <svg xmlns="http://www.w3.org/2000/svg" class="icn h-6 w-6 text-red-500 dark:text-gray-400 dark:hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
<!--                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />-->
<!--                                          </svg>-->
<!--                                          <strong class="ml-1">Delete</strong>-->
<!--                          </span>-->
                    </template>

                    <div @click="updateStatus(contract?.id)" v-if="!contract?.is_archived" style="cursor: pointer" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icn w-5 h-5 text-red-600 dark:text-red-100 dark:hover:text-red-200">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                      </svg>
                      <strong class="ml-1">Move to Archive</strong>
                    </div>
                    <div @click="updateStatus(contract?.id)" v-if="contract?.is_archived" style="cursor: pointer" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icn w-5 h-5 text-red-600 dark:text-red-100 dark:hover:text-red-200">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                      </svg>
                      <strong class="ml-1">Restore</strong>
                    </div>

                    <router-link :to="{ name: 'user.activity.log', params: { subject_type: contract.subject_type,subject_id: contract.id } }" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                      <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.3rem; width: 1.3rem" class="icn h-6 w-6 text-green-400 dark:text-gray-400 dark:hover:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <strong class="ml-1">Activity Log</strong>
                    </router-link>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          <template v-for="(subContract,subContractIndex) in contract?.sub_contracts" :key="subContract.id">
            <tr :title="subContract.is_archived ? 'This contract is in archived' : ''" class="text-gray-700 dark:text-gray-400 text-center">
              <td :class="subContract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">{{ contracts.from + masterIndex }}.{{ subContractIndex + 1 }}</td>
              <td :class="subContract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px"><nobr>{{ subContract?.contract_no }}</nobr></td>
              <td :class="subContract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">{{ subContract?.customer?.customer_name }}</td>
              <td :class="subContract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">{{ subContract?.customer?.customer_code }}</td>
              <td :class="subContract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">{{ subContract?.customer?.slot_partner?.code }}</td>
              <td :class="subContract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">{{ subContract?.service?.code }}</td>
              <td :class="subContract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">
                <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-200 rounded-full dark:bg-red-700 dark:text-red-100 font-bold"
                      v-if="subContract?.contract_type === 'master-contract'">MC</span>
                <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-200 rounded-full dark:bg-green-700 dark:text-green-100 font-bold"
                    v-else>SC</span>
              </td>
              <td :class="subContract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">
                <span v-if="subContract.rate_type === 'Open'">OP</span>
                <span v-else-if="subContract.rate_type === 'Force'">FL</span>
                <span v-else>DF</span>
              </td>
              <td :class="subContract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-xs" style="text-align: left;font-size: 11px"><nobr>From: {{ moment(subContract?.valid_from).format('DD-MM-YYYY') }}</nobr>  <br>
                To: {{ moment(subContract?.valid_till).format('DD-MM-YYYY') }}
              </td>
              <td :class="subContract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="text-align: left;font-size: 11px">{{ subContract?.short_note }}</td>
              <td :class="subContract.is_archived ? 'archived_row' : ''" class="px-4 py-3 text-sm" style="font-size: 11px">
                <span v-if="subContract?.approved_status == 'Pending'" class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-200 rounded-full dark:bg-red-700 dark:text-red-200 font-bold">{{ subContract?.approved_status }}</span>
                <span v-if="subContract?.approved_status == 'Approved'" class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-200 rounded-full dark:bg-green-700 dark:text-green-200 font-bold">{{ subContract?.approved_status }}</span>
                <br>
                <span v-if="subContract?.current_approver" style="font-size: 10px" class="text-xs mr-2 mb-2 mt-1 inline-block py-1 px-2.5 leading-none whitespace-nowrap align-baseline font-bold bg-gray-200 text-gray-700 rounded">Waiting For: {{ subContract?.current_approver }}</span>
              </td>
              <td class="items-center justify-center space-x-2 text-gray-600">
                <div class="relative inline-block text-left select-none" ref="menu">
                  <div>
                    <svg  @click="toggleMenu(subContract?.id)" style="cursor: pointer;height: 1.3rem;" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                  </div>
                  <div v-if="showingMenuIndex === subContract?.id" style="max-width: 150px" class="absolute z-50 right-0 w-56 mt-2 origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                    <div class="" role="none">
                      <template v-if="subContract?.rate_type == 'Fixed'">
                      <span v-if="subContract?.get_approve_button" @click="approveContract(1,subContract?.approver_composite_key,subContract?.id,subContract?.subject_type)" class="flex items-center px-4 cursor-pointer py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Approve">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                            </svg>
                            <strong class="ml-1">Approve</strong>
                      </span>
                        <!-- <span class="flex items-center px-4 cursor-pointer py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Copy Contract" @click="copyContractData(subContract.id)">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                          </svg>
                          <strong class="ml-1">Copy</strong>
                        </span> -->
                        <!--                      <router-link :to="{ name: 'commercial.contracts.edit', params: { contractId: subContract.id } }" :title="subContract.invoices_count ? 'Invoice Generated' : ''" :style="{ cursor: subContract.invoices_count ? 'not-allowed !important' : 'default' }" :class="subContract.invoices_count ? 'custom_disabled' : ''" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">-->
                        <!--                        <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">-->
                        <!--                          <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />-->
                        <!--                          <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />-->
                        <!--                        </svg>-->
                        <!--                        <strong class="ml-1">Edit</strong>-->
                        <!--                      </router-link>-->

                        <template v-if="subContract.invoices_count == 0">
                          <router-link :to="{ name: 'commercial.contracts.edit', params: { contractId: subContract.id } }" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                              <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                              <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                            </svg>
                            <strong class="ml-1">Edit</strong>
                          </router-link>
                        </template>
                        <template v-if="subContract.invoices_count != 0">
                          <a @click="toggleStatus('Invoice already generated for this contract.')" :class="subContract.invoices_count ? 'custom_text_opacity' : ''" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                              <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                              <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                            </svg>
                            <strong class="ml-1">Edit</strong>
                          </a>
                        </template>

                        <router-link :to="{ name: 'commercial.slot-charter-contracts.show', params: { contractId: subContract.id } }" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icn h-6 w-6 text-green-400 dark:text-gray-400 dark:hover:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                          </svg>
                          <strong class="ml-1">Show</strong>
                        </router-link>
                        <!--                      <span class="flex items-center px-4 py-1 cursor-pointer text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Copy Contract" @click="deleteContract(subContract.id)">-->
                        <!--                                    <svg xmlns="http://www.w3.org/2000/svg" class="icn h-6 w-6 text-red-500 dark:text-gray-400 dark:hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
                        <!--                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />-->
                        <!--                                    </svg>-->
                        <!--                                    <strong class="ml-1">Delete</strong>-->
                        <!--                            </span>-->
                      </template>
                      <template v-else-if="subContract?.rate_type == 'Open'">
                      <span v-if="subContract?.get_approve_button" @click="approveContract(1,subContract?.approver_composite_key,subContract?.id,subContract?.subject_type)" class="flex items-center px-4 cursor-pointer py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Approve">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                            </svg>
                            <strong class="ml-1">Approve</strong>
                      </span>

                        <template v-if="subContract.invoices_count == 0">
                          <router-link :to="{ name: 'commercial.open-slot-contract.edit', params: { contractId: subContract.id } }" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                              <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                              <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                            </svg>
                            <strong class="ml-1">Edit</strong>
                          </router-link>
                        </template>
                        <template v-if="subContract.invoices_count != 0">
                          <a @click="toggleStatus('Invoice already generated for this contract.')" :class="subContract.invoices_count ? 'custom_text_opacity' : ''" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                              <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                              <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                            </svg>
                            <strong class="ml-1">Edit</strong>
                          </a>
                        </template>

                        <router-link :to="{ name: 'commercial.open-slot-contract.show', params: { contractId: subContract.id } }" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icn h-6 w-6 text-green-400 dark:text-gray-400 dark:hover:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                          </svg>
                          <strong class="ml-1">Show</strong>
                        </router-link>
                        <!--                      <span class="flex items-center px-4 py-1 cursor-pointer text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Copy Contract" @click="deleteOpenSlotContract(subContract.id)">-->
                        <!--                                          <svg xmlns="http://www.w3.org/2000/svg" class="icn h-6 w-6 text-red-500 dark:text-gray-400 dark:hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
                        <!--                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />-->
                        <!--                                          </svg>-->
                        <!--                                          <strong class="ml-1">Delete</strong>-->
                        <!--                          </span>-->
                      </template>
                      <template v-else>
                      <span v-if="subContract?.get_approve_button" @click="approveContract(1,subContract?.approver_composite_key,subContract?.id,subContract?.subject_type)" class="flex items-center px-4 cursor-pointer py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Approve">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                   <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                            </svg>
                            <strong class="ml-1">Approve</strong>
                      </span>
                        <!-- <span class="flex items-center px-4 cursor-pointer py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Copy Contract" @click="copyForceContractData(subContract.id)">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                              </svg>
                              <strong class="ml-1">Copy</strong>
                            </span> -->
                        <!--                      <router-link :to="{ name: 'commercial.force-load-contract.edit', params: { contractId: subContract.id } }" :title="subContract.invoices_count ? 'Invoice Generated' : ''" :style="{ cursor: subContract.invoices_count ? 'not-allowed !important' : 'default' }" :class="subContract.invoices_count ? 'custom_disabled' : ''" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">-->
                        <!--                        <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">-->
                        <!--                          <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />-->
                        <!--                          <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />-->
                        <!--                        </svg>-->
                        <!--                        <strong class="ml-1">Edit</strong>-->
                        <!--                      </router-link>-->

                        <template v-if="subContract.invoices_count == 0">
                          <router-link :to="{ name: 'commercial.force-load-contract.edit', params: { contractId: subContract.id } }" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                              <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                              <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                            </svg>
                            <strong class="ml-1">Edit</strong>
                          </router-link>
                        </template>
                        <template v-if="subContract.invoices_count != 0">
                          <a @click="toggleStatus('Invoice already generated for this contract.')" :class="subContract.invoices_count ? 'custom_text_opacity' : ''" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                              <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                              <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                            </svg>
                            <strong class="ml-1">Edit</strong>
                          </a>
                        </template>

                        <router-link :to="{ name: 'commercial.force-load-contract.show', params: { contractId: subContract.id } }" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icn h-6 w-6 text-green-400 dark:text-gray-400 dark:hover:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                          </svg>
                          <strong class="ml-1">Show</strong>
                        </router-link>
                        <!--                      <span class="flex items-center px-4 py-1 cursor-pointer text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200" title="Copy Contract" @click="deleteForceLoadContract(subContract.id)">-->
                        <!--                                          <svg xmlns="http://www.w3.org/2000/svg" class="icn h-6 w-6 text-red-500 dark:text-gray-400 dark:hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">-->
                        <!--                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />-->
                        <!--                                          </svg>-->
                        <!--                                          <strong class="ml-1">Delete</strong>-->
                        <!--                          </span>-->
                      </template>

                      <div @click="updateStatus(subContract?.id)" v-if="!subContract?.is_archived" style="cursor: pointer" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icn w-5 h-5 text-red-600 dark:text-red-100 dark:hover:text-red-200">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        <strong class="ml-1">Move to Archive</strong>
                      </div>
                      <div @click="updateStatus(subContract?.id)" v-if="subContract?.is_archived" style="cursor: pointer" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icn w-5 h-5 text-red-600 dark:text-red-100 dark:hover:text-red-200">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        <strong class="ml-1">Restore</strong>
                      </div>

                      <router-link :to="{ name: 'user.activity.log', params: { subject_type: subContract.subject_type,subject_id: subContract.id } }" class="flex items-center px-4 py-1 text-gray-700 group hover:bg-green-300 transition-all ease-in-out duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.3rem; width: 1.3rem" class="icn h-6 w-6 text-green-400 dark:text-gray-400 dark:hover:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <strong class="ml-1">Activity Log</strong>
                      </router-link>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          </template>
        </template>

        </tbody>
        <tfoot v-if="!contracts?.data?.length" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="11">Loading...</td>
        </tr>
        <tr v-else-if="!contracts?.data?.length">
          <td colspan="11">No contract found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <!-- Pagination -->
    <Paginate :data="contracts" to="commercial.contracts.index" :page="page"></Paginate>
  </div>

  <permission-blocked-modal :message="message" v-if="permissionBlockedModal" @close-permission-blocked-modal="toggleStatus"></permission-blocked-modal>

</template>

<style lang="postcss" scoped>
@tailwind components;

@layer components {
  .tab {
    @apply p-2.5 text-xs;
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

  thead tr {
    @apply tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  th {
    @apply tab text-center;
  }
  tbody {
    @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
  }
  tbody tr {
    @apply text-gray-700 dark:text-gray-400;
  }
  tbody tr td {
    @apply tab text-center;
  }
  tfoot td {
    @apply tab text-center;
  }
  table, th,td{
    @apply border border-collapse;
  }
  .search {
    @apply float-right pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
  }

  >>> {
    --vs-controls-color: #374151;

    --vs-dropdown-bg: #282c34;
    --vs-dropdown-color: #eeeeee;
    --vs-dropdown-option-color: #eeeeee;

    --vs-selected-bg: #664cc3;
    --vs-selected-color: #374151;

    --vs-search-input-color: #4b5563;

    --vs-dropdown-option--active-bg: #664cc3;
    --vs-dropdown-option--active-color: #eeeeee;
  }

}
</style>