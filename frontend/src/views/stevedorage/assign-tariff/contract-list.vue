<script setup>
import {onMounted, ref, watch} from 'vue';
import useVoyage from '../../../composables/operation/useVoyage';
import useContractAssign from "../../../composables/commercial/useContractAssign";
import {useRoute} from "vue-router";
import moment from "moment";
import {onClickOutside} from "@vueuse/core/index";
import Title from "../../../services/title";

const { voyage,showVoyage, isLoading } = useVoyage();
const { contractAssigns, customerContracts, getContractAssigns, assignCustomerContract, loadCustomerContracts } = useContractAssign();

const route = useRoute();
const voyageId = route.params.voyageId;

const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  openTab.value = tabNumber;
}

const { setTitle } = Title();

setTitle('Assign Contract List');

const isContractAssignModalOpen = ref(0);
const voyageCustomerList = ref([]);
let contractAssignData = ref();

const menu = ref(false);
const showingMenuIndex = ref(-1);

let fixedContractCustomerList = ref({});
let openContractCustomerList = ref({});
let forceContractCustomerList = ref({});

watch(() => contractAssigns, (voyageCustomer) => {
  Object.keys(voyageCustomer.value).forEach(function(contractType) {
    if(contractType == "Open"){
      Object.keys(voyageCustomer.value[contractType]).forEach(function (contract){
        openContractCustomerList.value = voyageCustomer.value[contractType];
      });
    }else if(contractType == "Fixed"){
      Object.keys(voyageCustomer.value[contractType]).forEach(function (contract){
        fixedContractCustomerList.value = voyageCustomer.value[contractType];
      });
    } else{
      Object.keys(voyageCustomer.value[contractType]).forEach(function (contract){
        forceContractCustomerList.value = voyageCustomer.value[contractType];
      });
    }
  });
}, {deep: true});

function moveToFixedContract(key){

  if (!confirm('Are you sure to move this?')) {
    return;
  }
  let obj ={};
  obj[key] = openContractCustomerList.value[key];
  fixedContractCustomerList.value[key] = openContractCustomerList.value[key];
  delete openContractCustomerList.value[key];
}

function moveToOpenContract(key){

  if (!confirm('Are you sure to move this?')) {
    return;
  }
  let obj ={};
  obj[key] = fixedContractCustomerList.value[key];
  openContractCustomerList.value[key] = fixedContractCustomerList.value[key];
  delete fixedContractCustomerList.value[key];
}


function loadContractAssignData(customerId,rateType,sector,voyageCustomerId) {
  isContractAssignModalOpen.value = 1;
  let data = {
    customer_id: customerId,
    rate_type: rateType,
    sector: sector,
    voyage_customer_id: voyageCustomerId
  };
  loadCustomerContracts(data);
}

onClickOutside(menu, (event) => showingMenuIndex.value = -1);
function toggleMenu(index) {
  if (showingMenuIndex.value === index) {
    showingMenuIndex.value = -1;
  } else {
    showingMenuIndex.value = index;
  }
}

function openContractAssignModal() {
  isContractAssignModalOpen.value = 1;
}

function closeContractAssignModal() {
  isContractAssignModalOpen.value = 0;
}

function assignContract(voyageCustomerId,voyageId,customerId,contractId,sector,rateType) {
  let data = {
    voyage_customer_id: voyageCustomerId,
    voyage_id: voyageId,
    customer_id: customerId,
    contract_id: contractId,
    sector: sector,
    rate_type: rateType
  };
  assignCustomerContract(data);
  isContractAssignModalOpen.value = 0;

}

