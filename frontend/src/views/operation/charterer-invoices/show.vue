<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Charterer Invoice</h2>
    <default-button :title="'Charterer Invoice List'" :to="{ name: 'ops.charterer-invoices.index' }" :icon="icons.DataBase"></default-button>
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
                          <td><span :class="chartererInvoice?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ chartererInvoice?.business_unit }}</span></td>
                      </tr>
                      <tr>
                          <th class="w-40">Charterer Owner</th>
                          <td>{{ chartererInvoice?.opsChartererProfile?.name_and_code }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Contract</th>
                          <td>{{ chartererInvoice?.opsChartererContract?.contract_name  }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Vessel Name</th>
                          <td>{{ chartererInvoice.opsChartererContract?.opsVessel?.name  }}</td>
                      </tr>
                      <tr v-if="chartererInvoice.contract_type == 'Day Wise'">
                          <th class="w-40">Bill From</th>
                          <td>{{ formatDate(chartererInvoice.bill_from)  }}</td>
                      </tr>
                      <tr v-if="chartererInvoice.contract_type == 'Day Wise'">
                          <th class="w-40">Bill Till</th>
                          <td>{{ formatDate(chartererInvoice.bill_till)  }}</td>
                      </tr>
                      <tr v-if="chartererInvoice.contract_type == 'Day Wise'">
                          <th class="w-40">Total Days</th>
                          <td>{{ chartererInvoice.total_days  }}</td>
                      </tr>
                      <tr v-if="chartererInvoice.contract_type == 'Day Wise'">
                          <th class="w-40">Charge Per Day</th>
                          <td>{{ chartererInvoice.per_day_charge  }}</td>
                      </tr>
                      <tr v-if="chartererInvoice.contract_type == 'Day Wise'">
                          <th class="w-40">Total Amount</th>
                          <td>{{ chartererInvoice.total_amount  }}</td>
                      </tr>
                  </tbody>
              </table>

          </div>
      </div>
      <div class="flex md:gap-4 mt-1 md:mt-2" v-if="chartererInvoice.contract_type == 'Voyage Wise'">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Voyage Data</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th class="">Voyage</th>
                <th class="">
                  <nobr>Cargo Type</nobr>
                </th>
                <th class="">
                  <nobr>Cargo Quantity</nobr>
                </th>
                <th class="">
                  <nobr>Rate Per MT</nobr>
                </th>
                <th>
                  <nobr>Total Amount</nobr>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(sector, index) in chartererInvoice.opsChartererInvoiceVoyages">
                <td>
                  {{ chartererInvoice.opsChartererInvoiceVoyages[index]?.opsVoyage?.voyage_sequence }}
                </td>
                <td>
                  {{ chartererInvoice.opsChartererInvoiceVoyages[index]?.opsVoyage?.opsCargoType?.cargo_type }}
                </td>
                <td>
                  <span>
                    {{ numberFormat(chartererInvoice.opsChartererInvoiceVoyages[index]?.cargo_quantity) }}
                  </span>
                </td>
                <td>
                  <span>
                    {{ numberFormat(chartererInvoice.opsChartererInvoiceVoyages[index]?.rate_per_mt) }}
                  </span>
                </td>
                <td>
                  <span>
                    {{ numberFormat(chartererInvoice.opsChartererInvoiceVoyages[index]?.total_amount) }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="flex md:gap-4 mt-1 md:mt-2">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="9">Others Billable</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th class="">Particulars</th>
                <th class="">Currency</th>
                <th class="">Unit</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Exchange Rate (To USD)</th>
                <th>Exchange Rate (To BDT)</th>
                <th>Amount USD</th>
                <th>Amount BDT</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(sector, index) in chartererInvoice.opsChartererInvoiceOthers">
                  <td>
                    {{ chartererInvoice.opsChartererInvoiceOthers[index].particular }}
                  </td>
                  <td>
                    {{ chartererInvoice.opsChartererInvoiceOthers[index].currency }}
                  </td>
                  <td>
                    {{ chartererInvoice.opsChartererInvoiceOthers[index].cost_unit }}
                  </td>
                  <td>
                    {{ chartererInvoice.opsChartererInvoiceOthers[index].quantity }}
                  </td>
                  <td>
                    {{ numberFormat(chartererInvoice.opsChartererInvoiceOthers[index].rate) }}
                  </td>
                  <td>
                    {{ numberFormat(chartererInvoice.opsChartererInvoiceOthers[index].exchange_rate_usd) }}
                  </td>
                  <td>
                    {{ numberFormat(chartererInvoice.opsChartererInvoiceOthers[index].exchange_rate_bdt) }}
                  </td>
                  <td>
                    {{ numberFormat(chartererInvoice.opsChartererInvoiceOthers[index].amount_usd) }}
                  </td>
                  <td>
                    {{ numberFormat(chartererInvoice.opsChartererInvoiceOthers[index].amount_bdt) }}
                  </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="flex md:gap-4 mt-1 md:mt-2">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="9">Services Taken From Charterer</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th class="">Particulars</th>
                <th class="">Currency</th>
                <th class="">Unit</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Exchange Rate (To USD)</th>
                <th>Exchange Rate (To BDT)</th>
                <th>Amount USD</th>
                <th>Amount BDT</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(sector, index) in chartererInvoice.opsChartererInvoiceServices">
                  <td>
                    {{ chartererInvoice.opsChartererInvoiceServices[index].particular }}
                  </td>
                  <td>
                    {{ chartererInvoice.opsChartererInvoiceServices[index].currency }}
                  </td>
                  <td>
                    {{ chartererInvoice.opsChartererInvoiceServices[index].cost_unit }}
                  </td>
                  <td>
                    {{ chartererInvoice.opsChartererInvoiceServices[index].quantity }}
                  </td>
                  <td>
                    {{ numberFormat(chartererInvoice.opsChartererInvoiceServices[index].rate) }}
                  </td>
                  <td>
                    {{ numberFormat(chartererInvoice.opsChartererInvoiceServices[index].exchange_rate_usd) }}
                  </td>
                  <td>
                    {{ numberFormat(chartererInvoice.opsChartererInvoiceServices[index].exchange_rate_bdt) }}
                  </td>
                  <td>
                    {{ numberFormat(chartererInvoice.opsChartererInvoiceServices[index].amount_usd) }}
                  </td>
                  <td>
                    {{ numberFormat(chartererInvoice.opsChartererInvoiceServices[index].amount_bdt) }}
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
                <th>Subtotal</th>
                <th>Total Service Fee (Deduction)</th>
                <th>Discount (Deduction)</th>
                <th>Grand Total</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  {{ numberFormat(chartererInvoice.sub_total_amount) }}
                </td>
                <td>
                  {{ numberFormat(chartererInvoice.service_fee_deduction_amount) }}
                </td>
                <td>
                  {{ numberFormat(chartererInvoice.discounted_amount) }}
                </td>
                <td>
                  {{ numberFormat(chartererInvoice.grand_total) }}
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
import ChartererInvoiceForm from '../../../components/operations/ChartererInvoiceForm.vue';
import useChartererInvoice from '../../../composables/operations/useChartererInvoice';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from '../../../composables/useHelper';
import { formatDate } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const chartererInvoiceId = route.params.chartererInvoiceId;
const { chartererInvoice, showChartererInvoice, errors } = useChartererInvoice();
const { numberFormat } = useHelper();
const { setTitle } = Title();

setTitle('Charterer Invoice');

function CalculateAll() {
  console.log(parseFloat(chartererInvoice.value.grand_total))
  console.log(parseFloat(chartererInvoice.value.service_fee_deduction_amount))
  console.log(parseFloat(chartererInvoice.value.discounted_amount))
  chartererInvoice.value.sub_total_amount = parseFloat(chartererInvoice.value.grand_total) + +parseFloat(chartererInvoice.value.service_fee_deduction_amount ?? 0) + parseFloat(chartererInvoice.value.discounted_amount ?? 0)
}

onMounted(() => {
  showChartererInvoice(chartererInvoiceId).then(() => {
    CalculateAll();
  })
});
</script>