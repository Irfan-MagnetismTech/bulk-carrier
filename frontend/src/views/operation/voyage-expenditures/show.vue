<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Voyage Expenditure</h2>
    <default-button :title="'Expense Head List'" :to="{ name: 'ops.voyage-expenditures.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="voyageExpenditure?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ voyageExpenditure?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Port</th>
                        <td>{{ voyageExpenditure?.port?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Vessel </th>
                        <td>{{ voyageExpenditure?.opsVessel?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Voyage </th>
                        <td>{{ voyageExpenditure?.opsVoyage?.voyage_sequence }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Remarks </th>
                        <td>{{ voyageExpenditure?.remarks }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Attachment </th>
                        <td>
                          <span v-if="voyageExpenditure?.attachment != 'null'">
                            <a class="text-red-700" target="_blank" :href="env.BASE_API_URL+'/'+voyageExpenditure?.attachment">{{ 'Click Here' }}</a>
                          </span>
                          <span v-else>Not Found...</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="voyageExpenditure.opsVoyageExpenditureEntries?.length">
        <div class="w-full">
          <table class="w-full">
            <thead >
                <tr colspan="8">
                  <th class="w-40">Currency </th>
                  <th>Exchange Rate (To USD)</th>
                  <th>Exchange Rate (USD to BDT)</th>
                </tr>
                <tr colspan="8">
                  <th>{{ voyageExpenditure.currency }} </th>
                  <th>{{ voyageExpenditure.exchange_rate_usd }} </th>
                  <th>{{ voyageExpenditure.exchange_rate_bdt }} </th>
                </tr>
            </thead>
          </table>
          <table class="w-full mt-1 md:mt-2">

            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="8">Expesne Head Info</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th>SL</th>
                <th class="w-72">Expesne Head </th>
                <th>Invoice Date</th>
                <th>Invoice No.</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Amount <small>(USD)</small></th>
                <th>Amount <small>(BDT)</small></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(expenditure, index) in voyageExpenditure.opsVoyageExpenditureEntries">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  <span v-if="voyageExpenditure?.opsVoyageExpenditureEntries[index]?.opsExpenseHead?.name">{{ voyageExpenditure?.opsVoyageExpenditureEntries[index]?.opsExpenseHead?.name }}</span>
                </td>
                <td>
                  {{ voyageExpenditure?.opsVoyageExpenditureEntries[index]?.invoice_date ? moment(voyageExpenditure.opsVoyageExpenditureEntries?.invoice_date).format('DD-MM-YYYY') : null }}
                </td>
                <td>
                  {{ voyageExpenditure?.opsVoyageExpenditureEntries[index]?.invoice_no }}
                </td>
                <td>
                  <span>
                      {{ numberFormat(voyageExpenditure.opsVoyageExpenditureEntries[index].quantity) }}
                    </span>
                </td>
                <td>
                  <span>
                    {{ numberFormat(voyageExpenditure.opsVoyageExpenditureEntries[index].rate) }}
                  </span>
                </td>
                <td>
                  <span>
                    {{ numberFormat(voyageExpenditure.opsVoyageExpenditureEntries[index].amount_usd) }}
                  </span>
                </td>
                <td>
                  <span>
                    {{ numberFormat(voyageExpenditure.opsVoyageExpenditureEntries[index].amount_bdt) }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
    </div>

    <div class="flex md:gap-4 mt-1 md:mt-2">
      <div class="w-full">
        <table class="w-full whitespace-no-wrap" >
          <thead>
              <tr>
                  <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="4">Summary</td>
              </tr>
          </thead>
          <thead v-once>
            <tr class="w-full">
              <th>Sub Total (BDT)</th>
              <th>Discount (BDT) </th>
              <th>Grand Total (BDT)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                {{ numberFormat(voyageExpenditure.sub_total_bdt) }}
              </td>
              <td>
                {{ numberFormat(voyageExpenditure.discount_bdt) }}
              </td>
              <td>
                {{ numberFormat(voyageExpenditure.grand_total_bdt) }}
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
import useVoyageExpenditure from '../../../composables/operations/useVoyageExpenditure';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import moment from 'moment';
import env from '../../../config/env';

const icons = useHeroIcon();

const route = useRoute();
const voyageExpenditureId = route.params.voyageExpenditureId;
const { voyageExpenditure, showVoyageExpenditure, updateVoyageExpenditure, errors } = useVoyageExpenditure();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Voyage Expenditure Details');

onMounted(() => {
  showVoyageExpenditure(voyageExpenditureId);
});
</script>