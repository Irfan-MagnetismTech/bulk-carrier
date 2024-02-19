<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Vendor Bill Details</h2>
    <default-button :title="'Vendor Bill List'" :to="{ name: 'scm.vendor-bills.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="vendorBill?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ vendorBill?.business_unit }}</span></td>
                    </tr>
                    <tr>
                      <th class="w-40">Date.</th>
                      <td>{{ vendorBill?.date }}</td>
                    </tr>
                    <tr>
                      <th class="w-40">Vendor Name</th>
                      <td>{{ vendorBill.scmVendor?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Bill No.</th>
                        <td>{{ vendorBill?.bill_no }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Warehouse</th>
                        <td>{{ vendorBill.scmWarehouse?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Currency</th>
                        <td>{{ vendorBill.currency }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Foreign To USD</th>
                        <td>{{ vendorBill?.foreign_to_usd ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">USD To BDT</th>
                        <td>{{ vendorBill?.usd_to_bdt }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Cash Requisition No</th>
                        <td>{{ vendorBill?.scmCashRequisition?.no ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Remarks </th>
                        <td>{{ vendorBill?.remarks }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Attachment </th>
                        <td>{{ vendorBill?.remarks }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="vendorBill.scmVendorBillLines?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Material Information</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th>SL</th>
                <th class="w-72">MRR No</th>
                <th>Challan No</th>
                <th>PO No</th>
                <th>Amount</th>
                <th>Amount USD</th>
                <th>Amount BDT</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(certificate, index) in vendorBill.scmVendorBillLines">
                <td>
                  {{ index+1 }}
                </td>
                <td>
                  <span v-if="vendorBill.scmVendorBillLines[index]?.scmMrr?.ref_no">{{ vendorBill.scmVendorBillLines[index]?.scmMrr?.ref_no }}</span>
                </td>
                <td>
                  <span v-if="vendorBill.scmVendorBillLines[index]?.scmMrr?.challan_no">{{ vendorBill.scmVendorBillLines[index]?.scmMrr?.challan_no }}</span>
                </td>
                <td>
                  <span v-if="vendorBill.scmVendorBillLines[index]?.scmMrr?.scmPo?.ref_no">{{ vendorBill.scmVendorBillLines[index]?.scmMrr?.scmPo?.ref_no }}</span>
                </td>
                <td>
                    <span>
                        {{ numberFormat(vendorBill.scmVendorBillLines[index].amount) }}
                    </span>
                </td>
                <td>
                    <span>
                    {{ numberFormat(vendorBill.scmVendorBillLines[index].amount_usd) }}
                </span>
                </td>
                <td>
                    <span>
                        {{ numberFormat(vendorBill.scmVendorBillLines[index].amount_bdt) }}
                    </span>
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
import { onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHelper from "../../../composables/useHelper";
import useHeroIcon from "../../../assets/heroIcon";
import useStoreRequisition from "../../../composables/supply-chain/useStoreRequisition";
import StoreRequisitionShow from "../../../components/supply-chain/store-requisitions/StoreRequisitionShow.vue";
import { formatDate } from '../../../utils/helper';
import useVendor from '../../../composables/supply-chain/useVendor';
import useVendorBill from '../../../composables/supply-chain/useVendorBill';

const icons = useHeroIcon();

const route = useRoute();
const vendorBillId = route.params.vendorBillId;

const { getVendorBill, showVendorBill, vendorBill, updateVendorBill,materialObject, errors } = useVendorBill();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Vendor Bill Details');

const DEPARTMENTS = ['', 'Store Department', 'Engine Department', 'Provision Department'];

// watch(bunkerRequisition, (value) => {
//    bunkerRequisition.value.opsVoyage = value?.opsVoyage;
// });

onMounted(() => {
  showVendorBill(vendorBillId);
});


</script>