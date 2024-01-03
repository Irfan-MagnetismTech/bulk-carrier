<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Maritime Certificate</h2>
    <default-button :title="'Maritime Certificate List'" :to="{ name: 'ops.maritime-certifications.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">

  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useMaritimeCertificate from '../../../composables/operations/useMaritimeCertificate';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const maritimeCertificateId = route.params.maritimeCertificateId;
const { maritimeCertificate, maritimeCertificateLineObject, showMaritimeCertificate, updateMaritimeCertificate, errors } = useMaritimeCertificate();

const { setTitle } = Title();

setTitle('Maritime Certificate Details');

onMounted(() => {
  showMaritimeCertificate(maritimeCertificateId);
});
</script>