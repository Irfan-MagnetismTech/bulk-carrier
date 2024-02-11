<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Customer Invoice</h2>
    <default-button :title="'Customer Invoice List'" :to="{ name: 'ops.customer-invoices.index' }" :icon="icons.DataBase"></default-button>
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
                          <td><span :class="customerInvoice?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ customerInvoice?.business_unit }}</span></td>
                      </tr>                      
                      <tr>
                          <th class="w-40">Invoice Date</th>
                          <td>{{ formatDate(customerInvoice?.date) }}</td>
                      </tr>
                      <tr>
                          <th class="w-40">Customer Name</th>
                          <td>{{ customerInvoice.opsCustomer?.name_code }}</td>
                      </tr>                      
                  </tbody>
              </table>
          </div>
    </div>    
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="customerInvoice.opsCustomerInvoiceVoyages?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="5">Voyage Data</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th class="">Voyage No</th>
                <th class="">
                  <nobr>Vessel Name</nobr>
                </th>
                <th class="">
                  <nobr>Cargo Type</nobr>
                </th>
                <th>
                  <nobr>Total Amount</nobr>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(voyage, index) in customerInvoice.opsCustomerInvoiceVoyages" :key="index">
                <td class="!w-1/4">
                  {{ customerInvoice.opsCustomerInvoiceVoyages[index].opsVoyage?.voyage_sequence }}
                </td>
                <td>
                    {{ customerInvoice.opsCustomerInvoiceVoyages[index].opsVessel?.name }}                  
                </td>
                <td>
                    {{ customerInvoice.opsCustomerInvoiceVoyages[index].opsCargoType?.cargo_type }}                  
                </td>
                <td>
                  {{ customerInvoice.opsCustomerInvoiceVoyages[index].total_amount_bdt }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="customerInvoice.opsCustomerInvoiceOthers?.length">
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
              <th class="">Unit</th>
              <th>Quantity</th>
              <th>Rate</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(sector, index) in customerInvoice.opsCustomerInvoiceOthers">
                <td>
                  {{ customerInvoice.opsCustomerInvoiceOthers[index].particular }}
                </td>
                <td>
                  {{ customerInvoice.opsCustomerInvoiceOthers[index].cost_unit }}
                </td>
                <td>
                  {{ customerInvoice.opsCustomerInvoiceOthers[index].quantity }}
                </td>
                <td>
                  {{ numberFormat(customerInvoice.opsCustomerInvoiceOthers[index].rate) }}
                </td>
                <td>
                  {{ numberFormat(customerInvoice.opsCustomerInvoiceOthers[index].amount) }}
                </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2"  v-if="customerInvoice.opsCustomerInvoiceServices?.length">
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
              <th class="">Unit</th>
              <th>Quantity</th>
              <th>Rate</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(sector, index) in customerInvoice.opsCustomerInvoiceServices">
                <td>
                  {{ customerInvoice.opsCustomerInvoiceServices[index].particular }}
                </td>
                <td>
                  {{ customerInvoice.opsCustomerInvoiceServices[index].cost_unit }}
                </td>
                <td>
                  {{ customerInvoice.opsCustomerInvoiceServices[index].quantity }}
                </td>
                <td>
                  {{ numberFormat(customerInvoice.opsCustomerInvoiceServices[index].rate) }}
                </td>
                <td>
                  {{ numberFormat(customerInvoice.opsCustomerInvoiceServices[index].amount) }}
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
              <th>Sub Total</th>
              <th>Total Service Fee (Deduction)</th>
              <th>Discount (Deduction)</th>
              <th>Grand Total</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                {{ numberFormat(customerInvoice.sub_total_amount) }}
              </td>
              <td>
                {{ numberFormat(customerInvoice.service_fee_deduction_amount) }}
              </td>
              <td>
                {{ numberFormat(customerInvoice.discounted_amount) }}
              </td>
              <td>
                {{ numberFormat(customerInvoice.grand_total) }}
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
import CustomerInvoiceForm from '../../../components/operations/CustomerInvoiceForm.vue';
import useCustomerInvoice from '../../../composables/operations/useCustomerInvoice';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from '../../../composables/useHelper';
import { formatDate } from '../../../utils/helper';
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