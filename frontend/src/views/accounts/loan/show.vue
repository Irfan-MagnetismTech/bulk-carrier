<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useLoan from '../../../composables/accounts/useLoan';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';
import { formatDate } from "../../../utils/helper.js";


const icons = useHeroIcon();

const route = useRoute();
const loanId = route.params.loanId;
const { loan, showLoan, errors } = useLoan();

const { setTitle } = Title();

setTitle('Loan Details');

onMounted(() => {
  showLoan(loanId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Loan Details </h2>
    <default-button :title="'Loan List'" :to="{ name: 'acc.loans.index' }" :icon="icons.DataBase"></default-button>
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
            <td><span :class="loan?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ loan?.business_unit }}</span></td>
          </tr>
          <tr>
            <th class="w-40">Source Type</th>
            <td>{{ loan?.loanable_type ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Source Name</th>
            <td>{{ loan?.bank?.bank_name ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Loan Type</th>
            <td>{{ loan?.loan_type ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Loan Number</th>
            <td>{{ loan?.loan_number ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Loan Name</th>
            <td>{{ loan?.loan_name ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Total Sanctioned </th>
            <td>{{ loan?.total_sanctioned ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Sanctioned Limit </th>
            <td>{{ loan?.sanctioned_limit ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Total Installment</th>
            <td>{{ loan?.total_installment ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Interest Rate (%)</th>
            <td>{{ loan?.interest_rate ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Opening Date</th>
            <td>{{ formatDate(loan?.opening_date) ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Maturity Date</th>
            <td>{{ formatDate(loan?.maturity_date) ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40"> EMI Payment Date</th>
            <td>{{ formatDate(loan?.emi_date) ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40"> EMI Amount</th>
            <td>{{ loan?.emi_amount ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Loan Purpose</th>
            <td>{{ loan?.loan_purpose ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Mortgage</th>
            <td>{{ loan?.mortgage ?? '---' }}</td>
          </tr>
          <tr>
            <th class="w-40">Remarks </th>
            <td>{{ loan?.remarks ?? '---' }}</td>
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