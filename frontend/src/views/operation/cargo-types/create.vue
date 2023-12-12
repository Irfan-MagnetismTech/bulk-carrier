<template>
  <!-- Heading -->
  
  <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Add Cargo Type</h2>
      <default-button :title="'Cargo Type List'" :to="{ name: 'ops.configurations.cargo-types.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <form @submit.prevent="storeCargoType(cargoType)">
          <!-- Port Form -->
          <cargo-type-form v-model:form="cargoType" :errors="errors" :formType="formType"></cargo-type-form>
          <!-- Submit button -->
          <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
      </form>
  </div>
</template>
<script setup>
import CargoTypeForm from '../../../components/operations/configurations/CargoTypeForm.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useCargoType from '../../../composables/operations/useCargoType';
const icons = useHeroIcon();

const { cargoType, storeCargoType, isLoading, errors } = useCargoType();

const { setTitle } = Title();

setTitle('Create Cargo Type');

const formType = 'create';

</script>