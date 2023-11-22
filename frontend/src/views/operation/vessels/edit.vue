<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Vessel</h2>
    <default-button :title="'Vessel List'" :to="{ name: 'ops.vessels.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <form @submit.prevent="updateVessel(vessel, vesselId)">
          <!-- Port Form -->
          <vessel-form v-model:form="vessel" :errors="errors" :maritimeCertificateObject="maritimeCertificateObject" :bunkerObject="bunkerObject" :formType="formType"></vessel-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import VesselForm from '../../../components/operations/VesselForm.vue';
import useVessel from '../../../composables/operations/useVessel';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const vesselId = route.params.vesselId;
const { vessel, maritimeCertificateObject, bunkerObject, showVessel, updateVessel, errors } = useVessel();

const { setTitle } = Title();

const formType = 'edit';

setTitle('Edit Vessel');

onMounted(() => {
  showVessel(vesselId);
});
</script>