<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Cargo Type Details</h2>
    <default-button :title="'Cargo Type List'" :to="{ name: 'ops.configurations.cargo-types.index' }" :icon="icons.DataBase"></default-button>
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
                <!-- <tr>
                    <th class="w-40">Business Unit</th>
                    <td><span :class="cargoType?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ cargoType?.business_unit }}</span></td>
                </tr> -->
                <tr>
                    <th class="w-40">Type</th>
                    <td>{{ cargoType?.cargo_type }}</td>
                </tr>
                <tr>
                    <th class="w-40">Description</th>
                    <td>{{ cargoType?.description }}</td>
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
import useCargoType from '../../../composables/operations/useCargoType';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHeroIcon from "../../../assets/heroIcon";

const icons = useHeroIcon();

const route = useRoute();
const cargoTypeId = route.params.cargoTypeId;
const { cargoType, showCargoType, errors } = useCargoType();

const { setTitle } = Title();

onMounted(() => {
  showCargoType(cargoTypeId);
});
</script>