<template>
    <div class="flex items-center justify-between w-full my-3" v-once>
      <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Port Details</h2>
      <default-button :title="'Port List'" :to="{ name: 'ops.configurations.ports.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
        <div class="flex md:gap-4">
            <div class="w-full">
                <table class="w-full">
                    <thead>
                        <tr>
                            <td class="!text-center font-bold bg-green-600 uppercase text-white" colspan="2">Basic Info</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="w-40">Business Unit</th>
                            <td><span :class="port?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ port?.business_unit }}</span></td>
                        </tr>
                        <tr>
                            <th class="w-40">Port/Ghat Code</th>
                            <td>{{ port?.code }}</td>
                        </tr>
                        <tr>
                            <th class="w-40">Port/Ghat Name</th>
                            <td>{{ port?.name }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
  </template>
  <style lang="postcss" scoped>
  th, td, tr {
    @apply text-left border-gray-500
  }
  
  tfoot td{
    @apply text-center
  }
  
  </style>
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
   
  onMounted(() => {
    showPort(portId);
  });
  </script>