<template>
   <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700">Delivery Redelivery Details</h2>
    <default-button :title="'Delivery Redelivery Index'" :to="{ name: 'ops.delivery-redelivery.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <h4 class="text-md font-semibold">Basic Info</h4>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Business Unit <span class="text-red-500">*</span></span>
            <span class="show-block">{{ deliveryRedelivery.business_unit }}</span>
            
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Charterer Name <span class="text-red-500">*</span></span>
            <span class="show-block">{{ deliveryRedelivery.name }}</span>

        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Company Legal Name <span class="text-red-500">*</span></span>
            
            <span class="show-block">{{ deliveryRedelivery.company_legal_name }}</span>
          
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Charterer Owner Code <span class="text-red-500">*</span></span>
            
            <span class="show-block">{{ deliveryRedelivery.owner_code }}</span>
          
        </label>
        
        
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Contact No</span>
            
            <span class="show-block">{{ deliveryRedelivery.contact_no }}</span>
          
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Country</span>
            
            <span class="show-block">{{ deliveryRedelivery.country }}</span>
          
        </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Address</span>
          
          <span class="show-block">{{ deliveryRedelivery.address }}</span>
        
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Billing Address</span>
            
            <span class="show-block">{{ deliveryRedelivery.billing_address }}</span>
          
        </label>
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">

      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Billing Email </span>
            
            <span class="show-block">{{ deliveryRedelivery.billing_email }}</span>
          
        </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Email</span>
            
            <span class="show-block">{{ deliveryRedelivery.email }}</span>
          
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Website</span>
          
          <span class="show-block">{{ deliveryRedelivery.website }}</span>
        
      </label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>
    
    <h4 class="text-md font-semibold mt-4">Bank Account Info</h4>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Bank Name</span>
            
            <span class="show-block">{{ deliveryRedelivery.opsChartererBankAccounts[0].bank_name }}</span>

      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Bank Branch </span>
            <span class="show-block">{{ deliveryRedelivery.opsChartererBankAccounts[0].bank_branch_name }}</span>

            
        </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Account No</span>
          <span class="show-block">{{ deliveryRedelivery.opsChartererBankAccounts[0].account_no }}</span>
          
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Account Name</span>
            <span class="show-block">{{ deliveryRedelivery.opsChartererBankAccounts[0].account_name }}</span>

            
        </label>
        
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Swift Code</span>
            <span class="show-block">{{ deliveryRedelivery.opsChartererBankAccounts[0].swift_code }}</span>

            
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark:text-gray-300">Routing No</span>
          <span class="show-block">{{ deliveryRedelivery.opsChartererBankAccounts[0].routing_no }}</span>

          
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark:text-gray-300">Currency</span>
            <span class="show-block">{{ deliveryRedelivery.opsChartererBankAccounts[0].currency }}</span>
              
      </label>
      <label class="block w-full mt-2 text-sm"></label>
    </div>
</template>
<script setup>
import { ref, watchEffect, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useDeliveryRedelivery from '../../../composables/operations/useDeliveryRedelivery';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import useHelper from "../../../composables/useHelper";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";

const icons = useHeroIcon();

const route = useRoute();
const deliveryRedeliveryId = route.params.deliveryRedeliveryId;
const { deliveryRedelivery, showDeliveryRedelivery, errors } = useDeliveryRedelivery();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Delivery Redelivery Details');

const tableScrollWidth = ref(null);
const screenWidth = (screen.width > 768) ? screen.width - 260 : screen.width;


onMounted(() => {
  watchEffect(() => {
    showDeliveryRedelivery(deliveryRedeliveryId)
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