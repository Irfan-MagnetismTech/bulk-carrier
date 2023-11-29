<template>
   <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Charterer Profile Details</h2>
    <default-button :title="'Charterer Profile Index'" :to="{ name: 'ops.charterer-profiles.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <h4 class="text-md font-semibold">Basic Info</h4>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Business Unit </span>
            <span class="show-block">{{ chartererProfile.business_unit }}</span>
            
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Charterer Name </span>
            <span class="show-block">{{ chartererProfile.name }}</span>

            
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Company Legal Name </span>
            
            <span class="show-block">{{ chartererProfile.company_legal_name }}</span>
          
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Charterer Owner Code </span>
            
            <span class="show-block">{{ chartererProfile.owner_code }}</span>
          
        </label>
        
        
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Contact No</span>
            
            <span class="show-block">{{ chartererProfile.contact_no }}</span>
          
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Country</span>
            
            <span class="show-block">{{ chartererProfile.country }}</span>
          
        </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Address</span>
          
          <span class="show-block">{{ chartererProfile.address }}</span>
        
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Billing Address</span>
            
            <span class="show-block">{{ chartererProfile.billing_address }}</span>
          
        </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">

      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Billing Email </span>
            
            <span class="show-block">{{ chartererProfile.billing_email }}</span>
          
        </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Email</span>
            
            <span class="show-block">{{ chartererProfile.email }}</span>
          
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Website</span>
          
          <span class="show-block">{{ chartererProfile.website }}</span>
        
      </label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>
    
    <h4 class="text-md font-semibold mt-4">Bank Account Info</h4>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Bank Name</span>
            
            <span class="show-block">{{ chartererProfile.opsChartererBankAccounts[0].bank_name }}</span>

      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Bank Branch </span>
            <span class="show-block">{{ chartererProfile.opsChartererBankAccounts[0].bank_branch_name }}</span>

            
        </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Account No</span>
          <span class="show-block">{{ chartererProfile.opsChartererBankAccounts[0].account_no }}</span>
          
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Account Name</span>
            <span class="show-block">{{ chartererProfile.opsChartererBankAccounts[0].account_name }}</span>

            
        </label>
        
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Swift Code</span>
            <span class="show-block">{{ chartererProfile.opsChartererBankAccounts[0].swift_code }}</span>

            
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Routing No</span>
          <span class="show-block">{{ chartererProfile.opsChartererBankAccounts[0].routing_no }}</span>

          
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Currency</span>
            <span class="show-block">{{ chartererProfile.opsChartererBankAccounts[0].currency }}</span>
              
      </label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>
</template>
<script setup>
import { ref, watchEffect, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useChartererProfile from '../../../composables/operations/useChartererProfile';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";

const icons = useHeroIcon();

const route = useRoute();
const chartererProfileId = route.params.chartererProfileId;
const { chartererProfile, showChartererProfile, errors } = useChartererProfile();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Charterer Profile Details');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;


onMounted(() => {
  watchEffect(() => {
    showChartererProfile(chartererProfileId)
      .then(() => {
        const customDataTable = document.getElementById("customDataTable");

        if (customDataTable) {
          tableScrollWidth.value = customDataTable.scrollWidth;
        }
      })
      .catch((error) => {
        console.error("Error fetching users:", error);
      });
  });
});
</script>