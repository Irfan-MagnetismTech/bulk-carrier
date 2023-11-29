<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useBankAccount from '../../../composables/accounts/useBankAccount.js';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";

const icons = useHeroIcon();

const route = useRoute();
const bankAccountId = route.params.bankAccountId;
const { bankAccount, showBankAccount, errors } = useBankAccount();

const { setTitle } = Title();

setTitle('Bank Account Details');

onMounted(() => {
  showBankAccount(bankAccountId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Bank Account Details</h2>
    <default-button :title="'Bank Account List'" :to="{ name: 'acc.bank-accounts.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Bank Information</h2>
          <table class="w-full">
            <tbody>
              <tr>
                <th class="w-32">Bank Name</th>
                <td>{{ bankAccount?.bank_name }}</td>
              </tr>
              <tr>
                <th class="w-32">Branch Name</th>
                <td>{{ bankAccount?.branch_name }}</td>
              </tr>
              <tr>
                <th class="w-32">Account Type</th>
                <td>{{ bankAccount?.account_type }}</td>
              </tr>
              <tr>
                <th class="w-32">Account Name</th>
                <td>{{ bankAccount?.account_name }}</td>
              </tr>
              <tr>
                <th class="w-32">Account Number</th>
                <td>{{ bankAccount?.account_number }}</td>
              </tr>
              <tr>
                <th class="w-32">Contact Number</th>
                <td>{{ bankAccount?.contact_number }}</td>
              </tr>
              <tr>
                <th class="w-32">Routing Number</th>
                <td>{{ bankAccount?.routing_number }}</td>
              </tr>
              <tr>
                <th class="w-32">Opening Date</th>
                <td>{{ bankAccount?.opening_date }}</td>
              </tr>
              <tr>
                <th class="w-32">Opening Balance</th>
                <td>{{ bankAccount?.opening_balance }}</td>
              </tr>
              <tr>
                <th class="w-32">Business Unit</th>
                <td><span :class="bankAccount?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ bankAccount?.business_unit }}</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  </div>
</template>
<style lang="postcss" scoped>
  th, td {
    @apply text-left
  }
</style>