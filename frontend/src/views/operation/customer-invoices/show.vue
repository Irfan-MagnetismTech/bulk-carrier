<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Customer Invoice</h2>
    <default-button :title="'Customer Invoice List'" :to="{ name: 'ops.customer-invoices.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
        <span class="text-gray-700 dark-disabled:text-gray-300">Business Unit</span>

        <span class="show-block">
          {{ customerInvoice.business_unit }}
        </span>
      </label>
      <label class="block w-full mt-2 text-sm"></label>
      <label class="block w-full mt-2 text-sm"></label>

    </div>

    <h4 class="text-md font-semibold mt-3">Basic Info</h4>

    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Customer Owner</span>
              <span class="show-block">
                {{ customerInvoice.opsCustomerProfile?.name_and_code }}
              </span>
        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Contract</span>
              <span class="show-block">
                {{ customerInvoice.opsCustomerContract?.contract_name }}
              </span>
        </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel</span>
              <input type="text" readonly :value="customerInvoice.opsCustomerContract?.opsVessel?.name" class="form-input bg-gray-100" autocomplete="off" />
        </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2" v-if="customerInvoice.contract_type == 'Day Wise'">
          <label class="block w-full mt-2 text-sm">
                <span class="text-gray-700 dark-disabled:text-gray-300">Bill From</span>
                <input type="date" v-model.trim="customerInvoice.bill_from" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
                <span class="text-gray-700 dark-disabled:text-gray-300">Bill Till</span>
                <input type="date" v-model.trim="customerInvoice.bill_till" class="form-input" autocomplete="off" />
          </label>
          <label class="block w-full mt-2 text-sm">
                <span class="text-gray-700 dark-disabled:text-gray-300">Total Days</span>
                <span class="show-block !justify-center">
                    {{ customerInvoice?.total_days }}
                </span>
          </label>
          <label class="block w-full mt-2 text-sm">
                <span class="text-gray-700 dark-disabled:text-gray-300">Charge Per Day</span>
                <span class="show-block !justify-end">
                    {{ numberFormat(customerInvoice?.per_day_charge) }}
                </span>
          </label>
          <label class="block w-full mt-2 text-sm">
                <span class="text-gray-700 dark-disabled:text-gray-300">Total Amount</span>
                <span class="show-block !justify-end">
                    {{ numberFormat(customerInvoice?.total_amount) }}
                </span>
          </label>
    </div>

    <div id="sectors" class="mt-5" v-if="customerInvoice.contract_type == 'Voyage Wise'">
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
              <tr v-for="(sector, index) in customerInvoice.opsCustomerInvoiceVoyages" :key="index">
                <td class="!w-1/4">
                  <label class="block w-full mt-2 text-sm">
                  
                    <span class="show-block">
                      {{ customerInvoice.opsCustomerInvoiceVoyages[index].opsVoyage?.voyage_sequence }}
                    </span>
                  </label>
                </td>
                <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ customerInvoice.opsCustomerInvoiceVoyages[index].opsVoyage?.opsCargoType?.cargo_type }}
                      </span>
                  
                  </label>
                </td>
                <td>
                    <label class="block w-full mt-2 text-sm">
                    <input type="number" step="0.001" v-model.trim="customerInvoice.opsCustomerInvoiceVoyages[index].cargo_quantity" readonly class="form-input text-right" autocomplete="off" />
                  </label>
                </td>
                <td>
                  <label class="block w-full mt-2 text-sm">
                    <input type="number" step="0.001" v-model.trim="customerInvoice.opsCustomerInvoiceVoyages[index].rate_per_mt" readonly class="form-input text-right" autocomplete="off" />
                  
                  </label>
                </td>
                <td>
                  <label class="block w-full mt-2 text-sm">
                    <input type="text" v-model.trim="customerInvoice.opsCustomerInvoiceVoyages[index].total_amount" readonly class="form-input text-right" autocomplete="off" />
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
                <tr v-for="(sector, index) in customerInvoice.opsCustomerInvoiceOthers" :key="index">
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ customerInvoice.opsCustomerInvoiceOthers[index].particular }}
                      </span>
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ customerInvoice.opsCustomerInvoiceOthers[index].currency }}
                      </span>
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ customerInvoice.opsCustomerInvoiceOthers[index].cost_unit }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ customerInvoice.opsCustomerInvoiceOthers[index].quantity }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(customerInvoice.opsCustomerInvoiceOthers[index].rate) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(customerInvoice.opsCustomerInvoiceOthers[index].exchange_rate_usd) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(customerInvoice.opsCustomerInvoiceOthers[index].exchange_rate_bdt) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(customerInvoice.opsCustomerInvoiceOthers[index].amount_usd) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(customerInvoice.opsCustomerInvoiceOthers[index].amount_bdt) }}
                      </span>
                      
                    </label>
                  </td>
                </tr>
              </tbody>
              
            </table>     
          
        </div>
    </fieldset>
      
    <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
          <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Services Taken From Customer</legend>
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
                <tr v-for="(item, index) in customerInvoice.opsCustomerInvoiceServices" :key="index">
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ customerInvoice.opsCustomerInvoiceServices[index].particular }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ customerInvoice.opsCustomerInvoiceServices[index].currency }}
                      </span>
                     
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block">
                        {{ customerInvoice.opsCustomerInvoiceServices[index].particular }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(customerInvoice.opsCustomerInvoiceServices[index].quantity) }}
                      </span>
                      
                    </label>
                  </td>

                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(customerInvoice.opsCustomerInvoiceServices[index].rate) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(customerInvoice.opsCustomerInvoiceServices[index].exchange_rate_usd) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(customerInvoice.opsCustomerInvoiceServices[index].exchange_rate_bdt) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(customerInvoice.opsCustomerInvoiceServices[index].amount_usd) }}
                      </span>
                      
                    </label>
                  </td>
                  <td>
                    <label class="block w-full mt-2 text-sm">
                      <span class="show-block !justify-end">
                        {{ numberFormat(customerInvoice.opsCustomerInvoiceServices[index].amount_bdt) }}
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
                      {{ numberFormat(customerInvoice.sub_total_amount) }}
                    </span>
                  </td>
                  <td>
                    <span class="show-block !justify-end">
                      {{ numberFormat(customerInvoice.service_fee_deduction_amount) }}
                    </span>
                  </td>
                  <td>
                    <span class="show-block !justify-end">
                      {{ numberFormat(customerInvoice.discounted_amount) }}
                    </span>
                  </td>
                  <td>
                    <span class="show-block !justify-end">
                      {{ numberFormat(customerInvoice.grand_total) }}
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
import CustomerInvoiceForm from '../../../components/operations/CustomerInvoiceForm.vue';
import useCustomerInvoice from '../../../composables/operations/useCustomerInvoice';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from '../../../composables/useHelper';

const icons = useHeroIcon();

const route = useRoute();
const customerInvoiceId = route.params.customerInvoiceId;
const { customerInvoice, showCustomerInvoice, errors } = useCustomerInvoice();
const { numberFormat } = useHelper();
const { setTitle } = Title();

setTitle('Customer Invoice');

function CalculateAll() {
  console.log(parseFloat(customerInvoice.value.grand_total))
  console.log(parseFloat(customerInvoice.value.service_fee_deduction_amount))
  console.log(parseFloat(customerInvoice.value.discounted_amount))
  customerInvoice.value.sub_total_amount = parseFloat(customerInvoice.value.grand_total) + +parseFloat(customerInvoice.value.service_fee_deduction_amount ?? 0) + parseFloat(customerInvoice.value.discounted_amount ?? 0)
}

onMounted(() => {
  showCustomerInvoice(customerInvoiceId).then(() => {
    CalculateAll();
  })
});
</script>