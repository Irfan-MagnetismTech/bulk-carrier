<template>
  <div class="w-full my-2 overflow-hidden border shadow-xs dark:border-0">

    <h1 class="py-1 font-bold text-white bg-green-500 text-center uppercase">Slot Charter Contract</h1>
    <div class="w-full">
      <table class="w-full whitespace-no-wrap">
        <thead>
        <tr style="background-color: #d5d3d3" class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
          <td></td>
          <td class="uppercase font-bold">Dead Freight</td>
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
        <tr style="background-color: #d5d3d3" class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
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
    <div class="w-full">
      <table class="w-full whitespace-no-wrap" style="min-height: 176px">
        <thead>
        <tr style="background-color: #d5d3d3" class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
          <td class="" colspan="6">Ocean Freight</td>
          <td class="" colspan="3">Dead Freight</td>
          <td class="" colspan="4">Excess Load</td>
        </tr>
        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
          <td class="">Bound</td>
          <td class="">POL</td>
          <td class="">POD</td>
          <td class="">TERM</td>
          <td class="">PER</td>
          <td class="">CUR</td>
          <td class="">OFT/BFT</td>
          <td class="">BAF</td>
          <td class="">TTL</td>
          <td class="">LDN OFT</td>
          <td class="">LDN BAF</td>
          <td class="">MTY OFT</td>
          <td class="">MTY BAF</td>
        </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        <template v-for="(allocation,allocationIndex) in contract?.allocations" :key="allocationIndex">
          <template v-for="(port,portIndex) in allocation?.ports">
            <tr>
              <td :rowspan="allocation?.ports?.length" v-if="portIndex===0">{{ allocation?.bound }}</td>
              <td>{{ port?.pol }}</td>
              <td>{{ port?.pod }}</td>
              <td>{{ port?.term }}</td>
              <td>Tue</td>
              <td>{{ port?.s_pod_bill_currency }}</td>
              <td :rowspan="allocation?.ports?.length" v-if="portIndex===0">
                {{ allocation?.rates.find(rate => rate.charge_type === "BFT")?.dead_freight }}              
              </td>
              <td :rowspan="allocation?.ports?.length" v-if="portIndex===0">
                {{ allocation?.rates.find(rate => rate.charge_type === "BAF")?.dead_freight }}              
              </td>
              
              <td :rowspan="allocation?.ports?.length" v-if="portIndex===0">
                {{ allocation?.rates.reduce((previous, current) => parseFloat(previous) + parseFloat(current.dead_freight), 0) }}
              </td>

              <td :rowspan="allocation?.ports?.length" v-if="portIndex===0">
                {{ allocation?.rates.find(rate => rate.charge_type === "BFT")?.excess_laden }}
              </td>              
              <td :rowspan="allocation?.ports?.length" v-if="portIndex===0">
                {{ allocation?.rates.find(rate => rate.charge_type === "BAF")?.excess_laden }}
              </td>

              <td :rowspan="allocation?.ports?.length" v-if="portIndex===0">
                {{ allocation?.rates.find(rate => rate.charge_type === "BFT")?.excess_empty }}
              </td>              
              <td :rowspan="allocation?.ports?.length" v-if="portIndex===0">
                {{ allocation?.rates.find(rate => rate.charge_type === "BAF")?.excess_empty }}
              </td>

            </tr>
          </template>
        </template>
        </tbody>
        <tfoot>
        </tfoot>
      </table>
    </div>
  </div>
  <div class="w-full my-2 overflow-hidden border shadow-xs dark:border-0">
    <div class="grid grid-cols-2 gap-2">
      <div class="w-full">
        <table class="w-full whitespace-no-wrap" style="min-height: 176px">
          <thead>
          <tr style="background-color: #d5d3d3" class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
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
          <template v-for="(allocation,allocationIndex) in contract?.allocations" :key="allocationIndex">
            <template v-for="(port,portIndex) in allocation?.ports">
              <tr>
                <td :rowspan="sumPortLength(contract?.allocations)" v-if="allocationIndex===0 && portIndex===0">{{ contract?.service?.code }}</td>
                <td :rowspan="allocation?.ports?.length" v-if="portIndex===0">{{ allocation?.bound }}</td>
                <td>Lane - {{ portIndex+1 }}</td>
                <td>{{ port?.pol }}</td>
                <td>{{ port?.pod }}</td>
                <td :rowspan="allocation?.ports?.length" v-if="portIndex===0">{{ allocation?.tues }}</td>
                <td :rowspan="allocation?.ports?.length" v-if="portIndex===0">{{ allocation?.weight_limit_per_tue }}</td>
              </tr>
            </template>
          </template>
          </tbody>
          <tfoot>
          </tfoot>
        </table>
      </div>
      <div class="w-full">
        <table class="w-full whitespace-no-wrap">
          <thead>
          <tr style="background-color: #d5d3d3" class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
            <td colspan="2" class="">Basic Information</td>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr>
            <td class="">Contract#</td>
            <td class="">{{ contract?.contract_no }}</td>
          </tr>
          <tr>
            <td class="">Type</td>
            <td class="">
              <span v-if="contract?.contract_type=='master-contract'">Master</span>
              <span v-else>Sub</span>
            </td>
          </tr>
          <tr>
            <td class="">Status</td>
            <td class="">----</td>
          </tr>
          <tr>
            <td class="">Billing Party</td>
            <td class="">{{ contract?.billing_party }}</td>
          </tr>
          <tr>
            <td class="">Credit Days</td>
            <td class="">{{ contract?.due_date }}</td>
          </tr>
          <tr>
            <td class="">Sales PIC</td>
            <td class="">----</td>
          </tr>
          </tbody>
          <tfoot>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  <div class="w-full my-2 overflow-hidden border shadow-xs dark:border-0">
    <div class="grid grid-cols-2 gap-2">
      <div class="w-full">
        <table class="w-full whitespace-no-wrap">
          <thead>
          <tr style="background-color: #d5d3d3" class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
            <td class="" colspan="7">Surcharges</td>
          </tr>
          <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
            <td class="">Code</td>
            <td class="">Bound</td>
            <td class="">Group</td>
            <td class="">CUR</td>
            <td class="">Rate</td>
            <td class="">Per</td>
            <td class="">Calc.</td>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <template v-for="(allocation,dgAllocationIndex) in contract?.allocations" :key="dgAllocationIndex">
            <template v-for="(dgGroup,groupIndex) in allocation?.dgGroups">
              <tr>
                <td :rowspan="allocation?.dgGroups?.length*2" v-if="dgAllocationIndex===0 && groupIndex===0">DG</td>
                <td :rowspan="allocation?.dgGroups?.length" v-if="groupIndex===0">{{ allocation?.bound }}</td>
                <td>{{ dgGroup?.group }}</td>
                <td>{{ dgGroup?.currency }}</td>
                <td>{{ dgGroup?.rate }}</td>
                <td class="capitalize">{{ dgGroup?.per }}</td>
                <td>{{ dgGroup?.calculation }}</td>
              </tr>
            </template>
          </template>
          <template v-for="(allocation,rfAllocationIndex) in contract?.allocations" :key="rfAllocationIndex">
            <tr>
              <td :rowspan="contract?.allocations?.length" v-if="rfAllocationIndex===0" class="text-center">RF</td>
              <td>{{ allocation?.bound }}</td>
              <td>ALL</td>
              <td>{{ contract?.currency }}</td>
              <td>{{ allocation?.rf_per_plug_amount }}</td>
              <td class="capitalize">{{ allocation?.rf_unit }}</td>
              <td>Per {{ allocation?.rf_unit }}</td>
            </tr>
          </template>
          </tbody>
          <tfoot>
          </tfoot>
        </table>
      </div>
      <div class="w-full">
        <table class="w-full whitespace-no-wrap">
          <thead>
          <tr style="background-color: #d5d3d3" class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-700">
            <td colspan="5" class="">Validity</td>
          </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          <tr>
            <td class="">F/T</td>
            <td class="">Date</td>
            <td class="">On</td>
            <td class="">At</td>
            <td class="">Bound</td>
          </tr>
          <tr>
            <td class="">From</td>
            <td class="">{{ moment( contract?.valid_from).format('DD-MM-YYYY') }}</td>
            <td class="">{{ contract?.valid_from_on }}</td>
            <td class="">{{ contract?.valid_from_at }}</td>
            <td class="">{{ contract?.valid_from_bound }}</td>
          </tr>
          <tr>
            <td class="">To</td>
            <td class="">{{ moment( contract?.valid_till).format('DD-MM-YYYY') }}</td>
            <td class="">{{ contract?.valid_till_on }}</td>
            <td class="">{{ contract?.valid_till_at }}</td>
            <td class="">{{ contract?.valid_till_bound }}</td>
          </tr>
          </tbody>
          <tfoot>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  <div class="mt-5">

  </div>
