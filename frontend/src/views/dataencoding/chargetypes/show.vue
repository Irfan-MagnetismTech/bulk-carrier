
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Title from '../../../services/title';
import useChargeType from '../../../composables/dataencoding/useChargeType';
import Heading from '../../../components/Heading.vue';

const route = useRoute();
const chargeTypeId = route.params.chargeTypeId;
const { chargeType, isLoading, showChargeType } = useChargeType();
const { setTitle } = Title();

setTitle('Revenue Head Details');

onMounted(() => {
    showChargeType(chargeTypeId);
});
</script>
<template>
    <!-- Heading -->
    <heading label="Revenue Head Details" type="list" :to="{ name: 'dataencoding.chargetypes.index' }"></heading>
    <div v-if="isLoading" class="px-4 py-3 mb-8 bg-white divide-y rounded-lg shadow-md dark:bg-gray-800 text-center">
        <span class="text-base text-gray-600 dark:text-gray-400">Loading...</span>
    </div>
    <div v-else class="px-8 py-3 mb-8 bg-white divide-y rounded-lg shadow-md dark:bg-gray-800 dark:divide-gray-600">
      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">Field Name</th>
            <th class="px-4 py-3">Data</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3">Code</td>
            <td class="px-4 py-3 text-sm">{{ chargeType.code }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3">Name</td>
            <td class="px-4 py-3 text-sm">{{ chargeType.name }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3">Category</td>
            <td class="px-4 py-3 text-sm">{{ chargeType.category }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3">Unit</td>
            <td class="px-4 py-3 text-sm">{{ chargeType.unit }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3">Remarks</td>
            <td class="px-4 py-3 text-sm">{{ chargeType.remarks }}</td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
</template>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
    .group {
        @apply flex flex-col items-center justify-center w-full md:flex-row md:gap-2;
    }
    .group-item {
        @apply md:block w-full mt-3 text-sm flex items-center justify-between;
    }
    .group-title {
        @apply font-semibold uppercase text-gray-600 dark:text-gray-200;
    }
    .group-content {
        @apply ml-1 dark:text-gray-300 md:text-xs;
    }
    .group-empty {
        @apply hidden md:block md:w-full;
    }
    .label-item-title {
        @apply text-gray-700 dark:text-gray-300;
    }
    .label-item-input {
        @apply block w-full mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray;
    }
}
</style>