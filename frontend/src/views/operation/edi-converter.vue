<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';
import Title from '../../services/title';
import DropZone from '../../components/DropZone.vue';
import Heading from '../../components/Heading.vue';
import useEdiConverter from "../../composables/operation/useEdiConverter";

const route = useRoute();
const store = useStore();
const { setTitle } = Title();
const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));
const { ediFile, process, isLoading, errors } = useEdiConverter();
const isTooltipShowing = ref(false);

setTitle('EDI Converter');

watch(dropZoneFile, (value) => {
    if (value !== null && value !== undefined) {
      ediFile.file = value;
    }
});

onMounted(() => {

});
</script>

<template>
    <Heading :label="'EDI Converter'" type="none"></Heading>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <DropZone></DropZone>
        <form v-if="dropZoneFile !== null" @submit.prevent="process(ediFile)" class="flex flex-col items-center justify-center w-full gap-2 mt-2" enctype="multipart/form-data">
            <button :disabled="isLoading" class="flex items-center justify-center px-3 py-1 mt-2 text-gray-100 bg-purple-600 rounded">
                <span v-if="!isLoading">Convert</span>
                <svg v-else xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="w-6 h-6 iconify iconify--eos-icons" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                    <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z" opacity=".5" fill="currentColor" />
                    <path d="M20 12h2A10 10 0 0 0 12 2v2a8 8 0 0 1 8 8z" fill="currentColor">
                        <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite" />
                    </path>
                </svg>
            </button>
        </form>

        <ul class="text-red-400" v-if="errors?.[1] == 'ISOError'">
            <li class="ml-5 font-bold">{{ errors?.[0] }}</li>
            <li class="bold ml-10 font-semibold" v-for="(row,index,keys) in errors?.[2]">
              {{ row }}
            </li>
            
          </ul>
    </div>
</template>

<style lang="postcss" scoped>
.label-item-input {
    @apply block text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
}
.tooltip {
  @apply absolute px-2 py-1 text-gray-200 bg-gray-700 rounded dark:bg-gray-600 z-50;
}
</style>