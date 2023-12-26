<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Bunker Bill Details</h2>
    <default-button :title="'Purchase Bill List'" :to="{ name: 'ops.bunker-bills.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="bunkerBill?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ bunkerBill?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">Date</th>
                        <td>{{ bunkerBill?.date }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Vendor</th>
                        <td>{{ bunkerBill.scmVendor?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Bill No.</th>
                        <td>{{ bunkerBill?.vendor_bill_no }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Upload file(Supplier Invoice) </th>
                        <td>
                          <span v-if="bunkerBill?.attachment != 'null'">
                            <a class="text-red-700" target="_blank" :href="env.BASE_API_URL+'/'+bunkerBill?.attachment">{{ 'Click Here' }}</a>
                          </span>
                          <span v-else>Not Found...</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="w-40">Upload file(SRM Copy) </th>
                        <td>
                          <span v-if="bunkerBill?.smr_file_path != 'null'">
                            <a class="text-red-700" target="_blank" :href="env.BASE_API_URL+'/'+bunkerBill?.smr_file_path">{{ 'Click Here' }}</a>
                          </span>
                          <span v-else>Not Found...</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="w-40">Remarks </th>
                        <td>{{ bunkerBill?.remarks }}</td>
                    </tr>
                   
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="bunkerBill.opsBunkerBillLines?.length">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Bunker Information</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(line, index) in bunkerBill.opsBunkerBillLines">
                    <td>
                      <table class="w-full">
                        <tr>
                          <th>PR No.</th>
                          <th>Currency</th>
                          <th>Exchange Rate (To USD)</th>
                          <th>Exchange Rate (USD to BDT)</th>                          
                        </tr>                        
                        <tr>
                          <td>{{ bunkerBill.opsBunkerBillLines[index]?.opsBunkerRequisition?.requisition_no }}</td>
                          <td>{{ bunkerBill.opsBunkerBillLines[index]?.currency }}</td>
                          <td>{{ numberFormat(bunkerBill.opsBunkerBillLines[index]?.exchange_rate_usd) }}</td>
                          <td>{{ numberFormat(bunkerBill.opsBunkerBillLines[index]?.exchange_rate_bdt) }}</td>                                                    
                        </tr>                        
                      </table>
                      <table class="w-full mt-1 md:mt-2" v-if="bunkerBill.opsBunkerBillLines[index].opsBunkerBillLineItems.length">
                        <tr>
                          <th>Bunker</th>
                          <th>Quantity</th>
                          <th>Rate</th>
                          <th>Amount USD</th>
                          <th>Amount BDT</th>
                        </tr>
                        <tr  v-for="(item, key) in bunkerBill.opsBunkerBillLines[index].opsBunkerBillLineItems">
                          <td>{{ item.requisition_material }}</td>
                          <td>{{ item.quantity }}</td>
                          <td>{{ item.rate }}</td>
                          <td>{{ item.amount_usd }}</td>
                          <td>{{ item.amount_bdt }}</td>
                        </tr>
                      </table>
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
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="3">Summary</td>
                </tr>
            </thead>
            <thead v-once>
              <tr class="w-full">
                <th>Sub Total (BDT)</th>
                <th>Discount (BDT) </th>
                <th>Grand Total (BDT) </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  {{ numberFormat(bunkerBill.sub_total_bdt) }}
                </td>
                <td>
                  {{ numberFormat(bunkerBill.discount_bdt) }}
                </td>
                <td>
                  {{ numberFormat(bunkerBill.grand_total_bdt) }}
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
import useBunkerBill from '../../../composables/operations/useBunkerBill';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import moment from 'moment';
import env from '../../../config/env';

const icons = useHeroIcon();

const route = useRoute();
const bunkerBillId = route.params.bunkerBillId;
const { bunkerBill, showBunkerBill, errors } = useBunkerBill();
const { numberFormat } = useHelper();
const { setTitle } = Title();

setTitle('Bunker Bill Details');

watch(bunkerBill, (value) => {
  bunkerBill.value.scmVendor = value?.scmVendor;
});

onMounted(() => {
  showBunkerBill(bunkerBillId);
});
</script>