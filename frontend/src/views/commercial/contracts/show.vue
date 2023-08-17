<template>
    <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
        <span>Contract Details:</span>
        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">#{{ contractId }}</span>
    </div>
    <!-- Table -->
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
                        <td class="text-sm">Contract No</td>
                        <td class="text-sm">{{ contract?.contract_no }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Customer Name</td>
                      <td class="text-sm">{{ contract?.customer?.customer_name }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Service Name</td>
                      <td class="text-sm">{{ contract?.service?.name }} ({{ contract?.service?.route }})</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Contract Type</td>
                      <td class="text-sm">{{ contract?.contract_type }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Rate Type</td>
                      <td class="text-sm capitalize">{{ contract?.rate_type }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Valid From</td>
                      <td class="text-sm capitalize">{{ contract?.valid_from }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Valid From On</td>
                      <td class="text-sm capitalize">{{ contract?.valid_from_on }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Valid From At</td>
                      <td class="text-sm capitalize">{{ contract?.valid_from_at }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Valid From Bound</td>
                      <td class="text-sm capitalize">{{ contract?.valid_from_bound }}</td>
                    </tr>

                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Valid Till</td>
                      <td class="text-sm capitalize">{{ contract?.valid_till }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Valid Till On</td>
                      <td class="text-sm capitalize">{{ contract?.valid_till_on }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Valid Till At</td>
                      <td class="text-sm capitalize">{{ contract?.valid_till_at }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Valid Till Bound</td>
                      <td class="text-sm capitalize">{{ contract?.valid_till_bound }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Billing Party</td>
                      <td class="text-sm capitalize">{{ contract?.billing_party }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Billing Address</td>
                      <td class="text-sm capitalize">{{ contract?.billing_address }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Billing Emails</td>
                      <td class="text-sm">{{ contract?.billing_emails }}</td>
                    </tr>
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="text-sm">Billing Style</td>
                      <td class="text-sm capitalize" v-html="contract?.billing_style"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
  <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
    <span>Agents:</span>
  </div>
  <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap text-center table">
        <thead v-once>
        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">Port Name</th>
          <th class="px-4 py-3">Agent Name</th>
          <th class="px-4 py-3">Billing Name</th>
          <th class="px-4 py-3">Billing Email</th>
          <th class="px-4 py-3">Billing Style</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
           <tr v-for="(agent,index) in contract.agents" :key="index" class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">{{ agent?.port_code }}</td>
            <td class="text-sm">{{ agent?.name }}</td>
            <td class="text-sm">{{ agent?.billing_name }}</td>
            <td class="text-sm">{{ agent?.billing_email }}</td>
            <td class="text-sm">{{ agent?.billing_style }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
    <span>Allocations:</span>
  </div>
  <div class="border-b border-gray-200 dark:border-gray-700">
  <ul class="flex flex-wrap -mb-px">
    <li class="mr-2" v-for="(allocation,index) in contract.allocations" :key="index">
      <a href="#" class="inline-flex px-4 py-4 text-sm font-medium capitalize text-center text-gray-500 border-b-2 border-transparent rounded-t-lg dark:text-gray-400 group" v-on:click="toggleTabs(index + 1)" v-bind:class="{'text-purple-600 bg-white': openTab !== index+1, 'text-blue-600 rounded-t-lg border-b-2 border-blue-600 active': openTab === index+1}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> {{ allocation.bound }} Bound
      </a>
    </li>
  </ul>
    <div v-for="(allocation,boundIndex) in contract.allocations" :key="boundIndex" v-on:click="toggleTabs(boundIndex + 1)" v-bind:class="{'hidden': openTab !== boundIndex + 1, 'block': openTab === boundIndex + 1}">
      <div class="w-full overflow-hidden border rounded-lg shadow-xs dark:border-0">
        <div class="text-lg p-1 font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
          <span>Allocation:</span>
        </div>
        <div class="w-full overflow-x-auto p-1">
          <table class="w-full whitespace-no-wrap text-center table">
            <thead v-once>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th class="px-4 py-3">Tues</th>
              <th class="px-4 py-3">Weight Limit</th>
              <th class="px-4 py-3">HC</th>
              <th class="px-4 py-3">Reffer</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
            <tr class="text-gray-700 dark:text-gray-400">
              <td class="text-sm">{{ allocation?.tues }}</td>
              <td class="text-sm">{{ allocation?.weight_limit_per_tue }}</td>
              <td class="text-sm">{{ allocation?.hc }}</td>
              <td class="text-sm">{{ allocation?.reffer }}</td>
            </tr>
            </tbody>
          </table>
        </div>
        <div class="text-lg p-1 font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
          <span>Allocation Ports:</span>
        </div>
        <div class="w-full overflow-x-auto p-1">
          <table class="w-full whitespace-no-wrap text-center table">
            <thead v-once>
            <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th class="px-4 py-3">Pol</th>
              <th class="px-4 py-3">Pod</th>
              <th class="px-4 py-3">Is Excess</th>
              <th class="px-4 py-3">Term</th>
              <th class="px-4 py-3">Payment Location</th>
              <th class="px-4 py-3">Payment Currency</th>
              <th class="px-4 py-3">Recovery</th>
              <th class="px-4 py-3">Pol Bill To</th>
              <th class="px-4 py-3">Pod Bill To</th>
              <th class="px-4 py-3">Pod Bill Currency</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
            <tr v-for="(port,portIndex) in allocation.ports" :key="portIndex" class="text-gray-700 dark:text-gray-400">
              <td class="text-sm">{{ port?.pol }}</td>
              <td class="text-sm">{{ port?.pod }}</td>
              <td class="text-sm" v-if="port.is_excess === 1">Yes</td>
              <td class="text-sm" v-else>No</td>
              <td class="text-sm">{{ port?.term }}</td>
              <td class="text-sm">{{ port?.f_payment_location }}</td>
              <td class="text-sm">{{ port?.f_payment_currency }}</td>
              <td class="text-sm" v-if="port?.s_recovery===1">Yes</td>
              <td class="text-sm" v-else>No</td>
              <td class="text-sm">{{ port?.s_pol_bill_to }}</td>
              <td class="text-sm">{{ port?.s_pod_bill_to }}</td>
              <td class="text-sm">{{ port?.s_pod_bill_currency }}</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="text-lg p-1 font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once>
        <span>Allocation Rates:</span>
      </div>
      <div class="w-full overflow-x-auto p-1">
        <table class="w-full whitespace-no-wrap text-center table">
          <thead v-once>
          <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">Charge Type</th>
            <th class="px-4 py-3">Dead Freight</th>
            <th class="px-4 py-3">Excess Laden</th>
            <th class="px-4 py-3">Excess Empty</th>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr v-for="(rate,rateIndex) in allocation.rates" :key="rateIndex" class="text-gray-700 dark:text-gray-400">
            <td class="text-sm">{{ rate?.charge_type }}</td>
            <td class="text-sm">{{ rate?.dead_freight }}</td>
            <td class="text-sm">{{ rate?.excess_laden }}</td>
            <td class="text-sm">{{ rate?.excess_empty }}</td>
          </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
  <div class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 flex items-center gap-2" v-once></div>
</template>
<script setup>
import { onMounted } from '@vue/runtime-core';
import { useRoute } from 'vue-router';
import useContract from "../../../composables/commercial/useContract";
import {ref} from "vue";
import Title from "../../../services/title";

const route = useRoute();
const contractId = route.params.contractId;
const { contract, showContract } = useContract();

const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  openTab.value = tabNumber;
}

const { setTitle } = Title();

setTitle('Contract Details');

onMounted( () => {
    showContract(contractId);
});
</script>

<style lang="postcss" scoped>
.table, .table th, .table td{
  @apply border border-collapse border-gray-400 text-left pl-3 text-gray-700 font-medium;
}
.text-xs {
  font-size: 0.7rem;
  line-height: 0.7rem;
}
</style>
