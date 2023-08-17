<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2">
        <span>Voyage Expense Details:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ voyagPairName }}</span>
    </div>
    <!-- Table -->
  <div class="w-full">
    <div class="w-full">
      <div v-for="(entries, port) in expense">
        <h3 class="text-center text-2xl font-bold py-2 mb-5 text-gray-700 shadow-md">{{ port }} Port Expenses</h3>
        <table class="w-full whitespace-no-wrap mb-5">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
            <th class="text-left">Expense Head</th>
            <th>Invoice Date</th>
            <th>Invoice No</th>
            <th>Currency</th>
            <th>Amount</th>
            <th>
              Amount <br/>
              <small>BDT</small>
            </th>
            <th>Remarks</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800" v-for="(expenseGroup) in entries" :key="expenseGroup.id">

            <tr class="text-gray-700 dark:text-gray-400" v-if="expenseGroup?.entries?.length == 0 && expenseGroup?.subheads?.length == 0">
              <td class="text-left dark:text-gray-300 text-sm">{{ expenseGroup?.name }} </td>
              <td class="text-left dark:text-gray-300 text-sm"></td>
              <td class="text-left dark:text-gray-300 text-sm"></td>
              <td class="text-center dark:text-gray-300 text-sm"></td>
              <td class="text-center dark:text-gray-300 text-sm"></td>
              <td class="text-center dark:text-gray-300 text-sm"></td>
              <td class="text-left dark:text-gray-300 text-sm"></td>
            </tr>
            <tr class="text-gray-700 dark:text-gray-400" v-if="expenseGroup?.subheads?.length == 0" v-for="(entries) in expenseGroup.entries" >
              <td class="text-left dark:text-gray-300 text-sm">{{ entries?.head?.name }} {{ (entries?.heads) ? '-' : null }} {{ (entries?.heads) ? Object.values(entries?.heads)[0].name : null }}</td>
              <td class="text-left dark:text-gray-300 text-sm">{{ entries?.invoice?.date }}</td>
              <td class="text-left dark:text-gray-300 text-sm">{{ entries?.invoice?.invoice_number }}</td>
              <td class="text-center dark:text-gray-300 text-sm">{{ entries?.invoice?.currency }}</td>
              <td class="text-center dark:text-gray-300 text-sm">{{ entries?.amount }}</td>
              <td class="text-center dark:text-gray-300 text-sm">{{ entries?.amount_bdt }}</td>
              <td class="text-left dark:text-gray-300 text-sm">{{ entries?.remarks }}</td>
            </tr>
            <template v-else v-for="(subhead) in expenseGroup.subheads" >
              <tr class="text-gray-700 dark:text-gray-400" v-if="subhead?.entries?.length > 0 " v-for="(entries) in subhead.entries">
                <td class="text-left dark:text-gray-300 text-sm">{{ subhead?.name }} {{ (subhead?.heads) ? '-' : null }} {{ (subhead?.heads) ? Object.values(subhead?.heads)[0].name : null }}</td>
                
                <td class="text-left dark:text-gray-300 text-sm">{{ entries?.invoice?.date }}</td>
                <td class="text-left dark:text-gray-300 text-sm">{{ entries?.invoice?.invoice_number }}</td>
                <td class="text-center dark:text-gray-300 text-sm">{{ entries?.invoice?.currency }}</td>
                <td class="text-center dark:text-gray-300 text-sm">{{ entries?.amount }}</td>
                <td class="text-center dark:text-gray-300 text-sm">{{ entries?.amount_bdt }}</td>
                <td class="text-left dark:text-gray-300 text-sm">{{ entries?.remarks }}</td>
              </tr>
              <tr class="text-gray-700 dark:text-gray-400" v-else>
                <td class="text-left dark:text-gray-300 text-sm">{{ subhead?.name }} {{ (subhead?.heads) ? '-' : null }} {{ (subhead?.heads) ? Object.values(subhead?.heads)[0].name : null }}</td>

                <td class="text-left dark:text-gray-300 text-sm"></td>
                <td class="text-left dark:text-gray-300 text-sm"></td>
                <td class="text-left dark:text-gray-300 text-sm"></td>
                <td class="text-left dark:text-gray-300 text-sm"></td>
                <td class="text-left dark:text-gray-300 text-sm"></td>
                <td class="text-left dark:text-gray-300 text-sm"></td>

              </tr>
            </template>
            
          </tbody>
        </table>
      </div>
    </div>

    <div class="w-full">
      <div v-for="(expenseGroup, index) in globalExpense">
        <h3 class="text-center text-2xl font-bold py-2 mb-5 text-gray-700 shadow-md">{{ expenseGroup.name }} - Expenses</h3>
        <table class="w-full whitespace-no-wrap mb-5">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
            <th class="text-left">Expense Head</th>
            <th>Invoice Date</th>
            <th>Invoice No</th>
            <th>Currency</th>
            <th>Amount</th>
            <th>
              Amount <br/>
              <small>BDT</small>
            </th>
            <th>Remarks</th>
          </tr>
          </thead>
          <tbody>
            <template v-if="expenseGroup?.subheads?.length == 0">
              <template v-if="expenseGroup?.entries?.length > 0">
                <tr class="text-gray-700 dark:text-gray-400" v-for="(entries) in expenseGroup.entries" :key="entries.id">
                  <td class="text-left dark:text-gray-300 text-sm">{{ expenseGroup?.name }}</td>
                  <td class="text-left dark:text-gray-300 text-sm">{{ entries?.invoice?.date }}</td>
                  <td class="text-left dark:text-gray-300 text-sm">{{ entries?.invoice?.invoice_number }}</td>
                  <td class="text-center dark:text-gray-300 text-sm">{{ entries?.invoice?.currency }}</td>
                  <td class="text-center dark:text-gray-300 text-sm">{{ entries }}</td>
                  <!-- <td class="text-center dark:text-gray-300 text-sm">{{ $helper.numberFormat(entries?.amount) ?? '0.00' }}</td> -->
                  <td class="text-center dark:text-gray-300 text-sm">{{ $helper.numberFormat(entries?.amount_bdt) ?? '0.00' }}</td>
                  <td class="text-left dark:text-gray-300 text-sm">{{ entries?.remarks }}</td>
                </tr>
              </template>
              <template v-else>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-left dark:text-gray-300 text-sm">{{ expenseGroup?.name }}</td>
                      <td class="text-left dark:text-gray-300 text-sm"></td>
                      <td class="text-left dark:text-gray-300 text-sm"></td>
                      <td class="text-left dark:text-gray-300 text-sm"></td>
                      <td class="text-left dark:text-gray-300 text-sm"></td>
                      <td class="text-left dark:text-gray-300 text-sm"></td>
                      <td class="text-left dark:text-gray-300 text-sm"></td>
                    </tr>
              </template>
            </template>
            <template v-else>
                <template v-for="subhead in expenseGroup?.subheads" :key="subhead.id">
                  <template v-if="subhead?.entries?.length > 0">
                    <tr class="text-gray-700 dark:text-gray-400" v-for="(entry) in subhead.entries" :key="entry.id">
                      <td class="text-left dark:text-gray-300 text-sm">{{ subhead?.name }}</td>
                      <td class="text-left dark:text-gray-300 text-sm">{{ moment(entry?.invoice?.date).format('DD/MM/YYYY') }}</td>
                      <td class="text-left dark:text-gray-300 text-sm">{{ entry?.invoice?.invoice_number }}</td>
                      <td class="text-left dark:text-gray-300 text-sm">{{ entry?.invoice?.currency }}</td>
                      <td class="text-left dark:text-gray-300 text-sm">{{ $helper.numberFormat(entry.amount) ?? '0.00' }}</td>
                      <!-- <td class="text-left dark:text-gray-300 text-sm">{{ $helper.numberFormat(entry?.invoice?.total) ?? '0.00' }}</td> -->
                      <td class="text-left dark:text-gray-300 text-sm">{{ $helper.numberFormat(entry?.amount_bdt) ?? '0.00' }}</td>
                      <td class="text-left dark:text-gray-300 text-sm">{{ entry?.remarks }}</td>
                    </tr>
                  </template>
                  <template v-else>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-left dark:text-gray-300 text-sm">{{ subhead?.name }}</td>
                      <td class="text-left dark:text-gray-300 text-sm"></td>
                      <td class="text-left dark:text-gray-300 text-sm"></td>
                      <td class="text-left dark:text-gray-300 text-sm"></td>
                      <td class="text-left dark:text-gray-300 text-sm"></td>
                      <td class="text-left dark:text-gray-300 text-sm"></td>
                      <td class="text-left dark:text-gray-300 text-sm"></td>
                    </tr>
                  </template>


                </template>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted } from '@vue/runtime-core';
import moment from 'moment';
import { useRoute } from 'vue-router';
import useVoyageExpense from "../../../composables/finance/useVoyageExpense";
import useHelper from "../../../composables/useHelper";
import Title from '../../../services/title';


const $helper = new useHelper();
const route = useRoute();
const voyageId = route.params.pairId;
const { errors, voyagPairName, expense, globalExpense, showVoyageExpense } = useVoyageExpense();
const { setTitle } = Title();

setTitle('Voyage Expense Details');

onMounted(() => {
    showVoyageExpense(voyageId);
});


</script>

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
    @apply tab text-left;
  }
  tfoot td {
    @apply tab text-left;
  }

  table, th,td{
    @apply border border-collapse;
  }
}
</style>