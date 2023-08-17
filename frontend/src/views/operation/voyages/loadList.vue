<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';
import Title from '../../../services/title';
import DropZone from '../../../components/DropZone.vue';
import Heading from '../../../components/Heading.vue';
import LoadListTable from '../../../components/operation/LoadListTable.vue';
import useLoadList from '../../../composables/operation/useLoadList.js';
import useVoyage from '../../../composables/operation/useVoyage.js';
import env from '../../../config/env.js';

const route = useRoute();
const store = useStore();
const { setTitle } = Title();
const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));
const { loadList, totalContainer, totalDg, totalRefer, allLoadLists, process, quickAgentEntry, isLoading, errors } = useLoadList();
const { voyage, showVoyage } = useVoyage();
const isTooltipShowing = ref(false);
loadList.forceDuplicateStowage = 0;

loadList.voyage_id = route.params.voyageId;
loadList.filetype = (route.params.fileType == 'final') ? true : false;
watch(voyage, (value) => {
    if (value === null || value === undefined) {
        setTitle('TDR Upload');
    } else {
        setTitle(`${value.voyage} - TDR Upload`);
    }
});

watch(dropZoneFile, (value) => {
    if (value !== null && value !== undefined) {
        loadList.file = value;
    }
});

function downloadSampleTdrFile() {
  let baseUrl = env.BASE_API_URL;
  window.location.href = baseUrl+'samples/TDR Sample.xlsx';
}

onMounted(() => {
    showVoyage(route.params.voyageId);
});
</script>

<template>
    <Heading :label="`${route.params.fileType.charAt(0).toUpperCase() + route.params.fileType.slice(1)} TDR Upload: #${voyage?.voyage}`" type="none"></Heading>
  <div>
    <button @click="downloadSampleTdrFile" @mouseenter="isTooltipShowing = true" @mouseleave="isTooltipShowing = false" title="All red coloured columns are mandatory" class="px-4 py-2 float-right text-sm mb-2 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Download Sample File</button>
  </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <DropZone></DropZone>

        <div class="flex items-center justify-center !hidden">
          <div v-if="loadList.filetype==true" class="flex items-center">
            <input type="checkbox" disabled v-model="loadList.filetype" id="filetype" value="final" class="label-item-input" />
            <label for="filetype" class="pl-2">Final TDR</label>
          </div>
         
        </div>
        <form v-if="dropZoneFile !== null" @submit.prevent="process(loadList)" class="flex flex-col items-center justify-center w-full gap-2 mt-2" enctype="multipart/form-data">
            <input v-model.number="loadList.start" type="number" required min="1" placeholder="Line Starts From" class="label-item-input" />
            
            <div class="flex items-center" v-if="errors?.[1] == 'duplicate_stowage'">
              <input v-model="loadList.forceDuplicateStowage" type="checkbox" id="forceDuplicateStowage" value="1" class="label-item-input" />
              <label for="forceDuplicateStowage" class="pl-2">I am sure to upload duplicate stowages.</label>
            </div>
            <button :disabled="isLoading" class="flex items-center justify-center px-3 py-1 text-gray-100 bg-purple-600 rounded">
                <span v-if="!isLoading">Process</span>
                <svg v-else xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="w-6 h-6 iconify iconify--eos-icons" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                    <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z" opacity=".5" fill="currentColor" />
                    <path d="M20 12h2A10 10 0 0 0 12 2v2a8 8 0 0 1 8 8z" fill="currentColor">
                        <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite" />
                    </path>
                </svg>
            </button>

        
          <ul class="text-red-400" v-if="errors?.[1] == 'finalTdrError'">
            <li class="ml-5 font-bold">{{ errors?.[0] }}</li>
            <li class="bold ml-10 font-semibold" v-for="(row,index,keys) in errors?.[2]">
              {{ index }}. {{ row }}
            </li>
            
          </ul> 
          <ul class="text-red-400" v-if="errors?.[1] == 'incomplete'">
            <li class="ml-5 font-bold">{{ errors?.[0] }}</li>
            <li class="ml-5 font-bold">The following containers don't have required data: </li>
            <li class="bold ml-10 font-semibold" v-for="(row,index,keys) in errors?.[2]"><span v-if="Number.isInteger(key)">{{ keys+1 }}.</span> {{ row.container }}</li>
          </ul>

          <ul class="text-red-400" v-if="errors?.[1] == 'Invalid_Stowage'">
            <li class="ml-5 font-bold">{{ errors?.[0] }}</li>
            <li class="ml-5 font-bold text-red-700">The following containers have invalid stowage position. </li>
            <!-- <li>{{ errors?.[2] }}</li> -->
            <li class="bold ml-10 font-semibold" v-for="(row,index,keys) in errors?.[2].faulty"><span v-if="Number.isInteger(key)">{{ keys+1 }}.</span> {{ row }}</li>

            <li class="ml-5 font-bold text-red-700" v-if="(errors?.[2].null_stows.length>0)">The following containers don't have any stowage information. </li>
            <li class="bold ml-10 font-semibold" v-for="(row,index,keys) in errors?.[2].null_stows"><span v-if="Number.isInteger(key)">{{ keys+1 }}.</span> {{ row }}</li>
          </ul>

          <ul class="text-red-400" v-if="errors?.[1] == 'pol_pod_mismatch'">
            <li class="ml-5 font-bold">{{ errors?.[0] }}</li>
            <li class="bold ml-10 font-semibold">{{ errors?.[2] }}</li>
          </ul>

          <ul class="text-red-400" v-if="errors?.[1] == 'duplicate_stowage'">
            <li class="ml-5 font-bold">{{ errors?.[0] }}</li>
            <li class="bold ml-10 font-semibold">{{ errors?.[2] }}</li>
          </ul>

          <ul class="text-red-400" v-if="errors?.[1] == 'no_customer' || errors?.[1] == 'no_agent'">
            <li class="ml-5 font-bold">{{ errors?.[0] }}</li>
            <li class="ml-5 font-bold">Below are the missing authority's code: </li>
            <li class="bold ml-10 font-semibold" v-for="(row,index,keys) in errors?.[2]">
              <span v-if="Number.isInteger(key)">{{ keys+1 }}.</span>
              <span v-else>{{ index+1 }}.</span>
              {{ row }}</li>
            <li class="flex items-center justify-center my-2" v-if="errors?.[1] == 'no_agent'">
              <div class="flex items-center justify-center px-3 py-1 text-gray-100 bg-purple-600 rounded cursor-pointer hover:bg-purple-700" @click="quickAgentEntry(errors?.[2])">
                <span>Quick Entry</span>
              </div>
            </li>
          </ul>

          <ul class="text-indigo-400 flex justify-center items-center" v-if="totalContainer > 0">
            <li class="ml-5 font-bold">Total Containers: {{ totalContainer }}</li>
            <li class="ml-5 font-bold">Total DG: {{ totalDg }}</li>
            <li class="ml-5 font-bold">Total REFER: {{ totalRefer }}</li>
          </ul>
        </form>
    </div>
    <LoadListTable :data="allLoadLists"></LoadListTable>
</template>

<style lang="postcss" scoped>
.label-item-input {
    @apply block text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}
.tooltip {
  @apply absolute px-2 py-1 text-gray-200 bg-gray-700 rounded dark:bg-gray-600 z-50;
}
</style>