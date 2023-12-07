<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Charterer Invoice</h2>
    <default-button :title="'Charterer Invoice List'" :to="{ name: 'ops.charterer-invoices.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Business Unit</span>

        <span class="show-block">
          {{ chartererInvoice.business_unit }}
        </span>
      </label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>

    </div>

    <h4 class="text-md font-semibold mt-3">Basic Info</h4>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Charterer Owner</span>
              <span class="show-block">
                {{ chartererInvoice.opsChartererProfile?.name_and_code }}
              </span>
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Contract</span>
              <span class="show-block">
                {{ chartererInvoice.opsChartererContract?.contract_name }}
              </span>
        </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel</span>
              <input type="text" readonly :value="chartererInvoice.opsChartererContract?.opsVessel?.name" class="form-input bg-gray-100" autocomplete="off" />
        </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2" v-if="chartererInvoice.contract_type == 'Day Wise'">
          <label class="block w-full mt-2 text-sm">
                <span class="text-gray-700 dark-disabled:text-gray-300">Bill From</span>
                <input type="date" v-model.trim="chartererInvoice.bill_from" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
                <span class="text-gray-700 dark-disabled:text-gray-300">Bill Till</span>
                <input type="date" v-model.trim="chartererInvoice.bill_till" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
                <span class="text-gray-700 dark-disabled:text-gray-300">Total Days</span>
                <span class="show-block !justify-center">
                    {{ chartererInvoice?.total_days }}
                </span>
          </label>
          <label class="block w-full mt-2 text-sm">
                <span class="text-gray-700 dark-disabled:text-gray-300">Charge Per Day</span>
                <span class="show-block !justify-end">
                    {{ numberFormat(chartererInvoice?.per_day_charge) }}
                </span>
          </label>
          <label class="block w-full mt-2 text-sm">
                <span class="text-gray-700 dark-disabled:text-gray-300">Total Amount</span>
                <span class="show-block !justify-end">
                    {{ numberFormat(chartererInvoice?.total_amount) }}
                </span>
          </label>
    </div>

    <div id="sectors" class="mt-5" v-if="chartererInvoice.contract_type == 'Voyage Wise'">
        <h4 class="text-md font-semibold my-3">Voyage Data</h4>

        <table class="w-full whitespace-no-wrap" >
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
              <tr v-for="(sector, index) in chartererInvoice.opsChartererInvoiceVoyages" :key="index">
                <td class="!w-1/4">
                  <label class="block w-full mt-2 text-sm">
                  
                    <span class="show-block">
                      {{ chartererInvoice.opsChartererInvoiceVoyages[index].opsVoyage?.voyage_sequence }}
                    </span>
                  </label>
                </td>
                <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ chartererInvoice.opsChartererInvoiceVoyages[index].opsVoyage?.opsCargoType?.cargo_type }}
                      </span>
                  
                  </label>
                </td>
                <td>
                    <label class="block w-full mt-2 text-sm">
                    <input type="number" step="0.001" v-model.trim="chartererInvoice.opsChartererInvoiceVoyages[index].cargo_quantity" readonly class="form-input text-right" autocomplete="off" />
                  </label>
                </td>
                <td>
                  <label class="block w-full mt-2 text-sm">
                    <input type="number" step="0.001" v-model.trim="chartererInvoice.opsChartererInvoiceVoyages[index].rate_per_mt" readonly class="form-input text-right" autocomplete="off" />
                  
                  </label>
                </td>
                <td>
                  <label class="block w-full mt-2 text-sm">
                    <input type="text" v-model.trim="chartererInvoice.opsChartererInvoiceVoyages[index].total_amount" readonly class="form-input text-right" autocomplete="off" />
                  </label>
                </td>
              </tr>
            </tbody>
            
          </table>     
        
    </div>

    
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
        <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Other</legend>
        <div id="sectors" class="mt-5">
          <table class="w-full whitespace-no-wrap" >
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
                <tr v-for="(sector, index) in chartererInvoice.opsChartererInvoiceOthers" :key="index">
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ chartererInvoice.opsChartererInvoiceOthers[index].particular }}
                      </span>
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ chartererInvoice.opsChartererInvoiceOthers[index].currency }}
                      </span>
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ chartererInvoice.opsChartererInvoiceOthers[index].cost_unit }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ chartererInvoice.opsChartererInvoiceOthers[index].quantity }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(chartererInvoice.opsChartererInvoiceOthers[index].rate) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(chartererInvoice.opsChartererInvoiceOthers[index].exchange_rate_usd) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(chartererInvoice.opsChartererInvoiceOthers[index].exchange_rate_bdt) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(chartererInvoice.opsChartererInvoiceOthers[index].amount_usd) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(chartererInvoice.opsChartererInvoiceOthers[index].amount_bdt) }}
                      </span>
                      
                    </label>
                  </td>
                </tr>
              </tbody>
              
            </table>     
          
        </div>
    </fieldset>
      
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Services Taken From Charterer</legend>
        <div id="sectors" class="mt-5">

          <table class="w-full whitespace-no-wrap" >
              <thead v-once>
                <tr class="w-full">
                  <th>Particulars</th>
                  <th>Currency</th>
                  <th>Unit</th>
                  <th>Quantity</th>
                  <th>Rate</th>
                  <th>Exchange Rate (To USD)</th>
                  <th>Exchange Rate (USD To BDT)</th>
                  <th>Amount USD</th>
                  <th>Amount BDT</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in chartererInvoice.opsChartererInvoiceServices" :key="index">
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ chartererInvoice.opsChartererInvoiceServices[index].particular }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ chartererInvoice.opsChartererInvoiceServices[index].currency }}
                      </span>
                     
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ chartererInvoice.opsChartererInvoiceServices[index].particular }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(chartererInvoice.opsChartererInvoiceServices[index].quantity) }}
                      </span>
                      
                    </label>
                  </td>

                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(chartererInvoice.opsChartererInvoiceServices[index].rate) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(chartererInvoice.opsChartererInvoiceServices[index].exchange_rate_usd) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(chartererInvoice.opsChartererInvoiceServices[index].exchange_rate_bdt) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(chartererInvoice.opsChartererInvoiceServices[index].amount_usd) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(chartererInvoice.opsChartererInvoiceServices[index].amount_bdt) }}
                      </span>
                     
                    </label>
                  </td>
                </tr>
              </tbody>
            </table>     
          
        </div>
      </fieldset>

      <div id="sectors" class="mt-5">
        <table class="w-full whitespace-no-wrap" >
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
                    <span class="show-block !justify-end">
                      {{ numberFormat(chartererInvoice.sub_total_amount) }}
                    </span>
                  </td>
                  <td>
                    <span class="show-block !justify-end">
                      {{ numberFormat(chartererInvoice.service_fee_deduction_amount) }}
                    </span>
                  </td>
                  <td>
                    <span class="show-block !justify-end">
                      {{ numberFormat(chartererInvoice.discounted_amount) }}
                    </span>
                  </td>
                  <td>
                    <span class="show-block !justify-end">
                      {{ numberFormat(chartererInvoice.grand_total) }}
                    </span>
                  </td>
                </tr>
            </tbody>
        </table>
      </div>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import ChartererInvoiceForm from '../../../components/operations/ChartererInvoiceForm.vue';
import useChartererInvoice from '../../../composables/operations/useChartererInvoice';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from '../../../composables/useHelper';

const icons = useHeroIcon();

const route = useRoute();
const chartererInvoiceId = route.params.chartererInvoiceId;
const { chartererInvoice, showChartererInvoice, errors } = useChartererInvoice();
const { numberFormat } = useHelper();
const { setTitle } = Title();

setTitle('Charterer Invoice');

function CalculateAll() {
  chartererInvoice.value.sub_total_amount = parseFloat(chartererInvoice.value.grand_total) + +parseFloat(chartererInvoice.value.service_fee_deduction_amount) + parseFloat(chartererInvoice.value.discounted_amount)
}

onMounted(() => {
  showChartererInvoice(chartererInvoiceId).then(() => {
    CalculateAll();
  })
});
</script>