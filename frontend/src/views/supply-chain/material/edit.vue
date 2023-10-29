<script setup>
import MaterialForm from '../../../components/supply-chain/material/MaterialForm.vue';
import {ref ,onMounted , watch} from "vue";
import useMaterial from "../../../composables/supply-chain/useMaterial.js";
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();
import { useRoute } from 'vue-router';
import { useStore } from "vuex";
const store = useStore();

const { material, showMaterial, updateMaterial,isLoading,errors } = useMaterial();
const { setTitle } = Title();
const route = useRoute();
const materialId = route.params.materialId;


setTitle('Edit Material');

onMounted(() => {
    showMaterial(materialId);
});

watch(material, (value) => {
  if(value) {
    material.value.scm_material_category_name = material.value?.scmMaterialCategory;
  }
});

</script>
<template>
    <!-- Heading -->
    <div class="flex flex-col items-center justify-between w-full my-6 sm:flex-row" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Material</h2>
        <default-button :title="'Material List'" :to="{ name: 'scm.material.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form @submit.prevent="updateMaterial(material, materialId)">
            <material-form v-model:form="material" :errors="errors"></material-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600  hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
