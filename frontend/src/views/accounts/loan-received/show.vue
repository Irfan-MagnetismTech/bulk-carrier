<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useLoanReceived from '../../../composables/accounts/useLoanReceived';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';
import { formatDate } from "../../../utils/helper.js";


const icons = useHeroIcon();

const route = useRoute();
const loanReceivedId = route.params.loanReceivedId;
const { loanReceived, showLoanReceived, errors } = useLoanReceived();

const { setTitle } = Title();

setTitle('Loan Received Details');

onMounted(() => {
  showLoanReceived(loanReceivedId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Loan Received Details  # {{loanReceivedId}} </h2>
    <default-button :title="'Loan Received List'" :to="{ name: 'acc.loan-received.index' }" :icon="icons.DataBase"></default-button>
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
            <td><span :class="loanReceived?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ loanReceived?.business_unit }}</span></td>
          </tr>
          <tr>
            <th class="w-40">Loan Name</th>
            <td>{{ loanReceived?.loan?.loan_name ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Total Sanctioned</th>
            <td>{{ loanReceived?.loan?.total_sanctioned ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Total Received Amount</th>
            <td>{{ loanReceived?.loan?.total_received_amount ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Received Date</th>
            <td>{{ formatDate(loanReceived?.received_date) ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Payment Type</th>
            <td>{{ loanReceived?.payment_method ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Received Account Name</th>
            <td>{{ loanReceived?.bank?.account_name ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Instrument No </th>
            <td>{{ loanReceived?.instrument_no ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Instrument Date</th>
            <td>{{ formatDate(loanReceived?.instrument_date) ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Received Amount</th>
            <td>{{ loanReceived?.received_amount ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Interest Rate (%)</th>
            <td>{{ loanReceived?.interest_rate ?? '---' }}</td>
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