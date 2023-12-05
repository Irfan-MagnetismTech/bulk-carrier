<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Purchase Requistions</h2>
    <default-button :title="'Purchase Requistion List'" :to="{ name: 'ops.bunker-requisitions.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 relative">
      <form @submit.prevent="updateBunkerRequisiton(bunkerRequisiton, bunkerRequisitonId)">
          <!-- Port Form -->
          <bunker-requisiton-form v-model:form="bunkerRequisiton" :errors="errors" :formType="formType" :bunkerRequisitonBunkerObject="bunkerRequisitonBunkerObject"></bunker-requisiton-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import BunkerRequisitonForm from '../../../components/operations/BunkerRequisitonForm.vue';
import useBunkerRequisiton from '../../../composables/operations/useBunkerRequisiton';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const bunkerRequisitonId = route.params.bunkerRequisitonId;
const { bunkerRequisiton, bunkerRequisitonBunkerObject, showBunkerRequisiton, updateBunkerRequisiton, errors } = useBunkerRequisiton();

const { setTitle } = Title();

setTitle('Edit Purchase Requistion');

const formType = 'edit';

onMounted(() => {
  showBunkerRequisiton(bunkerRequisitonId);
});
</script>