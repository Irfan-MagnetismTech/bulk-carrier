<script setup>
import { onMounted, ref } from "vue";
import ActionButton from "../../../components/buttons/ActionButton.vue";
import Heading from "../../../components/Heading.vue";
import useVesselConditioning from "../../../composables/finance/useVesselConditioning";
import { useRouter } from "vue-router";
import useVoyage from "../../../composables/operation/useVoyage";
import usePort from "../../../composables/usePort";
import env from '../../../config/env';

const { voyageName, getVoyageName } = useVoyage();
const { portName, getPortsByNameOrCode } = usePort();

function fetchOptions(search, loading) {
  getPortsByNameOrCode(search);
}

const { agentTdrs, getAllAgentTdr, deleteAgentTdr, updateAgentTdrStatus } = useVesselConditioning();
const router = useRouter();


function changeAgentTdrStatus(agentTdrId, statusCode) {
  updateAgentTdrStatus(agentTdrId, statusCode)
}

function fetchVoyages(search, loading) {
  getVoyageName(search);
}

onMounted(() => {
  getAllAgentTdr();
});

function print(agentTdrId) {
  console.log(agentTdrId)
}

function editAgentTdr(agentTdrId) {
  console.log(agentTdrId)
}

const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  openTab.value = tabNumber;
};
</script>
<template>
  <!-- Heading -->

  <Heading
    :to="{ name: 'finance.vessel-conditioning.create' }"
    type="create"
    label="TDR"
  ></Heading>

  <div class="w-full">
    <div class="w-full">
      <table class="whitespace-no-wrap mb-5 w-full">
        <thead v-once>
          <tr
            class="bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-700 dark:text-gray-200"
          >
            <th>Voyage</th>
            <th>Port</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
          <tr v-for="(item, index) in agentTdrs" :key="index"
            class="text-left text-gray-700 dark:text-gray-400">
            <td class="px-1 text-left">{{ item?.voyage?.voyage }}</td>
            <td class="px-1 text-left">
             {{ item?.port }}
            </td>
           
            <td class="text-gray-600">
              <div class="flex items-center justify-center space-x-2">
                <action-button
                  @click="showAgentTdr(item?.id ?? -1)"
                  :action="'show'"
                ></action-button>
                <action-button
                  v-if="item?.is_final != 1"
                  :to="{
                    name: 'finance.vessel-conditioning.edit',
                    params: { agentTdrId: item?.id ?? -1 },
                  }"
                  :action="'edit'"
                ></action-button>
                <action-button
                  v-if="item?.is_final != 1"
                  @click="deleteAgentTdr(item?.id ?? -1)"
                  :action="'delete'"
                ></action-button>
                <a :href="env.BASE_API_URL+'finance/tdr/'+item?.id" target="_blank">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 text-green-500"><title>PDF Download</title><path d="M19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3M9.5 11.5C9.5 12.3 8.8 13 8 13H7V15H5.5V9H8C8.8 9 9.5 9.7 9.5 10.5V11.5M14.5 13.5C14.5 14.3 13.8 15 13 15H10.5V9H13C13.8 9 14.5 9.7 14.5 10.5V13.5M18.5 10.5H17V11.5H18.5V13H17V15H15.5V9H18.5V10.5M12 10.5H13V13.5H12V10.5M7 10.5H8V11.5H7V10.5Z" /></svg>
                </a>
                <a :href="env.BASE_API_URL+'finance/container-list-excel/'+item?.id" target="_blank">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 text-green-500"><title>Excel Download</title><path d="M14 2H6C4.89 2 4 2.9 4 4V20C4 21.11 4.89 22 6 22H18C19.11 22 20 21.11 20 20V8L14 2M18 20H6V4H13V9H18V20M12.9 14.5L15.8 19H14L12 15.6L10 19H8.2L11.1 14.5L8.2 10H10L12 13.4L14 10H15.8L12.9 14.5Z" /></svg>
                </a>
                <a :href="env.BASE_API_URL+'finance/download-last-uploaded-file/'+item?.id" target="_blank">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 text-green-500"><title>Download Previous Uploaded File</title><path d="M8 17V15H16V17H8M16 10L12 14L8 10H10.5V7H13.5V10H16M5 3H19C20.11 3 21 3.9 21 5V19C21 20.11 20.11 21 19 21H5C3.9 21 3 20.11 3 19V5C3 3.9 3.9 3 5 3M5 5V19H19V5H5Z" /></svg>
                </a>
                <span @click="changeAgentTdrStatus(item?.id , 1)" v-if="item?.is_final != 1" class="cursor-pointer flex items-center justify-between gap-1 px-2 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                  Make Final
                </span>
                <span v-else class="cursor-not-allowed flex items-center justify-between gap-1 px-2 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-purple">
                  Finalized
                </span>
              </div>
            </td>
          </tr>
         
        </tbody>
      </table>
    </div>
    <Paginate
      :data="expenseInvoices"
      to="finance.expense-invoices.index"
      :page="page"
    ></Paginate>
  </div>

 
</template>
<style lang="postcss" scoped>
th {
  @apply px-3 py-2;
}

td {
  @apply px-3 py-2 text-xs;
}

.dropdown-btn {
  @apply focus:outline-none flex w-full items-center justify-center gap-1.5 rounded-lg bg-gray-900 py-2 px-3 font-semibold text-white hover:bg-gray-700 focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-gray-50 dark:bg-blue-500 dark:hover:bg-blue-400 sm:w-auto;
}

table,
th,
td {
  @apply border-collapse border;
}

.tooltip {
  position: relative;
  display: inline-block;
}

.tooltip .tooltiptext {
  text-transform: capitalize;
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;
  position: absolute;
  z-index: 1;
  top: -140%;
  left: 50%;
  margin-left: -60px;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: black transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}

/*.tooltip {*/
/*    @apply absolute -top-8 z-50 right-0 px-2 py-1 text-gray-200 bg-gray-700 rounded dark:bg-gray-600;*/
/*}*/
/*.mytooltip {*/
/*  @apply text-gray-200 bg-gray-700 dark:bg-gray-600;*/
/*}*/
.icn {
  @apply h-5 w-5 transition duration-150 ease-in-out hover:translate-y-1 hover:transform;
}
</style>
