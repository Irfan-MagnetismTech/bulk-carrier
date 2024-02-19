<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Material Costing Details</h2>
    <default-button :title="'Material Costing List'" :to="{ name: 'scm.store-requisitions.index' }" :icon="icons.DataBase"></default-button>
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
                        <td><span :class="materialCosting?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ materialCosting?.business_unit }}</span></td>
                    </tr>
                    <tr>
                        <th class="w-40">PO No.</th>
                        <td>{{ materialCosting?.scmPo?.ref_no }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Warehouse</th>
                        <td>{{ materialCosting?.scmWarehouse?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Date</th>
                        <td>{{ formatDate(materialCosting?.date) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="!mt-8 md:mt-10">
           <template v-if="materialCosting.scmPo?.purchase_center == 'Foreign'">
            <template class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmSrLine, index ,key) in materialCosting.scmCostingLines" :key="index">
              <template v-for="(lines,index1,key1) in scmSrLine" :key="index1">
                <div class="table-responsive min-w-screen !mt-4 md:mt-6">
                   
                    <table class="!w-full">
                      <thead v-if="key1 == 0">
                        <tr>
                            <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Cost Information</td>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <td class="!text-center font-bold bg-green-400 uppercase text-white" colspan="7">LC No - {{ materialCosting.scmCostingLines[index][index1]['cfr'][0]['scmLcRecord']['lc_no'] }}</td>
                        </tr>
                    </thead>
                      <thead>
                        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                          <th class="py-3 !text-center ">Particular  <span class="text-red-500">*</span></th>
                          <th class="py-3 !text-center ">Exchange Rate</th>
                          <th class="py-3 !text-center ">USD</th>
                          <th class="py-3 !text-center ">BDT <span class="text-red-500">*</span></th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                          <template v-for="(LLNN,index2,key2) in lines.cfr">
                            <tr>
                              <td class="text-left">{{ LLNN.particulars }}</td>
                              <td class="!text-right">{{materialCosting.scmCostingLines[index][index1]['cfr'][index2].exchange_rate  }}</td>
                              <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['cfr'][index2].usd_amount}}</td>
                              <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['cfr'][index2].bdt_amount }}</td>
                            </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.total_cfr">
                              <tr>
                                <td class="!text-right !font-bold">{{ LLNN.particulars }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_cfr'][index2].exchange_rate }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_cfr'][index2].usd_amount }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_cfr'][index2].bdt_amount }}</td>
                              </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.cif">
                            <tr>
                              <td class="text-left">{{ LLNN.particulars }}</td>
                              <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['cif'][index2].exchange_rate }}</td>
                              <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['cif'][index2].usd_amount }}</td>
                              <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['cif'][index2].bdt_amount }}</td>
                            </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.total_cif">
                            <tr>
                              <td class="!text-right !font-bold">{{ LLNN.particulars }}</td>
                              <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_cif'][index2].exchange_rate }}</td>
                              <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_cif'][index2].usd_amount }}</td>
                              <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_cif'][index2].bdt_amount }}</td>
                            </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.a">
                              <tr>
                                <td class="text-left">{{ LLNN.particulars }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['a'][index2].exchange_rate }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['a'][index2].usd_amount }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['a'][index2].bdt_amount }}</td>
                              </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.total_assessable">
                              <tr>
                                <td class="!text-right !font-bold">{{ LLNN.particulars }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_assessable'][index2].exchange_rate }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_assessable'][index2].exchange_rate }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_assessable'][index2].exchange_rate }}</td>
                              </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.tti">
                                <tr>
                                  <td class="text-left">{{ LLNN.particulars }}</td>
                                  <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['tti'][index2].exchange_rate }}</td>
                                  <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['tti'][index2].usd_amount }}</td>
                                  <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['tti'][index2].bdt_amount }}</td>
                                </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.total_tti">
                              <tr>
                                <td class="!text-right !font-bold">{{ LLNN.particulars }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_tti'][index2].exchange_rate }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_tti'][index2].exchange_rate }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_tti'][index2].exchange_rate }}</td>
                              </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.tc">
                                <tr>
                                  <td class="text-left">{{ LLNN.particulars }}</td>
                                  <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['tc'][index2].exchange_rate }}</td>
                                  <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['tc'][index2].usd_amount }}</td>
                                  <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['tc'][index2].bdt_amount }}</td>
                                </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.total_tc">
                              <tr>
                                <td class="!text-right !font-bold">{{ LLNN.particulars }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_tc'][index2].exchange_rate }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_tc'][index2].usd_amount }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_tc'][index2].bdt_amount }}</td>
                              </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.total_lc_cost">
                              <tr>
                                <td class="text-left">{{ LLNN.particulars }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_lc_cost'][index2].exchange_rate }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_lc_cost'][index2].usd_amount }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['total_lc_cost'][index2].bdt_amount }}</td>
                              </tr>
                          </template>
                          <template v-for="(LLNN,index2,key2) in lines.grand_total">
                              <tr>
                                <td class="!text-right !font-bold">{{ LLNN.particulars }}</td>
                                <td class="!text-right">{{materialCosting.scmCostingLines[index][index1]['grand_total'][index2].exchange_rate  }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['grand_total'][index2].usd_amount }}</td>
                                <td class="!text-right">{{ materialCosting.scmCostingLines[index][index1]['grand_total'][index2].bdt_amount  }}</td>
                              </tr>
                          </template>
                      </tbody>
                      <tfoot v-if="key1 != 0">
                        <tr>
                          <td colspan="3" class="text-right">Total Allocateable</td>
                          <td class="!text-right">{{ materialCosting.total_allocateable }}</td>
                        </tr>
                      </tfoot>
                    </table>
                </div>
              </template>
            </template>
          </template>

          <template v-else-if="materialCosting.scmPo?.purchase_center == 'Local' || materialCosting.scmPo?.purchase_center == 'Plant'">
            <fieldset class="px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400">
              <legend class="px-2 text-gray-700 dark-disabled:text-gray-300">Costs </legend>
              <table class="w-full">
                <thead>
                  <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                      <th class="py-3 align-center">Cost Particulars  <span class="text-red-500">*</span></th>
                      <th class="py-3 align-center">Amount</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                  <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmCostingLine, index) in materialCosting.scmCostingLines" :key="index">
                    <td>
                      <label class="block w-full mt-2 text-sm">
                        {{ materialCosting.scmCostingLines[index].particulars }}
                      </label>
                    </td>
                    <td>
                      <label class="block w-full mt-2 text-sm">
                        {{ materialCosting.scmCostingLines[index].bdt_amount }}
                      </label>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td class="text-right">Total Allocateable</td>
                    <td><input type="number" :value="materialCosting.total_allocateable" readonly class="form-input vms-readonly-input"/></td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </fieldset>
          </template>
    </div>
    <div id="" v-if="materialCosting.scmCostingAllocations?.length"  class="!mt-8 md:mt-10">
    <div id="">
              <table class="w-full">
                <thead>
                        <tr>
                            <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="7">Cost Allocation </td>
                        </tr>
                    </thead>
                <thead>
                  <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800">
                      <th class="py-3 align-center !text-center">MRR Ref </th>
                      <th class="py-3 align-center !text-center">Total Value</th>
                      <th class="py-3 align-center !text-center">Allocated Amount </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800">
                  <tr class="text-gray-700 dark-disabled:text-gray-400" v-for="(scmCostingAllocation, index) in materialCosting.scmCostingAllocations" :key="index">
                    <td class="!text-center">{{ materialCosting.scmCostingAllocations[index]?.scmMrr?.ref_no }}
                    </td>
                    <td class="!text-center">{{ materialCosting.scmCostingAllocations[index].value }}
                    </td>
                    <td class="!text-center">{{ materialCosting.scmCostingAllocations[index].allocated_amount }}
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
import useMaterialCosting from '../../../composables/supply-chain/useMaterialCosting';

const icons = useHeroIcon();

const route = useRoute();
const materialCostingId = route.params.materialCostingId;

const { getMaterialCosting, materialCosting,showMaterialCosting, updateMaterialCosting,storeMaterialCosting,materialObject, errors, isLoading } = useMaterialCosting();

const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Material Costing Details');

const DEPARTMENTS = ['', 'Store Department', 'Engine Department', 'Provision Department'];

// watch(bunkerRequisition, (value) => {
//    bunkerRequisition.value.opsVoyage = value?.opsVoyage;
// });

onMounted(() => {
  showMaterialCosting(materialCostingId);
});


</script>