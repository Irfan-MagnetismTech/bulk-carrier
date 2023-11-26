<template>
  <!-- Heading -->
  
  <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Add Voyage</h2>
        <default-button :title="'Voyage List'" :to="{ name: 'ops.voyages.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <form @submit.prevent="storeVoyage(voyage)">
          <!-- Port Form -->
          <voyage-form v-model:form="voyage" :formType="formType" :errors="errors" :voyageSectorObject="voyageSectorObject" :portScheduleObject="portScheduleObject" :bunkerObject="bunkerObject"></voyage-form>
          <!-- Submit button -->
      </form>
  </div>
</template>
<script setup>
import VoyageForm from '../../../components/operations/VoyageForm.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useVoyage from '../../../composables/operations/useVoyage';
const icons = useHeroIcon();

const { voyage, portScheduleObject, voyageSectorObject, bunkerObject, storeVoyage, isLoading, errors } = useVoyage();

const { setTitle } = Title();

setTitle('Create Voyage');

const formType = 'create';
</script>