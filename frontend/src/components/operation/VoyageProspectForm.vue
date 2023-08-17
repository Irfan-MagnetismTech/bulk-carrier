<script setup>
import useContainerType from "../../composables/useContainerType";
import { useRoute } from 'vue-router';
import {onMounted} from "@vue/runtime-core";
import {watch} from "vue";

const props = defineProps({
  form: {
    required: false,
    default: {}
  },
  errors: { type: [Object, Array], required: false },
  page: {
    required: false,
    default: {},
  }
});

const route = useRoute();
const voyageId = route.params.voyageId;

function addProspect() {
  let obj = {
    quantity: '',
    iso: '',
    status: '',
    tues: '',
  };
  props.form.prospects.push(obj);
}

function removeSector(index){
  props.form.prospects.splice(index, 1);
}

const { containerTypes, getContainerIsoCode } = useContainerType();

function fetchOptions(search, loading) {
  getContainerIsoCode(search);
}

watch(() => props.form.prospects, (val) => {
  if (val?.length === 0) {
    addProspect();
  }
});

onMounted(() => {
  props.form.voyage_id = voyageId;
});

</script>

<template>
    <!-- South Sectors -->
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark:border-gray-400">
        <legend class="px-2 text-gray-700 dark:text-gray-300">Prospects <span class="text-red-500">*</span></legend>
        <table class="w-full whitespace-no-wrap" id="table">
            <thead>
                <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                    <th class="px-4 py-3 align-bottom">Iso Code</th>
                    <th class="px-4 py-3 align-bottom">Quantity</th>
                    <th class="px-4 py-3 align-bottom">Status</th>
                    <th class="px-4 py-3 align-bottom">Tues</th>
                    <th class="px-4 py-3 text-center align-bottom">Action</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <tr class="text-gray-700 dark:text-gray-400" v-for="(prospect, index) in form.prospects" :key="prospect.id">
                    <td class="px-1 py-1">
                      <table class="w-full">
                        <tr>
                          <td class="w-full" style="border: 0px">
                            <v-select v-model="form.prospects[index].iso" :id="'iso' + index" name="iso" @search="fetchOptions" value="iso_code" :options="containerTypes" label="iso_code" :reduce="containerTypes => containerTypes.iso_code" placeholder="Enter Iso Code" class="mt-1 placeholder-gray-600 w-full">
                              <template #search="{attributes, events}">
                                <input class="vs__search" :required="!form.prospects[index].iso" v-bind="attributes" v-on="events"/>
                              </template>
                            </v-select>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td class="px-1 py-1" style="width: 20%">
                      <input type="number" step=".01" placeholder="Quantity" v-model="form.prospects[index].quantity" required class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    </td>
                    <td class="px-1 py-1" style="width: 20%">
                      <select v-model="form.prospects[index].status" name="status" required class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                        <option value="">-- Select Status --</option>
                        <option value="FCL">FCL</option>
                        <option value="EMPTY">EMPTY</option>
                      </select>
                    </td>
                    <td class="px-1 py-1" style="width: 20%">
                      <input type="number" step=".01" placeholder="Tues" v-model="form.prospects[index].tues" required class="block w-full rounded xt-sm 1 dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input">
                    </td>
                    <td class="px-1 py-1 text-center">
                        <button v-if="index!=0" type="button" @click="removeSector(index)" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </button>
                      <button v-else type="button" @click="addProspect()" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                      </button>
                    </td>
                  <input type="hidden" v-model="form.voyage_id" >
                </tr>
            </tbody>
        </table>
    </fieldset>
</template>

<style lang="postcss" scoped>
#table, #table th, #table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 px-1
}

.input-group {
  @apply flex flex-col justify-center w-full h-full md:flex-row md:gap-2;
}

.label-group {
  @apply block w-full mt-3 text-sm;
}
.label-item-title {
  @apply text-gray-700 dark:text-gray-300;
}
.label-item-input {
  @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark:disabled:bg-gray-900;
}
.form-input {
  @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
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
</style>