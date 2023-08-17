
<script setup>
import {onMounted, ref, watch, watchEffect} from 'vue';
import { useRoute } from 'vue-router';

import useGenerateBlReport from '../../../composables/documentation/useGenerateBlReport';
import useCustomer from "../../../composables/commercial/useCustomer";
import useVoyage from "../../../composables/operation/useVoyage";
import Paginate from '../../../components/utils/paginate.vue';
import useFileUploader from '../../../composables/useFileUploader';
import useAdvisory from "../../../composables/operation/useAdvisory";
import moment from 'moment';
import env from '../../../config/env';
import Title from "../../../services/title";

const { voyage, advisory_agents, selectedCustomerAgents, getAgents, getVoyageDetails, isLoading, sendAdvisoryMail, getCustomerAgentsAndContainers, getCustomerAgentsAndContainersByAdvisoryType } = useVoyage();
const { outboxMails, externalEmail, advisoryFormParams, getOutboxMails, getHrlinesCC } = useAdvisory();
const { filePath, uploadedFileId, removeFile, process } = useFileUploader();

const { setTitle } = Title();

setTitle('Customer Advisory');

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
const { voyages, voyageName, voyageCustomers, getVoyageName, getVoyageCustomer } = useVoyage();
const { blLoadInfo, getBillOfLadding, uniqueServicePorts, generateBlReport, generateBl, getServiceUniquePorts } = useGenerateBlReport();
const filePusherIndex = ref('');
const globalRemark = ref('');
const globalFiles = ref([]);

const outBoxMailData = ref('');
const advisory_agents_object = [];

const isOutboxMailModalOpen = ref(0);

function fetchOptions(search, loading) {
  getVoyageName(search);
}

function checkSelected(index) {
  isALlSelected.value = Object.values(advisory_agents.value).every(advisory => advisory.isSelected);
  numberOfSelected.value = Object.values(advisory_agents.value).filter(advisory => advisory.isSelected).length;
  //advisory_agents.value[index].isSelected = true;
}

function openOutboxMailDetails(outboxMail) {

  isOutboxMailModalOpen.value = 1;
  outBoxMailData.value = outboxMail;
}

function closeOutboxMailModal(){
  isOutboxMailModalOpen.value = 0;
}


function checkAll() {
  Object.values(advisory_agents.value).forEach(function(advisory) {
    advisory.isSelected = isALlSelected.value;
  });

  checkSelected();
}

watch(advisory_agents, () => {
  Object.values(advisory_agents.value).forEach(function(advisory) {
    advisory.isSelected = false;
  });
});

watch(globalRemark, (remark) => {

  if(advisory_agents.value.length < 1) {
      globalRemark.value = null
      alert("Whoa! You cannot add remarks without any agent information.")
      return;
    }
  for(const agent in advisory_agents.value) {

    // if(advisory_agents.value[agent].note == null) {
      advisory_agents.value[agent].note = remark
    // }

  }

})

function selectedFile(event, index)
{
    if(advisory_agents.value.length < 1) {
      return alert("Whoa! You cannot attach file without any agent information.")
    }
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
    if(fileid == 'Global') {
      console.log("File ID: ", globalFiles.value[attachmentIndex].id)

      for(const agent in advisory_agents.value) {

        let attachments = advisory_agents.value[agent].mail_attachment?.attachments;
        for(const attachment in attachments) {

          if(attachments[attachment].id == globalFiles.value[attachmentIndex].id) {
            attachments.splice(attachment, 1)
          }
        }
      }
      globalFiles.value.splice(attachmentIndex, 1)

    } else {
      let attachments = advisory_agents.value[agentId]?.mail_attachment?.attachments;
      if(attachments) {
          attachments.splice(attachmentIndex, 1);
      }
      removeFile(fileid);
    }
}

const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  openTab.value = tabNumber;
}

function send() {
    let agents = advisory_agents.value;
    let voyage_id = formParams?.value?.voyage_id;
    let type = formParams?.value?.type;
    sendAdvisoryMail({ voyage_id, type, agents });

}

watch(() => filePath, (value) => {

        const index = filePusherIndex.value;

        if(index != 'Global') {
          if(advisory_agents.value[index].mail_attachment == null)
            {
                let attachments = {
                    attachments: []
                };
                advisory_agents.value[index].mail_attachment = attachments;
            }
            

            advisory_agents.value[index].mail_attachment?.attachments?.push({id: uploadedFileId.value, name: value.value});
        } else {

          if(index == 'Global') {
            for(const agent in advisory_agents.value) {

              if(advisory_agents.value[agent].mail_attachment == null)
              {
                  let attachments = {
                      attachments: []
                  };
                  advisory_agents.value[agent].mail_attachment = attachments;
              }

              advisory_agents.value[agent].mail_attachment?.attachments?.push({id: uploadedFileId.value, name: value.value});

            }

            globalFiles.value.push({id: uploadedFileId.value, name: value.value});

          }
          
        }


            
},{deep: true});