</template>
<script setup>
import { onMounted } from '@vue/runtime-core';
import { useRoute } from 'vue-router';
import moment from 'moment';
import {ref,watch} from "vue";
import useContract from "../../../composables/commercial/useContract";

const route = useRoute();
const contractId = route.params.contractId;
const { contract, showContract } = useContract();

const openTab = ref(1);
const toggleTabs = (tabNumber) => {
  openTab.value = tabNumber;
}

onMounted( () => {
    showContract(contractId);
});

// watch(() => contract, () => {
//  // console.log("Contract: " +contract.value);
//   if(contract?.value?.allocations?.length) {
//     contract?.value?.allocations.forEach((allocation, allocationIndex) => {
//       let group1 = {group: 'Group-1', currency: contract?.value?.currency, rate: allocation.dg_group_1, per: allocation.dg_unit, calculation: 'Per '+ allocation.dg_unit};
//       let group2 = {group: 'Group-2', currency: contract?.value?.currency, rate: allocation.dg_group_2, per: allocation.dg_unit, calculation: 'Per '+ allocation.dg_unit};
//       let group3 = {group: 'Group-3', currency: contract?.value?.currency, rate: allocation.dg_group_3, per: allocation.dg_unit, calculation: 'Per '+ allocation.dg_unit};
//       let groupC = {group: 'Group-C', currency: contract?.value?.currency, rate: allocation.dg_group_common, per: allocation.dg_unit, calculation: 'Per '+ allocation.dg_unit};
//       contract.value.allocations[allocationIndex].dgGroups = [group1, group2, group3, groupC];
//     });
//   }
// }, { deep: true });

function sumPortLength(allocations) {
  let length = 0;
  for (let allocation in allocations) {
    length += allocations[allocation].ports.length;
  }
  return length;
}
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