onMounted(() => {
  showVoyage(voyageId);
  getContractAssigns(voyageId);
});
</script>
<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Assigned Contract List</h2>
  </div>
  <div class="mb-5">
    <div class="w-full overflow-hidden shadow-xs">
      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap table1">
          <thead v-once>
          <tr class="text-xs text-gray-500 uppercase bg-green-400 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">Voyage</th>
            <th class="px-4 py-3">Vessel</th>
            <th class="px-4 py-3">ATA</th>
            <th class="px-4 py-3">ATD</th>
            <th class="px-4 py-3">ATB</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-center text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3 text-sm">{{ voyage?.voyage ?? '---' }}</td>
            <td class="px-4 py-3 text-sm">{{ voyage?.vessel?.name ?? '---' }}</td>
            <td class="px-4 py-3 text-sm">{{ moment(voyage?.arrival_date).format('DD-MMMM-YYYY') }}</td>
            <td class="px-4 py-3 text-sm">{{ moment(voyage?.departure_date).format('DD-MMMM-YYYY') }}</td>
            <td class="px-4 py-3 text-sm">{{ moment(voyage?.berthing_date).format('DD-MMMM-YYYY') }}</td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <ul class="flex flex-wrap mb-px">
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(1)" v-bind:class="{'text-purple-600 bg-white': openTab !== 1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 1}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Assign OS Contract
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(2)" v-bind:class="{'text-purple-600 bg-white': openTab !== 2, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 2}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Assign DF Contract
        </a>
      </li>
      <li class="mr-2">
        <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(3)" v-bind:class="{'text-purple-600 bg-white': openTab !== 3, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 3}">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Assign FL Contract
        </a>
      </li>
    </ul>
    <div v-on:click="toggleTabs(1)" v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">
        <div class="w-full mb-6 overflow-hidden rounded-lg shadow-xs" v-memo>
          <div class="w-full md:overflow-x-auto">
            <table class="w-full mt-2 whitespace-no-wrap border dark:border-0 table2">
              <thead>
              <tr>
                <th>Action</th>
                <th style="width: 200px">C. Code</th>
                <th style="width: 200px">S/O</th>
                <th>Sector</th>
                <th>CNT</th>
                <th>Tues</th>
                <th>Load Status</th>
                <th>Assigned Contract</th>
                <th>Contract List </th>
              </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <template v-for="(voyageCustomer, voyageCustomerKey, voyageCustomerIndex) in openContractCustomerList" :key="voyageCustomerIndex">
                <tr class="text-gray-700 dark:text-gray-400" v-for="(sector,sectorKey, index) in voyageCustomer" :key="sectorKey">
                  <td :rowspan="Object.keys(voyageCustomer).length" v-if="sectorKey == 0">
                    <button type="button" :disabled="isLoading" @click="moveToFixedContract(voyageCustomerKey)" class="w-full px-2 py-2 text-sm text-white bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"><nobr>Move to DF</nobr></button>
                  </td>
                  <td :rowspan="Object.keys(voyageCustomer).length" v-if="sectorKey == 0">
                    <strong>{{ sector?.customer_code }}</strong> <br>
                    {{ sector?.company_name }}
                  </td>
                  <td :rowspan="Object.keys(voyageCustomer).length" v-if="sectorKey == 0">
                    {{ sector?.slot_partner }} <br>
                    {{ sector?.slot_partner_name }}
                  </td>
                  <td>{{ sector?.sector }}</td>
                  <td>{{ sector?.total_container }}</td>
                  <td>{{ sector?.total_tues }}</td>
                  <td>
                    {{ sector?.load_status }}
                  </td>
                  <td>
                    <nobr>
                      <router-link v-if="sector?.contract_id" :to="{ name: 'commercial.slot-charter-contracts.show', params: { contractId: sector?.contract_id } }" class="flex items-center text-purple-800">
                        <strong class="ml-1">{{ sector?.contract_no }}</strong>
                      </router-link>
                    </nobr>
                  </td>
                  <td><button type="button" title="Contract List" @click="loadContractAssignData(sector?.customer_id,'Open',sector?.sector,sector?.voyage_customer_id)" :disabled="isLoading" class="w-full px-2 py-2 text-sm text-white bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"><nobr>Contract List</nobr></button></td>
                </tr>
              </template>
              </tbody>
              <tfoot v-if="!Object.keys(openContractCustomerList)?.length" class="bg-white dark:bg-gray-800">
              <tr v-if="isLoading">
                <td colspan="8" style="text-align: center">Loading...</td>
              </tr>
              <tr v-else-if="!Object.keys(openContractCustomerList)?.length">
                <td colspan="8" style="text-align: center">No data found.</td>
              </tr>
              </tfoot>
            </table>
          </div>
        </div>
    </div>
    <div v-on:click="toggleTabs(2)" v-bind:class="{'hidden': openTab !== 2, 'block': openTab === 2}">
        <div class="w-full mb-6 overflow-hidden rounded-lg shadow-xs" v-memo>
          <div class="w-full md:overflow-x-auto">
            <table class="w-full mt-2 whitespace-no-wrap border dark:border-0 table2">
              <thead>
              <tr>
                <th>Action</th>
                <th style="width: 200px">C. Code</th>
                <th style="width: 200px">S/O</th>
                <th>Sector</th>
                <th>CNT</th>
                <th>Tues</th>
                <th>Load Status</th>
                <th>Assigned Contract</th>
                <th>Contract</th>
              </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <template v-for="(voyageCustomer, voyageCustomerKey, voyageCustomerIndex) in fixedContractCustomerList" :key="voyageCustomerIndex">
                <tr class="text-gray-700 dark:text-gray-400" v-for="(sector,sectorKey, index) in voyageCustomer" :key="sectorKey">
                  <td :rowspan="Object.keys(voyageCustomer).length" v-if="sectorKey == 0">
                    <button type="button" @click="moveToOpenContract(voyageCustomerKey)" :disabled="isLoading" class="w-full px-2 py-2 text-sm text-white bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Move to OS</button>
                  </td>
                  <td :rowspan="Object.keys(voyageCustomer).length" v-if="sectorKey == 0">
                    <strong>{{ sector?.customer_code }}</strong> <br>
                    {{ sector?.company_name }}
                  </td>
                  <td :rowspan="Object.keys(voyageCustomer).length" v-if="sectorKey == 0">
                    {{ sector?.slot_partner }} <br>
                    {{ sector?.slot_partner_name }}
                  </td>
                  <td>{{ sector?.sector }}</td>
                  <td>{{ sector?.total_container }}</td>
                  <td>{{ sector?.total_tues }}</td>
                  <td>{{ sector?.load_status }}</td>
                  <td>
                    <nobr>
                      <router-link v-if="sector?.contract_id" :to="{ name: 'commercial.slot-charter-contracts.show', params: { contractId: sector?.contract_id } }" class="flex items-center text-purple-800">
                        <strong class="ml-1">{{ sector?.contract_no }}</strong>
                      </router-link>
                    </nobr>
                  </td>                  
                  <td :rowspan="Object.keys(voyageCustomer).length" v-if="sectorKey == 0">
                    <button type="button" title="Contract List" @click="loadContractAssignData(sector?.customer_id,'Fixed',sector?.sector,sector?.voyage_customer_id)" :disabled="isLoading" class="w-full px-2 py-2 text-sm text-white bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Contract List</button>
                  </td>
                </tr>
              </template>
              </tbody>
              <tfoot v-if="!Object.keys(fixedContractCustomerList)?.length" class="bg-white dark:bg-gray-800">
              <tr v-if="isLoading">
                <td colspan="8" style="text-align: center">Loading...</td>
              </tr>
              <tr v-else-if="!Object.keys(fixedContractCustomerList)?.length">
                <td colspan="8" style="text-align: center">No data found.</td>
              </tr>
              </tfoot>
            </table>
          </div>
        </div>
    </div>
    <div v-on:click="toggleTabs(3)" v-bind:class="{'hidden': openTab !== 3, 'block': openTab === 3}">
      <div class="w-full mb-6 overflow-hidden rounded-lg shadow-xs" v-memo>
        <div class="w-full md:overflow-x-auto">
          <table class="w-full mt-2 whitespace-no-wrap border dark:border-0 table2">
            <thead>
            <tr>
              <th style="width: 200px">C. Code</th>
              <th style="width: 200px">S/O</th>
              <th>Sector</th>
              <th>CNT</th>
              <th>Tues</th>
              <th>Load Status</th>
              <th>Assigned Contract</th>
              <th>Contract</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
            <template v-for="(voyageCustomer, voyageCustomerKey, voyageCustomerIndex) in forceContractCustomerList" :key="voyageCustomerIndex">
              <tr class="text-gray-700 dark:text-gray-400" v-for="(sector,sectorKey, index) in voyageCustomer" :key="sectorKey">
                <td :rowspan="Object.keys(voyageCustomer).length" v-if="sectorKey == 0">
                  <strong>{{ sector?.customer_code }}</strong> <br>
                  {{ sector?.company_name }}
                </td>
                <td :rowspan="Object.keys(voyageCustomer).length" v-if="sectorKey == 0">
                  {{ sector?.slot_partner }} <br>
                  {{ sector?.slot_partner_name }}
                </td>
                <td>{{ sector?.sector }}</td>
                <td>{{ sector?.total_container }}</td>
                <td>{{ sector?.total_tues }}</td>
                <td>{{ sector?.load_status }}</td>
                <td>
                    <nobr>
                      <router-link v-if="sector?.contract_id" :to="{ name: 'commercial.slot-charter-contracts.show', params: { contractId: sector?.contract_id } }" class="flex items-center text-purple-800">
                        <strong class="ml-1">{{ sector?.contract_no }}</strong>
                      </router-link>
                    </nobr>
                  </td>                
                <td><button type="button" title="Contract List" @click="loadContractAssignData(sector?.customer_id,'Force',sector?.sector,sector?.voyage_customer_id)" :disabled="isLoading" class="w-full px-2 py-2 text-sm text-white bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Contract List</button></td>
              </tr>
            </template>
            </tbody>
            <tfoot v-if="!Object.keys(forceContractCustomerList)?.length" class="bg-white dark:bg-gray-800">
            <tr v-if="isLoading">
              <td colspan="8" style="text-align: center">Loading...</td>
            </tr>
            <tr v-else-if="!Object.keys(forceContractCustomerList)?.length">
              <td colspan="8" style="text-align: center">No data found.</td>
            </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
    <div v-show="isContractAssignModalOpen" class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center">
      <!-- Modal -->
      <div class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
        <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
        <header class="flex justify-end">
          <button class="inline-flex items-center justify-center w-6 h-6 mb-2 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700" aria-label="close" @click="closeContractAssignModal">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
              <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
          </button>
        </header>
        <!-- Modal body -->
        <table class="w-full mb-5 whitespace-no-wrap border-collapse contract-assign-table table1">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th colspan="5" class=""></th>
            <th colspan="2" class="">Validity</th>
            <th rowspan="2" style="text-align: center; width: 300px;">Short Note</th>
            <th rowspan="2" style="text-align: center">Action</th>
          </tr>
          <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="">CID Code</th>
            <th class="">Customer Name</th>
            <th class="">Service</th>
            <th class="">Contract Type</th>
            <th class="" style="text-align: center">Per</th>
            <th class="" style="text-align: center">From</th>
            <th class="" style="text-align: center">Till</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr v-for="(customerContract,index) in customerContracts">
            <td>{{ customerContract?.customer?.customer_code }}</td>
            <td>{{ customerContract?.customer?.customer_name }}</td>
            <td>{{ customerContract?.service?.code }}</td>
            <td>{{ customerContract?.rate_type }}</td>
            <td>Per Tue</td>
            <td><nobr>{{ moment(customerContract?.valid_from).format('DD-MM-YYYY') }}</nobr></td>
            <td><nobr>{{ moment(customerContract?.valid_till).format('DD-MM-YYYY') }}</nobr></td>
            <td>{{ customerContract?.short_note ?? "---" }}</td>
            <td>
              <div class="flex items-center justify-center">

                <router-link v-if="customerContract?.rate_type==='Fixed'" :to="{ name: 'commercial.contracts.show', params: { contractId: customerContract.id } }" >
                  <button @click="openContractAssignModal" title="Contract Details" class="px-2 my-1 py-0.5 text-sm leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Details
                  </button>
                </router-link>
                <router-link v-else :to="{ name: 'commercial.open-slot-contract.show', params: { contractId: customerContract.id } }">
                  <button @click="openContractAssignModal" title="Contract Details" class="px-2 my-1 py-0.5 text-sm leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Details
                  </button>
                </router-link>
                <button @click="assignContract(customerContract?.voyage_customer_id,voyageId,customerContract?.customer_id,customerContract?.id,customerContract?.sector,customerContract?.rate_type)" :disabled="customerContract?.id === contractAssignData?.contract_id" :class="customerContract?.id === contractAssignData?.contract_id ? 'custom-disabled' : ''" title="Contract Assign" class="ml-2 px-2 py-0.5 my-1 text-sm leading-5 text-white transition-colors duration-150 bg-green-700 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  Assign
                </button>
              </div>
            </td>
          </tr>
          </tbody>
