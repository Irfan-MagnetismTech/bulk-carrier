<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Expense Head Details</h2>
    <default-button :title="'Expense Head List'" :to="{ name: 'ops.expense-heads.index' }" :icon="icons.DataBase"></default-button>
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
                        <th class="w-40">Business Unit</th>
                        <td><span :class="expenseHead?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ expenseHead?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Group</th>
                        <td>{{ expenseHead?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Voyage Report Visibility </th>
                        <td>{{ (expenseHead?.is_visible_in_voyage_report)?'Yes':'No' }}</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="expenseHead.opsSubHeads?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Heads</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th>SL</th>
                <th class="w-72">Name</th>
                <th>Usage Type (PER)</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(certificate, index) in expenseHead.opsSubHeads">
                <td class="w-40">
                  {{ index+1 }}
                </td>
                <td width="40%">
                  <span>{{ expenseHead?.opsSubHeads[index]?.name }}</span>
                </td>
                <td>
                  <span>{{ expenseHead?.opsSubHeads[index]?.billing_type }}</span>
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
</style>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import ExpenseHeadForm from '../../../components/operations/ExpenseHeadForm.vue';
import useExpenseHead from '../../../composables/operations/useExpenseHead';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const expenseHeadId = route.params.expenseHeadId;
const { expenseHead, showExpenseHead, errors } = useExpenseHead();

const { setTitle } = Title();

setTitle('Edit Expense Head');

onMounted(() => {
  showExpenseHead(expenseHeadId);
});
</script>