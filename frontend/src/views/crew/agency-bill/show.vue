<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useAgencyBill from "../../../composables/crew/useAgencyBill";
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';
import { formatDate } from "../../../utils/helper.js";

const icons = useHeroIcon();

const route = useRoute();
const agencyBillId = route.params.agencyBillId;
const { agencyBill, showAgencyBill, errors } = useAgencyBill();

const { setTitle } = Title();

setTitle('Agency Bill Details');

onMounted(() => {
  showAgencyBill(agencyBillId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Agency Bill Details</h2>
    <default-button :title="'Agency Bill'" :to="{ name: 'crw.agencyBills.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <table class="w-full">
            <thead>
            <tr>
              <td class="!text-center uppercase text-white font-bold bg-green-600" colspan="2">Basic Info</td>
            </tr>
            </thead>
            <tbody>
              <tr>
                <th class="w-40 text-left">Business Unit</th>
                <td class="text-left"><span :class="agencyBill?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ agencyBill?.business_unit }}</span></td>
              </tr>
              <tr>
                <th class="w-40 text-left">Agency Name</th>
                <td class="text-left">{{ agencyBill?.crwAgency?.agency_name }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Agency Contract</th>
                <td class="text-left">{{ agencyBill?.crwAgencyContract?.contract_name }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Applied Date</th>
                <td class="text-left">{{ formatDate(agencyBill?.applied_date) }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Bill Date</th>
                <td class="text-left">{{ formatDate(agencyBill?.invoice_date) }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Bill No</th>
                <td class="text-left">{{ agencyBill?.invoice_no }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Invoice Type</th>
                <td class="text-left">{{ agencyBill?.invoice_type }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Billing Currency</th>
                <td class="text-left">{{ agencyBill?.invoice_currency }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Bill Amount</th>
                <td class="text-left">{{ agencyBill?.invoice_amount }}</td>
              </tr>
              <tr>
                <th class="w-40 text-left">Remarks</th>
                <td class="text-left">{{ agencyBill?.remarks }}</td>
              </tr>
            </tbody>
          </table>
          <table class="w-full mt-1" id="profileDetailTable">
            <thead>
            <tr>
              <td class="!text-center text-white uppercase bg-green-600 font-bold" colspan="8">Bill Lines</td>
            </tr>
            <tr>
              <th>#</th>
              <th>Particular</th>
              <th>Description</th>
              <th>Per</th>
              <th>Quantity</th>
              <th>Rate</th>
              <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(billData,index) in agencyBill?.crwAgencyBillLines" :key="index">
              <td>{{ index + 1 }}</td>
              <td class="text-left">{{ billData?.particular }}</td>
              <td class="text-left">{{ billData?.description }}</td>
              <td class="text-left">{{ billData?.per }}</td>
              <td class="text-center">{{ billData?.quantity }}</td>
              <td class="text-right">{{ billData?.rate }}</td>
              <td class="text-right">{{ billData?.amount }}</td>
            </tr>
            </tbody>
            <tfoot v-if="!agencyBill?.crwAgencyBillLines?.length">
            <tr>
              <td colspan="7">No data found.</td>
            </tr>
            </tfoot>
          </table>
        </div>
      </div>
  </div>
</template>
<style lang="postcss" scoped>
  th, td, tr {
    @apply border-gray-500
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