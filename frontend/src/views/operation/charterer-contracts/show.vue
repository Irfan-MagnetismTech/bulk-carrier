<template>
   <div class="flex items-center justify-between w-full my-3" v-once>
      <h2 class="text-2xl font-semibold text-gray-700">Charterer Contract Details</h2>
      <default-button :title="'Charterer Contract Index'" :to="{ name: 'ops.charterer-contracts.index' }" :icon="icons.DataBase"></default-button>
    </div>

    <div id="basic-info">
      <h4 class="text-md font-semibold">Basic Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          
          <label class="block w-full mt-2 text-sm">
            <span class="show-block">{{ chartererContract.business_unit }}</span>
          </label>
          <label class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>

      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Contract Name</span>
              <span class="show-block">{{ chartererContract.contract_name }}</span>

          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Contract Type</span>
              <span class="show-block">{{ chartererContract.contract_type }}</span>

          </label>


          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel </span>
              
              <span class="show-block">{{ chartererContract.opsVessel?.name }}</span>

          </label>

          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Vessel Owner</span>
              <span class="show-block">{{ chartererContract.opsVessel?.owner_name }}</span>

          </label>

          
          
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Charterer Name</span>
              <span class="show-block">{{ chartererContract.opsChartererProfile?.name }}</span>


          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Charterer Owner Code</span>
              <span class="show-block">{{ chartererContract.opsChartererProfile?.owner_code }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Country</span>

          <span class="show-block">{{ chartererContract.country }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Address</span>
            <span class="show-block">{{ chartererContract.address }}</span>

        </label>
        
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Billing Address</span>
              <span class="show-block">{{ chartererContract.billing_address }}</span>

        </label>
        
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Email</span>
              <span class="show-block">{{ chartererContract.email }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Contact No.</span>
            <span class="show-block">{{ chartererContract.contact_no }}</span>

        </label>

        <label v-if="chartererContract?.attachment" class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Attachment </span>
              <!-- <button @click="dlChartererContractAttachment(chartererContract.id)" class="mt-1 flex bg-blue-500 hover:bg-blue-700 duration-150 text-white p-1 text-xs rounded-md">
                Download Attachment
                <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
              </button> -->
              <a download :href="env.BASE_API_URL+chartererContract.attachment" target="_blank" class="mt-1 flex bg-blue-500 hover:bg-blue-700 duration-150 text-white p-1 text-xs rounded-md">
                Download Attachment
                <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
              </a>
          </label>
      </div>
    </div>
    
    <div id="bank-account-info">
      <h4 class="text-md font-semibold mt-4">Bank Account Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Bank Name</span>
              <span class="show-block">{{ chartererContract.bank_name }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Bank Branch </span>
              <span class="show-block">{{ chartererContract.bank_branch_name }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Account No</span>
            <span class="show-block">{{ chartererContract.bank_account_no }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Account Name</span>
              <span class="show-block">{{ chartererContract.bank_account_name }}</span>

          </label>
          
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Swift Code</span>
              <span class="show-block">{{ chartererContract.swift_code }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Routing No</span>
            <span class="show-block">{{ chartererContract.routing_no }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Currency</span>
              <span class="show-block">{{ chartererContract.currency }}</span>

        </label>
        <label class="block w-full mt-2 text-sm"></label>
      </div>
    </div>

    <div id="local-agent-info">
      <h4 class="text-md font-semibold mt-4">Local Agent Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Port </span>
              <span class="show-block">{{ chartererContract.opsChartererContractsLocalAgents[0].opsPort?.code_name }}</span>
          </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Agent Name</span>
              <span class="show-block">{{ chartererContract.opsChartererContractsLocalAgents[0].agent_name }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Billing Name </span>
              <span class="show-block">{{ chartererContract.opsChartererContractsLocalAgents[0].agent_billing_name }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Billing Email</span>
            <span class="show-block">{{ chartererContract.opsChartererContractsLocalAgents[0].agent_billing_email }}</span>
            
        </label>
      </div>
    </div>

    <div id="contract-validity">
      <h4 class="text-md font-semibold mt-4">Contract Validity and Billing</h4>
      <div v-if="chartererContract.contract_type == 'Day Wise'" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Credit Days</span>
              <span class="show-block">{{ chartererContract.opsChartererContractsFinancialTerms.credit_days }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Billing Cycle </span>
              <span class="show-block">{{ chartererContract.opsChartererContractsFinancialTerms.billing_cycle }}</span>
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Valid From</span>
            <span class="show-block">{{ chartererContract.opsChartererContractsFinancialTerms.valid_from }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Valid Till</span>
            <span class="show-block">{{ chartererContract.opsChartererContractsFinancialTerms.valid_till }}</span>

        </label>
      </div>
      <div v-if="chartererContract.contract_type == 'Voyage Wise'" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Cargo Tariff </span>
              <span class="show-block">{{ chartererContract.opsChartererContractsFinancialTerms.opsCargoTariff?.tariff_name }}</span>

          </label>
        
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300"> Status</span>
              <span class="show-block">{{ chartererContract.status }}</span>

          </label>
          <label v-if="chartererContract.contract_type == 'Day Wise'" class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Approximate Load Amount</span>
            <span class="show-block">{{ chartererContract.opsChartererContractsFinancialTerms.approximate_load_amount }}</span>

          </label>
          <label v-else class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>
      </div>
    </div>

    <div id="rates-fess" class="mb-10">
      <h4 class="text-md font-semibold mt-4">Rates and Fees</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label v-if="chartererContract.contract_type == 'Day Wise'" class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Per Day Charge</span>
              <span class="show-block">{{ chartererContract.opsChartererContractsFinancialTerms.per_day_charge }}</span>

        </label>
        <label v-if="chartererContract.contract_type == 'Voyage Wise'" class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Per MT Charge</span>
              <span class="show-block">{{ chartererContract.opsChartererContractsFinancialTerms.per_ton_charge }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Cleaning Fee </span>
              <span class="show-block">{{ chartererContract.opsChartererContractsFinancialTerms.cleaning_fee }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Cancellation Fee <small>(%)</small></span>
            <span class="show-block">{{ chartererContract.opsChartererContractsFinancialTerms.cancellation_fee }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Others Fee</span>
            <span class="show-block">{{ chartererContract.opsChartererContractsFinancialTerms.others_fee }}</span>

        </label>
      </div>

      <div v-if="chartererContract.opsChartererContractsFinancialTerms?.bunker_provider " class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300 font-semibold">Bunker Provider</span>
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="">
            <input type="radio" checked disabled />
            {{ chartererContract.opsChartererContractsFinancialTerms?.bunker_provider }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
            
        </label>
        <label class="block w-full mt-2 text-sm"></label>
      </div>
    </div>
</template>
<script setup>
import { ref, watchEffect, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useChartererContract from '../../../composables/operations/useChartererContract';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';

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