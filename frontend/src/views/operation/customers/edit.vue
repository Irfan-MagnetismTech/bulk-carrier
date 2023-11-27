<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Customer</h2>
    <default-button :title="'Customer List'" :to="{ name: 'ops.configurations.customers.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <form @submit.prevent="updateCustomer(customer, customerId)">
          <!-- Port Form -->
          <customer-form v-model:form="customer" :errors="errors" :formType="formType"></customer-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
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

setTitle('Edit Customer');

const formType = 'edit';


onMounted(() => {
  showCustomer(customerId);
});
</script>