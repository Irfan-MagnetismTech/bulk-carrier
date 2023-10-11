<script setup>
import { onMounted } from '@vue/runtime-core';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import useMaterial from "../../../composables/supply-chain/useMaterial";
import Title from "../../../services/title";
import { ref } from "vue";
import { useFuse } from "@vueuse/integrations/useFuse";

const { materials, getMaterials, deleteMaterial, isLoading } = useMaterial();

const { setTitle } = Title();

setTitle('Materials');

onMounted(() => {
  getMaterials();
});
</script>

<template>
  <!-- Heading -->
  <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Material</h2>
    <router-link :to="{ name: 'supply-chain.material.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600  rounded-lg active:bg-purple-600  hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
    </router-link>
  </div>
  <!-- Table -->
  <div class="w-full overflow-hidden">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">SL. </th>
          <th class="px-4 py-3">Name</th>
          <th class="px-4 py-3">Short Code</th>
          <th class="px-4 py-3 text-center">Actions</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr class="text-gray-700 dark:text-gray-400 text-center" v-for="(material,index) in materials" :key="material.id">
          <td class="px-4 py-3 text-sm">{{ index + 1 }}</td>
          <td class="px-4 py-3 text-sm">{{ material.name }}</td>
          <td class="px-4 py-3 text-sm">{{ material.material_code }}</td>
          <td class="items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
            <action-button :action="'edit'" :to="{ name: 'supply-chain.material.edit', params: { materialId: material.id } }"></action-button>
            <action-button @click="deleteMaterial(material.id)" :action="'delete'"></action-button>
          </td>
        </tr>
        </tbody>
        <tfoot v-if="!materials?.length" class="bg-white dark:bg-gray-800">
        <tr v-if="isLoading">
          <td colspan="7">Loading...</td>
        </tr>
        <tr v-else-if="!materials?.length">
          <td colspan="7">No Material found.</td>
        </tr>
        </tfoot>
      </table>
    </div>
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