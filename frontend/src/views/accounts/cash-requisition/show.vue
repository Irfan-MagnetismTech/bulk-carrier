<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useCashRequisition from '../../../composables/accounts/useCashRequisition';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';

const icons = useHeroIcon();

const route = useRoute();
const cashRequisitionId = route.params.cashRequisitionId;
const { cashRequisition, showCashRequisition, errors } = useCashRequisition();

const { setTitle } = Title();

setTitle('Cash Requisition Details');

onMounted(() => {
  showCashRequisition(cashRequisitionId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">  Cash Requisition Details  # {{cashRequisitionId}} </h2>
    <default-button :title="'Cash Requisition List'" :to="{ name: 'acc.cash-requisitions.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <div class="flex md:gap-4">
      <div class="w-full">
        <table class="w-full">
          <thead>
          <tr>
            <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Basic Info</td>
          </tr>
          </thead>
          <tbody>
          <tr>
            <th class="w-40"> Business Unit </th>
            <td><span :class="cashRequisition?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ cashRequisition?.business_unit }}</span></td>
          </tr>
          <tr>
            <th class="w-40">Cost Center Name</th>
            <td>{{ cashRequisition?.costCenter?.name ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Applied Date</th>
            <td>{{ cashRequisition?.applied_date ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Pr Ref</th>
            <td>{{ cashRequisition?.scmPr?.ref_no ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Purpose</th>
            <td>{{ cashRequisition?.purpose ?? '---' }}</td>
          </tr>
          </tbody>
        </table>
        <table class="w-full mt-2">
          <thead>
          <tr>
            <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="5">Cash Requisition Line List </td>
          </tr>
          <tr>
            <th class="w-40 !text-center">Particular</th>
            <th class="w-40 !text-center">Remarks</th>
            <th class="w-40 !text-center">Amount</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(cashRequisitionLine, index) in cashRequisition?.accCashRequisitionLines" :key="index">
            <td class="text-left"> {{ cashRequisitionLine?.particular }} </td>
            <td class="text-left"> {{ cashRequisitionLine?.remarks }} </td>
            <td class="!text-right"> {{ cashRequisitionLine?.amount }} </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
<style lang="postcss" scoped>
th, td, tr {
  @apply text-left border-gray-500
}

tfoot td{
  @apply text-center
}

#profileDetailTable th{
  text-align: center;
}
#profileDetailTable thead tr{
  @apply bg-gray-200
}

</style>