<!--          <tfoot v-if="!contractAssignData?.customer?.contracts?.length" class="bg-white dark:bg-gray-800">-->
<!--          <tr v-if="isLoading">-->
<!--            <td colspan="9">Loading...</td>-->
<!--          </tr>-->
<!--          <tr v-else-if="!contractAssignData?.customer?.contracts?.length">-->
<!--            <td colspan="9">No contract found.</td>-->
<!--          </tr>-->
<!--          </tfoot>-->
        </table>
      </div>
    </div>
  </div>
</template>
<style lang="postcss" scoped>
.table1 th, .table1 td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 font-bold
}

.table2 td,th {
  @apply px-2 py-1 text-xs border-r dark:border-r-gray-600;
}
.table2 thead tr {
  @apply text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
}
.table2 tbody {
  @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
}

.table2 th,tr,td{
  font-size: 11px;
}

.tooltip {
  @apply absolute top-7 z-50 right-0 px-2 py-1 text-gray-200 bg-gray-700 rounded dark:bg-gray-600;
}
.icn {
  @apply w-5 h-5 transition duration-150 ease-in-out hover:translate-y-1 hover:transform;
}

.check {
  @apply rounded text-purple-500 focus:ring-purple-500 dark:bg-gray-600;
}

#modal{
  max-width: 65rem;
}

.contract-assign-table th {
  font-size: 10px;
}

.contract-assign-table th, .contract-assign-table tr,.contract-assign-table td {
  text-align: center;
  font-size: 12px;
}

.custom-disabled{
  pointer-events: none;
  cursor: default;
  opacity: 0.5;
}

</style>