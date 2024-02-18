<template>
  <!-- Heading -->
  
  <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Customer Invoice</h2>
      <default-button :title="'Customer Invoices List'" :to="{ name: 'ops.customer-invoices.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <form @submit.prevent="storeCustomerInvoice(customerInvoice)">
          <!-- Port Form -->
          <customer-invoice-form v-model:form="customerInvoice" :errors="errors" :formType="formType" :serviceObject="serviceObject" :otherObject="otherObject" :customerInvoiceVoyageObject="customerInvoiceVoyageObject"></customer-invoice-form>
          <!-- Submit button -->
          <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import CustomerInvoiceForm from '../../../components/operations/CustomerInvoiceForm.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useCustomerInvoice from '../../../composables/operations/useCustomerInvoice';
const icons = useHeroIcon();

const { customerInvoice, storeCustomerInvoice, isLoading, errors, otherObject, serviceObject , customerInvoiceVoyageObject} = useCustomerInvoice();
const { setTitle } = Title();

setTitle('Create Customer Invoices');

const formType = 'create';

</script>