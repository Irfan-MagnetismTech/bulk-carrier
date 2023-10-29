<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Voyage</h2>
    <default-button :title="'Voyage List'" :to="{ name: 'ops.voyages.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
      <form @submit.prevent="updateVoyage(voyage, voyageId)">
          <!-- Port Form -->
          <voyage-form v-model:form="voyage" :errors="errors" :maritimeCertificateObject="maritimeCertificateObject" :bunkerObject="bunkerObject" :formType="formType"></voyage-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import VoyageForm from '../../../components/operations/VoyageForm.vue';
import useVoyage from '../../../composables/operations/useVoyage';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const voyageId = route.params.voyageId;
const { voyage, maritimeCertificateObject, bunkerObject, showVoyage, updateVoyage, errors } = useVoyage();

const { setTitle } = Title();

const formType = 'edit';

setTitle('Edit Voyage');

onMounted(() => {
  showVoyage(voyageId);
});
</script>