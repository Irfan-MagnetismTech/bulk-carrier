<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Vessel Bunker</h2>
    <default-button :title="'Expense Head List'" :to="{ name: 'ops.vessel-bunkers.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <form @submit.prevent="updateVesselBunker(vesselBunker, vesselBunkerId)">
          <!-- Port Form -->
          <vessel-bunker-form v-model:form="vesselBunker" :errors="errors" :formType="formType"></vessel-bunker-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import VesselBunkerForm from '../../../components/operations/VesselBunkerForm.vue';
import useVesselBunker from '../../../composables/operations/useVesselBunker';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const vesselBunkerId = route.params.vesselBunkerId;
const { vesselBunker, showVesselBunker, updateVesselBunker, errors } = useVesselBunker();

const { setTitle } = Title();

setTitle('Edit Vessel Bunker');

const formType = 'edit';

onMounted(() => {
  showVesselBunker(vesselBunkerId);
});
</script>