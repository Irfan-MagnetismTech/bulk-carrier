
<script setup>
import {watch, toRefs, ref, computed } from 'vue';
import { useStore } from "vuex";
import Error from "../../Error.vue";
import moment from 'moment';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Editor from '@tinymce/tinymce-vue';
import useVoyageSof from "../../../composables/operation/sof/useVoyageSof";
import {useRoute} from "vue-router";
import DropZone from "../../../components/DropZone.vue";
import env from "../../../config/env.js";

const route = useRoute();
const voyageId = route.params.voyageId;
const { voyageSof, voyageSofLists, storeVoyageSofList, showVoyageSofList, deleteVoyageSofList, isVoyageSofModalOpen, isLoading, errors } = useVoyageSof();

const props = defineProps({
    data: {
        default: () => [],
    },
   uniqueServicePorts: {
    default: () => [],
   },
   excelFile: {
    default: () => [],
  },
});

const { data, uniqueServicePorts, voyageSofListsProp } = toRefs(props);
const store = useStore();
const dropZoneFile = ref(computed(() => store.getters.getDropZoneFile));

watch(dropZoneFile, (value) => {
  console.log("dropZoneFile", value);
  if (value !== null && value !== undefined) {
     props.excelFile.file = value;
  }
});

function downloadSampleFile() {
  let baseUrl = env.BASE_API_URL;
  window.location.href = baseUrl + "samples/Statement of Fact.xlsx";
}

watch(voyageSofListsProp, (newVal, oldVal) => {
  voyageSofLists.value = newVal;
});

</script>

<template>
  <div>
        <button
          @click="downloadSampleFile"
          @mouseenter="isTooltipShowing = true"
          @mouseleave="isTooltipShowing = false"
          title="All red coloured columns are mandatory"
          class="focus:outline-none float-right mb-2 rounded-lg border border-transparent bg-purple-600 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-purple-700 focus:shadow-outline-purple active:bg-purple-600"
        >
          Download Sample File
        </button>
      </div>
      <div class="mt-2">
        <DropZone></DropZone>
      </div>
</template>

<style lang="postcss" scoped>
@tailwind components;

@layer components {
    th {
        @apply px-3 py-2;
    }
    td {
        @apply px-3 py-2 text-xs border-r;
    }
    thead tr {
        @apply text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
    }
    tbody {
        @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
    }

  td {
    @apply px-3 py-2 text-xs;
  }
  thead tr {
    @apply text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  tbody {
    @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
  }

  table th,tr,td{
    border: 1px solid gray;
  }
  #modal{
    max-width: 50rem !important;
  }
}
</style>