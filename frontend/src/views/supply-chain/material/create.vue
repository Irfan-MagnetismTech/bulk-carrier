<script setup>
import {ref,onMounted} from "vue";

import useMaterial from "../../../composables/supply-chain/useMaterial.js";
import MaterialForm from '../../../components/supply-chain/material/MaterialForm.vue';

import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { material, storeMaterial,isLoading,errors} = useMaterial();
const { setTitle } = Title();
const page = ref('create');

setTitle('Create Material');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Material</h2>
        <default-button :title="'Material List'" :to="{ name: 'scm.material.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeMaterial(material)">
            <material-form v-model:form="material" :page="page" :errors="errors"></material-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
    </div>
    
</template>
