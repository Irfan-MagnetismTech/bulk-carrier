<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 ">Update Vessel Certificate</h2>
    <default-button :title="'Vessel Certificate List'" :to="{ name: 'ops.vessel-certificates.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
      <form @submit.prevent="updateVesselCertificate(vesselCertificate, vesselCertificateId)">
          <!-- Port Form -->
          <vessel-certificate-form v-model:form="vesselCertificate" :errors="errors" :formType="formType"></vessel-certificate-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
  </div>
</template>
<script setup>
import { watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import VesselCertificateForm from '../../../components/operations/VesselCertificateForm.vue';
import useVesselCertificate from '../../../composables/operations/useVesselCertificate';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const vesselCertificateId = route.params.vesselCertificateId;
const { vesselCertificate, showVesselCertificate, updateVesselCertificate, errors } = useVesselCertificate();

const { setTitle } = Title();

setTitle('Edit Vessel Certificate');

const formType = 'edit';

onMounted(() => {
  showVesselCertificate(vesselCertificateId);
});


</script>