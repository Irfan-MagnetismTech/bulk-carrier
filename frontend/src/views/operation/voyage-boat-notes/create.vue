<template>
  <!-- Heading -->
  
  <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Add Voyage Boat Notes</h2>
      <default-button :title="'Voyage Boat Notes List'" :to="{ name: 'ops.voyage-boat-notes.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 relative">
      <form @submit.prevent="storeVoyageBoatNote(voyageBoatNote)">
          <!-- Port Form -->
          <voyage-boat-note-form v-model:form="voyageBoatNote" :errors="errors" :formType="formType"></voyage-boat-note-form>
          <!-- Submit button -->
          <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import VoyageBoatNoteForm from '../../../components/operations/VoyageBoatNoteForm.vue';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
import useVoyageBoatNote from '../../../composables/operations/useVoyageBoatNote';
const icons = useHeroIcon();

const { voyageBoatNote, storeVoyageBoatNote, isLoading, errors } = useVoyageBoatNote();
const { setTitle } = Title();

setTitle('Create Voyage Boat Notes');

const formType = 'create';

</script>