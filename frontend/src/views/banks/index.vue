<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Banks</h2>
        <router-link :to="{ name: 'banks.create' }" class="flex items-center justify-between gap-1 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
        </router-link>
    </div>
    <!-- Table -->
    <div class="w-full overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead v-once>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Beneficiary Bank</th>
                        <th class="px-4 py-3">Beneficiary Bank Branch</th>
                        <th class="px-4 py-3">Beneficiary Bank Address</th>
                        <th class="px-4 py-3">Swift Code</th>
                        <th class="px-4 py-3">Beneficiary Account No</th>
                        <th class="px-4 py-3">Beneficiary IBAN No</th>
                        <th class="px-4 py-3">Beneficiary Name</th>
                        <th class="px-4 py-3">Beneficiary Address</th>
                        <th class="px-4 py-3">Beneficiary Bank Currency</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400" v-for="(bank,index) in banks" :key="bank.id">
                        <td class="px-4 py-3 text-sm">{{ index + 1 }}</td>
                        <td class="px-4 py-3 text-sm">{{ bank.beneficiary_bank }}</td>
                        <td class="px-4 py-3 text-sm">{{ bank.beneficiary_bank_branch }}</td>
                        <td class="px-4 py-3 text-sm">{{ bank.beneficiary_bank_address }}</td>
                        <td class="px-4 py-3 text-sm">{{ bank.swift_code }}</td>
                        <td class="px-4 py-3 text-sm">{{ bank.beneficiary_account_no }}</td>
                        <td class="px-4 py-3 text-sm">{{ bank.beneficiary_iban_No }}</td>
                        <td class="px-4 py-3 text-sm">{{ bank.beneficiary_name }}</td>
                        <td class="px-4 py-3 text-sm">{{ bank.beneficiary_address }}</td>
                        <td class="px-4 py-3 text-sm">{{ bank.beneficiary_bank_currency }}</td>

                        <td class="flex items-center justify-center px-4 py-3 space-x-2 text-sm text-gray-600">
                            <action-button :action="'edit'" :to="{ name: 'banks.edit', params: { bankId: bank.id } }"></action-button>
                            <action-button @click="deleteBank(bank.id)" :action="'delete'"></action-button>
                          <action-button :action="'activity log'" :to="{ name: 'user.activity.log', params: { subject_type: bank.subject_type,subject_id: bank.id } }"></action-button>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!banks?.length" class="bg-white dark:bg-gray-800">
                <tr v-if="isLoading">
                  <td colspan="16">Loading...</td>
                </tr>
                <tr v-else-if="!banks?.length">
                  <td colspan="16">No banks found.</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>
<script setup>
import { onMounted, watchEffect } from 'vue';
import ActionButton from '../../components/buttons/ActionButton.vue';
import useBank from '../../composables/useBank';
import Title from "../../services/title";

const props = defineProps({
  page: {
    type: Number,
    default: 1,
  },
});

const { banks, getBanks, deleteBank, isLoading } = useBank();

const { setTitle } = Title();

setTitle('Banks');

onMounted(() => {
  watchEffect(() => {
    getBanks(props.page);
  });
});
</script>
<style lang="postcss" scoped>
@tailwind components;

@layer components {
  .tab {
    @apply p-2.5 text-xs;
  }
  thead tr {
    @apply font-semibold tracking-wide text-left text-gray-500 bg-gray-50 dark:text-gray-400 dark:bg-gray-800;
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
    @apply tab text-center;
  }
  tfoot td {
    @apply tab text-center;
  }
  table, th,td{
    @apply border border-collapse;
  }
}
</style>