<template>
  <!-- Heading -->
  
  <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Add Customer</h2>
      <default-button :title="'Customer List'" :to="{ name: 'ops.configurations.customers.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
      <form @submit.prevent="storeCustomer(customer)">
          <!-- Port Form -->
          <customer-form v-model:form="customer" :errors="errors"></customer-form>
          <!-- Submit button -->
          <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
      </form>
  </div>
</template>
<script setup>
import CustomerForm from '../../../components/operations/configurations/CustomerForm.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useCustomer from '../../../composables/operations/useCustomer';
const icons = useHeroIcon();

const { customer, storeCustomer, isLoading, errors } = useCustomer();

const { setTitle } = Title();

setTitle('Create Customer');
</script>