
<script setup>
import {onMounted, ref, watch, watchEffect} from 'vue';
import { useRoute } from 'vue-router';

import useGenerateBlReport from '../../../composables/documentation/useGenerateBlReport';
import useCustomer from "../../../composables/commercial/useCustomer";
import useVoyage from "../../../composables/operation/useVoyage";
import Paginate from '../../../components/utils/paginate.vue';
import useFileUploader from '../../../composables/useFileUploader';

const { voyage, advisory_agents, selectedCustomerAgents, getAgents, getVoyageDetails, isLoading, sendAdvisoryMail, getCustomerAgentsAndContainers, getCustomerAgentsAndContainersByAdvisoryType } = useVoyage();
const { filePath, uploadedFileId, removeFile, process } = useFileUploader();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
})

const route = useRoute();
const isALlSelected = ref(false);
const numberOfSelected = ref(0);
const message = ref("");
const formParams = ref({});
const { customers, getCustomers } = useCustomer();
const { voyages, voyageCustomers, getVoyageName, getVoyageCustomer } = useVoyage();
const { blLoadInfo, getBillOfLadding, uniqueServicePorts, generateBlReport, generateBl, getServiceUniquePorts } = useGenerateBlReport();
const filePusherIndex = ref('');
const advisory_agents_object = [];

function fetchOptions(search, loading) {
  getVoyageName(search);
}

function checkSelected(index) {
  advisory_agents.value[index].isSelected = true;
  // numberOfSelected.value = advisory_agents.value.filter(advisory => advisory.isSelected).length;
}

function checkAll() {
  advisory_agents.value.data.forEach(advisory => {
    advisory.isSelected = isALlSelected.value;
  });
  checkSelected();
}

function selectedFile(event, index)
{
    const currentFile = event.target.files[0];
    process(currentFile, 'files');

    filePusherIndex.value = index
}

function attachmentName(attachment)
{
    if(attachment)
    {
        let name = attachment.split('/').pop();
        let dot = (name.length > 15) ? '...' : '';
        return name.substr(0,15) + dot;
    } else {
        return null;
    }
}

function removeAttachment(fileid, agentId, attachmentIndex)
{
    let attachments = advisory_agents.value[agentId]?.mail_attachment?.attachments;
    if(attachments)
    {
        attachments.splice(attachmentIndex, 1);
    }
    removeFile(fileid);
}

function send() {
    let agents = advisory_agents.value;
    let voyage_id = formParams?.value?.voyage_id;
    let type = formParams?.value?.type;
    sendAdvisoryMail({ voyage_id, type, agents });

}

watch(() => filePath, (value) => {

        const index = filePusherIndex.value;

            if(advisory_agents.value[index].mail_attachment == null)
            {
                let attachments = {
                    attachments: []
                };
                advisory_agents.value[index].mail_attachment = attachments;
            }
            //alert(uploadedFileId);

            advisory_agents.value[index].mail_attachment?.attachments?.push({id: uploadedFileId.value, name: value.value});
},{deep: true});

watch(() => formParams?.value?.voyage_id, (value) => {
  if(value) {
    getServiceUniquePorts(value);
  }
},{deep: true});

onMounted(() => {
  watchEffect(() => {
    getBillOfLadding(props.page);
  });
});

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Sent E-mails</h2>
    </div>
    <!-- Table -->
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form class="flex items-end justify-between gap-4" @submit.prevent="getAgents(formParams)">
            <label class="block w-full text-sm">
              <span class="text-gray-700 dark:text-gray-300">Voyage</span>
              <v-select :options="voyages" placeholder="--Choose an option--" @search="fetchOptions" v-model="formParams.voyage_id" label="voyage" :reduce="voyages => voyages.id" required></v-select>
            </label>
            <label class="block w-full text-sm">
              <span class="text-gray-700 dark:text-gray-300">Advisory Type</span>
              <select v-model="formParams.type" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                <option value="" selected disabled>--Choose an option--</option>
                <option value="arrival">Arrival Advisory</option>
                <option value="departure">Departure Advisory</option>
              </select>
            </label>
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Submit</button>
        </form>
    </div>
  <h4 class="p-2 font-bold text-center text-gray-700 dark:text-gray-200">
    Sent Box
  </h4>
  <div class="w-full mb-2 overflow-hidden">
    <div class="w-full overflow-x-auto">
      <div class="flex items-center p-2 border rounded-lg">
        <div class="w-1/12">
            <span class="flex items-center justify-center w-8 h-8 p-2 bg-gray-400 rounded-full">
                AS
            </span>
        </div>
        <div class="w-3/12">
            <span class="block font-semibold">Delowar Hossain</span>
            <span class="font-semibold text-gray-500">2022/07/05</span>
        </div>
        <div class="w-5/12">
            <span class="font-semibold">Export Advisory for Voy MHRF0021S</span>
        </div>
        <div class="w-2/12">
            <span class="inline-block px-2 py-1 text-sm font-semibold text-white bg-green-500 rounded-full">Sent</span>
        </div>
        <div class="w-1/12">
          <!-- May be attachments name in column -->
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
          </svg>
        </div>
      </div>
    </div>
    <!-- Pagination -->
<!--    <Paginate :data="blLoadInfo" to="documentation.generate-bl.report.index" :page="page"></Paginate>-->
  </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
  th {
    @apply px-4 py-3;
  }
  td {
    @apply px-4 py-3 text-xs border-r-0;
  }
  thead tr {
    @apply text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  tbody tr {
    @apply text-gray-700 dark:text-gray-400;
  }
  .check {
    @apply rounded text-purple-500 focus:ring-purple-500 dark:bg-gray-600;
  }
  .email {
    @apply text-purple-500 hover:underline dark:text-purple-600;
  }
  .tel {
    @apply hover:underline;
  }
  .pill {
    @apply px-2 py-1 font-semibold leading-tight rounded-full;
  }
  .pill-success {
    @apply text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100;
  }
  .pill-warning {
    @apply text-orange-700 bg-orange-100 dark:text-white dark:bg-orange-600;
  }
  .tr-active {
    @apply bg-green-200 dark:bg-gray-700 dark:text-gray-50;
  }
  .bold {
    @apply font-semibold dark:text-gray-200 capitalize;
  }
  .td-border {
    @apply border-l dark:border-gray-700;
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
}
</style>
