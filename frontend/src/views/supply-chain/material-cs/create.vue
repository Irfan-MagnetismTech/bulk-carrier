<script setup>
import {ref,onMounted} from "vue";

import Title from "../../../services/title";
import useMaterialCs from "../../../composables/supply-chain/useMaterialCs";
import useHelper from "../../../composables/useHelper.js";
import MaterialCsForm from "../../../components/supply-chain/material-cs/MaterialCsForm.vue";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from '../../../components/buttons/DefaultButton.vue';

const icons = useHeroIcon();

const { getMaterialCs, materialCs, storeMaterialCs,materialObject, errors, isLoading } = useMaterialCs();
const page = ref('create');
const { setTitle } = Title();



setTitle('Create Material CS');
</script>
<template>
    <!-- Heading -->
    <div class="flex items-center justify-between w-full my-3" v-once>
        <h2 class="text-xl font-semibold text-gray-700 dark-disabled:text-gray-200">Create Material CS</h2>
        <default-button :title="'PR List'" :to="{ name: 'scm.store-issue-returns.index' }" :icon="icons.DataBase"></default-button>
    </div>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800 overflow-hidden">
        <form @submit.prevent="storeMaterialCs(materialCs)">
          <material-cs-form v-model:form="materialCs" :errors="errors" :materialObject="materialObject" :page="page"></material-cs-form>
            <!-- Submit button -->
            <button type="submit" :disabled="isLoading" class="flex items-center justify-between px-4 py-2 mt-4 text-sm leading-5 text-white transition-colors duration-150 bg-purple-600  border border-transparent rounded-lg fon2t-medium mt- active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Create</button>
        </form>
        
    </div>
</template>