watch(() => formParams?.value?.voyage_id, (value) => {
  if(value) {
    getServiceUniquePorts(value);
  }
},{deep: true});

onMounted(() => {
 getHrlinesCC();
});

</script>
<template>
    <!-- Heading -->
<!--    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>-->
<!--        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Send Customer Advisory</h2>-->
<!--    </div>-->
    <!-- Table -->
  <div class="border-b border-gray-200 dark:border-gray-700">
      <ul class="flex flex-wrap mt-2 -mb-px">
        <li class="mr-2">
          <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(1)" v-bind:class="{'text-purple-600 bg-white': openTab !== 1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 1}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Sent Advisory
          </a>
        </li>
        <li class="mr-2">
          <a href="#" class="inline-flex px-4 py-4 text-sm font-medium text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(2)" v-bind:class="{'text-purple-600 bg-white': openTab !== 2, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === 2}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
            </svg>
            Mail Outbox
          </a>
        </li>
      </ul>

    <div v-on:click="toggleTabs(1)" v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">
          <div class="px-4 py-3 mt-2 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
              <form class="flex items-end justify-between gap-4" @submit.prevent="getAgents(formParams)">
                  <label class="block w-full text-sm">
                    <span class="text-gray-700 dark:text-gray-300">Voyage <span class="text-red-500">*</span></span>
                    <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchOptions"  v-model="formParams.voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required></v-select>
                  </label>
                  <label class="block w-full text-sm">
                    <span class="text-gray-700 dark:text-gray-300">Advisory Type <span class="text-red-500">*</span></span>
                    <select v-model="formParams.type" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                      <option value="" selected disabled>--Choose an option--</option>
                      <option value="arrival">Arrival Advisory</option>
                      <option value="departure">Departure Advisory</option>
                    </select>
                  </label>
                  <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
              </form>
          </div>
        <h4 class="p-2 font-bold text-center text-gray-700 dark:text-gray-200">
          Local Agent List
        </h4>
        <div>
          <b>Special Recevier [Export]</b><span class="px-2 py-1 m-1 bg-gray-300 rounded-md">
            {{ externalEmail.external_export_receiver }}
          </span>
          <br/> <br/>
          <b>Special Recevier [Import]</b><span class="px-2 py-1 m-1 bg-gray-300 rounded-md">
            {{ externalEmail.external_import_receiver }}
          </span>
        </div>
        <div class="my-5 flex justify-center bg-gray-100 p-3">
          <div class="w-5/12">
            <label class="p-1 border border-purple-600 border-dashed cursor-pointer">
              <input type="file" class="hidden" @change="selectedFile($event, 'Global')"/>
              Choose File
            </label>
            <div v-for="(attachment, attachmentIndex) in globalFiles" class="flex items-center justify-between px-1 py-1 mt-2 rounded-md cursor-pointer hover:bg-cool-gray-400">
                          <span>
                              {{ attachmentName(attachment.name) }}
                          </span>
                          <button type="button" @click="removeAttachment('Global', null, attachmentIndex)" class="text-sm text-center text-red-700 cursor-pointer">
                              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" data-v-3b751c1b=""><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" data-v-3b751c1b=""></path></svg>
                          </button>
                  </div>
          </div>
          <div class="w-6/12 pl-5">
            <label class="p-1 ">
              <input type="text" placeholder="Remark/ Notes" v-model="globalRemark" class="w-full rounded-md" />
            </label>
          </div>
        </div>
        <div class="flex flex-row-reverse items-center justify-between mb-2">
          <button @click="send()" class="inline-flex gap-1 px-3 py-1 text-sm text-center bg-purple-500 rounded btn text-purple-50 hover:bg-purple-600 active:bg-purple-500 disabled:bg-purple-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 rotate-45 text-purple-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                          </svg>
            Send Email
          </button>
          <div class="flex items-center gap-1 text-xs text-red-500" v-if="message">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="w-5 h-5 iconify iconify--bx" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
              <path fill="currentColor" d="M11.001 10h2v5h-2zM11 16h2v2h-2z" />
              <path fill="currentColor" d="M13.768 4.2C13.42 3.545 12.742 3.138 12 3.138s-1.42.407-1.768 1.063L2.894 18.064a1.986 1.986 0 0 0 .054 1.968A1.984 1.984 0 0 0 4.661 21h14.678c.708 0 1.349-.362 1.714-.968a1.989 1.989 0 0 0 .054-1.968L13.768 4.2zM4.661 19L12 5.137L19.344 19H4.661z" />
            </svg>
            {{ message }}
          </div>
          <p class="text-sm font-semibold tracking-tighter text-gray-400">Selected: 2 of 5</p>
        </div>
        <div class="w-full mb-2 overflow-hidden border rounded-lg shadow-xs dark:border-0">
          <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
              <thead>
              <tr>
                  <th>
                      <input v-if="advisory_agents?.length != 0" v-model="isALlSelected" @change="checkAll()" type="checkbox" class="check" />
                  </th>
                  <th>
                    External
                  </th>
                  <th>Code/Name</th>
                  <th>Export Email</th>
                  <th>Import Email</th>
                  <th>Containers</th>
                  <th>Attachments</th>
                  <th>Notes</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(advisory,index) in advisory_agents" :key="index" class="transition-all duration-200 ease-in-out border-b-2 hover:bg-green-300">
                <td class="w-5">
                  <input type="checkbox" v-model="advisory.isSelected" @change="checkSelected()" class="check" />
                </td>
                <td class="w-5 border-l dark:border-gray-600">
                  <input type="checkbox" v-model="advisory.isSpecial" @change="checkSelected()" class="check" />
                </td>
                <td class="border-l dark:border-gray-600">
                  <span class="block px-2 py-1 mb-2 font-semibold text-center bg-gray-300 rounded-md">{{ advisory?.mlo_info?.code }}</span>
                  {{ advisory?.mlo_info?.name }}
                </td>
                <td class="border-l dark:border-gray-600" style="word-wrap: break-word !important; max-width: 150px !important; white-space: normal;">{{ advisory?.mlo_info?.export_email }}</td>
                <td class="border-l dark:border-gray-600" style="word-wrap: break-word !important; max-width: 150px !important; white-space: normal;">{{ advisory?.mlo_info?.import_email }}</td>
                <td class="td-border">
                  <span class="font-semibold text-gray-700 dark:text-gray-300">{{ advisory?.total }} Containers</span>
                  <div class="block my-2 overflow-y-scroll break-all max-h-8 hyphens-all">
                    {{ advisory.containers ? Object.keys(advisory.containers).map(key => `${advisory.containers[key]}`).join(",") : ''}}
                  </div>
                </td>
                <td class="td-border">
                      <label class="p-1 border border-purple-600 border-dashed cursor-pointer">
                          <input type="file" class="hidden" @change="selectedFile($event, index)"/>
                          Choose File
                      </label>

                      <div v-for="(attachment, attachmentIndex) in advisory?.mail_attachment?.attachments" class="flex items-center justify-between px-1 py-1 mt-2 rounded-md cursor-pointer hover:bg-cool-gray-100">
                          <span>
                              {{ attachmentName(attachment.name) }}
                          </span>
                          <button type="button" @click="removeAttachment(attachment.id, advisory?.mlo_info?.id, attachmentIndex)" class="text-sm text-center text-red-700 cursor-pointer">
                              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" data-v-3b751c1b=""><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" data-v-3b751c1b=""></path></svg>
                          </button>
                      </div>
                </td>
                <td class="td-border">
                  <input type="text" class="w-32" placeholder="Note" v-model="advisory.note" />
                </td>
              </tr>
              </tbody>
              <tfoot v-if="!advisory_agents?.length" class="bg-white dark:bg-gray-800">
              <tr v-if="isLoading">
                <td colspan="7" class="text-center">Loading...</td>
              </tr>
              <tr v-else-if="advisory_agents?.length == 0">
                <td colspan="7" class="text-center">No local agent found.</td>
              </tr>
              </tfoot>
            </table>
          </div>
          <!-- Pagination -->
      <!--    <Paginate :data="blLoadInfo" to="documentation.generate-bl.report.index" :page="page"></Paginate>-->
        </div>
    </div>

    <div v-on:click="toggleTabs(2)" v-bind:class="{'hidden': openTab !== 2, 'block': openTab === 2}">
      <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form class="flex items-end justify-between gap-4" @submit.prevent="getOutboxMails(advisoryFormParams)">
          <label class="block w-full text-sm">
            <span class="text-gray-700 dark:text-gray-300">Voyage <span class="text-red-500">*</span></span>
            <v-select :options="voyageName" placeholder="--Choose an option--" @search="fetchOptions" v-model="advisoryFormParams.voyage_id" label="voyage" :reduce="voyageName => voyageName.id" required></v-select>
          </label>
          <label class="block w-full text-sm">
            <span class="text-gray-700 dark:text-gray-300">Advisory Type <span class="text-red-500">*</span></span>
            <select v-model="advisoryFormParams.type" required class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
              <option value="" selected disabled>--Choose an option--</option>
              <option value="arrival">Arrival Advisory</option>
              <option value="departure">Departure Advisory</option>
            </select>
          </label>
          <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Search</button>
        </form>
      </div>
      <h4 class="p-2 font-bold text-center text-gray-700 dark:text-gray-200">
        Sent Box
      </h4>

      <div @click="openOutboxMailDetails(outbox)" v-for="(outbox,index) in outboxMails" :key="index" class="w-full mb-2 overflow-hidden cursor-pointer">
        <div class="w-full overflow-x-auto">
          <div class="flex items-center p-2 border rounded-lg">
            <div class="w-2/12">
            <span class="flex items-center justify-center w-8 h-8 p-2 bg-gray-400 rounded-full">
                {{ outbox?.mlo_agent?.name?.split(' ')[0]?.charAt(0) }}{{ outbox?.mlo_agent?.name?.split(' ')[1]?.charAt(0) }}

                
            </span>
            <span class="text-sm text-gray-400">
                  Sender: {{ outbox?.mail_sender?.name }}
            </span>
            </div>
            <div class="w-3/12">
              <span class="block font-semibold">{{ outbox?.mlo_agent?.name }}</span>
              <span class="font-semibold text-gray-500">
                {{ outbox?.updated_at ? moment(outbox?.updated_at).format('DD-MM-YYYY') : null}}
              </span>
            </div>
            <div class="w-5/12">
              <span class="font-semibold">{{ outbox?.subject }}</span>
            </div>
            <div class="flex justify-center w-2/12">
              <span v-if="outbox?.status == 'pending'" class="block w-5/12 px-2 py-1 text-sm font-semibold text-center text-white bg-blue-500 rounded-full">
                {{ outbox?.status }}
              </span>
              <span v-if="outbox?.status == 'failed'" class="block w-5/12 px-2 py-1 text-sm font-semibold text-center text-white bg-red-600 rounded-full">
                {{ outbox?.status }}
              </span>
              <span v-if="outbox?.status == 'sent'" class="block w-5/12 px-2 py-1 text-sm font-semibold text-center text-white bg-green-600 rounded-full">
                {{ outbox?.status }}
              </span>
            </div>
