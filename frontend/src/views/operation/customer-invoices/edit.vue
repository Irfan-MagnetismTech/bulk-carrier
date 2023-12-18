<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Customer Invoice</h2>
    <default-button :title="'Customer Invoice List'" :to="{ name: 'ops.customer-invoices.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <form @submit.prevent="updateCustomerInvoice(customerInvoice, customerInvoiceId)">
          <!-- Port Form -->
          <customer-invoice-form v-model:form="customerInvoice" :errors="errors" :formType="formType" :serviceObject="serviceObject" :otherObject="otherObject" :customerInvoiceVoyageObject="customerInvoiceVoyageObject"></customer-invoice-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import CustomerInvoiceForm from '../../../components/operations/CustomerInvoiceForm.vue';
import useCustomerInvoice from '../../../composables/operations/useCustomerInvoice';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const customerInvoiceId = route.params.customerInvoiceId;
const { customerInvoice, showCustomerInvoice, updateCustomerInvoice, errors, otherObject, serviceObject,customerInvoiceVoyageObject } = useCustomerInvoice();

const { setTitle } = Title();

setTitle('Edit Customer Invoice');

const formType = 'edit';

onMounted(() => {
  showCustomerInvoice(customerInvoiceId);
});
</script>