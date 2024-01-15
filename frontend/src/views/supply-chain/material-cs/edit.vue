<script setup>
import {ref,onMounted, watch} from "vue";

import Title from "../../../services/title";
import useMaterialCs from "../../../composables/supply-chain/useMaterialCs";
import MaterialCsForm from "../../../components/supply-chain/material-cs/MaterialCsForm.vue";
import { useRoute } from 'vue-router';

const { getMaterialCs, showMaterialCs, materialCs, updateMaterialCs, errors, isLoading,materialObj,materialList} = useMaterialCs();
const { getPrWiseMaterialList, prMaterialList } = useMaterialCs();

import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { setTitle } = Title();
const route = useRoute();
const materialCsId = route.params.materialCsId;
const formType = 'edit';

setTitle('Create Material CS');

onMounted(() => {
    showMaterialCs(materialCsId);
});

watch(() => materialCs, (val) => {
    //foreach materialCS.scmCsMaterials
    val.scmCsMaterials.forEach((item, index) => {
        materialList.push([]);
        getPrWiseMaterialList(item.scm_pr_id).then((res) => {
            materialList[index] = res;
        });
    });
});
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Edit Material CS</h2>
        <default-button :title="'Material CS List'" :to="{ name: 'scm.material-cs.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="updateMaterialCs(materialCs, materialCsId)">
            <material-cs-form :form="materialCs" :page="formType" :errors="errors" :formType="formType" :materialObj="materialObj" :materialList="materialList"></material-cs-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Update</button>
        </form>
    </div>
</template>
