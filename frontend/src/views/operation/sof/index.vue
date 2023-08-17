<script setup>
import { onMounted, ref } from "vue";
import ActionButton from "../../../components/buttons/ActionButton.vue";
import Heading from "../../../components/Heading.vue";
import { useRouter } from "vue-router";
import useVoyage from "../../../composables/operation/useVoyage";
import usePort from "../../../composables/usePort";
import useVoyageSof from "../../../composables/operation/sof/useVoyageSof";
import env from '../../../config/env';

const { voyageName, getVoyageName } = useVoyage();
const { portName, getPortsByNameOrCode } = usePort();

function fetchOptions(search, loading) {
  getPortsByNameOrCode(search);
}

const { sofs, getAllSofs, deleteVoyageSofList } = useVoyageSof();
const router = useRouter();

function fetchVoyages(search, loading) {
  getVoyageName(search);
}

onMounted(() => {
    getAllSofs();
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
    :to="{ name: 'operation.sof.create' }"
    type="create"
    label="Statement of Fact"
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
          <tr v-for="(item, index) in sofs" :key="index"
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
                  :to="{
                    name: 'operation.sof.edit',
                    params: { sofId: item?.id ?? -1 },
                  }"
                  :action="'edit'"
                ></action-button>
                <action-button
                  @click="deleteVoyageSofList(item?.id ?? -1)"
                  :action="'delete'"
                ></action-button>
                
                <a :href="env.BASE_API_URL+'sof/'+item?.id" target="_blank">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                  </svg>
                </a>
              </div>
            </td>
          </tr>
         
        </tbody>
      </table>
    </div>
    <Paginate
      :data="expenseInvoices"
      to="operation.sofs.index"
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
