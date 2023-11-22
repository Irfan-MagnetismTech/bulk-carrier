<template>
  <!-- Heading -->
  
  <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 ">Add Maritime Certificates</h2>
      <default-button :title="'Maritime Certificates List'" :to="{ name: 'ops.maritime-certifications.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
      <form @submit.prevent="storeMaritimeCertificate(maritimeCertificate)">
          <!-- Port Form -->
          <maritime-certificate-form v-model:form="maritimeCertificate" :errors="errors"></maritime-certificate-form>
          <!-- Submit button -->
          <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
      </form>
  </div>
</template>
<script setup>
import MaritimeCertificateForm from '../../../components/operations/MaritimeCertificateForm.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useMaritimeCertificate from '../../../composables/operations/useMaritimeCertificate';
const icons = useHeroIcon();

const { maritimeCertificate, storeMaritimeCertificate, isLoading, errors } = useMaritimeCertificate();

const { setTitle } = Title();

setTitle('Create Maritime Certificates');
</script>