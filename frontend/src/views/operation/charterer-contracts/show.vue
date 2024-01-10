<template>
   <div class="flex items-center justify-between w-full my-3" v-once>
      <h2 class="text-2xl font-semibold text-gray-700">Charterer Contract Details</h2>
      <default-button :title="'Charterer Contract Index'" :to="{ name: 'ops.charterer-contracts.index' }" :icon="icons.DataBase"></default-button>
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
                      <td><span :class="chartererContract?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ chartererContract?.business_unit }}</span></td>
                  </tr>
                  <tr>
                      <th class="w-40">Contract Name</th>
                      <td>{{  chartererContract.contract_name }}</td>
                  </tr>
                  <tr>
                      <th class="w-40">Contract Type</th>
                      <td>{{  chartererContract.contract_type }}</td>
                  </tr>
                  <tr>
                      <th class="w-40">Vessel</th>
                      <td>{{  chartererContract.opsVessel?.name }}</td>
                  </tr>
                  <tr>
                      <th class="w-40">Vessel Owner</th>
                      <td>{{  chartererContract.opsVessel?.owner_name }}</td>
                  </tr>
                  <tr>
                      <th class="w-40">Charterer Name</th>
                      <td>{{  chartererContract.opsChartererProfile?.name }}</td>
                  </tr>
                  <tr>
                      <th class="w-40">Charterer Owner Code</th>
                      <td>{{  chartererContract.opsChartererProfile?.owner_code }}</td>
                  </tr>
                  <tr>
                      <th class="w-40">Country</th>
                      <td>{{  chartererContract.country }}</td>
                  </tr>
                  <tr>
                      <th class="w-40">Address</th>
                      <td>{{  chartererContract.address }}</td>
                  </tr>
                  <tr>
                      <th class="w-40">Billing Address</th>
                      <td>{{  chartererContract.billing_address }}</td>
                  </tr>
                  <tr>
                      <th class="w-40">Email</th>
                      <td>{{  chartererContract.email }}</td>
                  </tr>
                  <tr>
                      <th class="w-40">Contact No.</th>
                      <td>{{  chartererContract.contact_no }}</td>
                  </tr>
                  <tr>
                      <th class="w-40">Attachment</th>
                      <td> <a download :href="env.BASE_API_URL+chartererContract.attachment" target="_blank" class="mt-1 inline bg-blue-500 hover:bg-blue-700 duration-150 text-white p-1 text-xs rounded-md">
                        Download Attachment
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                      </a></td>
                  </tr>
                  <tr v-if="chartererContract.opsChartererContractsFinancialTerms?.bunker_provider ">
                    <th class="w-40">Bunker Provider</th>
                    <td>{{ chartererContract.opsChartererContractsFinancialTerms?.bunker_provider }}</td>
                  </tr>   
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="chartererContract">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Bank Account Info</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="w-40">Bank Name</th>
                    <td>{{ chartererContract?.bank_name }}</td>
                </tr>
                <tr>
                    <th class="w-40">Bank Branch</th>
                    <td>{{ chartererContract?.bank_branch_name }}</td>
                </tr>               
                <tr>
                    <th class="w-40">Account No.</th>
                    <td>{{ chartererContract?.bank_account_no }}</td>
                </tr>
                <tr>
                    <th class="w-40">Account Name</th>
                    <td>{{ chartererContract?.bank_account_name }}</td>
                </tr>
                <tr>
                    <th class="w-40">Swift Code</th>
                    <td>{{ chartererContract?.swift_code }}</td>
                </tr>
                <tr>
                    <th class="w-40">Routing No</th>
                    <td>{{ chartererContract?.routing_no }}</td>
                </tr>
                <tr>
                    <th class="w-40">Currency</th>
                    <td>{{ chartererContract?.currency }}</td>
                </tr>
               
            </tbody>
          </table>
        </div>
    </div>

    <div class="flex md:gap-4 mt-1 md:mt-2" v-if="chartererContract">
        <div class="w-full">
          <table class="w-full">
            <thead>
                <tr>
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Local Agent Info</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="w-40">Port</th>
                    <td>{{ chartererContract.opsChartererContractsLocalAgents[0].opsPort?.code_name }}</td>
                </tr>
                <tr>
                    <th class="w-40">Agent Name</th>
                    <td>{{ chartererContract.opsChartererContractsLocalAgents[0].agent_name }}</td>
                </tr>
                <tr>
                    <th class="w-40">Billing Name</th>
                    <td>{{ chartererContract.opsChartererContractsLocalAgents[0].agent_billing_name }}</td>
                </tr>
                <tr>
                    <th class="w-40">Billing Email</th>
                    <td>{{ chartererContract.opsChartererContractsLocalAgents[0].agent_billing_email }}</td>
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
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Contract Validity and Billing</td>
                </tr>
            </thead>
            <tbody v-if="chartererContract.contract_type == 'Day Wise'">
                <tr>
                    <th class="w-40">Credit Days</th>
                    <td>{{ chartererContract.opsChartererContractsFinancialTerms.credit_days }}</td>
                </tr>
                <tr>
                    <th class="w-40">Billing Cycle</th>
                    <td>{{ chartererContract.opsChartererContractsFinancialTerms.billing_cycle }}</td>
                </tr>
                <tr>
                    <th class="w-40">Valid From</th>
                    <td>{{ formatDate(chartererContract.opsChartererContractsFinancialTerms.valid_from) }}</td>
                </tr>                               
                <tr>
                    <th class="w-40">Valid Till</th>
                    <td>{{ formatDate(chartererContract.opsChartererContractsFinancialTerms.valid_till) }}</td>
                </tr>                               
            </tbody>
            <tbody v-else>
                <tr>
                    <th class="w-40">Status</th>
                    <td>{{ chartererContract.status }}</td>
                </tr>
                <tr v-if="chartererContract.contract_type == 'Voyage Wise'">
                    <th class="w-40">Approximate Load Amount</th>
                    <td>{{ chartererContract.opsChartererContractsFinancialTerms.approximate_load_amount }}</td>
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
                    <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Rates and Fees</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="w-40">Per Day Charge</th>
                    <td>{{ chartererContract.opsChartererContractsFinancialTerms.per_day_charge }}</td>
                </tr>
                <tr>
                    <th class="w-40">Per MT Charge</th>
                    <td>{{ chartererContract.opsChartererContractsFinancialTerms.per_ton_charge }}</td>
                </tr>
                <tr v-if="chartererContract.contract_type == 'Voyage Wise'">
                    <th class="w-40">Cleaning Fee</th>
                    <td>{{ chartererContract.opsChartererContractsFinancialTerms.cleaning_fee }}</td>
                </tr>
                <tr v-if="chartererContract.contract_type == 'Voyage Wise'">
                    <th class="w-40">Cancellation Fee <small>(%)</small></th>
                    <td>{{ chartererContract.opsChartererContractsFinancialTerms.cancellation_fee }}</td>
                </tr>
                <tr v-if="chartererContract.contract_type == 'Voyage Wise'">
                    <th class="w-40">Others Fee</th>
                    <td>{{ chartererContract.opsChartererContractsFinancialTerms.others_fee }}</td>
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
import { ref, watchEffect, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useChartererContract from '../../../composables/operations/useChartererContract';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';
import { formatDate } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const chartererContractId = route.params.chartererContractId;
const { chartererContract, showChartererContract, downloadChartererContractAttachment, errors } = useChartererContract();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Charterer Contract Details');

onMounted(() => {
    showChartererContract(chartererContractId)
});

function dlChartererContractAttachment(chartererContactId) {
  downloadChartererContractAttachment(chartererContactId)
}

</script>