<script setup>
import {onMounted, watchEffect} from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useMaterial from "../../../composables/scm/useMaterial";
import Title from "../../../services/title";
import Paginate from '../../../components/utils/paginate.vue';
import useTableExportExcel from "../../../services/tableExportExcel";
const { tableToExcel } = useTableExportExcel();

const { materials, getMaterials, deleteMaterial, isLoading } = useMaterial();

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { setTitle } = Title();

setTitle('Materials');

onMounted(() => {
  watchEffect(() => {
    getMaterials(props.page, true);
  });
});
</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Materials</h2>
    <div class="flex">
      <button v-if="materials" type="button" @click="tableToExcel('material-list','Material List')" class="w-68 mt-5 flex items-center justify-center px-2 py-2 text-sm font-medium leading-2 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-500 hover:bg-gray-500 focus:outline-none focus:shadow-outline-purple">
            <span>Download Excel</span>
      </button>
      <router-link :to="{ name: 'scm.material.create' }" class="w-12 ml-5 mt-5 flex items-center justify-center gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-[#0F6B61]  rounded-lg active:bg-[#0F6B61]  hover:bg-[#0F6B90] focus:outline-none focus:shadow-outline-purple">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
      </router-link>
    </div>
  </div>
  <!-- Table -->
  <div class="w-full overflow-hidden">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">SL. </th>
          <th class="px-4 py-3">Material Name</th>
          <th class="px-4 py-3">Material Category</th>
          <th class="px-4 py-3">Unit</th>
          <th class="px-4 py-3 w-28">Min. Stock</th>
<!--          <th class="px-4 py-3">Is Consumable?</th>-->
          <th class="px-4 py-3 text-center">Actions</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(material,index) in (materials?.data ? materials?.data : materials)" :key="material.id">
          <td class="px-4 py-3 text-sm">{{ materials?.from + index }}</td>
          <td class="px-4 py-3 text-sm">{{ material.name }}</td>
          <td class="px-4 py-3 text-sm">{{ material?.category?.name }}</td>
          <td class="px-4 py-3 text-sm">{{ material.unit }}</td>
          <td class="px-4 py-3 text-sm">{{ material.min_stock }}</td>
<!--          <td class="px-4 py-3 text-sm">-->
<!--            <span v-if="material.is_consumable == 1" class="px-2 py-1 font-bold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Active</span>-->
<!--            <span v-else class="px-2 py-1 font-bold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">Inactive</span>  -->
<!--          </td>-->
          <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
            <action-button :action="'edit'" :to="{ name: 'scm.material.edit', params: { materialId: material.id } }"></action-button>
            <action-button @click="deleteMaterial(material.id)" :action="'delete'"></action-button>
            <!-- <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: material.subject_type,subject_id: material.id } }"></action-button> -->
          </td>
        </tr>
        </tbody>
        <tfoot v-if="!(materials?.data ? materials?.data.length : materials?.length)" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="5">Loading...</td>
        </tr>
        <tr v-else-if="!(materials?.data ? materials?.data.length : materials?.length)">
          <td colspan="5">No material found.</td>
        </tr>
        </tfoot>
      </table>

      <table class="w-full whitespace-no-wrap hidden" id="material-list">
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">ID. </th>
          <th class="px-4 py-3">Material Name</th>
          <th class="px-4 py-3">Material Category</th>
          <th class="px-4 py-3">Unit</th>
          <th class="px-4 py-3 w-28">Min. Stock</th>
          <th class="px-4 py-3 w-28">Opening Stock</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="material in (materials?.data ? materials?.data : materials)" :key="material.id">
          <td class="px-4 py-3 text-sm">{{ material?.id }}</td>
          <td class="px-4 py-3 text-sm">{{ material.name }}</td>
          <td class="px-4 py-3 text-sm">{{ material?.category?.name }}</td>
          <td class="px-4 py-3 text-sm">{{ material.unit }}</td>
          <td class="px-4 py-3 text-sm">{{ material.min_stock }}</td>
          <td></td>
        </tr>
        </tbody>
        <tfoot v-if="!(materials?.data ? materials?.data.length : materials?.length)" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="5">Loading...</td>
        </tr>
        <tr v-else-if="!(materials?.data ? materials?.data.length : materials?.length)">
          <td colspan="5">No material found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
    <Paginate :data="materials" to="scm.material.index" :page="page"></Paginate>
  </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
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
}

.input-group {
  @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
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
</style>