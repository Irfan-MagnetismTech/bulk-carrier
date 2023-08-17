
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Title from '../../../services/title';
import useMloAgent from '../../../composables/operation/useMloAgent';
import Heading from '../../../components/Heading.vue';

const route = useRoute();
const agentId = route.params.agentId;
const { agent, isLoading, showAgent } = useMloAgent();
const { setTitle } = Title();

setTitle('MLO Agent Details');

onMounted(() => {
    showAgent(agentId);
});
</script>
<template>
    <!-- Heading -->
    <heading label="MLO Agent Details" type="list" :to="{ name: 'operation.mlo.agents.index' }"></heading>
    <div v-if="isLoading" class="px-4 py-3 mb-8 bg-white divide-y rounded-lg shadow-md dark:bg-gray-800 text-center">
        <span class="text-base text-gray-600 dark:text-gray-400">Loading...</span>
    </div>
  <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap table">
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">Field Name</th>
          <th class="px-4 py-3">Data</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Agent Name</td>
            <td class="text-sm">{{ agent?.name }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Agent Code</td>
            <td class="text-sm">{{ agent?.code }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Address</td>
            <td class="text-sm">{{ agent?.address ?? '---' }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Shipping Address</td>
            <td class="text-sm">{{ agent?.shipping_address ?? '---' }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">Primary Email</td>
            <td class="text-sm">{{ agent?.primary_email }}</td>
          </tr>
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">CC Email</td>
            <td class="text-sm">{{ agent?.cc_email ?? '---' }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style lang="postcss" scoped>
.table, .table th, .table td{
  @apply border border-collapse border-gray-400 text-center text-gray-700 font-medium;
}
.text-xs {
  font-size: 0.7rem;
  line-height: 0.7rem;
}
</style>