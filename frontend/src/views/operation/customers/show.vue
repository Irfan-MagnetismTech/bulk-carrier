<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Customer Details</h2>
    <default-button :title="'Customer List'" :to="{ name: 'ops.configurations.customers.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
    <h4 class="text-md font-semibold">Customer Info</h4>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
        
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Customer Code </span>
            <span class="show-block">{{ customer.code }}</span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Legal Name </span>
            <span class="show-block">{{ customer.legal_name }}</span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Customer Name </span>
            <span class="show-block">{{ customer.name }}</span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Tax Identification</span>
            <span class="show-block">{{ customer.tax_id }}</span>
        </label>
        
    </div>
    
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Postal Address</span>
            <span class="show-block">{{ customer.postal_address }}</span>
      </label>
      <label class="block w-full mt-2 text-sm">
          <span class="text-gray-700 dark-disabled:text-gray-300">Post Code</span>
          <span class="show-block">{{ customer.post_code }}</span>
      </label>
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">City</span>
            <span class="show-block">{{ customer.city }}</span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Country </span>
            <span class="show-block">{{ customer.country }}</span>
        </label>
        
        
    </div>
    <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
      <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">Business License No.</span>
            <span class="show-block">{{ customer.business_license_no }}</span>
        </label>
        <label class="block w-full mt-2 text-sm">
            <span class="text-gray-700 dark-disabled:text-gray-300">BIN/GST/SST No.</span>
            <span class="show-block">{{ customer.bin_gst_sst_no }}</span>
        </label>
        <label class="block w-full mt-2 text-sm"></label>
        <label class="block w-full mt-2 text-sm"></label>

    </div>
    <div class="mt-3 md:mt-8">
      <h4 class="text-md font-semibold">Contact Info</h4>
      <div class="flex flex-col justify-center w-full md:flex-row md:gap-2">
          
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Phone </span>
              <span class="show-block">{{ customer.phone }}</span> 
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Company Reg. No</span>
              <span class="show-block">{{ customer.company_reg_no }}</span>
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Email <small>(General)</small></span>
              <span class="show-block">{{ customer.email_general }}</span>
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Email <small>(Agreement)</small></span>
              <span class="show-block">{{ customer.email_agreement }}</span>
          </label>
          <label class="block w-full mt-2 text-sm">
              <span class="text-gray-700 dark-disabled:text-gray-300">Email <small>(Invoice)</small></span>
              <span class="show-block">{{ customer.email_invoice }}</span>
          </label>
      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import CustomerForm from '../../../components/operations/configurations/CustomerForm.vue';
import useCustomer from '../../../composables/operations/useCustomer';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const customerId = route.params.customerId;
const { customer, showCustomer, updateCustomer, errors } = useCustomer();

const { setTitle } = Title();

setTitle('Customer Details');

onMounted(() => {
  showCustomer(customerId);
});
</script>