<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Update Maritime Certificate</h2>
    <default-button :title="'Maritime Certificate List'" :to="{ name: 'ops.maritime-certifications.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
      <form @submit.prevent="updateMaritimeCertificate(maritimeCertificate, maritimeCertificateId)">
          <!-- Port Form -->
          <maritime-certificate-form v-model:form="maritimeCertificate" :errors="errors" :formType="formType" :maritimeCertificateLineObject="maritimeCertificateLineObject"></maritime-certificate-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import MaritimeCertificateForm from '../../../components/operations/configurations/MaritimeCertificateForm.vue';
import useMaritimeCertificate from '../../../composables/operations/useMaritimeCertificate';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const maritimeCertificateId = route.params.maritimeCertificateId;
const { maritimeCertificate, maritimeCertificateLineObject, showMaritimeCertificate, updateMaritimeCertificate, errors } = useMaritimeCertificate();

const { setTitle } = Title();

setTitle('Edit Maritime Certificate');

const formType = 'edit';

onMounted(() => {
  showMaritimeCertificate(maritimeCertificateId);
});
</script>