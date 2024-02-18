<template>
  <!-- Heading -->
  
  <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Vessel Particular</h2>
      <default-button :title="'Vessel Particular List'" :to="{ name: 'ops.vessel-particulars.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <form @submit.prevent="storeVesselParticular(vesselParticular)">
          <!-- Port Form -->
          <vessel-particular-form v-model:form="vesselParticular" :errors="errors" :formType="formType"></vessel-particular-form>
          <!-- Submit button -->
          <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
      </form>
  </div>
</template>
<script setup>
import VesselParticularForm from '../../../components/operations/VesselParticularForm.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useVesselParticular from '../../../composables/operations/useVesselParticular';
const icons = useHeroIcon();

const { vesselParticular, storeVesselParticular, isLoading, errors } = useVesselParticular();

const { setTitle } = Title();
const formType = 'create';

setTitle('Create Vessel Particular');
</script>