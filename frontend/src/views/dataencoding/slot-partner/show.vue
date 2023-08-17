
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Title from '../../../services/title';
import useSlotPartner from '../../../composables/dataencoding/useSlotPartner';
import Heading from '../../../components/Heading.vue';

const route = useRoute();
const partnerId = route.params.partnerId;
const { partner, isLoading, showSlotPartner } = useSlotPartner();
const { setTitle } = Title();

setTitle('Slot Partner Details');

onMounted(() => {
    showSlotPartner(partnerId);
});
</script>
<template>
    <!-- Heading -->
    <heading label="Slot Partner Details" type="list" :to="{ name: 'dataencoding.slot-partner.index' }"></heading>
  <table class="w-full whitespace-no-wrap">
    <thead v-once>
    <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
      <th class="px-4 py-3">Code</th>
      <th class="px-4 py-3">Name</th>
      <th class="px-4 py-3">Principal Office Address</th>
      <th class="px-4 py-3">Country</th>
      <th class="px-4 py-3">Type</th>
    </tr>
    </thead>
    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
    <tr class="text-gray-700 text-center dark:text-gray-400">
      <td class="px-4 py-3 text-sm">{{ partner?.code }}</td>
      <td class="px-4 py-3 text-sm">{{ partner?.name }}</td>
      <td class="px-4 py-3 text-sm">{{ partner?.principal_office_address }}</td>
      <td class="px-4 py-3 text-sm">{{ partner?.country }}</td>
      <td class="px-4 py-3 text-sm">{{ partner?.type }}</td>
    </tr>
    </tbody>
  </table>
  <div v-if="isLoading" class="px-4 py-3 mb-8 bg-white divide-y rounded-lg shadow-md dark:bg-gray-800 text-center">
    <span class="text-base text-gray-600 dark:text-gray-400">Loading...</span>
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