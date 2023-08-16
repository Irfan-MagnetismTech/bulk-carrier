<script setup>
import bytes from 'bytes';
import useMultipleDropZone from '../services/multipleDropZone.js';
import {watchEffect,ref} from "vue";
import env from '../config/env';
import useContract from "../composables/commercial/useContract";

const props = defineProps({
  form: {
    type: [Object, Array],
    required: false,
    default: {}
  },
  page: {
    required: false,
    default: {}
  },
});

const { dragActive, droppedFile, toggleActive, drop, clearDropped, selectedFile } = useMultipleDropZone();
const { contractAttachments, deleteContractAttachment } = useContract();

watchEffect(() => {
  contractAttachments.value = props.form.attachments;
});


function removeAttachment(contractAttachmentIndex,contractAttachmentId){

  if(contractAttachmentId){
    deleteContractAttachment(contractAttachmentId,contractAttachmentIndex);
  }
  // if(contractAttachmentId){
  //   props.form.contract_attachments_to_delete.push(contractAttachmentId);
  // }
}

</script>

<template>
    <div @dragenter.prevent="toggleActive()" @dragleave.prevent="toggleActive()" @dragover.prevent @drop.prevent="drop" :class="{ 'bg-green-100 border-green-300 dark:bg-green-700': dragActive }" class="flex items-center justify-center w-full px-4 py-6 border-4 border-dashed rounded dark:border-2">
        <div class="flex custom-dropzone flex-col items-center justify-center gap-2 text-gray-500 dark:text-gray-100">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" :class="{ 'animate-bounce': droppedFile === null }" class="w-20 h-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
            </span>
            <p class="text-2xl font-semibold">Drag a file here</p>
            <p class="text-sm font-semibold">Or if you prefer...</p>
            <!-- File input -->
            <label for="file" class="p-2 text-sm font-semibold leading-tight text-gray-600 bg-purple-100 border rounded cursor-pointer dark:bg-purple-600 dark:border-none hover:bg-purple-200 dark:hover:bg-purple-700 hover:shadow-sm">
                <span class="text-purple-500 dark:text-gray-100">Select a file from your device</span>
                <input type="file" @change="selectedFile" name="file" id="file" class="hidden" multiple />
            </label>
            <!-- Dropped file info -->

          <div v-if="contractAttachments?.length" class="flex flex-col items-center justify-center space-x-1 text-base font-semibold text-gray-600 dark:text-gray-100">
            <div class="flex flex-wrap items-center justify-center">
              <span v-for="(contractAttachment,contractIndex) in contractAttachments" class="flex ml-2" :key="contractIndex">
                      <div>
                        <a target="_blank" :href="env.BASE_API_URL+'storage/'+contractAttachment?.file_path">{{ contractAttachment?.file_path?.split('/').pop() }}</a>
                      </div>
                      <button type="button" @click="removeAttachment(contractIndex,contractAttachment?.id)" class="ml-2">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </button>
                    </span>
            </div>
          </div>

            <div v-if="droppedFile.length" class="flex flex-col items-center justify-center space-x-1 text-base font-semibold text-gray-600 dark:text-gray-100">
                <div class="flex flex-wrap items-center justify-center">
                    <svg class="w-6 h-6 iconify iconify--mdi-light" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        <path d="M14 11a3 3 0 0 1-3-3V4H7a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-8h-4zm-2-3a2 2 0 0 0 2 2h3.586L12 4.414V8zM7 3h5l7 7v9a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3z" fill="currentColor" />
                    </svg>
                    <span v-for="(singleFile,index) in droppedFile" class="flex ml-2">
                      <div>
                        {{ singleFile.name }}
                        <span class="text-xs text-gray-500 dark:text-gray-300 block">{{ bytes(singleFile.size) }}</span>
                      </div>
                      <button type="button" @click="clearDropped(index)" class="ml-2">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </button>
                    </span>

                </div>
            </div>
        </div>
    </div>
</template>
