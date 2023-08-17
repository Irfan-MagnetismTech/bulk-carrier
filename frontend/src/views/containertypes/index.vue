<script setup>
import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../components/buttons/ActionButton.vue';
import useContainerType from "../../composables/useContainerType";
import {watchEffect, ref} from "vue";
import Title from "../../services/title";
import useDebouncedRef from "../../composables/useDebouncedRef";
import {watch} from "vue";
import Paginate from "../../components/utils/paginate.vue";


const props = defineProps({
  page: {
    type: Number,
    default: 1
  },
});
const { setTitle } = Title();
setTitle('Container Types');
const { containerTypes, containerTues, containerGroup, getContainerType, containerData, getContainerData, deleteContainerType, isLoading } = useContainerType();

// Code for global search starts here
const columns = ["iso_code", "group", "container_length", "container_height", "tues", "description"];
const searchKey = useDebouncedRef('', 800);
const table = "container_types";

watch(searchKey, newQuery => {
  getContainerType(props.page, columns, searchKey.value, table);
});
// Code for global search end here

function fetchContainerData(search, loading) {
  getContainerData(search);
}

const searchParams = ref({
  iso_code_name: null,
  iso_code: '',
  group_name: null,
  group: '',
  tues_name: null,
  tues: '',
});

function clearFormData(){
  searchParams.value = {
    iso_code_name: null,
    iso_code: '',
    group_name: null,
    group: '',
    tues_name: null,
    tues: '',
  };
}

onMounted(() => {
  watchEffect(() => {
    if(searchParams.value.iso_code_name){
      searchParams.value.iso_code = searchParams.value.iso_code_name.iso_code;
    }

    if(searchParams.value.group_name){
      searchParams.value.group = searchParams.value.group_name.group;
    }

    if(searchParams.value.tues_name){
      searchParams.value.tues = searchParams.value.tues_name.tues;
    }

    getContainerType(props.page, searchParams.value);
  });
});
</script>

<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Container Types</h2>
        <router-link :to="{ name: 'containertype.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
        </router-link>
    </div>

  <div class="w-full flex items-center justify-between mb-2 select-none">
    <fieldset class="w-full grid grid-cols-4 gap-1 px-2 pb-3 border border-gray-700 rounded dark:border-gray-400">
      <legend class="px-2 text-gray-700 uppercase dark:text-gray-300">Search Container</legend>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">ISO Code</label>
        <v-select placeholder="--Choose an option--" :options="containerData" @search="fetchContainerData" v-model="searchParams.iso_code_name" label="iso_code" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
        <input type="hidden" v-model="searchParams.iso_code">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Group</label>
        <v-select placeholder="--Choose an option--" :options="containerGroup" @search="fetchContainerData" v-model="searchParams.group_name" label="group" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
        <input type="hidden" v-model="searchParams.group">
      </div>
      <div>
        <label for="" class="text-xs" style="margin-left: .01rem">Tues</label>
        <v-select placeholder="--Choose an option--" :options="containerTues" @search="fetchContainerData" v-model="searchParams.tues_name" label="tues" class="block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"></v-select>
        <input type="hidden" v-model="searchParams.tues">
      </div>

      <div>
        <label for="">&nbsp;</label>
        <button @click="clearFormData" class="w-full flex items-center justify-center px-2 py-2 mt-1 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>Clear</span>
        </button>
      </div>
    </fieldset>
  </div>

<!--  <div class="flex items-center justify-between mb-2 select-none">-->
<!--    <span class="w-full text-xs font-medium text-gray-500 whitespace-no-wrap">Showing {{ containerTypes?.from }}-{{ containerTypes?.to }} of {{ containerTypes?.total }}</span>-->
<!--    &lt;!&ndash; Search &ndash;&gt;-->
<!--    <div class="relative w-full">-->
<!--      <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-0 w-5 h-5 mr-2 text-gray-500 bottom-2" viewBox="0 0 20 20" fill="currentColor">-->
<!--        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />-->
<!--      </svg>-->
<!--      <input type="text"  v-model.trim="searchKey" placeholder="Search..." class="search" />-->
<!--    </div>-->
<!--  </div>-->
    <!-- Table -->
    <div class="w-full overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">ISO Code</th>
                        <th class="px-4 py-3">Dimension</th>
                        <th class="px-4 py-3">Group</th>
                        <th class="px-4 py-3">Tues</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400" v-for="(cType,index) in (containerTypes?.data ? containerTypes?.data : containerTypes)" :key="cType.id">
                        <td class="px-4 py-3 text-sm">{{ (customers?.from) ? customers?.from + index : index+1 }}</td>
                        <td class="px-4 py-3 text-sm">{{ cType.iso_code }}</td>
                        <td class="px-4 py-3 text-sm">{{ cType.container_length }} x {{cType.container_height}}</td>
                        <td class="px-4 py-3 text-sm">{{ cType.group }}</td>
                        <td class="px-4 py-3 text-sm">{{ cType.tues }}</td>
                        <td class="px-4 py-3 text-sm">{{ cType.description }}</td>
                        <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
                            <action-button :action="'edit'" :to="{ name: 'containertype.edit', params: { containerTypeId: cType.id } }"></action-button>
                            <action-button @click="deleteContainerType(cType.id)" :action="'delete'"></action-button>
                          <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: cType.subject_type,subject_id: cType.id } }"></action-button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!(containerTypes?.data ? containerTypes?.data?.length : containerTypes?.length)" class="bg-white dark:bg-gray-800">
                <tr v-if="isLoading">
                  <td colspan="8">Loading...</td>
                </tr>
                <tr v-else-if="!(containerTypes?.data ? containerTypes?.data?.length : containerTypes?.length)">
                  <td colspan="8">No container type found.</td>
                </tr>
                </tfoot>
            </table>
        </div>
      <!-- Pagination -->
      <Paginate :data="containerTypes" to="commercial.customers.index" :page="page"></Paginate>
    </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
  .paginate-btn {
    @apply px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple;
  }
  .paginate-active {
    @apply text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600;
  }
  .tab {
    @apply p-2.5 text-xs;
  }
  thead tr {
    @apply font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
  }
  th {
    @apply tab text-center;
  }
  tbody {
    @apply bg-white divide-y dark:divide-gray-700 dark:bg-gray-800;
  }
  tbody tr {
    @apply text-gray-700 dark:text-gray-400;
  }
  tbody tr td {
    @apply tab text-center;
  }
  tfoot td {
    @apply tab text-center;
  }

  table, th,td{
    @apply border border-collapse;
  }
  .search-result {
    @apply px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-300;
  }
  .search {
    @apply float-right  pr-10 text-sm border border-gray-300 rounded dark:bg-gray-800 dark:text-gray-200 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray dark:border-0;
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