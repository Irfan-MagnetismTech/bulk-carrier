<template>
    <div @dragenter.prevent="toggleActive()" @dragleave.prevent="toggleActive()" style="height: auto" @dragover.prevent @drop.prevent="drop" :class="{ 'bg-green-100 border-green-300 dark-disabled:bg-green-700': dragActive }" class="flex items-center justify-center w-full px-4 py-6 border-4 border-dashed rounded dark-disabled:border-2">
        <div class="flex custom-dropzone flex-col items-center justify-center gap-2 text-gray-500 dark-disabled:text-gray-100">
            <p class="text-sm font-semibold">Drag a file here</p>
            <!-- File input -->
            <label for="file" class="p-2 text-sm font-semibold leading-tight text-gray-600 bg-purple-100 border rounded cursor-pointer dark-disabled:bg-purple-600 dark-disabled:border-none hover:bg-purple-200 dark-disabled:hover:bg-purple-700 hover:shadow-sm">
                <span class="text-purple-500 dark-disabled:text-gray-100">Select a file from your device</span>
                <input type="file" @change="selectedFile" name="file" id="file" class="hidden" />
            </label>
            <!-- Dropped file info -->
            <div v-if="droppedFile !== null" class="flex flex-col items-center justify-center space-x-1 text-base font-semibold text-gray-600 dark-disabled:text-gray-100">
                <div class="flex flex-wrap items-center justify-center">
                    <svg class="w-6 h-6 iconify iconify--mdi-light" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        <path d="M14 11a3 3 0 0 1-3-3V4H7a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-8h-4zm-2-3a2 2 0 0 0 2 2h3.586L12 4.414V8zM7 3h5l7 7v9a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3z" fill="currentColor" />
                    </svg>
                    <span class>{{ droppedFile?.name }}</span>
                    <button @click="clearDropped()" class="inline-flex items-center justify-center ml-2">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </button>
                </div>
                <span class="text-xs text-gray-500 dark-disabled:text-gray-300">{{ bytes(droppedFile?.size) }}</span>
            </div>
        </div>
    </div>
</template>
<script setup>
import bytes from 'bytes';
import useDropZone from '../services/dropZone.js';

const { dragActive, droppedFile, toggleActive, drop, clearDropped, selectedFile } = useDropZone();
</script>