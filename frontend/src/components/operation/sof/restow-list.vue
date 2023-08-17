
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
  voyageSofListsProp: {
    default: () => [],
  },
});

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
      <th colspan="13" class="text-center font-bold bg-red-500 text-white">
        Restow List
      </th>
      <th class="bg-red-500 flex justify-center items-center" style="border: 0px solid">
        <button class="bg-red-500 text-white font-bold rounded-full" @click="openDgListModal">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
        </button>
      </th>
    </tr>
    <tr>
      <th>SL.</th>
      <th>Sector</th>
      <th>Stow</th>
      <th>Final Stow</th>
      <th>Pol</th>
      <th>Pod</th>
      <th>Container</th>
      <th>Weight</th>
      <th>Oper.</th>
      <th>Size Type</th>
      <th>Box Operator</th>
      <th>Account</th>
      <th>Remarks</th>
      <th class="px-4 py-3 text-center">Actions</th>
    </tr>
    </thead>
    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
    <tr class="text-gray-700 dark:text-gray-400" v-for="(listData,index) in voyageSofLists" :key="index">
      <td>{{ index + 1 }}</td>
      <td>{{ listData?.sector }}</td>
      <td>{{ listData?.stow }}</td>
      <td>{{ listData?.final_stow }}</td>
      <td>{{ listData?.pol }}</td>
      <td>{{ listData?.pod }}</td>
      <td>{{ listData?.container }}</td>
      <td>{{ listData?.weight }}</td>
      <td>{{ listData?.oper }}</td>
      <td>{{ listData?.size_type }}</td>
      <td>{{ listData?.box_operator }}</td>
      <td>{{ listData?.account }}</td>
      <td v-html="listData?.remarks"></td>
      <td>
        <div class="flex items-center justify-center space-x-2 text-gray-600">
          <svg @click="updateDgList(listData?.id)" xmlns="http://www.w3.org/2000/svg" class="icn text-purple-500 dark:text-gray-400 dark:hover:text-purple-500 w-5 h-5 cursor-pointer" viewBox="0 0 20 20" fill="currentColor">
            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
          </svg>
          <action-button :action="'delete'" @click="deleteVoyageSofList(listData?.id,voyageId,'restow')"></action-button>
        </div>
      </td>
    </tr>
    </tbody>
    <tfoot v-if="!voyageSofLists?.length" class="bg-white dark:bg-gray-800">
    <tr v-if="isLoading">
      <td colspan="14" class="text-center">Loading...</td>
    </tr>
    <tr v-else-if="!voyageSofLists?.length">
      <td colspan="14" class="text-center">No restow list found.</td>
    </tr>
    </tfoot>
  </table>
  <div v-show="isVoyageSofModalOpen" class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center">
    <!-- Modal -->
    <form @submit.prevent="storeVoyageSofList(voyageSof,voyageId,'restow')">
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
            <option value="" selected>Select Sector</option>
            <option v-for="port in uniqueServicePorts" :value="port">{{ port }}</option>
          </select>
          <Error v-if="errors?.sector" :errors="errors.sector" />
        </label>
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Stow<span class="text-red-500">*</span></span>
          <input type="text" v-model="voyageSof.stow" required placeholder="Stow" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.stow" :errors="errors.stow" />
        </label>
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Final Stow<span class="text-red-500">*</span></span>
          <input type="text" v-model="voyageSof.final_stow" required placeholder="Stow" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.final_stow" :errors="errors.final_stow" />
        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Pol <span class="text-red-500">*</span></span>
          <select v-model="voyageSof.pol" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" required>
            <option value="" selected>Select Option</option>
            <option v-for="port in uniqueServicePorts" :value="port">{{ port }}</option>
          </select>
          <Error v-if="errors?.pol" :errors="errors.pol" />
        </label>
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Pod <span class="text-red-500">*</span></span>
          <select v-model="voyageSof.pod" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" required>
            <option value="" selected>Select Option</option>
            <option v-for="port in uniqueServicePorts" :value="port">{{ port }}</option>
          </select>
          <Error v-if="errors?.pod" :errors="errors.pod" />
        </label>
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Container No.<span class="text-red-500">*</span></span>
          <input type="text" v-model="voyageSof.container" required placeholder="Container" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.container" :errors="errors.container" />
        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Weight<span class="text-red-500">*</span></span>
          <input type="number" step=".01" v-model="voyageSof.weight" required placeholder="Weight" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.container" :errors="errors.weight" />
        </label>
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Oper<span class="text-red-500">*</span></span>
          <input type="text" v-model="voyageSof.oper" required placeholder="Oper" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.oper" :errors="errors.oper" />
        </label>
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Size Type<span class="text-red-500">*</span></span>
          <input type="text" v-model="voyageSof.size_type" required placeholder="Size Type" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.size_type" :errors="errors.size_type" />
        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Box Operator<span class="text-red-500">*</span></span>
          <input type="number" step=".01" v-model="voyageSof.weight" required placeholder="Weight" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.container" :errors="errors.weight" />
        </label>
        <label class="block w-full mt-3 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Account<span class="text-red-500">*</span></span>
          <input type="text" v-model="voyageSof.account" required placeholder="Account" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
          <Error v-if="errors?.account" :errors="errors.account" />
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