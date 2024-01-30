<script setup>
import {ref,onMounted, watchPostEffect} from "vue";

import Title from "../../../services/title";
import useHelper from "../../../composables/useHelper.js";
import useLcRecord from "../../../composables/supply-chain/useLcRecord";
import LcRecordShow from "../../../components/supply-chain/lc-records/LcRecordShow.vue";
import { useRoute } from 'vue-router';

const { getLcRecord, showLcRecord, lcRecord, updateLcRecord,materialObject, errors, isLoading } = useLcRecord();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';
import { formatDate } from "../../../config/setting";
import env from "../../../config/env";

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const lcRecordId = route.params.lcRecordId;
const formType = 'edit';

setTitle('Update LC Records');

onMounted(() => {
    showLcRecord(lcRecordId);
});
</script>
<template>
    <!-- Heading -->
    
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create LC Records</h2>
        <default-button :title="'PO List'" :to="{ name: 'scm.lc-records.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        
          
        <!-- <lc-record-show :form="lcRecord"></lc-record-show>       -->
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
                        <td><span :class="lcRecord?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ lcRecord?.business_unit }}</span></td>
                    </tr>

                    <tr>
                        <th class="w-40">LC No</th>
                        <td>{{ lcRecord?.lc_no }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">LC Date</th>
                        <td>{{ formatDate(lcRecord?.lc_date) }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">LC Expire Date</th>
                        <td>{{ formatDate(lcRecord?.expire_date) }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Weight</th>
                        <td>{{ lcRecord?.weight }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">No. Of Packet</th>
                        <td>{{ lcRecord?.no_of_packet }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">PO No</th>
                        <td>{{ lcRecord?.scmPo?.ref_no }}</td>
                    </tr>
                    
                    <tr>
                        <th class="w-40">Invoice Value</th>
                        <td>{{ lcRecord?.invoice_value }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Assessment Value</th>
                        <td>{{ lcRecord?.assessment_value }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Issuing Bank</th>
                        <td>{{ lcRecord?.issue_bank_name }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Advising Bank</th>
                        <td>{{ lcRecord?.advising_bank_name }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Beneficiary Bank</th>
                        <td>{{ lcRecord?.beneficiary_bank_name }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Discounting Bank</th>
                        <td>{{ lcRecord?.discounting_bank_name }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Party Name</th>
                        <td>{{ lcRecord?.scmVendor?.name }}</td>
                    </tr>

                    <tr>
                      <th class="w-40">Attachment</th>
                      <td>
                          <a type="button" v-if="typeof lcRecord?.attachment === 'string'" class="text-green-800" target="_blank" :href="env.BASE_API_URL+'/'+lcRecord?.attachment">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-4 h-4">
                              <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                            </svg>
                          </a>
                          <a v-else>---</a>    
                      </td>
                    </tr>

                    <tr>
                        <th class="w-40">PI Ref No</th>
                        <td>{{ lcRecord?.pi_ref_no }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Expected Shipment Date</th>
                        <td> {{ formatDate(lcRecord?.expected_shipment_date) }} </td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Shipment Date</th>
                        <td> {{ formatDate(lcRecord?.shipment_date) }} </td>
                    </tr>

                    
                    
                </tbody>
            </table>
        </div>

        
        <div class="md:flex md:gap-2 ">
          <div class="w-full mt-2">
            <table class="w-full">
                <thead>
                    <tr>
                        <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Bank Payment Details</td>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr>
                        <th class="w-40">LC Status / Type</th>
                        <td>{{ lcRecord?.lc_type }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Name of Bank</th>
                        <td>{{ formatDate(lcRecord?.bank_name) }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">CFR Value (BDT)</th>
                        <td>{{ formatDate(lcRecord?.cfr_value) }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">LC Margin (BDT)</th>
                        <td>{{ lcRecord?.lc_margin }}</td>
                    </tr>


                    
                    <tr v-for="(scmLcRecordLine,index) in lcRecord?.scmLcRecordLines" :key="index">
                        <th class="w-40">{{ scmLcRecordLine?.particular }}</th>
                        <td>{{ scmLcRecordLine?.amount }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Total Costs Relating To LC ( a + b + c + d)</th>
                        <td>{{ lcRecord?.total_cost }}</td>
                    </tr>
                    
                    <tr>
                        <th class="w-40">Documents Value (CFR - Margin) (BDT)</th>
                        <td>{{ lcRecord?.document_value }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Documents Value (CFR - Margin) (BDT)</th>
                        <td>{{ lcRecord?.document_value }}</td>
                    </tr>



                    
                    <tr>
                        <th class="w-40">Exchange Rate (BDT/USD)</th>
                        <td>{{ lcRecord?.exchange_rate }}</td>
                    </tr>

                    
                    <tr>
                        <th class="w-40">Market Rate (BDT/USD)</th>
                        <td>{{ lcRecord?.market_rate }}</td>
                    </tr>



                    
                    
                </tbody>
            </table>
        </div>

        
        <div class="w-full mt-2">
            <table class="w-full">
                <thead>
                    <tr>
                        <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Material Details</td>
                    </tr>
                </thead>
                <tbody>
                    
                    <tr v-for="(poMaterial, index) in lcRecord?.scmPo?.scmPoItems" :key="index">
                        <th class="w-40">{{ poMaterial?.scmMaterial?.name }}</th>
                        <td>{{ poMaterial?.scmMaterial?.hs_code }}</td>
                    </tr>

                </tbody>
            </table>
        </div>
        </div>




        
        
</div>
</template>









<style lang="postcss" scoped>
    .input-group {
        @apply flex flex-col justify-center w-full md:flex-row md:gap-2;
    }
    .label-group {
        @apply block w-full mt-3 text-sm;
    }
    .label-item-title {
        @apply text-gray-700 dark-disabled:text-gray-300 text-sm;
    }
    .label-item-input {
        @apply block w-full mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray disabled:opacity-50 disabled:bg-gray-200 disabled:cursor-not-allowed dark-disabled:disabled:bg-gray-900;
    }
    .form-input {
        @apply block mt-1 text-sm rounded dark-disabled:text-gray-300 dark-disabled:border-gray-600 dark-disabled:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark-disabled:focus:shadow-outline-gray;
    }
    .form-fieldset {
      @apply px-4 pb-4 mt-3 border border-gray-700 rounded dark-disabled:border-gray-400;
    }
    .form-legend {
      @apply px-2 text-gray-700 dark-disabled:text-gray-300;
    }
    .vs__selected{
    display: none !important;
    }
    .required-style{
        @apply text-red-400 font-semibold
    }

    table tr,td,th {
        @apply border border-gray-300
    }
    .table_tr {
      @apply text-gray-700 dark-disabled:text-gray-400;
    }
    .table_head_tr {
      @apply text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 dark-disabled:text-gray-400 dark-disabled:bg-gray-800;
    }
    .table_body {
      @apply bg-white divide-y dark-disabled:divide-gray-700 dark-disabled:bg-gray-800;
    }
    .remove_button {
      @apply px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple;
    }
    .add_button {
      @apply px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple;
    }

    th, td, tr {
      @apply text-left border-gray-500
    }

    th.text-center, td.text-center, tr.text-center {
      @apply text-center border-gray-500
    }
  
    th.text-right, td.text-right, tr.text-right {
      @apply text-right border-gray-500
    }
</style>
 