<template>
   <div class="flex items-center justify-between w-full my-3" v-once>
      <h2 class="text-2xl font-semibold text-gray-700">Voyage Boat Note Details</h2>
      <default-button :title="'Voyage Boat Note Index'" :to="{ name: 'ops.voyage-boat-notes.index' }" :icon="icons.DataBase"></default-button>
    </div>

    <div id="basic-info">
      <h4 class="text-md font-semibold">Basic Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          
          <label class="block w-full mt-2 text-sm">
            <span class="show-block">{{ voyageBoatNote.business_unit }}</span>
          </label>
          <label class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>

      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Contract Name</span>
              <span class="show-block">{{ voyageBoatNote.contract_name }}</span>

          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Contract Type</span>
              <span class="show-block">{{ voyageBoatNote.contract_type }}</span>

          </label>


          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Select Vessel <span class="text-red-500">*</span></span>
              
              <span class="show-block">{{ voyageBoatNote.opsVessel?.name }}</span>

          </label>

          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Vessel Owner</span>
              <span class="show-block">{{ voyageBoatNote.opsVessel?.owner_name }}</span>

          </label>

          
          
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Select Charterer <span class="text-red-500">*</span></span>
              <span class="show-block">{{ voyageBoatNote.opsChartererProfile?.name }}</span>


          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Charterer Code</span>
              <span class="show-block">{{ voyageBoatNote.opsChartererProfile?.owner_code }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 ">Country</span>

          <span class="show-block">{{ voyageBoatNote.country }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Address</span>
            <span class="show-block">{{ voyageBoatNote.address }}</span>

        </label>
        
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Billing Address</span>
              <span class="show-block">{{ voyageBoatNote.billing_address }}</span>

        </label>
        
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Email</span>
              <span class="show-block">{{ voyageBoatNote.email }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Contact No.</span>
            <span class="show-block">{{ voyageBoatNote.contact_no }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Attachment </span>
              <a download href="" class="block text-blue-500 hover:text-blue-700 ease-linear duration-200 font-semibold mt-2.5">Download Attachment</a>

          </label>
      </div>
    </div>
    
    <div id="bank-account-info">
      <h4 class="text-md font-semibold mt-4">Bank Account Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Bank Name</span>
              <span class="show-block">{{ voyageBoatNote.bank_name }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Bank Branch </span>
              <span class="show-block">{{ voyageBoatNote.bank_branch_name }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Account No</span>
            <span class="show-block">{{ voyageBoatNote.bank_account_no }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Account Name</span>
              <span class="show-block">{{ voyageBoatNote.bank_account_name }}</span>

          </label>
          
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Swift Code</span>
              <span class="show-block">{{ voyageBoatNote.swift_code }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Routing No</span>
            <span class="show-block">{{ voyageBoatNote.routing_no }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Currency</span>
              <span class="show-block">{{ voyageBoatNote.currency }}</span>

        </label>
        <label class="block w-full mt-2 text-sm"></label>
      </div>
    </div>

    <div id="local-agent-info">
      <h4 class="text-md font-semibold mt-4">Local Agent Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Port <span class="text-red-500">*</span></span>
              <span class="show-block">{{ voyageBoatNote.opsVoyageBoatNotesLocalAgents[0].port_code }}</span>
          </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Agent Name</span>
              <span class="show-block">{{ voyageBoatNote.opsVoyageBoatNotesLocalAgents[0].agent_name }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Billing Name </span>
              <span class="show-block">{{ voyageBoatNote.opsVoyageBoatNotesLocalAgents[0].agent_billing_name }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Billing Email</span>
            <span class="show-block">{{ voyageBoatNote.opsVoyageBoatNotesLocalAgents[0].agent_billing_email }}</span>
            
        </label>
      </div>
    </div>

    <div id="contract-validity">
      <h4 class="text-md font-semibold mt-4">Contract Validity and Billing</h4>
      <div v-if="voyageBoatNote.contract_type == 'Day Wise'" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Credit Days</span>
              <span class="show-block">{{ voyageBoatNote.opsVoyageBoatNotesFinancialTerms.credit_days }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Billing Cycle </span>
              <span class="show-block">{{ voyageBoatNote.opsVoyageBoatNotesFinancialTerms.billing_cycle }}</span>
          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Valid From</span>
            <span class="show-block">{{ voyageBoatNote.opsVoyageBoatNotesFinancialTerms.valid_from }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Valid Till</span>
            <span class="show-block">{{ voyageBoatNote.opsVoyageBoatNotesFinancialTerms.valid_till }}</span>

        </label>
      </div>
      <div v-if="voyageBoatNote.contract_type == 'Voyage Wise'" class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Cargo Tariff </span>
              <span class="show-block">{{ voyageBoatNote.opsVoyageBoatNotesFinancialTerms.opsCargoTariff?.tariff_name }}</span>

          </label>
        
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 "> Status</span>
              <span class="show-block">{{ voyageBoatNote.status }}</span>

          </label>
          <label v-if="voyageBoatNote.contract_type == 'Day Wise'" class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Approximate Load Amount</span>
            <span class="show-block">{{ voyageBoatNote.opsVoyageBoatNotesFinancialTerms.approximate_load_amount }}</span>

          </label>
          <label v-else class="block w-full mt-2 text-sm"></label>
          <label class="block w-full mt-2 text-sm"></label>
      </div>
    </div>

    <div id="rates-fess" class="mb-10">
      <h4 class="text-md font-semibold mt-4">Rates and Fees</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label v-if="voyageBoatNote.contract_type == 'Day Wise'" class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Per Day Charge</span>
              <span class="show-block">{{ voyageBoatNote.opsVoyageBoatNotesFinancialTerms.per_day_charge }}</span>

        </label>
        <label v-if="voyageBoatNote.contract_type == 'Voyage Wise'" class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Per MT Charge</span>
              <span class="show-block">{{ voyageBoatNote.opsVoyageBoatNotesFinancialTerms.per_ton_charge }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 ">Cleaning Fee </span>
              <span class="show-block">{{ voyageBoatNote.opsVoyageBoatNotesFinancialTerms.cleaning_fee }}</span>

          </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Cancellation Fee <small>(%)</small></span>
            <span class="show-block">{{ voyageBoatNote.opsVoyageBoatNotesFinancialTerms.cancellation_fee }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 ">Others Fee</span>
            <span class="show-block">{{ voyageBoatNote.opsVoyageBoatNotesFinancialTerms.others_fee }}</span>

        </label>
      </div>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700  font-semibold">Bunker Provider</span>
        </label>
        <label class="block w-full mt-2 text-sm">
          <span class="">
            <input type="radio" checked disabled />
            {{ voyageBoatNote.opsVoyageBoatNotesFinancialTerms?.bunker_provider }}</span>

              
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
import useVoyageBoatNote from '../../../composables/operations/useVoyageBoatNote';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";

const icons = useHeroIcon();

const route = useRoute();
const voyageBoatNoteId = route.params.voyageBoatNoteId;
const { voyageBoatNote, showVoyageBoatNote, errors } = useVoyageBoatNote();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Voyage Boat Note Details');

onMounted(() => {
    showVoyageBoatNote(voyageBoatNoteId)
});
</script>