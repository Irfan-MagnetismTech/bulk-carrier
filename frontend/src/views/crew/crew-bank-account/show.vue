<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useCrewBankAccount from '../../../composables/crew/useCrewBankAccount.js';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';

const icons = useHeroIcon();

const route = useRoute();
const crewBankAccountId = route.params.crewBankAccountId;
const { crewBankAccount, showCrewBankAccount, errors } = useCrewBankAccount();

const { setTitle } = Title();

setTitle('Crew Bank Account Details');

onMounted(() => {
  showCrewBankAccount(crewBankAccountId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200"> Crew Bank Account Details  # {{crewBankAccountId}} </h2>
    <default-button :title="'Crew Bank Account List'" :to="{ name: 'crw.crewBankAccounts.index' }" :icon="icons.DataBase"></default-button>
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
              <td><span :class="crewBankAccount?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ crewBankAccount?.business_unit }}</span></td>
            </tr>
            <tr>
              <th class="w-40"> Crew Name </th>
              <td>{{ crewBankAccount?.crwCrew?.full_name }}</td>
            </tr>
            <tr>
              <th class="w-40"> Crew Contact </th>
              <td>{{ crewBankAccount?.crwCrew?.pre_mobile_no }}</td>
            </tr>
            <tr>
              <th class="w-40"> Bank Name </th>
              <td>{{ crewBankAccount?.bank_name }}</td>
            </tr>
            <tr>
              <th class="w-40"> Branch Name </th>
              <td>{{ crewBankAccount?.branch_name }}</td>
            </tr>
            <tr>
              <th class="w-40"> Account Name </th>
              <td>{{ crewBankAccount?.account_name }}</td>
            </tr>
            <tr>
              <th class="w-40"> Account Number </th>
              <td>{{ crewBankAccount?.account_number }}</td>
            </tr>
            <tr>
              <th class="w-40"> Routing Number </th>
              <td>{{ crewBankAccount?.routing_number}}</td>
            </tr>
            <tr>
              <th class="w-40"> Benificiary Name </th>
              <td>{{ crewBankAccount?.benificiary_name }}</td>
            </tr>
            <tr>
              <th class="w-40"> Status </th>
              <td>
                <span v-if="!crewBankAccount?.is_active" class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">Deactive</span>
                <span v-else class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Active</span>              
              </td>
            </tr>
            <tr>
              <th class="w-40"> Attachment </th>
              <td>
                <a type="button" v-if="typeof crewBankAccount?.attachment === 'string'" class="text-green-800" target="_blank" :href="env.BASE_API_URL+'/'+crewBankAccount?.attachment">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                  </svg>
                </a>
                <a v-else>---</a>              
              </td>
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