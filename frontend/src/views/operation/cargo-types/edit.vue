<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Cargo Type</h2>
    <default-button :title="'Cargo Type List'" :to="{ name: 'ops.configurations.cargo-types.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
      <form @submit.prevent="updateCargoType(cargoType, cargoTypeId)">
          <!-- Port Form -->
          <cargo-type-form v-model:form="cargoType" :errors="errors"></cargo-type-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import CargoTypeForm from '../../../components/operations/configurations/CargoTypeForm.vue';
import useCargoType from '../../../composables/operations/useCargoType';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const cargoTypeId = route.params.cargoTypeId;
const { cargoType, showCargoType, updateCargoType, errors } = useCargoType();

const { setTitle } = Title();

setTitle('Edit Cargo Type');

onMounted(() => {
  showCargoType(cargoTypeId);
});
</script>