<!--            <div class="w-1/12">-->
<!--              &lt;!&ndash; May be attachments name in column &ndash;&gt;-->
<!--              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">-->
<!--                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />-->
<!--              </svg>-->
<!--            </div>-->
          </div>
        </div>
      </div>
      <div v-if="!outboxMails.length" class="w-full mb-2 overflow-hidden">
        <div class="w-full text-center text-red-800">
            <span>Sorry! No data found</span>
        </div>
      </div>

      <div v-show="isOutboxMailModalOpen" class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center">
        <!-- Modal -->
        <div class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
          <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
          <header class="flex justify-end">
            <button class="inline-flex items-center justify-center w-6 h-6 text-red-800 transition-colors duration-150 border-2 border-red-700 rounded-full dark:hover:text-gray-200 hover: hover:text-red-700" aria-label="close" @click="closeOutboxMailModal">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
                <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
              </svg>
            </button>
          </header>
          <!-- Modal body -->
          <div class="mt-4 mb-6">

            <div>
              <p class="mb-4 text-gray-600">
                Subject: <span class="font-semibold">{{ outBoxMailData?.subject }}</span>
              </p>
              <div v-html="outBoxMailData?.content">

              </div>
              <hr class="py-3"/>
              <p class="text-gray-500">Attachments</p>
              <ul>
                <li v-if="outBoxMailData" v-for="(attachment,index) in JSON.parse(outBoxMailData?.attachments)" :key="index" class="mb-2 font-semibold text-blue-500 hover:text-blue-700 hover:cursor-pointer">
                  <a :href="env.BASE_API_URL+'/storage/'+attachment.name" target="_blank">
                    {{ index + 1 }}. {{ attachmentName(attachment.name) }}
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800">
            <button type="button" @click="closeOutboxMailModal" style="color: #6b6e6c" class="w-full px-5 py-3 text-sm font-medium leading-5 text-gray-700 border border-gray-300 rounded-lg dark:text-gray-600 sm:px-4 sm:py-2 sm:w-auto hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
              Close
            </button>
          </footer>
        </div>
      </div>
    </div>
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

  #modal {
    max-width: 80%;
    max-height: 80%;
    overflow-y: scroll;
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
