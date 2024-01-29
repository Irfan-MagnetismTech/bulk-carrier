<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Material Details</h2>
    <default-button :title="'Movement Requistion List'" :to="{ name: 'scm.movement-requisitions.index' }" :icon="icons.DataBase"></default-button>
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
                        <th class="w-40">Material Name</th>
                        <td>{{ material?.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Material Code</th>
                        <td>{{ material?.material_code }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Material Category</th>
                        <td>{{ material?.scmMaterialCategory.name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Unit</th>
                        <td>{{ material?.unit }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">HS Code</th>
                        <td>{{ material?.hs_code }}</td>
                    </tr>
                    <tr>
                      <th class="w-40">Minimum Stock</th>
                      <td>{{ material?.minimum_stock }}</td>
                    </tr>
                    <tr>
                      <th class="w-40">Store Category</th>
                      <td>{{ material?.store_category }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Description </th>
                        <td>{{ material?.description }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Type </th>
                        <td>{{ material?.type }}</td>
                    </tr>
                    <tr>
                        <th class="w-40">Sample Photo </th>
                        <td>
                          <template v-if="material.sample_photo">
                            <a class="text-red-700" target="_blank" :href="env.BASE_API_URL+material?.sample_photo">{{
                                (typeof material?.sample_photo === 'string')
                                    ? '('+material?.sample_photo.split('/').pop()+')'
                                    : ''
                            }}</a>
                            </template>
                        </td>
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
import { onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import Title from "../../../services/title";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import useHelper from "../../../composables/useHelper";
import useHeroIcon from "../../../assets/heroIcon";
import useMovementRequisition from "../../../composables/supply-chain/useMovementRequisition";
import useMaterial from "../../../composables/supply-chain/useMaterial.js";
import { formatDate } from '../../../utils/helper';

const icons = useHeroIcon();

const route = useRoute();
const materialId = route.params.materialId;


const { material, showMaterial, updateMaterial,isLoading,errors } = useMaterial();
const { numberFormat } = useHelper();

const { setTitle } = Title();

setTitle('Material Details');


const DEPARTMENTS = ['', 'Store Department', 'Engine Department', 'Provision Department'];

// watch(bunkerRequisition, (value) => {
//    bunkerRequisition.value.opsVoyage = value?.opsVoyage;
// });



onMounted(() => {
  showMaterial(materialId);
});

</script>