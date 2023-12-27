<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useTransaction from '../../../composables/accounts/useTransaction';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';

const icons = useHeroIcon();

const route = useRoute();
const transactionId = route.params.transactionId;
const { transaction, showTransaction, errors } = useTransaction();

const { setTitle } = Title();

setTitle('Voucher Details');

onMounted(() => {
  showTransaction(transactionId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">  Voucher Details  # {{transactionId}} </h2>
    <default-button :title="'Voucher Record List'" :to="{ name: 'acc.transactions.index' }" :icon="icons.DataBase"></default-button>
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
            <td><span :class="transaction?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ transaction?.business_unit }}</span></td>
          </tr>
          <tr>
            <th class="w-40"> Cost Center Name </th>
            <td>{{ transaction?.costCenter?.name }}</td>
          </tr>
          <tr>
            <th class="w-40"> Voucher Type </th>
            <td>{{ transaction?.voucher_type }}</td>
          </tr>
          <tr>
            <th class="w-40"> Transaction Date </th>
            <td>{{ transaction?.transaction_date }}</td>
          </tr>
          <tr>
            <th class="w-40"> Instrument Type </th>
            <td>{{ transaction?.instrument_type }}</td>
          </tr>
          <tr>
            <th class="w-40"> Instrument Number </th>
            <td>{{ transaction?.instrument_no }}</td>
          </tr>
          <tr>
            <th class="w-40"> Instrument Date </th>
            <td>{{ transaction?.instrument_date }}</td>
          </tr>
          <tr>
            <th class="w-40"> Bill No </th>
            <td>{{ transaction?.bill_no }}</td>
          </tr>
          <tr>
            <th class="w-40"> Instrument Amount </th>
            <td>{{ transaction?.instrument_amount }}</td>
          </tr>
          <tr>
            <th class="w-40"> Narration Amount </th>
            <td>{{ transaction?.narration }}</td>
          </tr>
          </tbody>
        </table>

        <table class="w-full mt-2">
          <thead>
          <tr>
            <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="5"> Ledger Entry List </td>
          </tr>
          <tr>
            <th class="w-40 !text-center"> Account Name </th>
            <th class="w-40 !text-center"> Ref Bill </th>
            <th class="w-40 !text-center"> Debit Amount </th>
            <th class="w-40 !text-center"> Credit Amount </th>
            <th class="w-40 !text-center"> Remarks </th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(transactionEntry, index) in transaction?.ledgerEntries" :key="index">
            <td class="text-left"> {{ transactionEntry?.account?.account_name }} </td>
            <td class="text-left"> {{ transactionEntry?.ref_bill }} </td>
            <td class="text-left"> {{ transactionEntry?.dr_amount }} </td>
            <td class="text-left"> {{ transactionEntry?.cr_amount }} </td>
            <td class="text-left"> {{ transactionEntry?.remarks }} </td>
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