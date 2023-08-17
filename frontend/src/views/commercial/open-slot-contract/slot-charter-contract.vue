<template>
  <div class="w-full my-2 overflow-hidden border shadow-xs dark:border-0">

    <h1 class="py-1 font-bold text-white bg-green-500 text-center uppercase">Slot Charter Contract</h1>
    <div class="w-full">
      <table class="w-full whitespace-no-wrap">
        <thead>
        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
          <td></td>
          <td class="uppercase font-bold">Open Slot</td>
          <td></td>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        </tbody>
        <tfoot>
        </tfoot>
      </table>
    </div>
  </div>
  <div class="w-full my-2 overflow-hidden border shadow-xs dark:border-0">
    <div class="w-full">
      <table class="w-full whitespace-no-wrap">
        <thead>
        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
          <td class="">[CID] Customer Name</td>
          <td>Slot Owner</td>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <tr>
          <td class="">[{{ contract?.customer?.customer_code }}] {{ contract?.customer?.customer_name }}</td>
          <td>{{ contract?.customer?.slot_partner?.name }}-{{ contract?.customer?.slot_partner?.code }}</td>
        </tr>
        </tbody>
        <tfoot>
        </tfoot>
      </table>
    </div>
  </div>
  <div class="w-full my-2 overflow-hidden border shadow-xs dark:border-0">
    <div class="grid grid-cols-2 gap-2">
      <div class="w-full">
        <table class="w-full whitespace-no-wrap">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
            <td class="" colspan="7">Allocation</td>
          </tr>
          <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
            <td class="">Service</td>
            <td class="">Bound</td>
            <td class="">SEQ</td>
            <td class="">POL</td>
            <td class="">POD</td>
            <td class="">TEU</td>
            <td class="">WEIGHT</td>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr>
            <td class="">[{{ contract?.customer?.customer_code }}] {{ contract?.customer?.customer_name }}</td>
          </tr>
          </tbody>
          <tfoot>
          </tfoot>
        </table>
      </div>
      <div class="w-full">
        <table class="w-full whitespace-no-wrap">
          <thead>
          <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
            <td class="">[CID] Customer Name</td>
            <td>Slot Owner</td>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr>
            <td class="">[{{ contract?.customer?.customer_code }}] {{ contract?.customer?.customer_name }}</td>
            <td>{{ contract?.customer?.slot_partner?.name }}-{{ contract?.customer?.slot_partner?.code }}</td>
          </tr>
          </tbody>
          <tfoot>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted } from '@vue/runtime-core';
import { useRoute } from 'vue-router';
import {ref} from "vue";
import useOpenSlotContract from "../../../composables/commercial/useOpenSlotContract";

const route = useRoute();
const contractId = route.params.contractId;
const { contract, showContract } = useOpenSlotContract();

const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  openTab.value = tabNumber;
}

onMounted( () => {
    showContract(contractId);
});
</script>

<style lang="postcss" scoped>

/* table thead tr td border css */
table tr td {
  border: 1px solid #333434;
}

@tailwind components;
.table, .table th, .table td{
  @apply border border-collapse text-left pl-3 font-medium;
}

.text-xs {
  font-size: 0.7rem;
  line-height: 0.7rem;
}
@layer components {
  th {
    @apply p-10 border-r text-center;
  }

  tbody td,
  thead td,
  thead th {
    @apply px-1 py-1 text-xs border-r text-center;
  }
}
</style>
