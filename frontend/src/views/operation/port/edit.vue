<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Update Port</h2>
    <default-button :title="'Port List'" :to="{ name: 'ops.configurations.ports.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <form @submit.prevent="updatePort(port, portId)">
          <!-- Port Form -->
          <port-form v-model:form="port" :errors="errors"></port-form>
          <!-- Submit button -->
          <button type="submit" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
      </form>
  </div>
</template>
<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import PortForm from '../../../components/operations/configurations/PortForm.vue';
import usePort from '../../../composables/operations/usePort';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const portId = route.params.portId;
const { port, showPort, updatePort, errors } = usePort();

const { setTitle } = Title();

setTitle('Edit Port');

onMounted(() => {
  showPort(portId);
});
</script>