<template>
   <div class="flex items-center justify-between w-full my-3" v-once>
      <h2 class="text-2xl font-semibold text-gray-700">Lighter Noon Report Details</h2>
      <default-button :title="'Lighter Noon Report Index'" :to="{ name: 'ops.lighter-noon-reports.index' }" :icon="icons.DataBase"></default-button>
    </div>

    <div id="basic-info">
      <h4 class="text-md font-semibold">Basic Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          
          <label class="block w-full mt-2 text-sm">
            <span class="show-block">{{ lighterNoonReport.business_unit }}</span>
          </label>
          <label class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>

      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Contract Name</span>
              <span class="show-block">{{ lighterNoonReport.contract_name }}</span>

          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Contract Type</span>
              <span class="show-block">{{ lighterNoonReport.contract_type }}</span>

          </label>


          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Select Vessel <span class="text-red-500">*</span></span>
              
              <span class="show-block">{{ lighterNoonReport.opsVessel?.name }}</span>

          </label>

          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Vessel Owner</span>
              <span class="show-block">{{ lighterNoonReport.opsVessel?.owner_name }}</span>

          </label>

          
          
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Select Charterer <span class="text-red-500">*</span></span>
              <span class="show-block">{{ lighterNoonReport.opsChartererProfile?.name }}</span>


          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Charterer Code</span>
              <span class="show-block">{{ lighterNoonReport.opsChartererProfile?.owner_code }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Country</span>

          <span class="show-block">{{ lighterNoonReport.country }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Address</span>
            <span class="show-block">{{ lighterNoonReport.address }}</span>

        </label>
        
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Billing Address</span>
              <span class="show-block">{{ lighterNoonReport.billing_address }}</span>

        </label>
        
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Email</span>
              <span class="show-block">{{ lighterNoonReport.email }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Contact No.</span>
            <span class="show-block">{{ lighterNoonReport.contact_no }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Attachment </span>
              <a download href="" class="block text-blue-500 hover:text-blue-700 ease-linear duration-200 font-semibold mt-2.5">Download Attachment</a>

          </label>
      </div>
    </div>
    
    <div id="bank-account-info">
      <h4 class="text-md font-semibold mt-4">Bank Account Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Bank Name</span>
              <span class="show-block">{{ lighterNoonReport.bank_name }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Bank Branch </span>
              <span class="show-block">{{ lighterNoonReport.bank_branch_name }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Account No</span>
            <span class="show-block">{{ lighterNoonReport.bank_account_no }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Account Name</span>
              <span class="show-block">{{ lighterNoonReport.bank_account_name }}</span>

          </label>
          
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Swift Code</span>
              <span class="show-block">{{ lighterNoonReport.swift_code }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Routing No</span>
            <span class="show-block">{{ lighterNoonReport.routing_no }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Currency</span>
              <span class="show-block">{{ lighterNoonReport.currency }}</span>

        </label>
        <label class="block w-full mt-2 text-sm"></label>
      </div>
    </div>

    <div id="local-agent-info">
      <h4 class="text-md font-semibold mt-4">Local Agent Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Port <span class="text-red-500">*</span></span>
              <span class="show-block">{{ lighterNoonReport.opsLighterNoonReportsLocalAgents[0].port_code }}</span>
          </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Agent Name</span>
              <span class="show-block">{{ lighterNoonReport.opsLighterNoonReportsLocalAgents[0].agent_name }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Billing Name </span>
              <span class="show-block">{{ lighterNoonReport.opsLighterNoonReportsLocalAgents[0].agent_billing_name }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Billing Email</span>
            <span class="show-block">{{ lighterNoonReport.opsLighterNoonReportsLocalAgents[0].agent_billing_email }}</span>
            
        </label>
      </div>
    </div>

    <div id="contract-validity">
      <h4 class="text-md font-semibold mt-4">Contract Validity and Billing</h4>
      <div v-if="lighterNoonReport.contract_type == 'Day Wise'" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Credit Days</span>
              <span class="show-block">{{ lighterNoonReport.opsLighterNoonReportsFinancialTerms.credit_days }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Billing Cycle </span>
              <span class="show-block">{{ lighterNoonReport.opsLighterNoonReportsFinancialTerms.billing_cycle }}</span>
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Valid From</span>
            <span class="show-block">{{ lighterNoonReport.opsLighterNoonReportsFinancialTerms.valid_from }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Valid Till</span>
            <span class="show-block">{{ lighterNoonReport.opsLighterNoonReportsFinancialTerms.valid_till }}</span>

        </label>
      </div>
      <div v-if="lighterNoonReport.contract_type == 'Voyage Wise'" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Cargo Tariff </span>
              <span class="show-block">{{ lighterNoonReport.opsLighterNoonReportsFinancialTerms.opsCargoTariff?.tariff_name }}</span>

          </label>
        
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300"> Status</span>
              <span class="show-block">{{ lighterNoonReport.status }}</span>

          </label>
          <label v-if="lighterNoonReport.contract_type == 'Day Wise'" class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Approximate Load Amount</span>
            <span class="show-block">{{ lighterNoonReport.opsLighterNoonReportsFinancialTerms.approximate_load_amount }}</span>

          </label>
          <label v-else class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>
      </div>
    </div>

    <div id="rates-fess" class="mb-10">
      <h4 class="text-md font-semibold mt-4">Rates and Fees</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label v-if="lighterNoonReport.contract_type == 'Day Wise'" class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Per Day Charge</span>
              <span class="show-block">{{ lighterNoonReport.opsLighterNoonReportsFinancialTerms.per_day_charge }}</span>

        </label>
        <label v-if="lighterNoonReport.contract_type == 'Voyage Wise'" class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Per MT Charge</span>
              <span class="show-block">{{ lighterNoonReport.opsLighterNoonReportsFinancialTerms.per_ton_charge }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300">Cleaning Fee </span>
              <span class="show-block">{{ lighterNoonReport.opsLighterNoonReportsFinancialTerms.cleaning_fee }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Cancellation Fee <small>(%)</small></span>
            <span class="show-block">{{ lighterNoonReport.opsLighterNoonReportsFinancialTerms.cancellation_fee }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Others Fee</span>
            <span class="show-block">{{ lighterNoonReport.opsLighterNoonReportsFinancialTerms.others_fee }}</span>

        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark:text-gray-300 font-semibold">Bunker Provider</span>
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="">
            <input type="radio" checked disabled />
            {{ lighterNoonReport.opsLighterNoonReportsFinancialTerms?.bunker_provider }}</span>

              
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
import useLighterNoonReport from '../../../composables/operations/useLighterNoonReport';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";

const icons = useHeroIcon();

const route = useRoute();
const lighterNoonReportId = route.params.lighterNoonReportId;
const { lighterNoonReport, showLighterNoonReport, errors } = useLighterNoonReport();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Lighter Noon Report Details');

onMounted(() => {
    showLighterNoonReport(lighterNoonReportId)
});
</script>