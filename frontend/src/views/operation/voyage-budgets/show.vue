<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Voyage Budget Details</h2>
    <default-button :title="'Voyage Budget List'" :to="{ name: 'ops.voyage-budgets.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="voyageBudget?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ voyageBudget?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Vessel</th>
                        <td>{{ voyageBudget.opsVessel?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Title</th>
                        <td>{{ voyageBudget.title }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Effective From </th>
                        <td><span>
                          <nobr>{{ formatDate(voyageBudget?.effective_from) }}</nobr>
                          </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="w-40">Effective To </th>
                        <td><span>
                          <nobr>{{ formatDate(voyageBudget?.effective_till) }}</nobr>
                          </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="w-40">Voyage</th>
                        <td>{{ voyageBudget.opsVoyage?.voyage_sequence }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Currency </th>
                        <td>{{ voyageBudget?.currency }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Exchange Rate (To USD) </th>
                        <td>{{ voyageBudget.exchange_rate_usd }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Exchange Rate (To BDT) </th>
                        <td>{{ voyageBudget.exchange_rate_bdt }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="voyageBudget.opsVoyageBudgetEntries?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Expesne Head Information</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th class="w-40">Expesne Head</th>
                <th>Quantity </th>
                <th>Rate</th>
                <th>Amount <small>(USD)</small></th>
                <th>Amount <small>(BDT)</small></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(budget, index) in voyageBudget.opsVoyageBudgetEntries">
                <td>
                  <span v-if="voyageBudget.opsVoyageBudgetEntries[index]?.opsExpenseHead?.name">{{ voyageBudget.opsVoyageBudgetEntries[index]?.opsExpenseHead?.name }}</span>
                </td>
                <td>
                  {{ voyageBudget.opsVoyageBudgetEntries[index]?.quantity }}
                </td>
                <td>
                  {{ voyageBudget.opsVoyageBudgetEntries[index]?.rate }}
                </td>
                <td>
                  {{ numberFormat(voyageBudget.opsVoyageBudgetEntries[index]?.amount_usd) }}
                </td>
                <td>
                  {{ numberFormat(voyageBudget.opsVoyageBudgetEntries[index]?.amount_bdt) }}
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
import VoyageBudgetForm from '../../../components/operations/VoyageBudgetForm.vue';
import useVoyageBudget from '../../../composables/operations/useVoyageBudget';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import { formatDate } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const voyageBudgetId = route.params.voyageBudgetId;
const { voyageBudget, showVoyageBudget, errors } = useVoyageBudget();
const { numberFormat } = useHelper();
const { setTitle } = Title();

setTitle('Voyage Budget Details');

onMounted(() => {
  showVoyageBudget(voyageBudgetId);
});
</script>