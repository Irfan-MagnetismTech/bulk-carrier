<template>
  <!-- Heading -->
  
  <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Bunker Bill</h2>
      <default-button :title="'Bunker Billss List'" :to="{ name: 'ops.bunker-bills.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 relative">
      <form @submit.prevent="storeBunkerBill(bunkerBill)">
          <!-- Port Form -->
          <bunker-bill-form v-model:form="bunkerBill" :bunkerObject="bunkerObject" :errors="errors" :formType="formType"></bunker-bill-form>
          <!-- Submit button -->
          <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import BunkerBillForm from '../../../components/operations/BunkerBillForm.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useBunkerBill from '../../../composables/operations/useBunkerBill';
const icons = useHeroIcon();

const { bunkerBill, bunkerObject, storeBunkerBill, isLoading, errors } = useBunkerBill();
const { setTitle } = Title();

setTitle('Create Bunker Bills');

const formType = 'create';

</script>