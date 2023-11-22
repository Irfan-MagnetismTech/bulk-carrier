<template>
  <!-- Heading -->
  
  <div class="flex items-center justify-between w-full my-3 " v-once>
        <h2 class="text-2xl font-semibold text-gray-700 ">Create New Port</h2>
      <default-button :title="'Port List'" :to="{ name: 'ops.configurations.ports.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md ">
      <form @submit.prevent="storePort(port)">
          <!-- Port Form -->
          <port-form v-model:form="port" :errors="errors"></port-form>
          <!-- Submit button -->
          <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
      </form>
  </div>
</template>
<script setup>
import PortForm from '../../../components/operations/configurations/PortForm.vue';
import usePort from '../../../composables/operations/usePort';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";
const icons = useHeroIcon();

const { port, storePort, isLoading, errors } = usePort();

const { setTitle } = Title();

setTitle('Create Port');
</script>