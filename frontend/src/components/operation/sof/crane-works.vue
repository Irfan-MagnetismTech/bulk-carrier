
<script setup>
import {watch, toRefs} from 'vue';
import Error from "../../Error.vue";
import moment from 'moment';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import Editor from '@tinymce/tinymce-vue';
import useVoyageSof from "../../../composables/operation/sof/useVoyageSof";
import {useRoute} from "vue-router";

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
   craneworks: {
    default: () => [],
  },
});

function addMoreCraneTerminalWork() {
  let obj = {
    description: "",
    commence: "",
    complete: "",
    break_down: "",
    meal: "",
    other: "",
    total: "",
    gross_rate: "",
		net_rate: "",
		berth_productivity: ""
  };

  props.craneworks.push(obj);
}

function removeItem(index) {
  props.craneworks.splice(index, 1);
}

const { data, uniqueServicePorts, voyageSofListsProp } = toRefs(props);

function openDgListModal() {
  isVoyageSofModalOpen.value = 1;
  voyageSof.value = {};
}

function updateDgList(voyageSofId) {
  showVoyageSofList(voyageSofId);
}

function closeDgListModal() {
  isVoyageSofModalOpen.value = 0;
}

watch(voyageSofListsProp, (newVal, oldVal) => {
  voyageSofLists.value = newVal;
});

</script>

<template>
  <table class="w-full whitespace-no-wrap mt-2 border-collapse">
    <thead>
    <tr>
      <th colspan="9" class="text-center font-bold bg-red-500 text-white">
        Crane / Terminal Works
      </th>
      <th class="bg-red-500 flex justify-center items-center" style="border: 0px solid">
        <button class="bg-red-500 text-white font-bold rounded-full" @click="addMoreCraneTerminalWork">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
        </button>
      </th>
    </tr>
    <tr>
      <th>CRANE WORKS</th>
      <th>COMMENCE</th>
      <th>COMPLETE</th>
      <th>BREAK</th>
      <th>MEAL</th>
      <th>OTHER</th>
      <th>GROSS CRANE RATE</th>
      <th>NET CRANE RATE</th>
      <th>BERTH PRODUCTIVITY</th>
      <th class="px-4 py-3 text-center">Actions</th>
    </tr>
    </thead>
    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
    <tr class="text-gray-700 dark:text-gray-400" v-for="(crane_terminal_work,index) in props.craneworks" :key="index">
      <td>
        <input type="text" v-model="crane_terminal_work.description" placeholder="Title " class="w-full border-none" />
      </td>
      <td>
        <input type="datetime-local" v-model="crane_terminal_work.commence" class="w-full border-none" />
      </td>
      <td>
        <input type="datetime-local" v-model="crane_terminal_work.complete" class="w-full border-none" />
      </td>
      <td>
        <input type="text" v-model="crane_terminal_work.break_down" placeholder="BREAK" class="w-full border-none" />
      </td>
      <td>
        <input type="text" v-model="crane_terminal_work.meal" placeholder="MEAL" class="w-full border-none" />
      </td>
      <td>
        <input type="text" v-model="crane_terminal_work.other " placeholder="OTHER" class="w-full border-none" />
      </td>
      <td>
        <input type="text" v-model="crane_terminal_work.gross_rate" placeholder="GCR" class="w-full border-none" />
      </td>
      <td>
        <input type="text" v-model="crane_terminal_work.net_rate" placeholder="NCR" class="w-full border-none" />
      </td>
      <td>
        <input type="text" v-model="crane_terminal_work.berth_productivity" placeholder="PRODUCTIVITY" class="w-full border-none" />
      </td>
      <td class="text-center">
        <button class="bg-red-500 text-white font-bold rounded-full" @click="removeItem(index)">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </button>
      </td>
    </tr>
    </tbody>
  </table>
  <div v-show="isVoyageSofModalOpen" class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="storeVoyageSofList(voyageSof,voyageId,'dg')">
    <div class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
      <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
      <header class="flex justify-end">
        <button type="button" class="inline-flex items-center justify-center w-6 h-6 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700" aria-label="close" @click="closeDgListModal">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
            <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
          </svg>
        </button>
      </header>
      <!-- Modal body -->
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Sector <span class="text-red-500">*</span></span>
          <select v-model="voyageSof.sector" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" required>
            <option value="">Select Sector</option>
            <option v-for="port in uniqueServicePorts" :value="port">{{ port }}</option>
          </select>
          <Error v-if="errors?.sector" :errors="errors.sector" />
        </label>
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Date <span class="text-red-500">*</span></span>
          <input type="date" v-model="voyageSof.incident_date" required  class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.incident_date" :errors="errors.incident_date" />
        </label>
        <input type="hidden" v-model="voyageSof.id">
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Container No.<span class="text-red-500">*</span></span>
          <input type="text" v-model="voyageSof.container" required placeholder="Container No" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.container" :errors="errors.container" />
        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Class<span class="text-red-500">*</span></span>
          <input type="text" v-model="voyageSof.class" required placeholder="Class" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.class" :errors="errors.class" />
        </label>
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">UN No. <span class="text-red-500">*</span></span>
          <input type="text" v-model="voyageSof.un" required placeholder="Un No"  class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.un" :errors="errors.un" />
        </label>
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Stow<span class="text-red-500">*</span></span>
          <input type="text" v-model="voyageSof.stow" required placeholder="Stow" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.stow" :errors="errors.stow" />
        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Approval Code<span class="text-red-500">*</span></span>
          <input type="text" v-model="voyageSof.approval_code" required placeholder="Approval Code" class="block w-2/6 mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.approval_code" :errors="errors.approval_code" />
        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Remarks</span>
        <editor v-model="voyageSof.remarks" api-key="wljvu7gtfjb8h5ou2rcxw8d5tykej98zy10x8ot83jclsm3o"/>
          <Error v-if="errors?.remarks" :errors="errors.remarks" />
        </label>
      </div>
      <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800">
        <button type="button" @click="closeDgListModal" style="color: #6b6e6c" class="w-full px-5 py-3 text-sm font-medium leading-5 text-gray-700 border border-gray-300 rounded-lg dark:text-gray-600 sm:px-4 sm:py-2 sm:w-auto hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
          Cancel
        </button>
        <button type="submit" class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
          Submit
        </button>
      </footer>
    </div>
    </form>
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